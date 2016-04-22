<?php

Route::auth();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@home')->name('core.home');
Route::get('/user/{name}/{id}', 'User\UserController@show')->name('user.profile');
