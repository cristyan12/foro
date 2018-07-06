<?php

use App\{User, Token};
use App\Mail\TokenMail;
use Illuminate\Support\Facades\Mail;

class RegistrationTest extends FeatureTestCase
{
    /** @test */
    function a_user_can_create_an_account()
    {
        // Arrange
        Mail::fake();

        // Act
        $this->visitRoute('register')
            ->type('cvalera@mail.com', 'email')
            ->type('cristyan12', 'username')
            ->type('Cristyan', 'first_name')
            ->type('Valera', 'last_name')
            ->press('Regístrate');

        // Assert
        $this->seeInDatabase('users', [
            'email' => 'cvalera@mail.com',
            'username' => 'cristyan12',
            'first_name' => 'Cristyan',
            'last_name' => 'Valera',
        ]);

        $user = User::first();

        $this->seeInDatabase('tokens', [
            'user_id' => $user->id
        ]);

        $token = Token::where('user_id', $user->id)->first();

        $this->assertNotNull($token);

        Mail::assertSentTo($user, TokenMail::class, function ($mail) use ($token) {
            return $mail->token->id == $token->id;
        });

        return;
        
        // todo: Finish this feature
        $this->seeRouteIs('register_confirmation')
            ->see('Gracias por registrarte')
            ->see('Enviamos a tu correo un enlace para que inicies sesión');
    }
}