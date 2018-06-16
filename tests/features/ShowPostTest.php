<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowPostTest extends TestCase
{
    /** @test */
    function a_user_can_see_the_post_details()
    {
    	// Arrange
    	$user = $this->defaultUser([
    		'name' => 'Cristyan Valera'
    	]);

    	$post = factory(\App\Post::class)->make([
    		'title' => 'Este es el título del post',
    		'content' => 'Este es el contenido del post'
    	]);

    	// Act && Assert
    	$user->posts()->save($post);
    	
    	$this->visit(route('posts.show', $post))
    		->seeInElement('h1', $post->title)
    		->see($post->content)
    		->see($user->name);
    }
}
