<?php
namespace App\Services;

use App\Models\User;
use App\Jobs\EnviarEmailJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class UsuarioService
{
    public function registrarUsuario(array $data): User
    {
        return DB::transaction(function () use ($data) {

            $data['password'] = Hash::make($data['password']);
            $user = User::create($data);

            Cache::put("user_{$user->id}", $user, 300);
            EnviarEmailJob::dispatch($user)->onQueue('emails');

            return $user;
        });
    }

    public function atualizarUsuario(User $user, array $data): bool
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        
        Cache::forget("user_{$user->id}");
        return $user->update($data);
    }
}