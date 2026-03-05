<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('home');
})->name('home');

//
// USUARIOS
//

// Lista de usuarios
Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');
// Tela de cadastrp
Route::get('/cadastre-se', [UserController::class, 'create'])->name('users.create');
// Perfil do usuario
Route::get('/perfil/{user}', [UserController::class, 'show'])->name('users.show');
// Atualizar perfil
Route::get('/perfil/atualizar/{user}', [UserController::class, 'edit'])->name('users.edit');

Route::post('/cadastre-se', [UserController::class, 'store'])->name('users.store');
Route::delete('/remover-conta/{user}', [UserController::class, 'destroy'])->name('users.destroy');
Route::put('/perfil/atualizar/{user}', [UserController::class, 'update'])->name('users.update');


// Route::get();
// Route::get();
// Route::get();
