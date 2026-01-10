<?php

use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::post('/usuarios', [UsuarioController::class, 'store']);