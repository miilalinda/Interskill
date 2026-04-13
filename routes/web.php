<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MidiaController;

//
// USUARIOS
//

// Lista de usuários
Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');

// Mostrar perfil do usuário
Route::get('/usuarios/{user}', [UserController::class, 'show'])->name('users.show');

// Tela de cadastro
Route::get('/cadastre-se', [UserController::class, 'create'])->name('users.create');
Route::post('/cadastre-se', [UserController::class, 'store'])->name('users.store');

// Editar, atualizar e deletar usuários
Route::get('/usuarios/{user}/editar', [UserController::class, 'edit'])->name('users.edit');
Route::put('/usuarios/{user}', [UserController::class, 'update'])->name('users.update');
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

Route::get('/', [PostController::class, 'index'])->name('home');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
Route::post('/posts/{post}/comment', [PostController::class, 'comment'])->name('posts.comment');

});
// Rota para explorar perfis
Route::get('/explorar', [UserController::class, 'explore'])->name('users.explore');

