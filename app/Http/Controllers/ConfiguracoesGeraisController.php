<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfiguracoesGeraisController extends Controller
{
    /**
     * Exibe a tela de configurações gerais
     */
    public function index()
    {
        return view('configuracoes.gerais.index');
    }

    /**
     * Atualiza as configurações gerais
     */
    public function update(Request $request)
    {
        // TODO: Implementar salvamento das configurações
        return response()->json([
            'success' => true,
            'message' => 'Configurações salvas com sucesso!'
        ]);
    }
}
