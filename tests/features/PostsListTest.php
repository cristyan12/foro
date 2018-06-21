<?php

use Carbon\Carbon;

class PostsListTest extends FeatureTestCase
{
	/** @test */
    function a_user_can_see_the_posts_list_and_go_to_the_details()
    {
        $post = $this->createPost([
        	'title' => '¿Debo usar Laravel 5.3 o 5.1 LTS?'
        ]);

        $this->visit('/')
        	->seeInElement('h1', 'Posts')
        	->see($post->title)
        	->click($post->title)
        	->seePageIs($post->url);
    }

    /** @test */
    function the_posts_are_paginated()
    {
        // Arrange
        $first = factory(\App\Post::class)->create([
            'title' => 'Post mas antiguo',
            'created_at' => Carbon::now()->subDays(2)
        ]);

        factory(\App\Post::class)->times(15)->create([
            'created_at' => Carbon::now()->subDay()
        ]);

        $last = factory(\App\Post::class)->create([
            'title' => 'Post mas reciente',
            'created_at' => Carbon::now()
        ]);

        // Act
        $this->visit('/')
            ->see($last->title)
            ->dontSee($first->title)
            ->click('2')
            ->see($first->title)
            ->dontSee($last->title);
    }
}
