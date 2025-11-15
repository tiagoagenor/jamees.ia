<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Aplicativo;
use App\Helpers\PermissionHelper;

class AplicativoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Verificar permissão (se necessário)
        // if (!Auth::user()->temPermissao('aplicativos', 'listar')) {
        //     abort(403, 'Você não tem permissão para listar aplicativos.');
        // }

        $query = Aplicativo::query();

        // Filtro por nome
        $filtroNome = $request->get('nome');
        if ($filtroNome) {
            $query->where('nome', 'like', "%{$filtroNome}%");
        }

        // Filtro por código
        $filtroCodigo = $request->get('codigo');
        if ($filtroCodigo) {
            $query->where('codigo', 'like', "%{$filtroCodigo}%");
        }

        // Filtro por categoria
        $filtroCategoria = $request->get('categoria');
        if ($filtroCategoria && $filtroCategoria !== 'todas' && $filtroCategoria !== 'todos') {
            // Mapear categorias com hífen para formato do banco
            $categoriaMap = [
                'em-alta' => 'Em Alta',
                'e-commerce' => 'E-commerce',
                'logistica' => 'Logística',
                'gestao' => 'Gestão',
            ];
            
            $categoriaFiltro = $categoriaMap[$filtroCategoria] ?? ucfirst($filtroCategoria);
            $query->where('categoria', $categoriaFiltro);
        }

        // Filtro por status
        $filtroStatus = $request->get('status', 'todos');
        if ($filtroStatus !== 'todos') {
            $query->where('ativo', $filtroStatus === 'ativos');
        }

        // Ordenação tri-state
        $sortBy = $request->get('sort_by');
        $sortDirectionParam = strtolower($request->get('sort_direction'));
        $sortDirection = $sortDirectionParam === 'desc' ? 'desc' : ($sortDirectionParam === 'asc' ? 'asc' : null);
        $sortable = [
            'nome' => 'nome',
            'codigo' => 'codigo',
            'preco_mensal' => 'preco_mensal',
            'status' => 'ativo',
        ];
        if ($sortBy && isset($sortable[$sortBy]) && $sortDirection) {
            $query->orderBy($sortable[$sortBy], $sortDirection);
        } else {
            $query->orderBy('nome', 'asc');
        }

        $aplicativos = $query->paginate(15);
        
        // Obter categorias únicas para o filtro
        $categorias = Aplicativo::whereNotNull('categoria')
            ->distinct()
            ->pluck('categoria')
            ->sort()
            ->values();

        // Aplicativos em lançamento (últimos 5 criados)
        $lancamentos = Aplicativo::where('ativo', true)
            ->whereNotNull('imagem')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Aplicativos em alta (pode ser baseado em algum critério, por enquanto os mais recentes)
        $emAlta = Aplicativo::where('ativo', true)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Obter aplicativos contratados pela empresa
        $aplicativosContratados = [];
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        if ($empresaPrincipal) {
            $aplicativosContratados = $empresaPrincipal->aplicativos()->pluck('codigo')->toArray();
        }

        return view('aplicativos.index', compact('aplicativos', 'filtroNome', 'filtroCodigo', 'filtroCategoria', 'filtroStatus', 'sortBy', 'sortDirection', 'categorias', 'lancamentos', 'emAlta', 'aplicativosContratados'));
    }

    /**
     * Show the specified resource.
     */
    public function show(Aplicativo $aplicativo)
    {
        // Obter empresa principal e plano atual
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        $planoAtual = null;
        $periodoPermitido = null;
        $isTeste = false;
        $jaContratado = false;

        if ($empresaPrincipal) {
            $planoAtual = $empresaPrincipal->getPlanoAtual();
            
            // Verificar se o aplicativo já está contratado
            $jaContratado = $empresaPrincipal->aplicativos()->where('aplicativo_id', $aplicativo->id)->exists();
            
            if ($planoAtual) {
                $isTeste = $planoAtual->isTeste();
                
                // Se não for teste, o período do aplicativo deve ser o mesmo do plano
                if (!$isTeste && $planoAtual->periodo) {
                    $periodoPermitido = $planoAtual->periodo->value;
                }
            }
        }

        return view('aplicativos.show', compact('aplicativo', 'planoAtual', 'periodoPermitido', 'isTeste', 'jaContratado'));
    }

    /**
     * Show payment page for application
     */
    public function pagamento(Request $request, Aplicativo $aplicativo)
    {
        $request->validate([
            'periodo' => 'required|in:mensal,trimestral,semestral,anual',
        ]);

        $periodo = $request->get('periodo');
        $preco = $aplicativo->getPrecoPorPeriodo($periodo);

        // Obter empresa principal e plano atual
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        $planoAtual = null;
        $periodoPermitido = null;
        $isTeste = false;

        if ($empresaPrincipal) {
            $planoAtual = $empresaPrincipal->getPlanoAtual();
            
            if ($planoAtual) {
                $isTeste = $planoAtual->isTeste();
                
                // Se for teste, ativar diretamente sem mostrar pagamento
                if ($isTeste) {
                    return $this->ativarAplicativo($aplicativo, $empresaPrincipal);
                }
                
                // Se não for teste, o período do aplicativo deve ser o mesmo do plano
                if (!$isTeste && $planoAtual->periodo) {
                    $periodoPermitido = $planoAtual->periodo->value;
                    
                    // Validar se o período selecionado corresponde ao plano
                    if ($periodo !== $periodoPermitido) {
                        return redirect()->route('aplicativos.show', $aplicativo)
                            ->with('error', "Você só pode contratar aplicativos no período {$planoAtual->periodo->getLabel()} (mesmo período do seu plano atual).");
                    }
                }
            } else {
                return redirect()->route('aplicativos.show', $aplicativo)
                    ->with('error', 'Você precisa ter um plano ativo para contratar aplicativos.');
            }
        } else {
            return redirect()->route('aplicativos.show', $aplicativo)
                ->with('error', 'Empresa principal não encontrada.');
        }

        return view('aplicativos.pagamento', compact('aplicativo', 'periodo', 'preco', 'planoAtual', 'periodoPermitido', 'isTeste'));
    }

    /**
     * Activate application for company
     */
    public function ativarAplicativo(Aplicativo $aplicativo, $empresaPrincipal = null)
    {
        if (!$empresaPrincipal) {
            $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        }

        if (!$empresaPrincipal) {
            return redirect()->route('aplicativos.show', $aplicativo)
                ->with('error', 'Empresa principal não encontrada.');
        }

        // Verificar se o aplicativo já está contratado
        $jaContratado = $empresaPrincipal->aplicativos()->where('aplicativo_id', $aplicativo->id)->exists();

        if (!$jaContratado) {
            // Adicionar aplicativo à empresa
            $empresaPrincipal->aplicativos()->attach($aplicativo->id);
            
            // Recarregar a empresa para garantir que os relacionamentos estão atualizados
            $empresaPrincipal->refresh();
            $empresaPrincipal->unsetRelation('aplicativos');
        }

        // Atualizar sessão de aplicativos
        $aplicativos = $empresaPrincipal->aplicativos()->get();
        session(['aplicativos_empresa' => $aplicativos->pluck('codigo')->toArray()]);
        session()->save(); // Forçar salvamento da sessão

        return redirect()->route('aplicativos.show', $aplicativo)
            ->with('success', "Aplicativo {$aplicativo->nome} contratado com sucesso!");
    }

    /**
     * Process payment and activate application
     */
    public function processarPagamento(Request $request, Aplicativo $aplicativo)
    {
        $request->validate([
            'periodo' => 'required|in:mensal,trimestral,semestral,anual',
            'forma_pagamento' => 'required|in:cartao,pix',
        ]);

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal) {
            return redirect()->route('aplicativos.show', $aplicativo)
                ->with('error', 'Empresa principal não encontrada.');
        }

        $planoAtual = $empresaPrincipal->getPlanoAtual();

        if (!$planoAtual) {
            return redirect()->route('aplicativos.show', $aplicativo)
                ->with('error', 'Você precisa ter um plano ativo para contratar aplicativos.');
        }

        // Validar período
        $periodo = $request->get('periodo');
        if (!$planoAtual->isTeste() && $planoAtual->periodo) {
            if ($periodo !== $planoAtual->periodo->value) {
                return redirect()->back()
                    ->with('error', "Você só pode contratar aplicativos no período {$planoAtual->periodo->getLabel()} (mesmo período do seu plano atual).");
            }
        }

        // Aqui você processaria o pagamento com o gateway de pagamento
        // Por enquanto, vamos apenas ativar o aplicativo
        
        // Verificar se o aplicativo já está contratado
        $jaContratado = $empresaPrincipal->aplicativos()->where('aplicativo_id', $aplicativo->id)->exists();

        if (!$jaContratado) {
            // Adicionar aplicativo à empresa
            $empresaPrincipal->aplicativos()->attach($aplicativo->id);
            
            // Recarregar a empresa para garantir que os relacionamentos estão atualizados
            $empresaPrincipal->refresh();
            $empresaPrincipal->unsetRelation('aplicativos');
        }

        // Atualizar sessão de aplicativos
        $aplicativos = $empresaPrincipal->aplicativos()->get();
        session(['aplicativos_empresa' => $aplicativos->pluck('codigo')->toArray()]);
        session()->save(); // Forçar salvamento da sessão

        return redirect()->route('aplicativos.show', $aplicativo)
            ->with('success', "Aplicativo {$aplicativo->nome} contratado com sucesso!");
    }

    /**
     * Cancel application for company
     */
    public function cancelar(Aplicativo $aplicativo)
    {
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();

        if (!$empresaPrincipal) {
            return redirect()->route('aplicativos.show', $aplicativo)
                ->with('error', 'Empresa principal não encontrada.');
        }

        // Verificar se o aplicativo está contratado
        $jaContratado = $empresaPrincipal->aplicativos()->where('aplicativo_id', $aplicativo->id)->exists();

        if (!$jaContratado) {
            return redirect()->route('aplicativos.show', $aplicativo)
                ->with('error', 'Este aplicativo não está contratado.');
        }

        // Remover aplicativo da empresa
        $empresaPrincipal->aplicativos()->detach($aplicativo->id);
        
        // Recarregar a empresa para garantir que os relacionamentos estão atualizados
        $empresaPrincipal->refresh();
        $empresaPrincipal->unsetRelation('aplicativos');

        // Atualizar sessão de aplicativos
        $aplicativos = $empresaPrincipal->aplicativos()->get();
        session(['aplicativos_empresa' => $aplicativos->pluck('codigo')->toArray()]);
        session()->save(); // Forçar salvamento da sessão

        return redirect()->route('aplicativos.show', $aplicativo)
            ->with('success', "Aplicativo {$aplicativo->nome} cancelado com sucesso!");
    }
}
