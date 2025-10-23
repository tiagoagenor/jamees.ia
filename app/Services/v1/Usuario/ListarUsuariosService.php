<?php

namespace App\Services\v1\Usuario;

use Illuminate\Http\Request;
use App\Models\Usuario;

class ListarUsuariosService
{
    public function execute(Request $request)
    {
        $query = Usuario::with('empresas', 'geral', 'enderecos', 'telefones');

        // Filtros
        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('empresa_id')) {
            $query->whereHas('empresas', function($q) use ($request) {
                $q->where('empresa_id', $request->empresa_id);
            });
        }

        if ($request->filled('cpf')) {
            $query->whereHas('geral', function($q) use ($request) {
                $q->where('cpf', 'like', '%' . $request->cpf . '%');
            });
        }

        if ($request->filled('uf')) {
            $query->whereHas('enderecos', function($q) use ($request) {
                $q->where('uf', $request->uf);
            });
        }

        // Ordenação
        $sortBy = $request->get('sort_by', 'nome');
        $sortDirection = $request->get('sort_direction', 'asc');

        if (in_array($sortBy, ['nome', 'email', 'status', 'criado_em'])) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $usuarios = $query->paginate(15)->withQueryString();

        return [
            'usuarios' => $usuarios,
            'filtros' => $request->only(['nome', 'email', 'status', 'empresa_id', 'cpf', 'uf']),
            'ordenacao' => [
                'sort_by' => $sortBy,
                'sort_direction' => $sortDirection
            ]
        ];
    }
}
