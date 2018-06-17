<?php

use App\Post;

class PostModelTest extends TestCase
{
	/** @test */
	function adding_a_title_generates_a_slug()
	{
		$post = new Post([
			'title' => 'Como instalar Laravel'
		]);

		$this->assertSame('como-instalar-laravel', $post->slug);
	}

	/** @test */
	function editing_the_title_changes_the_slug()
	{
		$post = new Post([
			'title' => 'Como instalar Laravel'
		]);

		$post->title = 'Como instalar Laravel 5.1 LTS';

		$this->assertSame('como-instalar-laravel-51-lts', $post->slug);
	}
}
