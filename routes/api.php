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

Route::namespace('Api')->group(function () {
    Route::post('auth/generate-token', 'AuthController@GenerateToken');
    Route::middleware('api_auth')->prefix('user')->group(function () {
        Route::get('profile', 'UserController@profile');
        Route::match(['get', 'post'], 'check', 'UserController@check');
        Route::post('contacts', 'UserController@contacts');
        Route::match(['get', 'post'], 'search', 'UserController@search');
    });
});
