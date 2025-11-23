<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Empresa;
use App\Models\Whitelabel;
use App\Models\UltimaAcesso;
use App\Models\ConfiguracaoEmpresa;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Buscar empresa atual da sessão
        $currentCompany = null;
        if (session('empresa_atual_id')) {
            $currentCompany = Empresa::find(session('empresa_atual_id'));
        }

        // Se não há empresa selecionada, selecionar a primeira disponível
        if (!$currentCompany && $user->empresas->count() > 0) {
            // Primeiro, tentar encontrar empresa principal
            $empresaPrincipal = $user->empresas()->wherePivot('principal', 1)->first();

            if ($empresaPrincipal) {
                $currentCompany = $empresaPrincipal;
                session(['empresa_atual_id' => $empresaPrincipal->id]);
                session(['whitelabel_atual_id' => $empresaPrincipal->whitelabel_id]);
            } else {
                // Se não existe empresa principal, pegar a primeira empresa da lista
                $currentCompany = $user->empresas->first();
                session(['empresa_atual_id' => $currentCompany->id]);
                session(['whitelabel_atual_id' => $currentCompany->whitelabel_id]);
            }
        }

        $currentWhitelabel = null;
        if (session('whitelabel_atual_id')) {
            $currentWhitelabel = Whitelabel::find(session('whitelabel_atual_id'));
        }

        $lastAccess = null;
        if ($currentCompany) {
            $lastAccess = UltimaAcesso::where('usuario_id', $user->id)
                ->where('empresa_id', $currentCompany->id)
                ->first();
        }

        $configuracao = null;
        if ($currentCompany) {
            $configuracao = ConfiguracaoEmpresa::where('empresa_id', $currentCompany->id)->first();
        }

        return view('dashboard', compact('user', 'currentCompany', 'currentWhitelabel', 'lastAccess', 'configuracao'));
    }
}
