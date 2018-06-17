<?php

class ShowPostTest extends FeatureTestCase
{
    /** @test */
    function a_user_can_see_the_post_details()
    {
    	// Arrange
    	$user = $this->defaultUser([
    		'name' => 'Cristyan Valera',
            'email' => 'cristyan12@mail.com'
    	]);

    	$post = $this->createPost([
    		'title' => 'Este es el título del post',
    		'content' => 'Este es el contenido del post',
            'user_id' => $user->id,
    	]);

    	// Act && Assert

    	$this->visit($post->url)
    		->seeInElement('h1', $post->title)
    		->see($post->content)
    		->see($user->name);
    }

    /** @test */
    function old_urls_are_redirected()
    {
        // Arrange
        $post = $this->createPost([
            'title' => 'Old Title'
        ]);

        // Act
        $url = $post->url;

        $post->update(['title' => 'New Title']);

        // Assert
        $this->visit($url)
            ->seePageIs($post->url);
    }
}
