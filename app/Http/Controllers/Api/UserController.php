<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Текущий пользователь
     *
     * @param Request $request
     * @return null|User
     */
    public function index(Request $request)
    {
        return $request->user();
    }

    /**
     * Регистрация пользователя
     *
     * @param Request $request
     * @return null|User
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);

        return $user;
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = md5( time() ).'.'.md5($request->get('email'));
            $user->forceFill([
                'api_token' => $token
            ])->save();

            return response()->json([
                'token' => $token
            ]);
        }

        return response()->json([
            'message' => 'The provided credentials do not match our records.'
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->forceFill([
            'api_token' => null
        ])->save();

        return response()->json([
            'message' => 'success'
        ]);
    }
}
