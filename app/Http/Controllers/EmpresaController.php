<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Services\v1\Empresa\ListarEmpresasService;
use App\Services\v1\Empresa\FormularioCriarEmpresaService;
use App\Services\v1\Empresa\CriarEmpresaService;
use App\Services\v1\Empresa\MostrarEmpresaService;
use App\Services\v1\Empresa\FormularioEditarEmpresaService;
use App\Services\v1\Empresa\AtualizarEmpresaService;
use App\Services\v1\Empresa\DeletarEmpresaService;

class EmpresaController extends Controller
{
    public function __construct(
        private ListarEmpresasService $listarEmpresasService,
        private FormularioCriarEmpresaService $formularioCriarEmpresaService,
        private CriarEmpresaService $criarEmpresaService,
        private MostrarEmpresaService $mostrarEmpresaService,
        private FormularioEditarEmpresaService $formularioEditarEmpresaService,
        private AtualizarEmpresaService $atualizarEmpresaService,
        private DeletarEmpresaService $deletarEmpresaService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->listarEmpresasService->execute($request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->formularioCriarEmpresaService->execute();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->criarEmpresaService->execute($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Empresa $empresa)
    {
        return $this->mostrarEmpresaService->execute($empresa);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empresa $empresa)
    {
        return $this->formularioEditarEmpresaService->execute($empresa);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empresa $empresa)
    {
        return $this->atualizarEmpresaService->execute($request, $empresa);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empresa $empresa)
    {
        return $this->deletarEmpresaService->execute($empresa);
    }
}
