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

Route::get('register_confirmation', function() {
	return view('register.confirm');
})->name('register_confirmation');
