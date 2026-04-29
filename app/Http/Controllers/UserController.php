<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Parceria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // Lista todos os usuários
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // Perfil do usuário
    public function show(User $user)
    {
        $user->loadCount(['posts', 'followers', 'following']);

        $posts = $user->posts()
            ->with(['medias', 'likes', 'comments', 'user'])
            ->latest()
            ->get();

        return view('users.show', compact('user', 'posts'));
    }

    // Formulário de criação
    public function create()
    {
        return view('users.create');
    }

    // SALVAR USUÁRIO
    public function store(Request $request)
    {
        // 🔥 limpar CPF (remove tudo que não for número)
        $cpf = preg_replace('/\D/', '', $request->cpf);

        // 🔥 garante no máximo 11 dígitos
        $cpf = substr($cpf, 0, );

        $request->validate([
            'nome' => 'required',
            'user_nome' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'cpf' => 'required|digits:11|unique:users,cpf',
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
            'cpf' => $cpf,
            'password' => Hash::make($request->password),
            'foto_perfil' => $caminho
        ]);

        Auth::login($user);

        return redirect()->route('users.show', $user->id);
    }

    // Editar usuário
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // ATUALIZAR USUÁRIO
    public function update(Request $request, User $user)
    {
        $request->validate([
            'nome' => 'required',
            'user_nome' => 'required|unique:users,user_nome,' . $user->id,
            'foto_perfil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'senha_atual' => 'required',
            'password' => 'nullable|min:6|confirmed'
        ]);

        if (!Hash::check($request->senha_atual, $user->password)) {
            return back()->withErrors(['senha_atual' => 'Senha atual incorreta']);
        }

        $user->nome = $request->nome;
        $user->user_nome = $request->user_nome;

        if ($request->hasFile('foto_perfil')) {
            $caminho = $request->file('foto_perfil')->store('usuarios', 'public');
            $user->foto_perfil = $caminho;
        }

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.show', $user->id)
            ->with('success', 'Perfil atualizado com sucesso!');
    }

    // DELETAR USUÁRIO
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index');
    }

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

    // SOLICITAR PARCERIA
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

    // ACEITAR PARCERIA
    public function aceitarParceria($id)
    {
        $parceria = Parceria::findOrFail($id);
        $parceria->update(['status' => 'aceito']);

        return back();
    }

    // RECUSAR PARCERIA
    public function recusarParceria($id)
    {
        $parceria = Parceria::findOrFail($id);
        $parceria->update(['status' => 'recusado']);

        return back();
    }

    // LISTAR PARCERIAS
    public function parcerias()
    {
        $parcerias = Parceria::with('solicitante')
            ->where('user_id', auth()->id())
            ->where('status', 'pendente')
            ->get();

        return view('users.parcerias', compact('parcerias'));
    }

    // REMOVER FOTO
    public function deleteFoto(User $user)
    {
        if (auth()->id() !== $user->id) {
            abort(403);
        }

        if ($user->foto_perfil) {
            Storage::disk('public')->delete($user->foto_perfil);
        }

        $user->foto_perfil = null;
        $user->save();

        return back()->with('success', 'Foto removida!');
    }
}
