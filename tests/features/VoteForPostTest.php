<?php

use App\Vote;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class VoteForPostTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    function a_user_can_upvote_for_a_post()
     {
         $this->actingAs($user = $this->defaultUser());

         $post = $this->createPost();
         
         $this->postJson($post->url . '/upvote')
            ->assertSuccessful()
            ->assertJson([
                'new_score' => 1
            ]);

        // Assert
        $this->assertDatabaseHas('votes', [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'vote' => 1,
        ]);

        $this->assertSame(1, $post->fresh()->score);
     }

    /** @test */
    function a_user_can_downvote_for_a_post()
    {
        $this->actingAs($user = $this->defaultUser());

        $post = $this->createPost();

        $this->postJson($post->url . '/downvote')
            ->assertSuccessful()
            ->assertJson([
                'new_score' => -1
            ]);

        // Assert
        $this->assertDatabaseHas('votes', [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'vote' => -1,
        ]);

        $this->assertSame(-1, $post->fresh()->score);
    }

    /** @test */
    function a_user_can_undovote_a_post()
    {
        $this->actingAs($user = $this->defaultUser());

        $post = $this->createPost();

        Vote::upvote($post);

        $this->deleteJson($post->url . '/vote')
            ->assertSuccessful()
            ->assertJson([
                'new_score' => 0
            ]);

        // Assert
        $vote = Vote::first();

        $this->assertNull($vote);

        $this->assertSame(0, $post->fresh()->score);
    }

    /** @test */
    function a_guest_cannot_vote_for_a_post()
    {
        $user = $this->defaultUser();

        $post = $this->createPost();

        $this->postJson("{$post->url}/upvote")
            ->assertStatus(401)
            ->assertJson(['error' => 'Unauthenticated.']);

        $vote = Vote::first();

        $this->assertNull($vote);

        $this->assertSame(0, $post->fresh()->score);
    } 
}
