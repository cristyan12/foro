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
        Vote::upvote($this->post);

        // Assert
        $this->assertDatabaseHas('votes', [
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
            'vote' => 1,
        ]);

        $this->assertSame(1, $this->post->score);
    }

    /** @test */
    function a_post_cannot_be_upvoted_twice_by_the_same_user()
    {
        // Act
        Vote::upvote($this->post);
        Vote::upvote($this->post);

        // Assert
        $this->assertSame(1, Vote::count());
        $this->assertSame(1, $this->post->score);
    }

    /** @test */
    function a_post_can_be_downvoted()
    {
        // Act
        Vote::downvote($this->post);
        
        // Assert
        $this->assertDatabaseHas('votes', [
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
            'vote' => -1,
        ]);

        $this->assertSame(-1, $this->post->score);
    }

    /** @test */
    function a_post_cannot_be_downvoted_twice_by_the_same_user()
    {
        // Act
        Vote::downvote($this->post);
        Vote::downvote($this->post);

        // Assert
        $this->assertSame(1, Vote::count());
        $this->assertSame(-1, $this->post->score);
    }

    /** @test */
    function a_user_can_switch_from_upvote_to_downvote()
    {
        // Act
        Vote::upvote($this->post);
        Vote::downvote($this->post);

        // Assert
        $this->assertSame(1, Vote::count());
        $this->assertSame(-1, $this->post->score);
    }

    /** @test */
    function a_user_can_switch_from_downvote_to_upvote()
    {
        // Act
        Vote::downvote($this->post);
        Vote::upvote($this->post);

        // Assert
        $this->assertSame(1, Vote::count());
        $this->assertSame(1, $this->post->score);
    }

    /** @test */
    function the_post_score_is_calculated_correctly()
    {
        Vote::create([
            'post_id' => $this->post->id,
            'user_id' => $this->anyone()->id,
            'vote' => 1,
        ]);

        Vote::upvote($this->post);

        // Assert
        $this->assertSame(2, Vote::count());
        $this->assertSame(2, $this->post->score);
    }
}