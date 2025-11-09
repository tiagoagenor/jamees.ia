<?php

namespace App\Services\v1\Usuario;

use Illuminate\Support\Facades\Auth;
use App\Models\Empresa;
use App\Models\Grupo;
use App\Models\Funcionario;
use App\Helpers\PermissionHelper;

class FormularioCriarUsuarioService
{
    public function execute()
    {
        $user = Auth::user();

        // Verificar se o usuário logado é do grupo admin
        $isAdmin = $user->grupos()->where('administrativo', true)->exists();

        if ($isAdmin) {
            // Se for admin: buscar empresa principal + todas as empresas vinculadas
            $empresaPrincipal = $user->empresaPrincipal();
            $empresasVinculadas = $user->empresas()->get();

            // Criar lista de IDs de todas as empresas (principal + vinculadas)
            $todasEmpresasIds = $empresasVinculadas->pluck('id')->toArray();
            if ($empresaPrincipal && !in_array($empresaPrincipal->id, $todasEmpresasIds)) {
                $todasEmpresasIds[] = $empresaPrincipal->id;
            }

            $empresas = Empresa::whereIn('id', $todasEmpresasIds)->get();
        } else {
            // Se não for admin: mostrar apenas empresas vinculadas ao usuário
            $empresaIds = $user->empresas->pluck('id')->toArray();
            $empresas = Empresa::whereIn('id', $empresaIds)->get();
        }

        // Buscar grupos das empresas disponíveis
        $grupos = collect();
        if ($isAdmin) {
            // Para admin: buscar grupos de todas as empresas
            $grupos = Grupo::whereIn('empresa_id', $todasEmpresasIds)
                ->where('ativo', true)
                ->orderBy('administrativo', 'desc')
                ->orderBy('nome')
                ->get();
        } else {
            // Para não-admin: buscar grupos das empresas do usuário
            $grupos = Grupo::whereIn('empresa_id', $empresaIds)
                ->where('ativo', true)
                ->orderBy('administrativo', 'desc')
                ->orderBy('nome')
                ->get();
        }

        // Buscar funcionários disponíveis para vincular (sem usuário vinculado)
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        $funcionarios = collect();
        if ($empresaPrincipal) {
            $funcionarios = Funcionario::daEmpresa($empresaPrincipal->id)
                ->whereNull('usuario_id')
                ->orderBy('nome')
                ->get();
        }

        return view('usuarios.create', [
            'empresas' => $empresas,
            'grupos' => $grupos,
            'funcionarios' => $funcionarios
        ]);
    }
}
