<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MidiaController;
use App\Http\Controllers\MessageController;
//
// USUARIOS
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
// LOGIN
//

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'attempt'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//
// ROTAS PROTEGIDAS
//

Route::middleware('auth')->group(function () {

    // HOME (FEED)
    Route::get('/', [PostController::class, 'index'])->name('home');

    // POSTS
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::post('/posts/{post}/comment', [PostController::class, 'comment'])->name('posts.comment');

    // EXPLORAR PERFIS
    Route::get('/explorar', [UserController::class, 'explore'])->name('users.explore');

    // SEGUIR / DEIXAR DE SEGUIR
    Route::post('/seguir/{user}', [UserController::class, 'follow'])->name('follow');
    Route::delete('/deixar-seguir/{user}', [UserController::class, 'unfollow'])->name('unfollow');

    // PARCERIAS
    Route::post('/parceria/{user}', [UserController::class, 'solicitarParceria'])->name('parceria.solicitar');
    Route::post('/parceria/{id}/aceitar', [UserController::class, 'aceitarParceria'])->name('parceria.aceitar');
    Route::post('/parceria/{id}/recusar', [UserController::class, 'recusarParceria'])->name('parceria.recusar');
    Route::get('/parcerias', [UserController::class, 'parcerias'])->name('parcerias');



    Route::get('/chat/{user}', [MessageController::class, 'chat'])->name('chat');
    Route::post('/chat/{user}', [MessageController::class, 'send'])->name('chat.send');
    Route::get('/chat/{user}/messages', [MessageController::class, 'getMessages']);
    Route::get('/inbox', [MessageController::class, 'inbox'])->name('chat.inbox');
    Route::get('/notificacoes', [MessageController::class, 'notificacoes']);
});
