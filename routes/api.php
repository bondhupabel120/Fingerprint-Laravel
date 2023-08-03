<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'AuthController@login');
Route::post('registration', 'AuthController@registration');
Route::get('check-login', 'AuthController@checkLogin');
Route::post('logout', 'AuthController@logout');
Route::post('refresh', 'AuthController@refresh');
Route::post('me', 'AuthController@me');

Route::get('users', 'AuthController@users');

Route::resource('task', 'TaskController');
Route::post('send_email', 'TaskController@sendEmail');