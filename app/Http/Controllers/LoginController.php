<?php

namespace App\Http\Controllers;

use App\Token;

class LoginController extends Controller
{
    public function login($token)
    {
        $token = Token::findActive($token);

        if (is_null($token)) {
            alert('Este enlace ya expiró, por favor solicita otro');

            return redirect()->route('token');
        }

        $token->login();

        return redirect('/');
    }

    // public function logout()
    // {
    //     auth()->logout();

    //     request()->session()->flush();

    //     request()->session()->regenerate();

    //     alert('Hasta pronto!');

    //     return redirect('/');
    // }
}
