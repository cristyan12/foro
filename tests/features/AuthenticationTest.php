<?php

use App\Token;

class AuthenticationTest extends FeatureTestCase
{
    /** @test */
    function a_user_can_login_with_a_token_url()
    {
        // Arrange
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        // Act
        $this->visit("login/{$token->token}");

        // Assert
        $this->seeIsAuthenticated()
            ->seeIsAuthenticatedAs($user);

        $this->dontSeeInDatabase('tokens', [
            'id' => $token->id
        ]);

        $this->seePageIs('/');
    }
}
