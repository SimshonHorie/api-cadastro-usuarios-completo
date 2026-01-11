<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UsuarioService;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Jobs\EnviarEmailBoasVindas;
use Illuminate\Auth\Events\Registered;

class UsuarioController extends Controller
{
    public function __construct(protected UsuarioService $service) {}

    public function index()
    {
        $usuarios = \App\Models\User::all();
        return Inertia::render('Usuarios/Index', [
            'usuarios' => $usuarios
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios,email',
            'role'  => 'nullable|string|in:admin,user',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = \App\Models\User::create([
            'nome' => $data['nome'],
            'email' => $data['email'],
            'role' => $data['role'] ?? 'user',
            'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
        ]);

        \Illuminate\Support\Facades\Redis::setex('usuario:' . $user->id, 300, json_encode($user));
        \App\Jobs\EnviarEmailBoasVindas::dispatch($user)->onConnection('rabbitmq');

        event(new Registered($user));

        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect(route('usuarios.index'))->with('message', 'Novo usuário criado com sucesso!');
        }

        Auth::login($user);

        return redirect(route('dashboard'));
    }

    public function update(Request $request, User $usuario)
    {
        $data = $request->validate([
            'nome'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios,email,' . $usuario->id,
            'role'  => 'required|string|in:admin,user',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $usuario->nome = $data['nome'];
        $usuario->email = $data['email'];
        $usuario->role = $data['role'];

        if ($request->filled('password')) {
            $usuario->password = \Illuminate\Support\Facades\Hash::make($data['password']);
        }

        $usuario->save();

        return redirect()->route('usuarios.index')->with('message', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $usuario)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Ação não autorizada.');
        }

        if ($usuario->id === Auth::id()) {
            return back()->with('error', 'Você não pode excluir sua própria conta.');
        }

        $usuario->delete();

        session()->flash('message', 'Usuário excluído com sucesso!');
    
        return redirect()->route('usuarios.index');
    }

    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        return Inertia::render('Usuarios/Create');
    }

    public function edit(\App\Models\User $usuario)
    {
        return Inertia::render('Usuarios/Edit', [
            'usuario' => $usuario
        ]);
    }
}