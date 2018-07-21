<?php

Route::get('{category?}', [
	'uses' => 'ShowPostController@index',
	'as' => 'posts.index'
]);

Route::get('/home', 'HomeController@index');

Route::get('posts/{post}-{slug}', [
	'uses' => 'ShowPostController@show',
	'as' => 'posts.show'
])->where('post', '\d+');