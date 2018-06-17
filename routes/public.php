<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('posts/{post}-{slug}', [
	'uses' => 'ShowPostController@show',
	'as' => 'posts.show'
])->where('post', '\d+');