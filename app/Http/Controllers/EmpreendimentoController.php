<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empreendimento;
use App\Models\Lote;
use App\Helpers\PermissionHelper;
use App\Helpers\PublicPathHelper;
use Illuminate\Support\Facades\Auth;
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
                'sinal_valor' => 'nullable|numeric|min:0',
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
                'sinal_valor' => 'nullable|numeric|min:0',
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

        return view('loteamento.lote.vender', compact('empreendimento', 'lote'));
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

        return view('loteamento.lote.reservar', compact('empreendimento', 'lote'));
    }
}
