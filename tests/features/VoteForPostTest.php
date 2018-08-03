<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class VoteForPostTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    function a_user_can_vote_for_a_post()
     {
         $this->actingAs($user = $this->defaultUser());

         $post = $this->createPost();
         
         $this->postJson($post->url . '/vote')
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
}
