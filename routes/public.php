<?php

Route::get('/', [
	'uses' => 'ShowPostController@index',
	'as' => 'posts.index'
]);

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('posts/{post}-{slug}', [
	'uses' => 'ShowPostController@show',
	'as' => 'posts.show'
])->where('post', '\d+');