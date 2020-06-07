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
Route::prefix('user')->group(function () {
    Route::post('create', 'UserController@create');
    Route::post('login', 'UserController@login');

    Route::get('get1', 'UserController@get');

    Route::get('activation', 'UserController@activation');
    
    Route::middleware('auth:api')->get('get', 'UserController@get');
    Route::middleware('auth:api')->post('logout','UserController@logout');
    Route::middleware('auth:api')->post('password-reset','UserController@passwordReset');
});

Route::post('profile', 'UserController@getProfile');

Route::get('test', function () {
    return response()->json([
        'status' => 'success', 
    ]);
});

Route::get('testing', function () {
    return 'hello there';
});

Route::get('xy', function () {
    return 'hello rachel';
});

