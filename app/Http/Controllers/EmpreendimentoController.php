<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empreendimento;
use App\Models\Lote;
use App\Helpers\PermissionHelper;
use App\Helpers\PublicPathHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmpreendimentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::user()->temPermissao('empreendimento', 'listar')) {
            abort(403, 'Você não tem permissão para listar empreendimentos.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual) {
            abort(403, 'Empresa atual não encontrada.');
        }

        $empreendimentos = Empreendimento::where('empresa_id', $empresaAtual->id)
            ->with(['lotes.status']) // Eager loading para evitar N+1
            ->orderBy('nome')
            ->get();

        return view('empreendimento.index', compact('empreendimentos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->temPermissao('empreendimento', 'criar')) {
            abort(403, 'Você não tem permissão para criar empreendimentos.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual) {
            abort(403, 'Empresa atual não encontrada.');
        }

        return view('empreendimento.create', compact('empresaAtual'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->temPermissao('empreendimento', 'criar')) {
            abort(403, 'Você não tem permissão para criar empreendimentos.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual) {
            abort(403, 'Empresa atual não encontrada.');
        }

        // Log detalhado ANTES de tudo para diagnosticar o problema
        Log::info('Diagnóstico de upload (store)', [
            'has_imagem' => $request->hasFile('imagem'),
            'has_imagem_mapa' => $request->hasFile('imagem_mapa'),
            'content_type' => $request->header('Content-Type'),
            'content_length' => $request->header('Content-Length'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'max_file_uploads' => ini_get('max_file_uploads'),
            'memory_limit' => ini_get('memory_limit'),
            'all_files' => $request->allFiles(),
        ]);

        // Log antes da validação para verificar se o arquivo chegou
        if ($request->hasFile('imagem')) {
            $file = $request->file('imagem');
            Log::info('Arquivo recebido antes da validação (store)', [
                'nome_arquivo' => $file->getClientOriginalName(),
                'tamanho' => $file->getSize(),
                'tamanho_mb' => round($file->getSize() / 1024 / 1024, 2),
                'mime_type' => $file->getMimeType(),
                'is_valid' => $file->isValid(),
                'error_code' => $file->getError(),
                'error_message' => $file->isValid() ? null : $file->getErrorMessage(),
            ]);
        } else {
            Log::warning('Arquivo imagem NÃO chegou ao servidor (store)', [
                'request_all' => array_keys($request->all()),
                'request_files' => array_keys($request->allFiles()),
                'content_type_header' => $request->header('Content-Type'),
                'content_length_header' => $request->header('Content-Length'),
            ]);
        }

        if ($request->hasFile('imagem_mapa')) {
            $file = $request->file('imagem_mapa');
            Log::info('Arquivo do mapa recebido antes da validação (store)', [
                'nome_arquivo' => $file->getClientOriginalName(),
                'tamanho' => $file->getSize(),
                'tamanho_mb' => round($file->getSize() / 1024 / 1024, 2),
                'mime_type' => $file->getMimeType(),
                'is_valid' => $file->isValid(),
                'error_code' => $file->getError(),
                'error_message' => $file->isValid() ? null : $file->getErrorMessage(),
            ]);
        }

        try {
            $validated = $request->validate([
                'nome' => 'required|string|max:255',
                'imagem' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:10240',
                'imagem_mapa' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:10240',
                'zoom_default' => 'nullable|integer|min:50|max:300',
                'valor_m2' => 'nullable|numeric|min:0',
                'maximo_parcelas' => 'nullable|integer|min:1',
                'sinal' => 'required|in:1,2',
                'sinal_tipo' => 'nullable|in:1,2',
                'sinal_valor_forma' => 'nullable|in:valor,porcentagem',
                'sinal_valor' => 'nullable|numeric|min:0',
                'juros_forma' => 'nullable|in:valor,porcentagem',
                'juros' => 'nullable|numeric',
                'multa_forma' => 'nullable|in:valor,porcentagem',
                'multa' => 'nullable|numeric',
                'juros_por_parcela' => 'nullable|numeric',
                'status' => 'required|in:0,1',
                'quadra_numeracao_tipo' => 'required|in:1,2',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erro de validação no upload de imagem (store)', [
                'erros' => $e->errors(),
                'dados_request' => $request->except(['imagem', 'imagem_mapa']), // Não logar arquivos
                'has_imagem' => $request->hasFile('imagem'),
                'has_imagem_mapa' => $request->hasFile('imagem_mapa'),
            ]);
            throw $e;
        }

        // Se zoom_default estiver vazio ou 0, usar default 180
        if (empty($validated['zoom_default']) || $validated['zoom_default'] == 0) {
            $validated['zoom_default'] = 180;
        }

        $validated['empresa_id'] = $empresaAtual->id;

        // Criar empreendimento primeiro para obter o ID
        $empreendimento = Empreendimento::create($validated);

        // Criar pastas específicas para este empreendimento
        $pastaEmpreendimento = 'img_empreendimentos/' . $empreendimento->id . '_cover';
        $pastaMapa = 'img_empreendimentos/' . $empreendimento->id . '_mapa';

        // Criar diretórios se não existirem
        if (!file_exists(PublicPathHelper::path($pastaEmpreendimento))) {
            mkdir(PublicPathHelper::path($pastaEmpreendimento), 0755, true);
        }
        if (!file_exists(PublicPathHelper::path($pastaMapa))) {
            mkdir(PublicPathHelper::path($pastaMapa), 0755, true);
        }

        // Upload de imagem de capa se houver
        if ($request->hasFile('imagem')) {
            try {
                $file = $request->file('imagem');

                // Log detalhado do arquivo
                Log::info('Tentativa de upload de imagem', [
                    'empreendimento_id' => $empreendimento->id,
                    'nome_arquivo' => $file->getClientOriginalName(),
                    'tamanho' => $file->getSize(),
                    'tamanho_mb' => round($file->getSize() / 1024 / 1024, 2),
                    'mime_type' => $file->getMimeType(),
                    'extensao' => $file->getClientOriginalExtension(),
                    'caminho_temp' => $file->getPathname(),
                    'pasta_destino' => $pastaEmpreendimento,
                ]);

                // Verificar se o upload foi bem-sucedido
                if (!$file->isValid()) {
                    $errorMessage = $file->getErrorMessage();
                    $errorCode = $file->getError();

                    Log::error('Arquivo de imagem inválido', [
                        'empreendimento_id' => $empreendimento->id,
                        'nome_arquivo' => $file->getClientOriginalName(),
                        'codigo_erro' => $errorCode,
                        'mensagem_erro' => $errorMessage,
                        'tamanho' => $file->getSize(),
                        'tamanho_mb' => round($file->getSize() / 1024 / 1024, 2),
                        'upload_max_filesize' => ini_get('upload_max_filesize'),
                        'post_max_size' => ini_get('post_max_size'),
                        'max_file_uploads' => ini_get('max_file_uploads'),
                    ]);

                    throw new \Exception('Arquivo inválido. Código de erro: ' . $errorCode . '. Mensagem: ' . $errorMessage);
                }

                // Verificar se a pasta existe e tem permissão de escrita
                $pastaPath = PublicPathHelper::path($pastaEmpreendimento);
                if (!is_dir($pastaPath)) {
                    Log::error('Pasta de destino não existe', [
                        'empreendimento_id' => $empreendimento->id,
                        'pasta' => $pastaPath,
                    ]);
                    throw new \Exception('Pasta de destino não existe: ' . $pastaPath);
                }

                if (!is_writable($pastaPath)) {
                    Log::error('Pasta de destino sem permissão de escrita', [
                        'empreendimento_id' => $empreendimento->id,
                        'pasta' => $pastaPath,
                        'permissoes' => substr(sprintf('%o', fileperms($pastaPath)), -4),
                    ]);
                    throw new \Exception('Pasta de destino sem permissão de escrita: ' . $pastaPath);
                }

                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(PublicPathHelper::path($pastaEmpreendimento), $fileName);

                // Salvar apenas a URL relativa
                $empreendimento->imagem = $pastaEmpreendimento . '/' . $fileName;
                $empreendimento->save();

                Log::info('Upload de imagem bem-sucedido', [
                    'empreendimento_id' => $empreendimento->id,
                    'arquivo_salvo' => $empreendimento->imagem,
                    'tamanho' => filesize(PublicPathHelper::path($empreendimento->imagem)),
                ]);
            } catch (\Exception $e) {
                // Log do erro completo
                Log::error('Erro ao fazer upload da imagem', [
                    'empreendimento_id' => $empreendimento->id,
                    'erro' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'arquivo' => $e->getFile(),
                    'linha' => $e->getLine(),
                ]);

                // Se der erro, deletar o empreendimento criado e retornar com erro
                $empreendimento->delete();
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Erro ao fazer upload da imagem: ' . $e->getMessage() . '. Tamanho máximo permitido: 10MB. Verifique se o arquivo não excede o limite e se é um formato válido (JPG, PNG, GIF, WEBP). Verifique os logs para mais detalhes.');
            }
        }

        // Upload de imagem do mapa se houver
        if ($request->hasFile('imagem_mapa')) {
            try {
                $file = $request->file('imagem_mapa');

                // Log detalhado do arquivo
                Log::info('Tentativa de upload de imagem do mapa', [
                    'empreendimento_id' => $empreendimento->id,
                    'nome_arquivo' => $file->getClientOriginalName(),
                    'tamanho' => $file->getSize(),
                    'tamanho_mb' => round($file->getSize() / 1024 / 1024, 2),
                    'mime_type' => $file->getMimeType(),
                    'extensao' => $file->getClientOriginalExtension(),
                    'caminho_temp' => $file->getPathname(),
                    'pasta_destino' => $pastaMapa,
                ]);

                // Verificar se o upload foi bem-sucedido
                if (!$file->isValid()) {
                    $errorMessage = $file->getErrorMessage();
                    $errorCode = $file->getError();

                    Log::error('Arquivo de imagem do mapa inválido', [
                        'empreendimento_id' => $empreendimento->id,
                        'nome_arquivo' => $file->getClientOriginalName(),
                        'codigo_erro' => $errorCode,
                        'mensagem_erro' => $errorMessage,
                        'tamanho' => $file->getSize(),
                        'tamanho_mb' => round($file->getSize() / 1024 / 1024, 2),
                        'upload_max_filesize' => ini_get('upload_max_filesize'),
                        'post_max_size' => ini_get('post_max_size'),
                        'max_file_uploads' => ini_get('max_file_uploads'),
                    ]);

                    throw new \Exception('Arquivo inválido. Código de erro: ' . $errorCode . '. Mensagem: ' . $errorMessage);
                }

                // Verificar se a pasta existe e tem permissão de escrita
                $pastaPath = PublicPathHelper::path($pastaMapa);
                if (!is_dir($pastaPath)) {
                    Log::error('Pasta de destino do mapa não existe', [
                        'empreendimento_id' => $empreendimento->id,
                        'pasta' => $pastaPath,
                    ]);
                    throw new \Exception('Pasta de destino não existe: ' . $pastaPath);
                }

                if (!is_writable($pastaPath)) {
                    Log::error('Pasta de destino do mapa sem permissão de escrita', [
                        'empreendimento_id' => $empreendimento->id,
                        'pasta' => $pastaPath,
                        'permissoes' => substr(sprintf('%o', fileperms($pastaPath)), -4),
                    ]);
                    throw new \Exception('Pasta de destino sem permissão de escrita: ' . $pastaPath);
                }

                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(PublicPathHelper::path($pastaMapa), $fileName);

                // Salvar apenas a URL relativa
                $empreendimento->imagem_mapa = $pastaMapa . '/' . $fileName;
                $empreendimento->save();

                Log::info('Upload de imagem do mapa bem-sucedido', [
                    'empreendimento_id' => $empreendimento->id,
                    'arquivo_salvo' => $empreendimento->imagem_mapa,
                    'tamanho' => filesize(PublicPathHelper::path($empreendimento->imagem_mapa)),
                ]);
            } catch (\Exception $e) {
                // Log do erro completo
                Log::error('Erro ao fazer upload da imagem do mapa', [
                    'empreendimento_id' => $empreendimento->id,
                    'erro' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'arquivo' => $e->getFile(),
                    'linha' => $e->getLine(),
                ]);

                // Se der erro, deletar o empreendimento criado e retornar com erro
                $empreendimento->delete();
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Erro ao fazer upload da imagem do mapa: ' . $e->getMessage() . '. Tamanho máximo permitido: 10MB. Verifique se o arquivo não excede o limite e se é um formato válido (JPG, PNG, GIF, WEBP). Verifique os logs para mais detalhes.');
            }
        }

        return redirect()->route('empreendimentos.index')
            ->with('success', 'Empreendimento criado com sucesso.');
    }

    /**
     * Display the specified resource.
     * Redireciona para a tela de lotes.
     */
    public function show(Empreendimento $empreendimento)
    {
        if (!Auth::user()->temPermissao('empreendimento', 'visualizar')) {
            abort(403, 'Você não tem permissão para visualizar empreendimentos.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual || $empreendimento->empresa_id !== $empresaAtual->id) {
            abort(403, 'Empreendimento não encontrado.');
        }

        // Carregar relacionamentos
        $empreendimento->load(['lotes', 'quadras', 'empresa']);

        return view('empreendimento.show', compact('empreendimento'));
    }

    /**
     * Display the mapa page for the specified empreendimento.
     */
    public function mapa(Empreendimento $empreendimento)
    {
        if (!Auth::user()->temPermissao('empreendimento', 'visualizar')) {
            abort(403, 'Você não tem permissão para visualizar empreendimentos.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual || $empreendimento->empresa_id !== $empresaAtual->id) {
            abort(403, 'Empreendimento não encontrado.');
        }

        // Verificar se existe imagem do mapa
        if (!$empreendimento->imagem_mapa || empty($empreendimento->imagem_mapa) || !file_exists(PublicPathHelper::path($empreendimento->imagem_mapa))) {
            return view('empreendimento.mapa-sem-imagem', compact('empreendimento'));
        }

        // Carregar lotes com suas posições de pinos
        $lotes = $empreendimento->lotes()->with(['status', 'quadra'])->get();

        // Buscar quadras do empreendimento
        $quadras = $empreendimento->quadras()->orderBy('nome')->get();

        // Buscar status de lotes disponíveis
        $statusLotes = \App\Models\LoteStatus::where('empresa_id', $empresaAtual->id)
            ->orderBy('nome')
            ->get();

        return view('empreendimento.mapa', compact('empreendimento', 'lotes', 'statusLotes', 'quadras'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empreendimento $empreendimento)
    {
        if (!Auth::user()->temPermissao('empreendimento', 'editar')) {
            abort(403, 'Você não tem permissão para editar empreendimentos.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual || $empreendimento->empresa_id !== $empresaAtual->id) {
            abort(403, 'Empreendimento não encontrado.');
        }

        return view('empreendimento.edit', compact('empreendimento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empreendimento $empreendimento)
    {
        if (!Auth::user()->temPermissao('empreendimento', 'editar')) {
            abort(403, 'Você não tem permissão para editar empreendimentos.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual || $empreendimento->empresa_id !== $empresaAtual->id) {
            abort(403, 'Empreendimento não encontrado.');
        }

        // Log antes da validação para verificar se o arquivo chegou
        if ($request->hasFile('imagem')) {
            $file = $request->file('imagem');
            Log::info('Arquivo recebido antes da validação (update)', [
                'empreendimento_id' => $empreendimento->id,
                'nome_arquivo' => $file->getClientOriginalName(),
                'tamanho' => $file->getSize(),
                'tamanho_mb' => round($file->getSize() / 1024 / 1024, 2),
                'mime_type' => $file->getMimeType(),
                'is_valid' => $file->isValid(),
                'error_code' => $file->getError(),
                'error_message' => $file->isValid() ? null : $file->getErrorMessage(),
            ]);
        }

        if ($request->hasFile('imagem_mapa')) {
            $file = $request->file('imagem_mapa');
            Log::info('Arquivo do mapa recebido antes da validação (update)', [
                'empreendimento_id' => $empreendimento->id,
                'nome_arquivo' => $file->getClientOriginalName(),
                'tamanho' => $file->getSize(),
                'tamanho_mb' => round($file->getSize() / 1024 / 1024, 2),
                'mime_type' => $file->getMimeType(),
                'is_valid' => $file->isValid(),
                'error_code' => $file->getError(),
                'error_message' => $file->isValid() ? null : $file->getErrorMessage(),
            ]);
        }

        try {
            $validated = $request->validate([
                'nome' => 'required|string|max:255',
                'imagem' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:10240',
                'imagem_mapa' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:10240',
                'zoom_default' => 'nullable|integer|min:50|max:300',
                'valor_m2' => 'nullable|numeric|min:0',
                'maximo_parcelas' => 'nullable|integer|min:1',
                'sinal' => 'required|in:1,2',
                'sinal_tipo' => 'nullable|in:1,2',
                'sinal_valor_forma' => 'nullable|in:valor,porcentagem',
                'sinal_valor' => 'nullable|numeric|min:0',
                'juros_forma' => 'nullable|in:valor,porcentagem',
                'juros' => 'nullable|numeric',
                'multa_forma' => 'nullable|in:valor,porcentagem',
                'multa' => 'nullable|numeric',
                'juros_por_parcela' => 'nullable|numeric',
                'status' => 'required|in:0,1',
                'quadra_numeracao_tipo' => 'required|in:1,2',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erro de validação no upload de imagem (update)', [
                'empreendimento_id' => $empreendimento->id,
                'erros' => $e->errors(),
                'dados_request' => $request->except(['imagem', 'imagem_mapa']), // Não logar arquivos
                'has_imagem' => $request->hasFile('imagem'),
                'has_imagem_mapa' => $request->hasFile('imagem_mapa'),
            ]);
            throw $e;
        }

        // Se zoom_default estiver vazio ou 0, usar default 180
        if (empty($validated['zoom_default']) || $validated['zoom_default'] == 0) {
            $validated['zoom_default'] = 180;
        }

        // Criar pastas específicas para este empreendimento se não existirem
        $pastaEmpreendimento = 'img_empreendimentos/' . $empreendimento->id . '_cover';
        $pastaMapa = 'img_empreendimentos/' . $empreendimento->id . '_mapa';

        // Criar diretórios se não existirem
        if (!file_exists(PublicPathHelper::path($pastaEmpreendimento))) {
            mkdir(PublicPathHelper::path($pastaEmpreendimento), 0755, true);
        }
        if (!file_exists(PublicPathHelper::path($pastaMapa))) {
            mkdir(PublicPathHelper::path($pastaMapa), 0755, true);
        }

        // Upload de imagem de capa se houver
        if ($request->hasFile('imagem')) {
            try {
                // Deletar imagem antiga se existir (pode estar em pasta antiga ou nova)
                if ($empreendimento->imagem) {
                    $oldFilePath = PublicPathHelper::path($empreendimento->imagem);
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                $file = $request->file('imagem');

                // Log detalhado do arquivo
                Log::info('Tentativa de upload de imagem (update)', [
                    'empreendimento_id' => $empreendimento->id,
                    'nome_arquivo' => $file->getClientOriginalName(),
                    'tamanho' => $file->getSize(),
                    'tamanho_mb' => round($file->getSize() / 1024 / 1024, 2),
                    'mime_type' => $file->getMimeType(),
                    'extensao' => $file->getClientOriginalExtension(),
                ]);

                // Verificar se o upload foi bem-sucedido
                if (!$file->isValid()) {
                    $errorMessage = $file->getErrorMessage();
                    $errorCode = $file->getError();

                    Log::error('Arquivo de imagem inválido (update)', [
                        'empreendimento_id' => $empreendimento->id,
                        'nome_arquivo' => $file->getClientOriginalName(),
                        'codigo_erro' => $errorCode,
                        'mensagem_erro' => $errorMessage,
                        'tamanho' => $file->getSize(),
                        'tamanho_mb' => round($file->getSize() / 1024 / 1024, 2),
                    ]);

                    throw new \Exception('Arquivo inválido. Código de erro: ' . $errorCode . '. Mensagem: ' . $errorMessage);
                }

                // Verificar se a pasta existe e tem permissão de escrita
                $pastaPath = PublicPathHelper::path($pastaEmpreendimento);
                if (!is_dir($pastaPath)) {
                    Log::error('Pasta de destino não existe (update)', [
                        'empreendimento_id' => $empreendimento->id,
                        'pasta' => $pastaPath,
                    ]);
                    throw new \Exception('Pasta de destino não existe: ' . $pastaPath);
                }

                if (!is_writable($pastaPath)) {
                    Log::error('Pasta de destino sem permissão de escrita (update)', [
                        'empreendimento_id' => $empreendimento->id,
                        'pasta' => $pastaPath,
                        'permissoes' => substr(sprintf('%o', fileperms($pastaPath)), -4),
                    ]);
                    throw new \Exception('Pasta de destino sem permissão de escrita: ' . $pastaPath);
                }

                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(PublicPathHelper::path($pastaEmpreendimento), $fileName);

                // Salvar apenas a URL relativa
                $validated['imagem'] = $pastaEmpreendimento . '/' . $fileName;

                Log::info('Upload de imagem bem-sucedido (update)', [
                    'empreendimento_id' => $empreendimento->id,
                    'arquivo_salvo' => $validated['imagem'],
                ]);
            } catch (\Exception $e) {
                Log::error('Erro ao fazer upload da imagem (update)', [
                    'empreendimento_id' => $empreendimento->id,
                    'erro' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Erro ao fazer upload da imagem: ' . $e->getMessage() . '. Verifique os logs para mais detalhes.');
            }
        }

        // Upload de imagem do mapa se houver
        if ($request->hasFile('imagem_mapa')) {
            try {
                // Deletar imagem antiga se existir (pode estar em pasta antiga ou nova)
                if ($empreendimento->imagem_mapa) {
                    $oldFilePath = PublicPathHelper::path($empreendimento->imagem_mapa);
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                $file = $request->file('imagem_mapa');

                // Log detalhado do arquivo
                Log::info('Tentativa de upload de imagem do mapa (update)', [
                    'empreendimento_id' => $empreendimento->id,
                    'nome_arquivo' => $file->getClientOriginalName(),
                    'tamanho' => $file->getSize(),
                    'tamanho_mb' => round($file->getSize() / 1024 / 1024, 2),
                    'mime_type' => $file->getMimeType(),
                    'extensao' => $file->getClientOriginalExtension(),
                ]);

                // Verificar se o upload foi bem-sucedido
                if (!$file->isValid()) {
                    $errorMessage = $file->getErrorMessage();
                    $errorCode = $file->getError();

                    Log::error('Arquivo de imagem do mapa inválido (update)', [
                        'empreendimento_id' => $empreendimento->id,
                        'nome_arquivo' => $file->getClientOriginalName(),
                        'codigo_erro' => $errorCode,
                        'mensagem_erro' => $errorMessage,
                        'tamanho' => $file->getSize(),
                        'tamanho_mb' => round($file->getSize() / 1024 / 1024, 2),
                    ]);

                    throw new \Exception('Arquivo inválido. Código de erro: ' . $errorCode . '. Mensagem: ' . $errorMessage);
                }

                // Verificar se a pasta existe e tem permissão de escrita
                $pastaPath = PublicPathHelper::path($pastaMapa);
                if (!is_dir($pastaPath)) {
                    Log::error('Pasta de destino do mapa não existe (update)', [
                        'empreendimento_id' => $empreendimento->id,
                        'pasta' => $pastaPath,
                    ]);
                    throw new \Exception('Pasta de destino não existe: ' . $pastaPath);
                }

                if (!is_writable($pastaPath)) {
                    Log::error('Pasta de destino do mapa sem permissão de escrita (update)', [
                        'empreendimento_id' => $empreendimento->id,
                        'pasta' => $pastaPath,
                        'permissoes' => substr(sprintf('%o', fileperms($pastaPath)), -4),
                    ]);
                    throw new \Exception('Pasta de destino sem permissão de escrita: ' . $pastaPath);
                }

                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(PublicPathHelper::path($pastaMapa), $fileName);

                // Salvar apenas a URL relativa
                $validated['imagem_mapa'] = $pastaMapa . '/' . $fileName;

                Log::info('Upload de imagem do mapa bem-sucedido (update)', [
                    'empreendimento_id' => $empreendimento->id,
                    'arquivo_salvo' => $validated['imagem_mapa'],
                ]);
            } catch (\Exception $e) {
                Log::error('Erro ao fazer upload da imagem do mapa (update)', [
                    'empreendimento_id' => $empreendimento->id,
                    'erro' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Erro ao fazer upload da imagem do mapa: ' . $e->getMessage() . '. Verifique os logs para mais detalhes.');
            }
        }

        $empreendimento->update($validated);

        return redirect()->route('empreendimentos.index')
            ->with('success', 'Empreendimento atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empreendimento $empreendimento)
    {
        if (!Auth::user()->temPermissao('empreendimento', 'deletar')) {
            abort(403, 'Você não tem permissão para deletar empreendimentos.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual || $empreendimento->empresa_id !== $empresaAtual->id) {
            abort(403, 'Empreendimento não encontrado.');
        }

        // Verificar se há lotes vendidos vinculados ao empreendimento
        $lotesVendidos = $empreendimento->lotes()
            ->whereHas('status', function($query) {
                $query->where('tipo', 2); // Tipo 2 = Vendido
            })
            ->get();

        if ($lotesVendidos->count() > 0) {
            $nomesLotesVendidos = $lotesVendidos->pluck('nome')->toArray();
            $mensagem = 'Não é possível deletar este empreendimento pois existem lotes vendidos vinculados a ele.';

            if ($lotesVendidos->count() == 1) {
                $mensagem = "Não é possível deletar este empreendimento pois o lote \"{$nomesLotesVendidos[0]}\" está vendido.";
            } else {
                $mensagem = 'Não é possível deletar este empreendimento pois existem ' . $lotesVendidos->count() . ' lotes vendidos vinculados a ele: ' . implode(', ', array_slice($nomesLotesVendidos, 0, 5));
                if (count($nomesLotesVendidos) > 5) {
                    $mensagem .= ' e mais ' . (count($nomesLotesVendidos) - 5) . ' lote(s).';
                } else {
                    $mensagem .= '.';
                }
            }

            return redirect()->back()
                ->with('error', $mensagem);
        }

        // Deletar imagens e pastas se existirem
        $pastaEmpreendimento = 'img_empreendimentos/' . $empreendimento->id . '_cover';
        $pastaMapa = 'img_empreendimentos/' . $empreendimento->id . '_mapa';

        // Deletar imagem de capa se existir
        if ($empreendimento->imagem) {
            $imagePath = PublicPathHelper::path($empreendimento->imagem);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Deletar imagem do mapa se existir
        if ($empreendimento->imagem_mapa) {
            $mapaPath = PublicPathHelper::path($empreendimento->imagem_mapa);
            if (file_exists($mapaPath)) {
                unlink($mapaPath);
            }
        }

        // Deletar pastas se estiverem vazias
        $pastaCoverPath = PublicPathHelper::path($pastaEmpreendimento);
        if (file_exists($pastaCoverPath) && is_dir($pastaCoverPath)) {
            // Verificar se a pasta está vazia
            if (count(scandir($pastaCoverPath)) == 2) { // 2 = . e ..
                rmdir($pastaCoverPath);
            }
        }

        $pastaMapaPath = PublicPathHelper::path($pastaMapa);
        if (file_exists($pastaMapaPath) && is_dir($pastaMapaPath)) {
            // Verificar se a pasta está vazia
            if (count(scandir($pastaMapaPath)) == 2) { // 2 = . e ..
                rmdir($pastaMapaPath);
            }
        }

        $empreendimento->delete();

        return redirect()->route('empreendimentos.index')
            ->with('success', 'Empreendimento deletado com sucesso.');
    }

    /**
     * Salvar posições dos pinos dos lotes no mapa
     */
    public function salvarPosicoesPinos(Request $request, Empreendimento $empreendimento)
    {
        if (!Auth::user()->temPermissao('empreendimento', 'editar')) {
            return response()->json(['error' => 'Você não tem permissão para editar empreendimentos.'], 403);
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual || $empreendimento->empresa_id !== $empresaAtual->id) {
            return response()->json(['error' => 'Empreendimento não encontrado.'], 403);
        }

        $validated = $request->validate([
            'pinos' => 'nullable|array',
            'pinos.*.lote_id' => 'required_with:pinos|uuid|exists:lote,id',
            'pinos.*.x' => 'required_with:pinos|numeric|min:0|max:100',
            'pinos.*.y' => 'required_with:pinos|numeric|min:0|max:100',
            'remover' => 'nullable|array',
            'remover.*' => 'uuid|exists:lote,id',
        ]);

        try {
            // Salvar/atualizar posições dos pinos
            if (isset($validated['pinos']) && count($validated['pinos']) > 0) {
                foreach ($validated['pinos'] as $pinoData) {
                    $lote = Lote::find($pinoData['lote_id']);

                    // Verificar se o lote pertence ao empreendimento
                    if ($lote && $lote->empreendimento_id === $empreendimento->id) {
                        $lote->posicao_pino = [
                            'x' => $pinoData['x'],
                            'y' => $pinoData['y'],
                        ];
                        $lote->save();
                    }
                }
            }

            // Remover posições dos pinos (limpar posicao_pino no banco)
            if (isset($validated['remover']) && count($validated['remover']) > 0) {
                foreach ($validated['remover'] as $loteId) {
                    $lote = Lote::find($loteId);

                    // Verificar se o lote pertence ao empreendimento
                    if ($lote && $lote->empreendimento_id === $empreendimento->id) {
                        $lote->posicao_pino = null;
                        $lote->save();
                    }
                }
            }

            $message = 'Alterações salvas com sucesso.';
            if (isset($validated['pinos']) && count($validated['pinos']) > 0) {
                $message = 'Posições dos pinos salvas com sucesso.';
            }
            if (isset($validated['remover']) && count($validated['remover']) > 0) {
                if (isset($validated['pinos']) && count($validated['pinos']) > 0) {
                    $message = 'Pinos atualizados e removidos com sucesso.';
                } else {
                    $message = 'Pinos removidos com sucesso.';
                }
            }

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao salvar posições dos pinos: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a listing of empreendimentos for map view
     */
    public function mapaIndex()
    {
        if (!Auth::user()->temPermissao('empreendimento', 'visualizar')) {
            abort(403, 'Você não tem permissão para visualizar mapas.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual) {
            abort(403, 'Empresa atual não encontrada.');
        }

        $empreendimentos = Empreendimento::where('empresa_id', $empresaAtual->id)
            ->with(['lotes.status']) // Eager loading para evitar N+1
            ->orderBy('nome')
            ->get();

        return view('loteamento.mapa.index', compact('empreendimentos'));
    }

    /**
     * Display the map view for an empreendimento (same as mapa method)
     */
    public function mapaView(Empreendimento $empreendimento)
    {
        if (!Auth::user()->temPermissao('empreendimento', 'visualizar')) {
            abort(403, 'Você não tem permissão para visualizar empreendimentos.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual || $empreendimento->empresa_id !== $empresaAtual->id) {
            abort(403, 'Empreendimento não encontrado.');
        }

        // Verificar se existe imagem do mapa
        if (!$empreendimento->imagem_mapa || empty($empreendimento->imagem_mapa) || !file_exists(PublicPathHelper::path($empreendimento->imagem_mapa))) {
            return view('loteamento.mapa.sem-imagem', compact('empreendimento'));
        }

        // Carregar lotes com suas posições de pinos
        $lotes = $empreendimento->lotes()->with(['status', 'quadra'])->get();

        // Adicionar contagem de comentários do cliente atual para cada lote
        foreach ($lotes as $lote) {
            if ($lote->cliente_id) {
                $lote->comentarios_count = $lote->comentarios()
                    ->where('cliente_id', $lote->cliente_id)
                    ->count();
            } else {
                $lote->comentarios_count = 0;
            }
        }

        // Buscar quadras do empreendimento
        $quadras = $empreendimento->quadras()->orderBy('nome')->get();

        // Buscar status de lotes disponíveis
        $statusLotes = \App\Models\LoteStatus::where('empresa_id', $empresaAtual->id)
            ->orderBy('nome')
            ->get();

        return view('loteamento.mapa.view', compact('empreendimento', 'lotes', 'statusLotes', 'quadras'));
    }

    /**
     * Exibir tela de vender lote
     */
public function venderLote(Empreendimento $empreendimento, Lote $lote)
    {
        if (!Auth::user()->temPermissao('empreendimento', 'visualizar')) {
            abort(403, 'Você não tem permissão para visualizar empreendimentos.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual || $empreendimento->empresa_id !== $empresaAtual->id) {
            abort(403, 'Empreendimento não encontrado.');
        }

        // Verificar se o lote pertence ao empreendimento
        if ($lote->empreendimento_id !== $empreendimento->id) {
            abort(404, 'Lote não encontrado neste empreendimento.');
        }

        // Carregar relacionamentos do lote
        $lote->load(['quadra', 'status', 'cliente']);

        // Buscar clientes ativos da empresa
        $clientes = \App\Models\Cliente::where('empresa_id', $empresaAtual->id)
            ->where('status', true)
            ->orderBy('nome')
            ->get();

        // Obter quantidade máxima de parcelas do empreendimento
        $maxParcelas = $empreendimento->maximo_parcelas ?? 1;

        return view('loteamento.lote.vender', compact('empreendimento', 'lote', 'clientes', 'maxParcelas'));
    }

    /**
     * Gerar parcelas para venda de lote
     */
    public function gerarParcelasVenda(Request $request, Empreendimento $empreendimento, Lote $lote)
    {
        if (!Auth::user()->temPermissao('empreendimento', 'visualizar')) {
            return response()->json(['error' => 'Você não tem permissão para visualizar empreendimentos.'], 403);
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual || $empreendimento->empresa_id !== $empresaAtual->id) {
            return response()->json(['error' => 'Empreendimento não encontrado.'], 403);
        }

        // Verificar se o lote pertence ao empreendimento
        if ($lote->empreendimento_id !== $empreendimento->id) {
            return response()->json(['error' => 'Lote não encontrado neste empreendimento.'], 404);
        }

        $validated = $request->validate([
            'cliente_id' => 'required|exists:cliente,id',
            'quantidade_parcelas' => 'required|integer|min:1',
            'data_primeira_parcela' => 'required|date',
            'valor_entrada' => 'required|numeric|min:0',
            'parcela_anual' => 'nullable|boolean',
            'parcelas_anuais' => 'nullable|array',
        ]);

        // Validar estrutura do array de parcelas anuais manualmente
        if (isset($validated['parcelas_anuais']) && is_array($validated['parcelas_anuais'])) {
            foreach ($validated['parcelas_anuais'] as $index => $parcela) {
                if (!isset($parcela['ano']) || !is_numeric($parcela['ano'])) {
                    unset($validated['parcelas_anuais'][$index]);
                    continue;
                }
                if (!isset($parcela['valor']) || !is_numeric($parcela['valor'])) {
                    $validated['parcelas_anuais'][$index]['valor'] = 0;
                }
                if (!isset($parcela['ativa'])) {
                    $validated['parcelas_anuais'][$index]['ativa'] = false;
                }
            }
            $validated['parcelas_anuais'] = array_values($validated['parcelas_anuais']);
        }

        $valorLote = $lote->valor ?? 0;
        if ($valorLote <= 0) {
            return response()->json(['error' => 'O lote não possui valor definido.'], 400);
        }

        $quantidade = $validated['quantidade_parcelas'];
        $valorEntrada = floatval($validated['valor_entrada']);
        $temParcelaAnual = $validated['parcela_anual'] ?? false;

        // Calcular valor total das parcelas anuais (se houver)
        $valorTotalParcelasAnuais = 0;
        if ($temParcelaAnual && isset($validated['parcelas_anuais'])) {
            foreach ($validated['parcelas_anuais'] as $parcelaAnual) {
                if (isset($parcelaAnual['ativa']) && $parcelaAnual['ativa'] &&
                    isset($parcelaAnual['valor']) && floatval($parcelaAnual['valor']) > 0) {
                    $valorTotalParcelasAnuais += floatval($parcelaAnual['valor']);
                }
            }
        }

        // Verificar se entrada + parcelas anuais não ultrapassam o valor do lote
        $valorTotalJaPago = $valorEntrada + $valorTotalParcelasAnuais;
        if ($valorTotalJaPago > $valorLote) {
            return response()->json([
                'error' => 'A soma da entrada e das parcelas anuais (R$ ' . number_format($valorTotalJaPago, 2, ',', '.') . ') ultrapassa o valor do lote (R$ ' . number_format($valorLote, 2, ',', '.') . '). Por favor, ajuste os valores.'
            ], 400);
        }

        // Calcular valor base para as parcelas (valor do lote - entrada - parcelas anuais)
        $valorBase = $valorLote - $valorEntrada - $valorTotalParcelasAnuais;

        // Obter juros por parcela do empreendimento (em porcentagem)
        $jurosPorParcela = floatval($empreendimento->juros_por_parcela ?? 0);
        if ($jurosPorParcela < 0) {
            $jurosPorParcela = 0;
        }

        // Se o valor base for negativo ou zero, não há parcelas mensais
        if ($valorBase <= 0) {
            $valorParcela = 0;
        } else {
            // Calcular valor da parcela mensal
            $valorParcela = $valorBase / $quantidade;
        }

        $parcelas = [];

        // Usar a data da primeira parcela informada pelo usuário
        $dataPrimeiraParcela = \Carbon\Carbon::parse($validated['data_primeira_parcela']);

        // Gerar parcelas mensais
        // A primeira parcela será na data informada pelo usuário
        // As seguintes serão nos meses subsequentes
        for ($i = 0; $i < $quantidade; $i++) {
            // A primeira parcela (i=0) será na data informada
            // A segunda parcela (i=1) será 1 mês depois, etc.
            $vencimento = $dataPrimeiraParcela->copy()->addMonths($i);

            // Calcular valor da parcela com juros (se houver)
            // Juros por parcela é aplicado como porcentagem sobre o valor da parcela
            $valorParcelaComJuros = $valorParcela;
            $valorJuros = 0;
            if ($jurosPorParcela > 0) {
                $valorJuros = ($valorParcela * $jurosPorParcela) / 100;
                $valorParcelaComJuros = $valorParcela + $valorJuros;
            }

            $parcelas[] = [
                'numero' => $i + 1, // Numeração começa em 1
                'tipo' => 'Mensal',
                'valor' => round($valorParcelaComJuros, 2),
                'valor_sem_juros' => round($valorParcela, 2),
                'valor_juros' => round($valorJuros, 2),
                'vencimento' => $vencimento->format('Y-m-d'),
                'status' => 'Pendente'
            ];
        }

        // Adicionar parcelas anuais se marcado
        if ($temParcelaAnual && isset($validated['parcelas_anuais'])) {
            $contadorAnual = 1;

            foreach ($validated['parcelas_anuais'] as $parcelaAnual) {
                if (isset($parcelaAnual['ativa']) && $parcelaAnual['ativa'] &&
                    isset($parcelaAnual['valor']) && floatval($parcelaAnual['valor']) > 0) {

                    $anoParcela = intval($parcelaAnual['ano'] ?? ($dataPrimeiraParcela->year + $contadorAnual));
                    $vencimentoAnual = $dataPrimeiraParcela->copy()->setDate($anoParcela, $dataPrimeiraParcela->month, $dataPrimeiraParcela->day);

                    $valorAnual = round(floatval($parcelaAnual['valor']), 2);
                    $parcelas[] = [
                        'numero' => $contadorAnual++,
                        'tipo' => 'Anual',
                        'valor' => $valorAnual,
                        'valor_sem_juros' => $valorAnual, // Parcelas anuais não têm juros por parcela
                        'valor_juros' => 0,
                        'vencimento' => $vencimentoAnual->format('Y-m-d'),
                        'status' => 'Pendente'
                    ];
                }
            }
        }

        // Ordenar parcelas por data de vencimento
        usort($parcelas, function($a, $b) {
            return strcmp($a['vencimento'], $b['vencimento']);
        });

        // Renumerar parcelas mensais e anuais após ordenação
        $contadorMensal = 1;
        $contadorAnual = 1;
        foreach ($parcelas as &$parcela) {
            if ($parcela['tipo'] === 'Mensal') {
                $parcela['numero'] = $contadorMensal++;
            } else {
                $parcela['numero'] = $contadorAnual++;
            }
        }

        // Calcular soma total de todas as parcelas (com juros)
        $somaTotalParcelas = 0;
        foreach ($parcelas as $parcela) {
            $somaTotalParcelas += $parcela['valor'];
        }

        // Calcular valor total (entrada + soma de todas as parcelas)
        $valorTotal = $valorEntrada + $somaTotalParcelas;

        return response()->json([
            'parcelas' => $parcelas,
            'resumo' => [
                'valor_lote' => $valorLote,
                'valor_entrada' => $valorEntrada,
                'valor_parcelas_anuais' => $valorTotalParcelasAnuais,
                'valor_base' => $valorBase,
                'quantidade_parcelas' => $quantidade,
                'valor_parcela' => round($valorParcela, 2),
                'juros_por_parcela' => $jurosPorParcela,
                'total_parcelas' => count($parcelas),
                'soma_total_parcelas' => round($somaTotalParcelas, 2),
                'valor_total' => round($valorTotal, 2)
            ]
        ]);
    }

    /**
     * Exibir tela de reservar lote
     */
    public function reservarLote(Empreendimento $empreendimento, Lote $lote)
    {
        if (!Auth::user()->temPermissao('empreendimento', 'visualizar')) {
            abort(403, 'Você não tem permissão para visualizar empreendimentos.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual || $empreendimento->empresa_id !== $empresaAtual->id) {
            abort(403, 'Empreendimento não encontrado.');
        }

        // Verificar se o lote pertence ao empreendimento
        if ($lote->empreendimento_id !== $empreendimento->id) {
            abort(404, 'Lote não encontrado neste empreendimento.');
        }

        // Carregar relacionamentos do lote
        $lote->load(['quadra', 'status', 'cliente']);

        // Buscar clientes ativos da empresa
        $clientes = \App\Models\Cliente::where('empresa_id', $empresaAtual->id)
            ->where('status', true)
            ->orderBy('nome')
            ->get();

        // Buscar comentários do lote
        $comentarios = $lote->comentarios()->with('usuario')->get();

        return view('loteamento.lote.reservar', compact('empreendimento', 'lote', 'clientes', 'comentarios'));
    }

    /**
     * Salvar reserva do lote
     */
    public function salvarReserva(Request $request, Empreendimento $empreendimento, Lote $lote)
    {
        if (!Auth::user()->temPermissao('empreendimento', 'editar')) {
            abort(403, 'Você não tem permissão para editar empreendimentos.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual || $empreendimento->empresa_id !== $empresaAtual->id) {
            abort(403, 'Empreendimento não encontrado.');
        }

        // Verificar se o lote pertence ao empreendimento
        if ($lote->empreendimento_id !== $empreendimento->id) {
            abort(404, 'Lote não encontrado neste empreendimento.');
        }

        $request->validate([
            'cliente_id' => 'required|exists:cliente,id',
            'comentario' => 'nullable|string|max:5000',
        ]);

        try {
            DB::beginTransaction();

            // Buscar ou criar status "Reservado"
            $statusReservado = \App\Models\LoteStatus::where('empresa_id', $empresaAtual->id)
                ->whereRaw('LOWER(nome) = ?', ['reservado'])
                ->first();

            // Se não existir, criar o status "Reservado"
            if (!$statusReservado) {
                $statusReservado = \App\Models\LoteStatus::create([
                    'empresa_id' => $empresaAtual->id,
                    'nome' => 'Reservado',
                    'cor' => '#f59e0b', // Cor laranja para reservado
                    'tipo' => 1, // 1 = não vendido, 2 = vendido
                ]);
            }

            // Se o lote já estava reservado por outro cliente, salvar histórico da reserva anterior
            if ($lote->cliente_id && $lote->cliente_id !== $request->cliente_id) {
                // Buscar reserva ativa no histórico (sem data_fim)
                $reservaAtiva = \App\Models\LoteReservaHistorico::where('lote_id', $lote->id)
                    ->whereNull('data_fim')
                    ->first();

                if ($reservaAtiva) {
                    // Finalizar reserva anterior
                    $reservaAtiva->data_fim = now();
                    $reservaAtiva->save();
                } else {
                    // Criar histórico da reserva anterior (caso não exista no histórico)
                    \App\Models\LoteReservaHistorico::create([
                        'lote_id' => $lote->id,
                        'cliente_id' => $lote->cliente_id,
                        'usuario_id' => Auth::user()->id,
                        'data_reserva' => $lote->criado_em ?? now(),
                        'data_fim' => now(),
                    ]);
                }
            }

            // Vincular cliente ao lote e alterar status para "Reservado"
            $lote->cliente_id = $request->cliente_id;
            $lote->lote_status_id = $statusReservado->id;
            $lote->save();

            // Criar nova reserva no histórico (sempre criar, mesmo se for a primeira vez)
            \App\Models\LoteReservaHistorico::create([
                'lote_id' => $lote->id,
                'cliente_id' => $request->cliente_id,
                'usuario_id' => Auth::user()->id,
                'data_reserva' => now(),
            ]);

            // Criar comentário se fornecido (vinculado ao cliente)
            if ($request->filled('comentario')) {
                \App\Models\LoteComentario::create([
                    'lote_id' => $lote->id,
                    'cliente_id' => $request->cliente_id,
                    'usuario_id' => Auth::user()->id,
                    'comentario' => $request->comentario,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('loteamentos.mapa.view', $empreendimento->id)
                ->with('success', 'Lote reservado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao reservar lote: ' . $e->getMessage());
        }
    }

    /**
     * Exibir tela de comentários do lote
     */
    public function comentariosLote(Empreendimento $empreendimento, Lote $lote)
    {
        if (!Auth::user()->temPermissao('empreendimento', 'visualizar')) {
            abort(403, 'Você não tem permissão para visualizar empreendimentos.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual || $empreendimento->empresa_id !== $empresaAtual->id) {
            abort(403, 'Empreendimento não encontrado.');
        }

        // Verificar se o lote pertence ao empreendimento
        if ($lote->empreendimento_id !== $empreendimento->id) {
            abort(404, 'Lote não encontrado neste empreendimento.');
        }

        // Carregar relacionamentos do lote
        $lote->load(['quadra', 'status', 'cliente']);

        // Buscar comentários do lote vinculados ao cliente atual (se houver cliente)
        if ($lote->cliente_id) {
            $comentarios = $lote->comentarios()
                ->where('cliente_id', $lote->cliente_id)
                ->with(['usuario', 'cliente'])
                ->orderBy('criado_em', 'desc')
                ->get();
        } else {
            // Se não houver cliente, mostrar todos os comentários
            $comentarios = $lote->comentarios()->with(['usuario', 'cliente'])->orderBy('criado_em', 'desc')->get();
        }

        return view('loteamento.lote.comentarios', compact('empreendimento', 'lote', 'comentarios'));
    }

    /**
     * Exibir comentários do lote filtrados por cliente específico
     */
    public function comentariosLotePorCliente(Empreendimento $empreendimento, Lote $lote, \App\Models\Cliente $cliente)
    {
        if (!Auth::user()->temPermissao('empreendimento', 'visualizar')) {
            abort(403, 'Você não tem permissão para visualizar empreendimentos.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual || $empreendimento->empresa_id !== $empresaAtual->id) {
            abort(403, 'Empreendimento não encontrado.');
        }

        // Verificar se o lote pertence ao empreendimento
        if ($lote->empreendimento_id !== $empreendimento->id) {
            abort(404, 'Lote não encontrado neste empreendimento.');
        }

        // Carregar relacionamentos do lote
        $lote->load(['quadra', 'status', 'cliente']);

        // Buscar comentários do lote vinculados ao cliente específico
        $comentarios = $lote->comentarios()
            ->where('cliente_id', $cliente->id)
            ->with(['usuario', 'cliente'])
            ->orderBy('criado_em', 'desc')
            ->get();

        return view('loteamento.lote.comentarios', compact('empreendimento', 'lote', 'comentarios', 'cliente'));
    }

    /**
     * Salvar novo comentário do lote
     */
    public function salvarComentario(Request $request, Empreendimento $empreendimento, Lote $lote)
    {
        if (!Auth::user()->temPermissao('empreendimento', 'editar')) {
            abort(403, 'Você não tem permissão para editar empreendimentos.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual || $empreendimento->empresa_id !== $empresaAtual->id) {
            abort(403, 'Empreendimento não encontrado.');
        }

        // Verificar se o lote pertence ao empreendimento
        if ($lote->empreendimento_id !== $empreendimento->id) {
            abort(404, 'Lote não encontrado neste empreendimento.');
        }

        $request->validate([
            'comentario' => 'required|string|max:5000',
            'cliente_id' => 'nullable|exists:cliente,id',
        ]);

        try {
            // Vincular comentário ao cliente especificado no request ou ao cliente atual do lote (se houver)
            $clienteId = $request->cliente_id ?? $lote->cliente_id;

            \App\Models\LoteComentario::create([
                'lote_id' => $lote->id,
                'cliente_id' => $clienteId,
                'usuario_id' => Auth::user()->id,
                'comentario' => $request->comentario,
            ]);

            // Redirecionar para a rota correta dependendo se há um cliente específico
            if ($request->cliente_id) {
                return redirect()
                    ->route('loteamentos.lote.comentarios.cliente', [$empreendimento->id, $lote->id, $request->cliente_id])
                    ->with('success', 'Comentário adicionado com sucesso!');
            }

            return redirect()
                ->route('loteamentos.lote.comentarios', [$empreendimento->id, $lote->id])
                ->with('success', 'Comentário adicionado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao adicionar comentário: ' . $e->getMessage());
        }
    }

    /**
     * Salvar venda do lote e criar parcelas em contas-a-receber
     */
    public function salvarVenda(Request $request, Empreendimento $empreendimento, Lote $lote)
    {
        if (!Auth::user()->temPermissao('empreendimento', 'criar')) {
            return response()->json(['error' => 'Você não tem permissão para criar vendas.'], 403);
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual || $empreendimento->empresa_id !== $empresaAtual->id) {
            return response()->json(['error' => 'Empreendimento não encontrado.'], 403);
        }

        // Verificar se o lote pertence ao empreendimento
        if ($lote->empreendimento_id !== $empreendimento->id) {
            return response()->json(['error' => 'Lote não encontrado neste empreendimento.'], 404);
        }

        $validated = $request->validate([
            'cliente_id' => 'required|exists:cliente,id',
            'parcelas' => 'required|array|min:1',
            'parcelas.*.numero' => 'required|integer',
            'parcelas.*.tipo' => 'required|string|in:Mensal,Anual',
            'parcelas.*.valor' => 'required|numeric|min:0.01',
            'parcelas.*.valor_sem_juros' => 'required|numeric|min:0',
            'parcelas.*.vencimento' => 'required|date',
            'valor_entrada' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Buscar cliente
            $cliente = \App\Models\Cliente::findOrFail($validated['cliente_id']);





            // Buscar campos necessários (primeiros disponíveis)
            $planoConta = \App\Models\PlanoConta::daEmpresa($empresaAtual->id)->first();
            if (!$planoConta) {
                throw new \Exception('Nenhum plano de contas encontrado. Por favor, cadastre um plano de contas primeiro.');
            }

            $contaEmpresa = \App\Models\ContaEmpresa::daEmpresa($empresaAtual->id)->ativas()->first();
            if (!$contaEmpresa) {
                throw new \Exception('Nenhuma conta bancária encontrada. Por favor, cadastre uma conta bancária primeiro.');
            }

            $formaPagamento = \App\Models\FormaPagamento::daEmpresa($empresaAtual->id)->disponiveis()->first();
            if (!$formaPagamento) {
                throw new \Exception('Nenhuma forma de pagamento encontrada. Por favor, cadastre uma forma de pagamento primeiro.');
            }

            // Obter configurações do empreendimento
            $jurosTipo = 'por_dia'; // Sempre por dia conforme solicitado
            $jurosForma = $empreendimento->juros_forma ?? 'valor';
            // Preservar todas as casas decimais do juros - usar o valor diretamente do modelo
            // O Eloquent já faz o cast correto para decimal quando salvar
            $juros = $empreendimento->juros ?? 0;
            $multaForma = $empreendimento->multa_forma ?? 'valor';
            // Preservar todas as casas decimais da multa - usar o valor diretamente do modelo
            $multa = $empreendimento->multa ?? 0;

            // Gerar código único para todas as parcelas
            $parcelaCodigo = \Illuminate\Support\Str::uuid();

            // Criar descrição base
            $descricaoBase = 'Venda Lote ' . ($lote->nome ?? $lote->id) . ' - ' . $cliente->nome;

            // Verificar se há entrada
            $valorEntrada = floatval($validated['valor_entrada'] ?? 0);
            $temEntrada = $valorEntrada > 0;

            // Se houver entrada, criar movimentação separada para entrada (sem parcela_codigo)
            if ($temEntrada) {
                \App\Models\Movimentacao::create([
                    'id' => \Illuminate\Support\Str::uuid(),
                    'empresa_id' => $empresaAtual->id,
                    'plano_conta_id' => $planoConta->id,
                    'centro_custo_id' => null,
                    'forma_pagamento_id' => $formaPagamento->id,
                    'conta_empresa_id' => $contaEmpresa->id,
                    'situacao' => \App\Enums\MovimentacaoSituacaoEnum::PAGA, // Entrada é paga na hora
                    'tipo' => \App\Enums\MovimentacaoTipoEnum::RECEBER,
                    'parcela_codigo' => null, // Entrada não tem parcela_codigo
                    'numero_parcela' => null, // Entrada não tem número de parcela
                    'entidade_tipo' => \App\Enums\EntidadeTipoEnum::LOTEAMENTO->value,
                    'entidade_id' => $lote->id,
                    'descricao' => $descricaoBase . ' - Entrada',
                    'vencimento' => now()->toDateString(),
                    'observacao' => null,
                    'informacao_complementar' => 'Cliente: ' . $cliente->nome . ' | Lote: ' . ($lote->nome ?? $lote->id) . ' | Entrada',
                    'valor' => $valorEntrada,
                    'juros' => 0, // Entrada não tem juros
                    'juros_tipo' => null,
                    'juros_forma' => null,
                    'multa' => 0, // Entrada não tem multa
                    'multa_forma' => null,
                    'desconto' => 0,
                    'valor_total' => $valorEntrada,
                    'data_compensacao' => now()->toDateString(),
                    'criado_em' => now(),
                    'atualizado_em' => now(),
                ]);
            }

            // Criar movimentação para cada parcela (todas as parcelas, sem ignorar nenhuma)
            foreach ($validated['parcelas'] as $parcelaData) {
                $numeroParcela = $parcelaData['numero'];
                $valorSemJuros = floatval($parcelaData['valor_sem_juros']);
                $valorComJuros = floatval($parcelaData['valor']); // Valor com juros já calculado
                $vencimento = \Carbon\Carbon::parse($parcelaData['vencimento']);
                $hoje = \Carbon\Carbon::now()->startOfDay();
                $estaVencida = $vencimento->lt($hoje);

                // Calcular juros baseado na forma
                $jurosCalculado = 0;
                // Converter para float apenas para cálculos, mas preservar o valor original para salvar
                $jurosFloat = (float)$juros;
                if ($jurosFloat > 0) {
                    // Calcular valor do juros baseado na forma (valor ou porcentagem)
                    if ($jurosForma === 'porcentagem') {
                        $jurosCalculado = ($valorSemJuros * $jurosFloat) / 100;
                    } else {
                        $jurosCalculado = $jurosFloat;
                    }

                    // Aplicar juros apenas se:
                    // - Tipo for "fixo" (sempre aplica)
                    // - Tipo for "por_dia" E a parcela estiver vencida
                    if ($jurosTipo === 'por_dia' && !$estaVencida) {
                        $jurosCalculado = 0;
                    }
                }

                // Calcular multa baseado na forma (só aplica se estiver vencida)
                $multaCalculada = 0;
                // Converter para float apenas para cálculos, mas preservar o valor original para salvar
                $multaFloat = (float)$multa;
                if ($multaFloat > 0 && $estaVencida) {
                    if ($multaForma === 'porcentagem') {
                        $multaCalculada = ($valorSemJuros * $multaFloat) / 100;
                    } else {
                        $multaCalculada = $multaFloat;
                    }
                }

                // Calcular valor total seguindo a mesma lógica do MovimentacaoController
                // valor_total = valor + juros (se aplicável) + multa - desconto
                $desconto = 0; // Não há desconto nas vendas de lote por enquanto
                $valorTotal = $valorComJuros; // Usar valor com juros como base

                // Aplicar multa (já foi calculada apenas se estiver vencida)
                if ($multaCalculada > 0) {
                    $valorTotal += $multaCalculada;
                }

                // Aplicar desconto (se houver no futuro)
                if ($desconto > 0) {
                    $valorTotal -= $desconto;
                }

                // Criar descrição da parcela
                $tipoParcela = $parcelaData['tipo'] === 'Anual' ? 'Anual' : 'Mensal';
                $descricao = $descricaoBase . ' - Parcela ' . $numeroParcela . ' (' . $tipoParcela . ')';
                $informacaoComplementar = 'Cliente: ' . $cliente->nome . ' | Lote: ' . ($lote->nome ?? $lote->id);

                // Salvar movimentação com valor COM juros
                \App\Models\Movimentacao::create([
                    'id' => \Illuminate\Support\Str::uuid(),
                    'empresa_id' => $empresaAtual->id,
                    'plano_conta_id' => $planoConta->id,
                    'centro_custo_id' => null,
                    'forma_pagamento_id' => $formaPagamento->id,
                    'conta_empresa_id' => $contaEmpresa->id,
                    'situacao' => \App\Enums\MovimentacaoSituacaoEnum::PENDENTE,
                    'tipo' => \App\Enums\MovimentacaoTipoEnum::RECEBER,
                    'parcela_codigo' => $parcelaCodigo, // Parcelas têm parcela_codigo
                    'numero_parcela' => $numeroParcela,
                    'entidade_tipo' => \App\Enums\EntidadeTipoEnum::LOTEAMENTO->value,
                    'entidade_id' => $lote->id,
                    'descricao' => $descricao,
                    'vencimento' => $vencimento->format('Y-m-d'),
                    'observacao' => null,
                    'informacao_complementar' => $informacaoComplementar,
                    'valor' => $valorComJuros, // Salvar valor COM juros
                    'juros' => $juros,
                    'juros_tipo' => $jurosTipo,
                    'juros_forma' => $jurosForma,
                    'multa' => $multa,
                    'multa_forma' => $multaForma,
                    'desconto' => 0,
                    'valor_total' => $valorTotal,
                    'data_compensacao' => null,
                    'criado_em' => now(),
                    'atualizado_em' => now(),
                ]);

                // Salvar dados da parcela na tabela lote_venda_parcela (com e sem juros)
                \App\Models\LoteVendaParcela::create([
                    'id' => \Illuminate\Support\Str::uuid(),
                    'lote_id' => $lote->id,
                    'numero' => $numeroParcela,
                    'tipo' => $tipoParcela,
                    'valor_sem_juros' => $valorSemJuros,
                    'valor_com_juros' => $valorComJuros,
                    'vencimento' => $vencimento->format('Y-m-d'),
                ]);
            }

            // Atualizar lote com cliente e status vendido
            $statusVendido = \App\Models\LoteStatus::firstOrCreate(
                [
                    'empresa_id' => $empresaAtual->id,
                    'nome' => 'Vendido',
                ],
                [
                    'cor' => '#10b981',
                    'tipo' => 2, // Vendido
                ]
            );

            $lote->cliente_id = $cliente->id;
            $lote->lote_status_id = $statusVendido->id;
            $lote->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venda salva com sucesso! As parcelas foram criadas em contas-a-receber.',
                'parcela_codigo' => (string)$parcelaCodigo,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Erro ao salvar venda: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Listar todas as vendas de lotes
     */
    public function vendasIndex(Request $request)
    {
        if (!Auth::user()->temPermissao('empreendimento', 'visualizar')) {
            abort(403, 'Você não tem permissão para visualizar vendas.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual) {
            abort(403, 'Empresa atual não encontrada.');
        }

        // Buscar todos os lotes vendidos
        $query = \App\Models\Lote::whereHas('status', function($q) {
                $q->where('nome', 'Vendido');
            })
            ->whereHas('empreendimento', function($q) use ($empresaAtual) {
                $q->where('empresa_id', $empresaAtual->id);
            })
            ->with(['empreendimento', 'quadra', 'status', 'cliente']);

        // Filtros
        $filtroEmpreendimento = $request->get('empreendimento_id');
        if ($filtroEmpreendimento) {
            $query->where('empreendimento_id', $filtroEmpreendimento);
        }

        $filtroCliente = $request->get('cliente_id');
        if ($filtroCliente) {
            $query->where('cliente_id', $filtroCliente);
        }

        $filtroQuadra = $request->get('quadra_id');
        if ($filtroQuadra) {
            $query->where('quadra_id', $filtroQuadra);
        }

        $vendas = $query->orderBy('criado_em', 'desc')->paginate(20);

        // Buscar dados para filtros
        $empreendimentos = \App\Models\Empreendimento::daEmpresa($empresaAtual->id)
            ->orderBy('nome')
            ->get();

        $clientes = \App\Models\Cliente::daEmpresa($empresaAtual->id)
            ->ativos()
            ->orderBy('nome')
            ->get();

        return view('loteamento.vendas.index', compact('vendas', 'empreendimentos', 'clientes'));
    }

    /**
     * Exibir parcelas de uma venda de lote
     */
    public function vendasParcelas(\App\Models\Lote $lote)
    {
        if (!Auth::user()->temPermissao('empreendimento', 'visualizar')) {
            abort(403, 'Você não tem permissão para visualizar parcelas.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        if (!$empresaAtual) {
            abort(403, 'Empresa atual não encontrada.');
        }

        // Verificar se o lote pertence à empresa
        if ($lote->empreendimento->empresa_id !== $empresaAtual->id) {
            abort(403, 'Lote não encontrado.');
        }

        // Verificar se o lote foi vendido
        if (!$lote->status || $lote->status->nome !== 'Vendido') {
            abort(404, 'Este lote não foi vendido.');
        }

        // Buscar parcelas da venda (tabela lote_venda_parcela) com paginação
        $parcelas = \App\Models\LoteVendaParcela::where('lote_id', $lote->id)
            ->orderBy('numero')
            ->paginate(15);

        // Separar parcelas mensais e anuais (usar getCollection() para trabalhar com a coleção paginada)
        $parcelasMensais = $parcelas->getCollection()->where('tipo', 'Mensal');
        $parcelasAnuais = $parcelas->getCollection()->where('tipo', 'Anual');

        // Buscar movimentações relacionadas ao lote
        $movimentacoes = \App\Models\Movimentacao::where('entidade_tipo', \App\Enums\EntidadeTipoEnum::LOTEAMENTO->value)
            ->where('entidade_id', $lote->id)
            ->orderBy('vencimento')
            ->get();

        // Buscar movimentação de entrada (sem parcela_codigo e com "Entrada" na descrição)
        $movimentacaoEntrada = $movimentacoes->first(function($m) {
            return $m->parcela_codigo === null &&
                   $m->numero_parcela === null &&
                   str_contains($m->descricao, 'Entrada');
        });

        $valorEntrada = $movimentacaoEntrada ? $movimentacaoEntrada->valor : 0;

        // Carregar relacionamentos
        $lote->load(['empreendimento', 'quadra', 'cliente', 'status']);

        return view('loteamento.vendas.parcelas', compact('lote', 'parcelas', 'parcelasMensais', 'parcelasAnuais', 'movimentacoes', 'movimentacaoEntrada', 'valorEntrada'));
    }
}
