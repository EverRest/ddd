<?php

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
Route::resource('events', 'EventController')
    ->only(['index', 'store', 'show', 'update', 'destroy']);
//Route::get('users', 'UserController@index');
//Route::resource('user', 'UserController')->only(['store', 'show', 'update', 'destroy']);

