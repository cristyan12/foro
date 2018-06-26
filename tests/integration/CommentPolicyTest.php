<?php

use App\Comment;
use App\Policies\CommentPolicy;

use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommentPolicyTest extends TestCase
{
    use DatabaseTransactions;
    
    /** @test */
    function the_post_author_can_select_a_comment_as_an_answer()
    {
        // Arrange
        $comment = factory(Comment::class)->create();

        $policy = new CommentPolicy;

        // Assert
        $this->assertTrue(
            $policy->accept($comment->post->user, $comment)
        );
    }

     /** @test */
    function the_non_post_author_cannot_select_a_comment_as_an_answer()
    {
        // Arrange
        $comment = factory(Comment::class)->create();

        $policy = new CommentPolicy;

        // Assert
        $this->assertFalse(
            $policy->accept($this->defaultUser(), $comment)
        );
    }
}
