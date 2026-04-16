<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Parceria;
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
    $user->loadCount(['posts', 'followers', 'following']);

    $posts = $user->posts()
        ->with(['medias', 'likes', 'comments', 'user'])
        ->latest()
        ->get();

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
    // ✅ VALIDAÇÃO
    $request->validate([
        'nome' => 'required',
        'user_nome' => 'required|unique:users,user_nome,' . $user->id,
        'foto_perfil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'senha_atual' => 'required',
        'password' => 'nullable|min:6|confirmed'
    ]);

    // 🔒 CONFIRMAR SENHA ATUAL
    if (!Hash::check($request->senha_atual, $user->password)) {
        return back()->withErrors(['senha_atual' => 'Senha atual incorreta']);
    }

    // ✅ ATUALIZA DADOS PERMITIDOS
    $user->nome = $request->nome;
    $user->user_nome = $request->user_nome;

    // 🚫 NÃO ALTERA MAIS
    // $user->email = ...
    // $user->cpf = ...

    // 📸 FOTO
    if ($request->hasFile('foto_perfil')) {
        $caminho = $request->file('foto_perfil')->store('usuarios', 'public');
        $user->foto_perfil = $caminho;
    }

    // 🔑 SENHA NOVA (opcional)
    if ($request->password) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return redirect()->route('users.show', $user->id)
        ->with('success', 'Perfil atualizado com sucesso!');
}

    // Deleta um usuário
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index');
    }

    // Nova função: Explorar perfis
    // EXPLORAR
    public function explore()
    {
        $users = User::where('id', '!=', auth()->id())
            ->withCount(['posts', 'followers', 'following'])
            ->latest()
            ->paginate(9);

        return view('users.explore', compact('users'));
    }

    // SEGUIR
    public function follow(User $user)
    {
        auth()->user()->following()->syncWithoutDetaching([$user->id]);
        return back();
    }

    // DEIXAR DE SEGUIR
    public function unfollow(User $user)
    {
        auth()->user()->following()->detach($user->id);
        return back();
    }

    // ENVIAR SOLICITAÇÃO
    public function solicitarParceria(User $user)
    {
        Parceria::firstOrCreate(
            [
                'user_id' => $user->id,
                'solicitante_id' => auth()->id(),
            ],
            [
                'status' => 'pendente'
            ]
        );

        return back();
    }

    // ACEITAR
    public function aceitarParceria($id)
    {
        $parceria = Parceria::findOrFail($id);
        $parceria->update(['status' => 'aceito']);

        return back();
    }

    // RECUSAR
    public function recusarParceria($id)
    {
        $parceria = Parceria::findOrFail($id);
        $parceria->update(['status' => 'recusado']);

        return back();
    }

    public function parcerias()
    {
        $parcerias = \App\Models\Parceria::with('solicitante')
            ->where('user_id', auth()->id())
            ->where('status', 'pendente')
            ->get();

        return view('users.parcerias', compact('parcerias'));
    }
}
