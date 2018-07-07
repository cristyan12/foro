<?php

Route::get('register', [
    'uses' => 'RegisterController@create',
    'as' => 'register'
]);

Route::post('register', [
    'uses' => 'RegisterController@store'
]);

Route::get('register_confirmation', [
    'uses' => 'RegisterController@confirm',
    'as' => 'register_confirmation'
]);

Route::get('token', [
	'uses' => 'TokenController@create',
	'as' => 'token'
]);

Route::post('token', [
    'uses' => 'TokenController@store'
]);

Route::get('register_confirmation', function() {
	return view('register.confirm');
})->name('register_confirmation');

Route::get('login/{token}', [
    'uses' => 'LoginController@login',
    'as' => 'login'
]);

