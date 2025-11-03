<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Services\v1\Usuario\ListarUsuariosService;
use App\Services\v1\Usuario\FormularioCriarUsuarioService;
use App\Services\v1\Usuario\CriarUsuarioService;
use App\Services\v1\Usuario\MostrarUsuarioService;
use App\Services\v1\Usuario\FormularioEditarUsuarioService;
use App\Services\v1\Usuario\AtualizarUsuarioService;
use App\Services\v1\Usuario\DeletarUsuarioService;

class UsuarioController extends Controller
{
    public function __construct(
        private ListarUsuariosService $listarUsuariosService,
        private FormularioCriarUsuarioService $formularioCriarUsuarioService,
        private CriarUsuarioService $criarUsuarioService,
        private MostrarUsuarioService $mostrarUsuarioService,
        private FormularioEditarUsuarioService $formularioEditarUsuarioService,
        private AtualizarUsuarioService $atualizarUsuarioService,
        private DeletarUsuarioService $deletarUsuarioService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->listarUsuariosService->execute($request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->formularioCriarUsuarioService->execute();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->criarUsuarioService->execute($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Usuario $usuario)
    {
        return $this->mostrarUsuarioService->execute($usuario);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Usuario $usuario)
    {
        return $this->formularioEditarUsuarioService->execute($usuario);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Usuario $usuario)
    {
        return $this->atualizarUsuarioService->execute($request, $usuario);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario)
    {
        return $this->deletarUsuarioService->execute($usuario);
    }
}
