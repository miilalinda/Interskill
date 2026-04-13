<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function login()
    {
        return view('auth.login');
    }

    public function attempt(Request $request)
    {
        // Validação
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6']
        ], [
            'email.required' => 'O email é obrigatório',
            'email.email' => 'Digite um email válido',
            'password.required' => 'A senha é obrigatória',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres'
        ]);

        $credenciais = $request->only('email', 'password');

        if (Auth::attempt($credenciais)) {

            $request->session()->regenerate();

            return redirect()->route('home'); // melhor mudar pra home
        }

        return back()->withErrors([
            'email' => 'Email ou senha incorretos'
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
