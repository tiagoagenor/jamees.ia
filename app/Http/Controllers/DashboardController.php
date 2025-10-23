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

        // Se não há empresa selecionada, selecionar a primeira disponível
        if (!session('current_company') && $user->empresas->count() > 0) {
            $firstCompany = $user->empresas->first();
            session(['current_company' => $firstCompany]);
            session(['current_company_id' => $firstCompany->id]);
        }

        $currentCompany = session('current_company');
        $currentWhitelabel = session('current_whitelabel');
        $lastAccess = session('last_access');

        return view('dashboard', compact('user', 'currentCompany', 'currentWhitelabel', 'lastAccess'));
    }
}
