<?php

use App\Comment;

class AcceptAnswerTest extends FeatureTestCase
{
    /** @test */
    function the_post_author_can_accept_a_comment_as_the_post_answer()
    {
        // Arrange
        $comment = factory(Comment::class)->create([
            'comment' => 'Esta será la respuesta del post'
        ]);

        // Act
        $this->actingAs($comment->post->user)
            ->visit($comment->post->url)
            ->press('Aceptar respuesta');

        // Assert
        $this->seeInDatabase('posts', [
            'id' => $comment->post_id,
            'pending' => false,
            'answer_id' => $comment->id,
        ]);
        
        $this->seePageIs($comment->post->url)
            ->seeInElement('.answer', $comment->comment);

    }
}
