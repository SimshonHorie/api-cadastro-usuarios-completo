<?php
namespace App\Policies;

use App\Models\User;

class UsuarioPolicy
{
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }
    public function delete(User $user, User $model): bool
    {
        return $user->role === 'admin' && $user->id !== $model->id;
    }

    public function update(User $user, User $model): bool
    {
        return $user->role === 'admin';
    }
}