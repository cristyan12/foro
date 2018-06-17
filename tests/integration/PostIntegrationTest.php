<?php

use App\Post;

class PostIntegrationTest extends FeatureTestCase
{
    /** @test */
    function a_slug_is_generated_and_saved_to_the_database()
    {	
    	$post = $this->createPost([
    		'title' => 'Como instalar Laravel'
    	]);

		$this->assertSame('como-instalar-laravel', $post->slug);
    }
}
