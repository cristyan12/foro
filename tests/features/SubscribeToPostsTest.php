<?php

class SubscribeToPostsTest extends FeatureTestCase
{
    /** @test */
    function a_user_can_subscribe_to_a_post()
    {
        // Arrange
        $post = $this->createPost();
        $user = $this->defaultUser();

        // Act
        $this->actingAs($user)
            ->visit($post->url)
            ->press('Suscribirse al post');

        // Assert
        $this->seeInDatabase('subscriptions', [
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);

        $this->seePageIs($post->url)
            ->dontSee('Suscribirse al post');
    }
}
