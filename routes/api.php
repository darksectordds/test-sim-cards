<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('register', [\App\Http\Controllers\Api\AuthController::class, 'register']);

Route::group([
    'middleware' => 'auth:api',
    'namespace' => 'Api'
], function() {

    Route::get('user', [\App\Http\Controllers\Api\AuthController::class, 'index']);
    Route::get('logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);

    Route::group([
        'prefix' => 'sim_cards',
        'as' => 'sim_cards.'
    ], function() {
        Route::get('/', [\App\Http\Controllers\Api\SimCardsController::class, 'index'])->name('index');
    });

    Route::group([
        'prefix' => 'contracts',
        'as' => 'contracts.',
        'middleware' => 'admin',
    ], function() {
        Route::get('/', [\App\Http\Controllers\Api\ContractsController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Api\ContractsController::class, 'store'])->name('store');
    });
});