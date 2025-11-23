<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AtualizacaoSistema;

class AtualizacaoSistemaController extends Controller
{
    /**
     * Lista todas as atualizações do sistema
     */
    public function index()
    {
        $atualizacoes = AtualizacaoSistema::orderBy('created_at', 'desc')->paginate(20);
        
        return view('atualizacoes.index', compact('atualizacoes'));
    }

    /**
     * Exibe os detalhes de uma atualização
     */
    public function show(AtualizacaoSistema $atualizacao)
    {
        return view('atualizacoes.show', compact('atualizacao'));
    }
}
