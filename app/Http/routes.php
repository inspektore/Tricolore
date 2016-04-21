<?php

Route::auth();

Route::get('/', 'HomeController@index')->name('core.index');
Route::get('/home', 'HomeController@home')->name('core.home');
Route::get('/user/{name}/{id}', 'User\UserController@show')->name('user.profile');
