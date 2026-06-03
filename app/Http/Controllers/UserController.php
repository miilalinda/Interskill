<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Parceria;
use App\Models\Skill; // 🔥 IMPORTANTE
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Notification;

class UserController extends Controller
{
    // LISTAR USUÁRIOS
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // PERFIL
    public function show(User $user)
    {
        $user->load([
            'skills',
            'followers',
            'following',
            'highlights'
        ]);
        $posts = $user->posts()
            ->with(['medias', 'likes', 'comments', 'user'])
            ->latest()
            ->get();

        $user->posts_count = $posts->count();
        $user->followers_count = $user->followers->count();
        $user->following_count = $user->following->count();

        return view('users.show', compact('user', 'posts'));
    }
    // FORM CADASTRO
    public function create()
    {
        return view('users.create');
    }

    // CADASTRO + REDIRECT ONBOARDING
    public function store(Request $request)
    {
        $cpf = preg_replace('/\D/', '', $request->cpf);
        $cpf = substr($cpf, 0, 11);

        $request->merge([
            'cpf' => $cpf
        ]);

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

        return redirect()->route('onboarding');
    }

    // 🔥 ONBOARDING (CORRIGIDO)
    public function onboarding()
    {
        $skills = Skill::orderBy('nome')->get();

        return view('users.onboarding', compact('skills'));
    }

    // SALVAR ONBOARDING
    public function saveOnboarding(Request $request)
    {
        $user = auth()->user();

        $user->bio = $request->bio;
        $user->save();

        if ($request->skills) {
            foreach ($request->skills as $skillId => $nivel) {
                $user->skills()->syncWithoutDetaching([
                    $skillId => ['nivel' => $nivel]
                ]);
            }
        }

        return redirect()->route('home');
    }

    // EDITAR PERFIL
    public function edit(User $user)
    {
        Gate::authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    // ATUALIZAR PERFIL
    public function update(Request $request, User $user)
    {
        Gate::authorize('update', $user);

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
    public function explore(Request $request)
{
    $users = collect();

    if ($request->filled('q')) {

        $q = trim($request->q);

        $users = User::where('id', '!=', auth()->id())

            ->where(function ($query) use ($q) {

                $query->where('nome', 'LIKE', "%{$q}%")
                    ->orWhere('user_nome', 'LIKE', "%{$q}%")
                    ->orWhereHas('skills', function ($skill) use ($q) {

                        $skill->where('nome', 'LIKE', "%{$q}%");

                    });

            })

            ->with('skills')
            ->withCount([
                'posts',
                'followers',
                'following'
            ])
            ->paginate(9);
    }

    return view('users.explore', compact('users'));
}

    // SEGUIR
    public function follow(User $user)
    {
        auth()->user()
            ->following()
            ->syncWithoutDetaching([$user->id]);

        // cria notificação
        if (auth()->id() != $user->id) {

            Notification::create([

                'user_id' => $user->id,

                'from_user_id' => auth()->id(),

                'type' => 'follow',

                'message' => auth()->user()->nome . ' começou a seguir você.',

                'url' => route('users.show', auth()->id())

            ]);
        }

        return back();
    }

    // DEIXAR DE SEGUIR
    public function unfollow(User $user)
    {
        auth()->user()->following()->detach($user->id);
        return back();
    }

    public function removeFollower(User $user)
    {
        auth()->user()->followers()->detach($user->id);

        return back();
    }

    // PARCERIAS
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

    public function aceitarParceria($id)
    {
        $parceria = Parceria::findOrFail($id);
        $parceria->update(['status' => 'aceito']);
        return back();
    }

    public function recusarParceria($id)
    {
        $parceria = Parceria::findOrFail($id);
        $parceria->update(['status' => 'recusado']);
        return back();
    }

    public function parcerias()
    {
        $parcerias = Parceria::with('solicitante')
            ->where('user_id', auth()->id())
            ->where('status', 'pendente')
            ->get();

        return view('users.parcerias', compact('parcerias'));
    }

    // FOTO PERFIL
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

    public function removeHighlight(\App\Models\Highlight $highlight)
{
    // impede apagar destaque dos outros
    if ($highlight->user_id !== auth()->id()) {
        abort(403);
    }

    // remove imagem
    if ($highlight->imagem) {
        \Storage::disk('public')->delete($highlight->imagem);
    }

    // remove audio
    if ($highlight->audio) {
        \Storage::disk('public')->delete($highlight->audio);
    }

    // remove destaque do banco
    $highlight->delete();

    return back()->with('success', 'Destaque removido!');
}

public function reactHighlight(Request $request, \App\Models\Highlight $highlight)
{
    $request->validate([
        'emoji' => 'nullable|string|max:10',
    ]);

    \App\Models\HighlightReaction::updateOrCreate(
        [
            'highlight_id' => $highlight->id,
            'user_id' => auth()->id(),
        ],
        [
            'emoji' => $request->emoji ?? '❤️',
        ]
    );

    return response()->json([
        'success' => true,
        'count' => $highlight->reactions()->count(),
    ]);
}
}
