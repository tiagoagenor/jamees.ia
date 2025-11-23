<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Empresa;
use App\Models\Whitelabel;
use App\Models\UltimaAcesso;
use App\Models\Configuracao;
use App\Models\AtualizacaoSistema;
use App\Models\Ideia;
use App\Enums\IdeiaStatusEnum;

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

        $configuracoes = [];
        if ($currentCompany) {
            $grupo = 'dashboard';
            $configuracoes = [
                'texto_boas_vindas' => Configuracao::buscar($currentCompany->id, $grupo, 'texto_boas_vindas'),
                'frase_empresa' => Configuracao::buscar($currentCompany->id, $grupo, 'frase_empresa'),
                'video_institucional' => Configuracao::buscar($currentCompany->id, $grupo, 'video_institucional'),
                'titulo_video_institucional' => Configuracao::buscar($currentCompany->id, $grupo, 'titulo_video_institucional', 'Vídeo Institucional'),
                'logo_empresa' => Configuracao::buscar($currentCompany->id, $grupo, 'logo_empresa'),
                'mostrar_video_default' => Configuracao::buscar($currentCompany->id, $grupo, 'mostrar_video_default', '1'),
            ];
        }

        // Buscar últimas 10 atualizações do sistema
        $atualizacoes = AtualizacaoSistema::orderBy('created_at', 'desc')->limit(10)->get();

        // Buscar ideias em desenvolvimento (máximo 10)
        $ideiasDesenvolvimento = Ideia::where('status', IdeiaStatusEnum::EM_DESENVOLVIMENTO->value)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', compact('user', 'currentCompany', 'currentWhitelabel', 'lastAccess', 'configuracoes', 'atualizacoes', 'ideiasDesenvolvimento'));
    }
}
