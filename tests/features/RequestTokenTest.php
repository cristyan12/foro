<?php

use App\Token;
use App\Mail\TokenMail;
use Illuminate\Support\Facades\Mail;

class RequestTokenTest extends FeatureTestCase
{
    /** @test */
    function a_guest_user_can_request_a_token()
    {
        // Arrange
        Mail::fake();

        $user = $this->defaultUser(['email' => 'cristyan12@mail.com']);

        // Act
        $this->visitRoute('token')
            ->type('cristyan12@mail.com', 'email')
            ->press('Solicitar token');

        // A new token is created in the database
        $token = Token::where('user_id', $user->id)->first();

        // Assert
        $this->assertNotNull($token, 'A token was not created');

        Mail::assertSentTo($user, TokenMail::class, function($mail) use ($token) {
            return $mail->token->id === $token->id;
        });

        $this->dontSeeIsAuthenticated();

        $this->see('Enviamos a tu email un enlace para que inicies sesión');
    }

    /** @test */
    function a_guest_user_can_request_a_token_without_an_email()
    {
        // Arrange
        Mail::fake();

        // Act
        $this->visitRoute('token')
            ->press('Solicitar token');

        // A new token is NOT created in the database
        $token = Token::first();

        // Assert
        $this->assertNull($token, 'A token was created');

        Mail::assertNotSent(TokenMail::class);

        $this->dontSeeIsAuthenticated();

        $this->seeErrors([
            'email' => 'El campo correo electrónico es obligatorio'
        ]);
    }

    /** @test */
    function a_guest_user_can_request_a_token_an_invalid_email()
    {
        // Act
        $this->visitRoute('token')
            ->type('Cristyan', 'email')
            ->press('Solicitar token');

        // Assert
        $this->seeErrors([
            'email' => 'El campo correo electrónico no corresponde con una dirección de e-mail válida.',
        ]);
    }

    /** @test */
    function a_guest_user_can_request_a_token_with_a_non_existent_email()
    {
        // Arrange
        $this->defaultUser(['email' => 'cristyan12@mail.com']);

        // Act
        $this->visitRoute('token')
            ->type('numenor21@mail.com', 'email')
            ->press('Solicitar token');

        // Assert
        $this->seeErrors([
            'email' => 'El campo correo electrónico no existe.',
        ]);
    }
}
