<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MidiaController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SkillController;
use App\Models\Skill;

//
// 🔎 SKILLS (AUTOCOMPLETE)
//
Route::get('/skills', function (Request $request) {
    if (!$request->search) {
        return response()->json([]);
    }

    return Skill::where('nome', 'like', '%' . $request->search . '%')
        ->select('id', 'nome', 'category')
        ->limit(10)
        ->get();
});

//
// 👤 USUÁRIOS
//
Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');
Route::get('/usuarios/{user}', [UserController::class, 'show'])->name('users.show');

Route::get('/cadastre-se', [UserController::class, 'create'])->name('users.create');
Route::post('/cadastre-se', [UserController::class, 'store'])->name('users.store');

Route::get('/perfil/{user}/editar', [UserController::class, 'edit'])->name('users.edit');
Route::put('/perfil/{user}', [UserController::class, 'update'])->name('users.update');

Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])->name('users.destroy');

Route::delete('/midia/{id}', [MidiaController::class, 'destroy'])->name('midia.destroy');

//
// 🔐 LOGIN
//
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'attempt'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//
// 🔒 ROTAS PROTEGIDAS
//
Route::middleware('auth')->group(function () {

    // 🏠 HOME
    Route::get('/', [PostController::class, 'feed'])->name('home');

    // 🔥 ONBOARDING
    Route::get('/onboarding', [UserController::class, 'onboarding'])->name('onboarding');
    Route::post('/onboarding', [UserController::class, 'saveOnboarding'])->name('onboarding.save');

    // 📝 POSTS
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::post('/posts/{post}/comment', [PostController::class, 'comment'])->name('posts.comment');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

    // 🔎 EXPLORAR
    Route::get('/explorar', [UserController::class, 'explorar'])->name('users.explore');

    // 👥 SEGUIR
    Route::post('/seguir/{user}', [UserController::class, 'follow'])->name('follow');
    Route::delete('/deixar-seguir/{user}', [UserController::class, 'unfollow'])->name('unfollow');

    Route::delete('/remover-seguidor/{user}', [UserController::class, 'removeFollower'])
    ->name('remove.follower')
    ->middleware('auth');

    // 🤝 PARCERIAS
    Route::post('/parceria/{user}', [UserController::class, 'solicitarParceria'])->name('parceria.solicitar');
    Route::post('/parceria/{id}/aceitar', [UserController::class, 'aceitarParceria'])->name('parceria.aceitar');
    Route::post('/parceria/{id}/recusar', [UserController::class, 'recusarParceria'])->name('parceria.recusar');
    Route::get('/parcerias', [UserController::class, 'parcerias'])->name('parcerias');

    // 💬 CHAT
    Route::get('/chat/{user}', [MessageController::class, 'chat'])->name('chat');
    Route::post('/chat/{user}', [MessageController::class, 'send'])->name('chat.send');
    Route::get('/chat/{user}/messages', [MessageController::class, 'getMessages']);
    Route::get('/inbox', [MessageController::class, 'inbox'])->name('chat.inbox');

    // 🖼️ FOTO PERFIL
    Route::delete('/user/{user}/foto', [UserController::class, 'deleteFoto'])->name('user.deleteFoto');

    // 📰 FEED
    Route::get('/feed', [PostController::class, 'feed'])->name('feed');

    // 🔔 NOTIFICAÇÕES (CORRIGIDO)
    Route::get('/notificacoes', function () {
        return response()->json([
            'count' => \App\Models\Parceria::where('user_id', auth()->id())
                ->where('status', 'pendente')
                ->count()
        ]);
    })->name('notificacoes');

    Route::post('/skills', [SkillController::class, 'store']);
});
