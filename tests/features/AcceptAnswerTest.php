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

    /** @test */
    function the_non_post_author_dont_see_the_accept_answer_button()
    {
        // Arrange
        $comment = factory(Comment::class)->create([
            'comment' => 'Esta será la respuesta del post'
        ]);

        // Act
        $this->actingAs($this->defaultUser())
            ->visit($comment->post->url)
            ->dontSee('Aceptar respuesta');
    }

    /** @test */
    function the_non_post_author_cannot_accept_a_comment_as_the_post_answer()
    {
        // Arrange
        $comment = factory(Comment::class)->create([
            'comment' => 'Esta será la respuesta del post'
        ]);

        // Act
        $this->actingAs($this->defaultUser());
        $this->post(route('comments.accept', $comment));

        // Assert
        $this->seeInDatabase('posts', [
            'id' => $comment->post_id,
            'pending' => true,
        ]);
    }

     /** @test */
    function the_accept_button_is_hidden_when_the_comment_is_already_the_post_answer()
    {
        $comment = factory(Comment::class)->create([
            'comment' => 'Esta será la respuesta del post'
        ]);

        $this->actingAs($comment->post->user);

        $comment->markAsAnswer();

        $this->visit($comment->post->url)
            ->dontSee('Aceptar respuesta');
    }
}