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
        $result = $this->listarEmpresasService->execute($request);

        return view('empresas.index', $result);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $result = $this->formularioCriarEmpresaService->execute();

        return view('empresas.create', $result);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = $this->criarEmpresaService->execute($request);

        if ($result['success']) {
            return redirect()->route('empresas.index')
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
    public function show(Empresa $empresa)
    {
        $result = $this->mostrarEmpresaService->execute($empresa);

        return view('empresas.show', $result);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empresa $empresa)
    {
        $result = $this->formularioEditarEmpresaService->execute($empresa);

        return view('empresas.edit', $result);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empresa $empresa)
    {
        $result = $this->atualizarEmpresaService->execute($request, $empresa);

        if ($result['success']) {
            return redirect()->route('empresas.index')
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
    public function destroy(Empresa $empresa)
    {
        $result = $this->deletarEmpresaService->execute($empresa);

        if ($result['success']) {
            return redirect()->route('empresas.index')
                ->with('success', $result['message']);
        } else {
            return redirect()->back()
                ->with('error', $result['message']);
        }
    }
}
