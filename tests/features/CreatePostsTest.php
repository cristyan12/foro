<?php

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
		]);

		$this->see('Esta es una pregunta');
	}

	/** @test */
	function create_post_form_validation()
	{
		$this->actingAs($this->defaultUser())
			->visit(route('posts.create'))
			->press('Publicar')
			->seePageIs(route('posts.create'))
			->assertSee('El campo título es obligatorio')
			->assertSee('El campo contenido es obligatorio');
	}
	
}