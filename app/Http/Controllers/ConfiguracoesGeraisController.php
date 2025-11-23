<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\ConfiguracaoEmpresa;
use App\Helpers\PermissionHelper;

class ConfiguracoesGeraisController extends Controller
{
    /**
     * Exibe a tela de configurações gerais
     */
    public function index()
    {
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        $configuracao = null;
        
        if ($empresaPrincipal) {
            $configuracao = ConfiguracaoEmpresa::where('empresa_id', $empresaPrincipal->id)->first();
        }
        
        return view('configuracoes.gerais.index', compact('configuracao'));
    }

    /**
     * Atualiza as configurações gerais
     */
    public function update(Request $request)
    {
        $request->validate([
            'texto_boas_vindas' => 'nullable|string|max:500',
            'frase_empresa' => 'nullable|string|max:500',
            'video_institucional' => 'nullable|string|max:500',
            'logo_empresa' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        
        if (!$empresaPrincipal) {
            return response()->json([
                'success' => false,
                'message' => 'Empresa principal não encontrada.'
            ], 404);
        }

        // Buscar configuração existente
        $configuracao = ConfiguracaoEmpresa::where('empresa_id', $empresaPrincipal->id)->first();

        $data = [
            'texto_boas_vindas' => $request->input('texto_boas_vindas'),
            'frase_empresa' => $request->input('frase_empresa'),
            'video_institucional' => $request->input('video_institucional'),
        ];

        // Upload da logo
        if ($request->hasFile('logo_empresa')) {
            // Deletar logo antiga se existir
            if ($configuracao && $configuracao->logo_empresa) {
                Storage::disk('public')->delete($configuracao->logo_empresa);
            }
            
            $logo = $request->file('logo_empresa');
            $logoPath = $logo->store('logos', 'public');
            $data['logo_empresa'] = $logoPath;
        } else {
            // Manter logo existente se não houver novo upload
            if ($configuracao && $configuracao->logo_empresa) {
                $data['logo_empresa'] = $configuracao->logo_empresa;
            }
        }

        $configuracao = ConfiguracaoEmpresa::updateOrCreate(
            ['empresa_id' => $empresaPrincipal->id],
            $data
        );

        return response()->json([
            'success' => true,
            'message' => 'Configurações salvas com sucesso!'
        ]);
    }
}
