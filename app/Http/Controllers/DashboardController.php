<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Empresa;
use App\Models\Whitelabel;
use App\Models\UltimaAcesso;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $empresaAtual = null;
        $whitelabelAtual = null;
        $ultimoAcesso = null;

        if (session('empresa_atual_id')) {
            $empresaAtual = Empresa::with('whitelabel')->find(session('empresa_atual_id'));
            $whitelabelAtual = $empresaAtual?->whitelabel;
        }

        if (session('whitelabel_atual_id') && session('empresa_atual_id')) {
            $ultimoAcesso = UltimaAcesso::with(['whitelabel', 'empresa'])
                ->where('usuario_id', $user->id)
                ->where('whitelabel_id', session('whitelabel_atual_id'))
                ->where('empresa_id', session('empresa_atual_id'))
                ->first();
        }

        return view('dashboard');
    }
}
