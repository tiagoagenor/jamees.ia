<?php

namespace App\Services\v1\Usuario;

use Illuminate\Support\Facades\Auth;
use App\Models\Empresa;
use App\Models\Grupo;

class FormularioCriarUsuarioService
{
    public function execute()
    {
        $user = Auth::user();

        // Buscar empresa principal do usuário logado
        $empresaPrincipal = $user->empresas()->wherePivot('principal', 1)->first();

        if ($empresaPrincipal) {
            // Incluir empresa principal + empresas filhas
            $empresas = Empresa::where(function($q) use ($empresaPrincipal) {
                $q->where('id', $empresaPrincipal->id) // Empresa principal
                  ->orWhere('empresa_id', $empresaPrincipal->id); // Empresas filhas
            })->get();
        } else {
            // Se não tem empresa principal, mostrar todas as empresas do usuário
            $empresaIds = $user->empresas->pluck('id')->toArray();
            $empresas = Empresa::whereIn('id', $empresaIds)->get();
        }

        // Buscar grupos da empresa principal
        $grupos = collect();
        if ($empresaPrincipal) {
            $grupos = Grupo::where('empresa_id', $empresaPrincipal->id)
                ->where('ativo', true)
                ->orderBy('administrativo', 'desc')
                ->orderBy('nome')
                ->get();
        }

        return view('usuarios.create', [
            'empresas' => $empresas,
            'empresaPrincipal' => $empresaPrincipal,
            'grupos' => $grupos
        ]);
    }
}
