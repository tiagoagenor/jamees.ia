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
        $result = $this->listarUsuariosService->execute($request);

        return view('usuarios.index', $result);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $result = $this->formularioCriarUsuarioService->execute();

        return view('usuarios.create', $result);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = $this->criarUsuarioService->execute($request);

        if ($result['success']) {
            return redirect()->route('usuarios.index')
                ->with('success', $result['message']);
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', $result['message']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Usuario $usuario)
    {
        $result = $this->mostrarUsuarioService->execute($usuario);

        return view('usuarios.show', $result);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Usuario $usuario)
    {
        $result = $this->formularioEditarUsuarioService->execute($usuario);

        return view('usuarios.edit', $result);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Usuario $usuario)
    {
        $result = $this->atualizarUsuarioService->execute($request, $usuario);

        if ($result['success']) {
            return redirect()->route('usuarios.index')
                ->with('success', $result['message']);
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', $result['message']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario)
    {
        $result = $this->deletarUsuarioService->execute($usuario);

        if ($result['success']) {
            return redirect()->route('usuarios.index')
                ->with('success', $result['message']);
        } else {
            return redirect()->back()
                ->with('error', $result['message']);
        }
    }
}
