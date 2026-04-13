<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Lista todos os usuários (página administrativa)
    public function index()
    {
        $users = User::all(); // Pega todos os usuários cadastrados
        return view('users.index', compact('users'));
    }

    // Mostra o perfil de um usuário específico
    public function show(User $user)
    {
        // Pega os posts do usuário, mais recentes primeiro
        $posts = $user->posts()->latest()->get();
        return view('users.show', compact('user', 'posts'));
    }

    // Página de cadastro de usuário
    public function create()
    {
        return view('users.create');
    }

    // Salva um novo usuário no banco de dados
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

        $user = User::create([
            'nome' => $request->nome,
            'user_nome' => $request->user_nome,
            'email' => $request->email,
            'cpf' => $request->cpf,
            'password' => Hash::make($request->password),
            'foto_perfil' => $caminho
        ]);

        Auth::login($user);

        return redirect()->route('users.show', $user->id);
    }

    // Página para editar usuário
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // Atualiza os dados de um usuário
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

    // Deleta um usuário
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index');
    }

    // Nova função: Explorar perfis
    public function explore()
    {
        $users = User::all();
        return view('users.explore', compact('users'));
    }
}
