<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User as Users;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Cookie\CookieJar;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Получение пользователя по запросу
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        return $request->user();
    }

    /**
     * Авторизация через OAuth 2.0 и выдача двух токенов:
     *  - access_token
     *  - refresh_token
     *
     * Внимание: авторизация осуществляется через back-end code поскольку
     * сервер авторизации OAuth имеет локальный grand ключ, который
     * мы выдаем всем зарегистрированным пользователям нашего приложения,
     * и поэтому этот ключ необходимо держать в секрете.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function login (Request $request)
    {
        if (Users::where('email', $request['email'])->exists()) {
            $http = new \GuzzleHttp\Client;

            $cookie_dev_key = 'debug';
            $cookieJar = CookieJar::fromArray([
                $cookie_dev_key => $request->cookie($cookie_dev_key),
            ], $request->getHttpHost());

            $email = $request->get('email');
            $pass = $request->get('password');

            try {
                $response = $http->post(url('/').config('services.passport.login_endpoint'), [
                    'form_params' => [
                        'grant_type' => 'password',
                        'client_id' => config('services.passport.client_id'),
                        'client_secret' => config('services.passport.client_secret'),
                        'username' => $email,
                        'password' => $pass,
                    ],
                    'cookies' => $cookieJar,
                ]);

                return $response->getBody()->getContents();

            } catch (BadResponseException $exception) {
                if ($exception->getCode() === 400) {
                    return response()->json([
                        'message' => 'Invalid Request. Please enter a username or a password.'
                    ], $exception->getCode());
                } else if ($exception->getCode() === 401) {
                    return response()->json([
                        'message' => 'Your credentials are incorrect. Please try again.'
                    ], $exception->getCode());
                }

                return response()->json([
                    'message' => 'Something went wrong on the server.'
                ], $exception->getCode());
            }
        }

        return response()->json([
            'message' => 'Your credentials are incorrect or email address is not verified.'
        ], 403);
    }

    /**
     * Регистрация пользователя
     *
     * @param Request $request
     * @return mixed
     */
    public function register (Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'email', 'max:191', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'conditions' => ['accepted'],
        ]);

        $user = Users::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $message = 'User not created.';
        $status = 400;
        if ($user) {
            $message = 'success';
            $status = 200;
        }

        return response()->json(['message' => $message], $status);
    }

    /**
     * Выход пользователя из системы
     *
     * Внимание: поскольку пока не ясно большой разницы между revoke
     * и delete, то принято решение не хранить лишние ненужные токены...
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout (Request $request)
    {
        auth()->user()->tokens->each(function($token){
            $token->delete();
        });

        return response()->json(['message' => 'Logged out successfully.'], 200);
    }
}
