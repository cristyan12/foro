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

        Mail::assertSent(TokenMail::class, function ($mail) use ($token, $user) {
            return $mail->hasTo($user) && $mail->token->id == $token->id;
        });

        // todo: Finish this feature
        $this->see('Gracias por registrarte')
            ->see('Enviamos a tu correo un enlace para que inicies sesión');
    }
    
    /** @test */
    function it_register_form_validation()
    {
        // Arrange
        Mail::fake();
        
        // Act
        $this->visitRoute('register')
            ->press('Regístrate');

        // Assert
        $this->seePageIs(route('register'))
            ->seeErrors([
                'email' => 'El campo correo electrónico es obligatorio',
                'username' => 'El campo usuario es obligatorio',
                'first_name' => 'El campo nombre es obligatorio',
                'last_name' => 'El campo apellido es obligatorio',
            ]);
    }
}
