<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {

        User::factory()->create([
            'nome' => 'Administrador',
            'email' => 'admin@teste.com',
            'role' => 'admin',
        ]);

        User::factory(10)->create()->each(function ($user) {
            \Illuminate\Support\Facades\Redis::setex('usuario:' . $user->id, 300, json_encode($user));
        });
    }
}