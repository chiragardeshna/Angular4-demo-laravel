<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'cors'], function () {

    Route::get('/user', 'Api\AuthController@user');
    Route::post('/login', 'Api\AuthController@attempt');
    Route::post('/validate', 'Api\AuthController@validateToken');

    Route::get('/users/me', 'Api\UserController@me');
    Route::resource('/users', 'Api\UserController');
});
