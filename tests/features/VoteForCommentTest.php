<?php

use App\Comment;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class VoteForCommentTest extends TestCase
{
    use DatabaseTransactions;

    protected $comment;
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->actingAs($this->user = $this->defaultUser());

        $this->comment = factory(Comment::class)->create();
    }

    //** @test */
    function a_user_can_upvote_for_a_comment()
    {
        // Act
        $this->postJson("comments/{$this->comment->id}/vote/1")
            ->assertSuccessful()
            ->assertJson([
                'new_score' => 1
            ]);

        $this->comment->refresh();

        // Assert
        $this->assertSame(1, $this->comment->current_vote);

        $this->assertSame(1, $this->comment->score);
    }
}
