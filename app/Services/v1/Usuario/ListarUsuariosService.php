<?php

namespace App\Services\v1\Usuario;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Models\Empresa;

class ListarUsuariosService
{
    public function execute(Request $request)
    {
        $user = Auth::user();

        // Buscar empresa principal do usuário logado
        $empresaPrincipal = $user->empresas()->wherePivot('principal', 1)->first();


        if ($empresaPrincipal) {
            // Filtrar usuários da empresa principal + empresas filhas
            $empresaIds = [$empresaPrincipal->id];
            $empresaIds = array_merge($empresaIds, $empresaPrincipal->empresasFilhas->pluck('id')->toArray());

            $query = Usuario::with('empresas', 'geral', 'enderecos', 'telefones', 'grupos')
                            ->whereHas('empresas', function($q) use ($empresaIds) {
                                $q->whereIn('empresa.id', $empresaIds);
                            });
        } else {
            // Se não tem empresa principal, mostrar usuários das empresas do usuário logado
            $empresaIds = $user->empresas->pluck('id')->toArray();
            $query = Usuario::with('empresas', 'geral', 'enderecos', 'telefones', 'grupos')
                            ->whereHas('empresas', function($q) use ($empresaIds) {
                                $q->whereIn('empresa.id', $empresaIds);
                            });
        }

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

        // Filtrar empresas para mostrar apenas as da empresa principal
        if ($empresaPrincipal) {
            $empresaIds = [$empresaPrincipal->id];
            $empresaIds = array_merge($empresaIds, $empresaPrincipal->empresasFilhas->pluck('id')->toArray());
            $empresas = Empresa::whereIn('id', $empresaIds)->get();
        } else {
            $empresas = $user->empresas;
        }

        return view('usuarios.index', [
            'usuarios' => $usuarios,
            'empresas' => $empresas,
            'filtros' => $request->only(['nome', 'email', 'status', 'empresa_id', 'cpf', 'uf']),
            'ordenacao' => [
                'sort_by' => $sortBy,
                'sort_direction' => $sortDirection
            ]
        ]);
    }
}
