<?php

namespace App\Services\v1\Usuario;

use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Models\Empresa;

class FormularioEditarUsuarioService
{
    public function execute(Usuario $usuario)
    {
        $usuario->load('empresas', 'geral', 'enderecos', 'telefones');

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

        return view('usuarios.edit', [
            'usuario' => $usuario,
            'empresas' => $empresas,
            'empresaPrincipal' => $empresaPrincipal
        ]);
    }
}
