<?php 

use App\Vote;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class APostCanBeVotedTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    function a_post_can_be_voted()
    {
        // Arrange
        $this->actingAs($user = $this->defaultUser());
        
        $post = $this->createPost();

        // Act
         Vote::upvote($post);

        // Assert
        $this->assertDatabaseHas('votes', [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'vote' => 1,
        ]);

        $this->assertSame(1, $post->score);
    }
}