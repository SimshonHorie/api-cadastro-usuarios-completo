<?php
namespace App\Services;

use App\Models\User;
use App\Jobs\EnviarEmailBoasVindas;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UsuarioService
{
    public function cadastrar(array $dados): User
    {
        return DB::transaction(function () use ($dados) {
 
            $user = User::create($dados);

            // Cache Redis (TTL 300s)
            Cache::put("usuario:{$user->id}", $user, 300);

            EnviarEmailBoasVindas::dispatch($user)->onQueue('emails');

            return $user;
        });
    }
}