<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Configuracao;
use App\Helpers\PermissionHelper;

class ConfiguracoesGeraisController extends Controller
{
    /**
     * Exibe a tela de configurações gerais
     */
    public function index()
    {
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        $configuracoes = [];
        
        if ($empresaPrincipal) {
            // Configurações do Dashboard
            $grupoDashboard = 'dashboard';
            $configuracoes['dashboard'] = [
                'texto_boas_vindas' => Configuracao::buscar($empresaPrincipal->id, $grupoDashboard, 'texto_boas_vindas'),
                'frase_empresa' => Configuracao::buscar($empresaPrincipal->id, $grupoDashboard, 'frase_empresa'),
                'video_institucional' => Configuracao::buscar($empresaPrincipal->id, $grupoDashboard, 'video_institucional'),
                'titulo_video_institucional' => Configuracao::buscar($empresaPrincipal->id, $grupoDashboard, 'titulo_video_institucional', 'Vídeo Institucional'),
                'logo_empresa' => Configuracao::buscar($empresaPrincipal->id, $grupoDashboard, 'logo_empresa'),
                'mostrar_video_default' => Configuracao::buscar($empresaPrincipal->id, $grupoDashboard, 'mostrar_video_default', '1'),
            ];
            
            // Configurações Gerais
            $grupoGeral = 'geral';
            $configuracoes['geral'] = [
                'limite_registros' => Configuracao::buscar($empresaPrincipal->id, $grupoGeral, 'limite_registros', 20),
            ];
        }
        
        return view('configuracoes.gerais.index', compact('configuracoes', 'empresaPrincipal'));
    }

    /**
     * Atualiza as configurações gerais
     */
    public function update(Request $request)
    {
        $request->validate([
            'limite_registros' => 'nullable|integer|min:10|max:200',
            'texto_boas_vindas' => 'nullable|string|max:500',
            'frase_empresa' => 'nullable|string|max:500',
            'video_institucional' => 'nullable|string|max:500',
            'titulo_video_institucional' => 'nullable|string|max:255',
            'logo_empresa' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'mostrar_video_default' => 'nullable|in:0,1',
        ]);

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        
        if (!$empresaPrincipal) {
            return response()->json([
                'success' => false,
                'message' => 'Empresa principal não encontrada.'
            ], 404);
        }

        // Salvar configurações gerais
        if ($request->has('limite_registros')) {
            Configuracao::salvar($empresaPrincipal->id, 'geral', 'limite_registros', $request->input('limite_registros'));
        }

        // Salvar configurações do dashboard
        $grupoDashboard = 'dashboard';

        // Salvar texto_boas_vindas
        if ($request->has('texto_boas_vindas')) {
            Configuracao::salvar($empresaPrincipal->id, $grupoDashboard, 'texto_boas_vindas', $request->input('texto_boas_vindas'));
        }

        // Salvar frase_empresa
        if ($request->has('frase_empresa')) {
            Configuracao::salvar($empresaPrincipal->id, $grupoDashboard, 'frase_empresa', $request->input('frase_empresa'));
        }

        // Salvar video_institucional
        if ($request->has('video_institucional')) {
            Configuracao::salvar($empresaPrincipal->id, $grupoDashboard, 'video_institucional', $request->input('video_institucional'));
        }

        // Salvar titulo_video_institucional
        if ($request->has('titulo_video_institucional')) {
            Configuracao::salvar($empresaPrincipal->id, $grupoDashboard, 'titulo_video_institucional', $request->input('titulo_video_institucional'));
        }

        // Salvar mostrar_video_default
        $mostrarVideoDefault = $request->input('mostrar_video_default', '1');
        Configuracao::salvar($empresaPrincipal->id, $grupoDashboard, 'mostrar_video_default', $mostrarVideoDefault);

        // Upload da logo
        if ($request->hasFile('logo_empresa')) {
            // Buscar logo antiga
            $logoAntiga = Configuracao::buscar($empresaPrincipal->id, $grupoDashboard, 'logo_empresa');
            
            // Deletar logo antiga se existir
            if ($logoAntiga) {
                Storage::disk('public')->delete($logoAntiga);
            }
            
            $logo = $request->file('logo_empresa');
            $logoPath = $logo->store('logos', 'public');
            Configuracao::salvar($empresaPrincipal->id, $grupoDashboard, 'logo_empresa', $logoPath);
        }

        return response()->json([
            'success' => true,
            'message' => 'Configurações salvas com sucesso!'
        ]);
    }

    /**
     * Deleta a logo da empresa
     */
    public function deletarLogo(Request $request)
    {
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        
        if (!$empresaPrincipal) {
            return response()->json([
                'success' => false,
                'message' => 'Empresa principal não encontrada.'
            ], 404);
        }

        $grupoDashboard = 'dashboard';
        $logoAntiga = Configuracao::buscar($empresaPrincipal->id, $grupoDashboard, 'logo_empresa');
        
        if ($logoAntiga) {
            // Deletar arquivo físico
            Storage::disk('public')->delete($logoAntiga);
            
            // Deletar configuração do banco
            Configuracao::where('empresa_id', $empresaPrincipal->id)
                ->where('grupo', $grupoDashboard)
                ->where('chave', 'logo_empresa')
                ->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logo deletada com sucesso!'
        ]);
    }
}
