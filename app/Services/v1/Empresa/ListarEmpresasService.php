<?php

namespace App\Services\v1\Empresa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Empresa;

class ListarEmpresasService
{
    public function execute(Request $request)
    {
        $user = Auth::user();

        // Buscar empresa principal do usuário
        $empresaPrincipal = $user->empresas()->wherePivot('principal', 1)->first();

        if ($empresaPrincipal) {
            // Incluir empresa principal + empresas filhas
            $query = Empresa::with('whitelabel', 'contatos', 'enderecos')
                            ->where(function($q) use ($empresaPrincipal) {
                                $q->where('id', $empresaPrincipal->id) // Empresa principal
                                  ->orWhere('empresa_id', $empresaPrincipal->id); // Empresas filhas
                            });
        } else {
            // Se não tem empresa principal, mostrar todas as empresas do usuário
            $empresaIds = $user->empresas->pluck('id')->toArray();
            $query = Empresa::with('whitelabel', 'contatos', 'enderecos')
                            ->whereIn('id', $empresaIds);
        }

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

        return view('empresas.index', [
            'empresas' => $empresas,
            'empresaPrincipal' => $empresaPrincipal,
            'filtros' => $request->only(['nome_fantasia', 'razao_social', 'cnpj', 'tipo', 'status', 'uf']),
            'ordenacao' => [
                'sort_by' => $sortBy,
                'sort_direction' => $sortDirection
            ]
        ]);
    }
}
