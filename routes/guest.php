<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

// Posts
Route::get('posts/create', [
	'uses' => 'CreatePostController@create',
	'as' => 'posts.create'
]);

Route::post('posts/create', [
	'uses' => 'CreatePostController@store',
	'as' => 'posts.store'
]);