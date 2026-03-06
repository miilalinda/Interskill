<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('home');
})->name('home');

//
// USUARIOS
//

// Lista de usuarios
Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');

// Tela de cadastro
Route::get('/cadastre-se', [UserController::class, 'create'])->name('users.create');

Route::post('/cadastre-se', [UserController::class, 'store'])->name('users.store');

// Perfil do usuario
Route::get('/perfil/{user}', [UserController::class, 'show'])->name('users.show');

//
// LOGIN
//

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//
// ROTAS PROTEGIDAS
//

Route::middleware('auth')->group(function () {

    Route::get('/perfil/atualizar/{user}', [UserController::class, 'edit'])->name('users.edit');

    Route::put('/perfil/atualizar/{user}', [UserController::class, 'update'])->name('users.update');

    Route::delete('/remover-conta/{user}', [UserController::class, 'destroy'])->name('users.destroy');


});
