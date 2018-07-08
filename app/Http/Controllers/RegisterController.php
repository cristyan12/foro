<?php

namespace App\Http\Controllers;

use App\{User, Token};
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function create()
    {
        return view('register.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'username' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        $user = User::create($request->all());

        Token::generateFor($user)->sendByEmail();

        alert('Gracias por registrarte');
        alert('Enviamos a tu correo un enlace para que inicies sesión');

        return back();
    }
}
