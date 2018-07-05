<?php

use Illuminate\Support\Facades\Notification;

class WriteCommentTest extends FeatureTestCase
{
    /** @test*/
    function user_can_write_a_comment()
    {
        // Arrange
        Notification::fake();
        
        $post = $this->createPost();

        $user = $this->defaultUser();

        // Act
        $this->actingAs($user)
            ->visit($post->url)
            ->type('Un comentario', 'comment')
            ->press('Publicar comentario');

        // Assert
        $this->seeInDatabase('comments', [
            'comment' => 'Un comentario',
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);

        $this->seePageIs($post->url);
    }
}
