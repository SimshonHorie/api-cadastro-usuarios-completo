<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsuarioRequest;
use App\Services\UsuarioService;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;

class UsuarioController extends Controller
{
    public function __construct(private UsuarioService $usuarioService) {}

    public function create()
    {
        return Inertia::render('Usuarios/Create');
    }

    public function store(StoreUsuarioRequest $request)
    {
        $usuario = $this->usuarioService->cadastrar($request->validated());

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Usuário criado!',
                'data' => $usuario
            ], 201);
        }

        return redirect()->back()->with('message', 'Usuário criado!');
    }
}
