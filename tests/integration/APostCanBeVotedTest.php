<?php 

use App\Vote;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class APostCanBeVotedTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $post;

    function setUp()
    {
        parent::setUp();

        $this->actingAs($this->user = $this->defaultUser());
     
        $this->post = $this->createPost();   
    }

    /** @test */
    function a_post_can_be_upvoted()
    {
        // Act
        $this->post->upvote();

        // Assert
        $this->assertSame(1, $this->post->current_vote);

        $this->assertSame(1, $this->post->score);
    }

    /** @test */
    function a_post_cannot_be_upvoted_twice_by_the_same_user()
    {
        // Act
        $this->post->upvote();

        $this->post->upvote();

        // Assert
        $this->assertSame(1, Vote::count());

        $this->assertSame(1, $this->post->score);
    }

    /** @test */
    function a_post_can_be_downvoted()
    {
        // Act
        $this->post->downvote();
        
        // Assert
        $this->assertSame(-1, $this->post->current_vote);

        $this->assertSame(-1, $this->post->score);
    }

    /** @test */
    function a_post_cannot_be_downvoted_twice_by_the_same_user()
    {
        // Act
        $this->post->downvote();

        $this->post->downvote();

        // Assert
        $this->assertSame(1, Vote::count());

        $this->assertSame(-1, $this->post->score);
    }

    /** @test */
    function a_user_can_switch_from_upvote_to_downvote()
    {
        // Act
        $this->post->upvote();
        
        $this->post->downvote();

        // Assert
        $this->assertSame(-1, $this->post->current_vote);
        
        $this->assertSame(-1, $this->post->score);
    }

    /** @test */
    function a_user_can_switch_from_downvote_to_upvote()
    {
        // Act
        $this->post->downvote();

        $this->post->upvote();

        // Assert
        $this->assertSame(1, Vote::count());

        $this->assertSame(1, $this->post->score);
    }

    /** @test */
    function the_post_score_is_calculated_correctly()
    {
        $this->post->votes()->create([
            'user_id' => $this->anyone()->id,
            'vote' => 1,
        ]);

        $this->post->upvote();

        // Assert
        $this->assertSame(2, Vote::count());
        
        $this->assertSame(2, $this->post->score);
    }

    /** @test */
    function the_post_can_be_unvoted()
    {
        $this->assertNull($this->post->current_vote);
     
        $this->post->upvote();

        $this->assertSame(1, $this->post->current_vote);

        $this->post->undoVote();

        $this->assertNull($this->post->current_vote);

        $this->assertSame(0, $this->post->score);
    }

    /** @test */
    function get_vote_from_user_returns_the_vote_from_the_right_post()
    {
        $this->post->upvote();

        $anotherPost = $this->createPost();

        $this->assertSame(1, $this->post->current_vote);

        $this->assertNull($anotherPost->current_vote);
    }
}