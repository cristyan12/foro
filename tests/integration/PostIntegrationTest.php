<?php

use App\Post;

use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostIntegrationTest extends TestCase
{
	use DatabaseTransactions;

    /** @test */
    function a_slug_is_generated_and_saved_to_the_database()
    {
    	$user = $this->defaultUser();

    	$post = factory(Post::class)->make([
    		'title' => 'Como instalar Laravel'
    	]);

		$user->posts()->save($post);

		$this->assertSame('como-instalar-laravel', $post->slug);

		/*
		* Formas de verificar si un registro se guardo
		* correctamente en la base de datos

		* Primera
		$this->seeInDatabase('posts', [
			'slug' => 'como-instalar-laravel'
		]);

		* Segunda
		$this->assertSame('como-instalar-laravel', $post->slug);

		* Tercera
		$this->assertSame(
			'como-instalar-laravel',
			$post->fresh()->slug,
			'Estos atributos no son identicos'
		);
		*/
    }
}
