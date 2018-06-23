<?php

use App\Comment;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MarkCommentAsAnswerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    function a_post_can_be_answered()
    {
        // Arrange
        $post = $this->createPost();

        $comment = factory(Comment::class)->create([
            'post_id' => $post->id
        ]);

        // Act
        $comment->markAsAnswer();

        // Assert
        $this->assertTrue($comment->fresh()->answer);

        $this->assertFalse($post->fresh()->pending);
    }

    /** @test */
    function a_post_can_only_have_one_answer()
    {
        // Arrange
        $post = $this->createPost();

        $comments = factory(Comment::class)->times(2)->create([
            'post_id' => $post->id
        ]);

        // Act
        $comments->first()->markAsAnswer();
        
        $comments->last()->markAsAnswer();

        // Assert
        $this->assertFalse($comments->first()->fresh()->answer);

        $this->assertTrue($comments->last()->fresh()->answer);
    }
}
