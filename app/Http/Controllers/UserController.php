<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Retorna a pagina com a lista de usuarios
    public function index()
    {
        $users = User::all(); // Pega todos os usuarios cadastrados

        return view('users.index', compact('users'));
    }

    // Mostra o perfil do usuarios
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'user_nome' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'cpf' => 'required|unique:users',
            'password' => 'required|min:6',
            'foto_perfil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $caminho = null;

        if ($request->hasFile('foto_perfil')) {
            $caminho = $request->file('foto_perfil')->store('usuarios', 'public');
        }

        User::create([
            'nome' => $request->nome,
            'user_nome' => $request->user_nome,
            'email' => $request->email,
            'cpf' => $request->cpf,
            'password' => Hash::make($request->password),
            'foto_perfil' => $caminho
        ]);

        return redirect()->route('users.index');
    }
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nome' => 'required',
            'user_nome' => 'required|unique:users,user_nome,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'cpf' => 'required|unique:users,cpf,' . $user->id,
        ]);

        $user->update([
            'nome' => $request->nome,
            'user_nome' => $request->user_nome,
            'email' => $request->email,
            'cpf' => $request->cpf,
        ]);

        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index');
    }
}
