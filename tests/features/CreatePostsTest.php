<?php
use App\Post;

class CreatePostsTest extends FeatureTestCase
{
	/** @test */
	function a_user_create_a_post()
	{
		// Arrange
		$title = 'Esta es una pregunta';
		$content = 'Este es el contenido';

		$this->actingAs($user = $this->defaultUser());

		// Act
		$this->visit(route('posts.create'))
			->type($title, 'title')
			->type($content, 'content')
			->press('Publicar');

		// Assert
		$this->seeInDatabase('posts', [
			'title' => $title,
			'content' => $content,
			'pending' => true,
			'user_id' => $user->id,
			'slug' => 'esta-es-una-pregunta'
		]);

		$post = Post::first();

		// Test the author is subscribed automatically to the post
		$this->seeInDatabase('subscriptions', [
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);
        
		$this->seePageIs($post->url);
	}

	// /** @test */
	// function creating_a_post_requires_authentication()
	// {
	// 	$this->visit(route('posts.create'))
 //            ->dontSeeIsAuthenticated()
 //            ->seeRouteIs('token');
	// }

	/** @test */
	function create_post_form_validation()
	{
		$this->actingAs($this->defaultUser())
			->visit(route('posts.create'))
			->press('Publicar')
			->seePageIs(route('posts.create'))
			->seeErrors([
				'title' => 'El campo título es obligatorio',
				'content' => 'El campo contenido es obligatorio'
			]);
	}
}