<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/User/register', 'ApiController@registerUser');

Route::post('/User/login', 'ApiController@loginUser');

Route::put('/User/update', 'ApiController@updateUser');

Route::delete('/User/delete', 'ApiController@deleteUser');
