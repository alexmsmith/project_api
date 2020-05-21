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
Route::post('user/create', 'UserController@create');
Route::post('user/login', 'UserController@login');

Route::middleware('auth:api')->get('/user/get', 'UserController@get');
Route::middleware('auth:api')->post('/user/logout','UserController@logout');

