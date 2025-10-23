<?php

namespace App\Services\v1\Empresa;

use Illuminate\Http\Request;
use App\Models\Empresa;

class ListarEmpresasService
{
    public function execute(Request $request)
    {
        $query = Empresa::with('whitelabel', 'contatos', 'enderecos');

        // Filtros
        if ($request->filled('nome_fantasia')) {
            $query->where('nome_fantasia', 'like', '%' . $request->nome_fantasia . '%');
        }

        if ($request->filled('razao_social')) {
            $query->where('razao_social', 'like', '%' . $request->razao_social . '%');
        }

        if ($request->filled('cnpj')) {
            $query->where('cnpj', 'like', '%' . $request->cnpj . '%');
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('whitelabel_id')) {
            $query->where('whitelabel_id', $request->whitelabel_id);
        }

        if ($request->filled('uf')) {
            $query->whereHas('enderecos', function($q) use ($request) {
                $q->where('uf', $request->uf);
            });
        }

        // Ordenação
        $sortBy = $request->get('sort_by', 'nome_fantasia');
        $sortDirection = $request->get('sort_direction', 'asc');

        if (in_array($sortBy, ['nome_fantasia', 'razao_social', 'cnpj', 'tipo', 'status', 'criado_em'])) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $empresas = $query->paginate(15)->withQueryString();

        return [
            'empresas' => $empresas,
            'filtros' => $request->only(['nome_fantasia', 'razao_social', 'cnpj', 'tipo', 'status', 'whitelabel_id', 'uf']),
            'ordenacao' => [
                'sort_by' => $sortBy,
                'sort_direction' => $sortDirection
            ]
        ];
    }
}
