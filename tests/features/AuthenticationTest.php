<?php

use App\Token;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthenticationTest extends FeatureTestCase
{
    /** @test */
    function a_user_can_login_with_a_token_url()
    {
        // Arrange
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        // Act
        $this->visitRoute('login', ['token' => $token->token]);

        // Assert
        $this->seeIsAuthenticated()
            ->seeIsAuthenticatedAs($user);

        $this->dontSeeInDatabase('tokens', [
            'id' => $token->id
        ]);

        $this->seePageIs('/');
    }

    /** @test */
    function a_user_cannot_login_with_a_invalid_token()
    {
        // Arrange
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        $invalidToken = str_random(60);

        // Act
        $this->visit("login/{$invalidToken}");

        // Assert
        $this->dontSeeIsAuthenticated()
            ->seeRouteIs('token')
            ->see('Este enlace ya expiró, por favor solicita otro');

        $this->SeeInDatabase('tokens', [
            'id' => $token->id
        ]);
    }

    /** @test */
    function a_user_cannot_use_the_same_token_twice()
    {
        // Arrange
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        $token->login();

        Auth::logout();

        // Act
        $this->visitRoute('login', ['token' => $token->token]);

        // Assert
        $this->dontSeeIsAuthenticated()
            ->seeRouteIs('token')
            ->see('Este enlace ya expiró, por favor solicita otro');
    }

    /** @test */
    function the_token_expires_after_30_minutes()
    {
        // Arrange
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        // Act
        Carbon::setTestNow(Carbon::parse('+31 minutes'));

        $this->visitRoute('login', ['token' => $token->token]);

        // Assert
        $this->dontSeeIsAuthenticated()
            ->seeRouteIs('token')
            ->see('Este enlace ya expiró, por favor solicita otro');
    }

    /** @test */
    function the_token_is_case_sensitive()
    {
        // Arrange
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        // Act
        $this->visitRoute('login', ['token' => strtolower($token->token)]);

        // Assert
        $this->dontSeeIsAuthenticated()
            ->seeRouteIs('token')
            ->see('Este enlace ya expiró, por favor solicita otro');
    }
}
