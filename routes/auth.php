<?php

// Routes that requires authentication
Route::post('logout', [
    'uses' => 'LoginController@logout',
    'as' => 'logout'
]);

// Posts
Route::get('posts/create', [
    'uses' => 'CreatePostController@create',
    'as' => 'posts.create'
]);

Route::post('posts/create', [
    'uses' => 'CreatePostController@store',
    'as' => 'posts.store'
]);

// Votes
Route::pattern('module', '[a-z]+');

Route::bind('votable', function ($votableId, $route) {
    $modules = [
        'posts' => \App\Post::class,
        'comments' => \App\Comment::class,
    ];

    $model = $modules[$route->parameter('module')] ?? null;

    abort_unless($model, 404);

    return $model::findOrFail($votableId);
});

Route::post('{module}/{votable}/vote/1', [
    'uses' => 'VoteController@upvote'
]) ;

Route::post('{module}/{votable}/vote/-1', [
    'uses' => 'VoteController@downvote'
]) ;

Route::delete('{module}/{votable}/vote', [
    'uses' => 'VoteController@undoVote'
]);

// Comments
Route::post('posts/{post}/comments/create', [
    'uses' => 'CommentController@store',
    'as' => 'comments.store'
]);

Route::post('comments/{comment}/accept', [
    'uses' => 'CommentController@accept',
    'as' => 'comments.accept'
]);

// Subscriptions
Route::post('posts/{post}/subscribe', [
    'uses' => 'SubscriptionController@subscribe',
    'as' => 'posts.subscribe'
]);

Route::delete('posts/{post}/unsubscribe', [
    'uses' => 'SubscriptionController@unsubscribe',
    'as' => 'posts.unsubscribe'
]);

Route::get('mis-posts/{category?}', [
    'uses' => 'ListPostController',
    'as' => 'posts.mine'
]);