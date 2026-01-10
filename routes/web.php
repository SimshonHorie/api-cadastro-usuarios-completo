<?php

use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/usuarios/novo', [UsuarioController::class, 'create'])->name('usuarios.create');
Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
