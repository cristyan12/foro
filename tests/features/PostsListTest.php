<?php

use Carbon\Carbon;
use App\{Post, Category};

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
    function a_user_can_see_the_posts_filtered_by_status()
    {
        $pendingPost = factory(Post::class)->create([
            'title' => 'Post pendiente',
            'pending' => true
        ]);

        $completedPost = factory(Post::class)->create([
            'title' => 'Post completado',
            'pending' => false
        ]);

        $this->visitRoute('posts.pending')
            ->see($pendingPost->title)
            ->dontSee($completedPost->title);

        $this->visitRoute('posts.completed')
            ->see($completedPost->title)
            ->dontSee($pendingPost->title);
    }

    /** @test */
    function a_user_can_see_post_filtered_by_category()
    {
        // Arrange
        $laravel = factory(Category::class)->create([
            'name' => 'Laravel', 'slug' => 'laravel'
        ]);

        $vue = factory(Category::class)->create([
            'name' => 'Vue.js', 'slug' => 'vue-js'
        ]);

        $laravelPost = factory(Post::class)->create([
            'title' => 'Post de Laravel',
            'category_id' => $laravel->id
        ]);

        $vuePost = factory(Post::class)->create([
            'title' => 'Post de Vue.js',
            'category_id' => $vue->id
        ]);

        // Act && Assert
        $this->visit('/')
            ->see($laravelPost->title)
            ->see($vuePost->title)
            ->within('.categories', function () {
                $this->click('Laravel');
            })
            ->seeInElement('h1', 'Posts de Laravel')
            ->see($laravelPost->title)
            ->dontSee($vuePost->title);
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
