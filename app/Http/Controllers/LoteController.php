<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lote;
use App\Models\Empreendimento;
use App\Models\Quadra;
use App\Models\LoteStatus;
use App\Helpers\PermissionHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class LoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Empreendimento $empreendimento)
    {
        if (!Auth::user()->temPermissao('lote', 'listar')) {
            abort(403, 'Você não tem permissão para listar lotes.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        if (!$empresaPrincipal || $empreendimento->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Empreendimento não encontrado.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();

        // Filtros
        $filtroNome = $request->get('nome');
        $filtroQuadra = $request->get('quadra');
        $filtroStatus = $request->get('status');
        $filtroValorMin = $request->get('valor_min');
        $filtroValorMax = $request->get('valor_max');

        // Ordenação
        $sortBy = $request->get('sort_by');
        $sortDirection = $request->get('sort_direction', 'asc');

        // Sempre usar join com quadra para permitir ordenação por quadra (padrão ou quando solicitado)
        $query = Lote::select('lote.*')
            ->leftJoin('quadra', 'lote.quadra_id', '=', 'quadra.id')
            ->where('lote.empreendimento_id', $empreendimento->id)
            ->with(['quadra', 'status']); // Manter eager loading

        // Aplicar filtros com prefixo de tabela
        if ($filtroNome) {
            $query->where('lote.nome', 'like', "%{$filtroNome}%");
        }
        if ($filtroQuadra) {
            $query->where('lote.quadra_id', $filtroQuadra);
        }
        if ($filtroStatus) {
            $query->where('lote.lote_status_id', $filtroStatus);
        }
        if ($filtroValorMin) {
            $query->where('lote.valor', '>=', $filtroValorMin);
        }
        if ($filtroValorMax) {
            $query->where('lote.valor', '<=', $filtroValorMax);
        }

        // Ordenação
        // Sempre ordenar por quadra primeiro, depois por nome (numericamente quando possível)
        if ($sortBy && $sortBy === 'quadra') {
            $query->orderBy('quadra.nome', $sortDirection)
                  ->orderByRaw("CAST(REGEXP_SUBSTR(lote.nome, '[0-9]+') AS UNSIGNED) ASC")
                  ->orderBy('lote.nome', 'asc'); // Fallback para ordenação alfabética
        } elseif ($sortBy === 'm2') {
            $query->orderBy('lote.m2', $sortDirection)
                  ->orderBy('quadra.nome', 'asc')
                  ->orderByRaw("CAST(REGEXP_SUBSTR(lote.nome, '[0-9]+') AS UNSIGNED) ASC")
                  ->orderBy('lote.nome', 'asc'); // Fallback para ordenação alfabética
        } elseif ($sortBy === 'valor') {
            $query->orderBy('lote.valor', $sortDirection)
                  ->orderBy('quadra.nome', 'asc')
                  ->orderByRaw("CAST(REGEXP_SUBSTR(lote.nome, '[0-9]+') AS UNSIGNED) ASC")
                  ->orderBy('lote.nome', 'asc'); // Fallback para ordenação alfabética
        } elseif ($sortBy) {
            $query->orderBy('lote.' . $sortBy, $sortDirection)
                  ->orderBy('quadra.nome', 'asc')
                  ->orderByRaw("CAST(REGEXP_SUBSTR(lote.nome, '[0-9]+') AS UNSIGNED) ASC")
                  ->orderBy('lote.nome', 'asc'); // Fallback para ordenação alfabética
        } else {
            // Ordenação padrão: por quadra, depois por nome do lote (numericamente)
            $query->orderBy('quadra.nome', 'asc')
                  ->orderByRaw("CAST(REGEXP_SUBSTR(lote.nome, '[0-9]+') AS UNSIGNED) ASC")
                  ->orderBy('lote.nome', 'asc'); // Fallback para ordenação alfabética
        }

        // Paginação
        $perPage = 20;
        $lotes = $query->paginate($perPage)->appends($request->query());

        // Buscar dados para filtros
        $quadras = Quadra::where('empreendimento_id', $empreendimento->id)
            ->orderBy('nome')
            ->get();

        $statusDisponiveis = LoteStatus::where('empresa_id', $empresaAtual->id)
            ->orderBy('nome')
            ->get();

        return view('lote.index', compact(
            'empreendimento',
            'lotes',
            'quadras',
            'statusDisponiveis',
            'filtroNome',
            'filtroQuadra',
            'filtroStatus',
            'filtroValorMin',
            'filtroValorMax'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Empreendimento $empreendimento)
    {
        if (!Auth::user()->temPermissao('lote', 'criar')) {
            abort(403, 'Você não tem permissão para criar lotes.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        if (!$empresaPrincipal || $empreendimento->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Empreendimento não encontrado.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        // Buscar quadras vinculadas a este empreendimento
        $quadras = Quadra::where('empreendimento_id', $empreendimento->id)
            ->orderBy('nome')
            ->get();

        $status = LoteStatus::where('empresa_id', $empresaAtual->id)->orderBy('nome')->get();

        return view('lote.create', compact('empreendimento', 'quadras', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Empreendimento $empreendimento)
    {
        if (!Auth::user()->temPermissao('lote', 'criar')) {
            abort(403, 'Você não tem permissão para criar lotes.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        if (!$empresaPrincipal || $empreendimento->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Empreendimento não encontrado.');
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'quadra_id' => 'required|exists:quadra,id',
            'lote_status_id' => 'required|exists:lote_status,id',
            'frente' => 'nullable|numeric|min:0',
            'fundo' => 'nullable|numeric|min:0',
            'lateral_direita' => 'nullable|numeric|min:0',
            'lateral_esquerda' => 'nullable|numeric|min:0',
            'valor_m2' => 'required|numeric|min:0',
            'm2' => 'required|numeric|min:0',
            'm2_tipo' => 'required|in:1,2',
            'valor' => 'nullable|numeric|min:0',
            'observacao' => 'nullable|string',
        ]);

        // Validação condicional: se tipo for manual (2), m² deve ser preenchido manualmente
        // Se tipo for calculado (1), m² será calculado automaticamente se não fornecido

        $validated['empreendimento_id'] = $empreendimento->id;

        // Calcular m² apenas se o tipo for calculado (1)
        if ($validated['m2_tipo'] == 1 && empty($validated['m2'])) {
            $area = 0;

            // Área base (frente × fundo)
            if (!empty($validated['frente']) && !empty($validated['fundo'])) {
                $area = $validated['frente'] * $validated['fundo'];
            }

            // Adicionar lateral direita (se preenchida)
            if (!empty($validated['lateral_direita']) && !empty($validated['fundo'])) {
                $area += $validated['lateral_direita'] * $validated['fundo'];
            }

            // Adicionar lateral esquerda (se preenchida)
            if (!empty($validated['lateral_esquerda']) && !empty($validated['fundo'])) {
                $area += $validated['lateral_esquerda'] * $validated['fundo'];
            }

            if ($area > 0) {
                $validated['m2'] = $area;
            }
        }

        // Calcular valor se não fornecido
        if (empty($validated['valor']) && !empty($validated['m2'])) {
            $valor_m2 = $validated['valor_m2'] ?? $empreendimento->valor_m2;
            if ($valor_m2) {
                $validated['valor'] = $validated['m2'] * $valor_m2;
            }
        }

        Lote::create($validated);

        $action = $request->input('action', 'save');

        if ($action === 'save_and_new') {
            return redirect()->route('lotes.create', $empreendimento->id)
                ->with('success', 'Lote criado com sucesso. Preencha os dados para criar outro lote.');
        }

        return redirect()->route('lotes.index', $empreendimento->id)
            ->with('success', 'Lote criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lote $lote)
    {
        if (!Auth::user()->temPermissao('lote', 'visualizar')) {
            abort(403, 'Você não tem permissão para visualizar lotes.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        if (!$empresaPrincipal || $lote->empreendimento->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Lote não encontrado.');
        }

        $lote->load(['empreendimento', 'quadra', 'status', 'cliente']);

        // Buscar clientes únicos que já reservaram este lote
        $clientesReservaram = \App\Models\LoteReservaHistorico::where('lote_id', $lote->id)
            ->with('cliente')
            ->get()
            ->map(function ($reserva) {
                return $reserva->cliente;
            })
            ->filter()
            ->unique('id')
            ->values();

        return view('lote.show', compact('lote', 'clientesReservaram'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lote $lote)
    {
        if (!Auth::user()->temPermissao('lote', 'editar')) {
            abort(403, 'Você não tem permissão para editar lotes.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        if (!$empresaPrincipal || $lote->empreendimento->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Lote não encontrado.');
        }

        $empresaAtual = PermissionHelper::getEmpresaAtual();
        // Buscar quadras vinculadas a este empreendimento
        $quadras = Quadra::where('empreendimento_id', $lote->empreendimento_id)
            ->orderBy('nome')
            ->get();

        $status = LoteStatus::where('empresa_id', $empresaAtual->id)->orderBy('nome')->get();

        return view('lote.edit', compact('lote', 'quadras', 'status'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lote $lote)
    {
        if (!Auth::user()->temPermissao('lote', 'editar')) {
            abort(403, 'Você não tem permissão para editar lotes.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        if (!$empresaPrincipal || $lote->empreendimento->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Lote não encontrado.');
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'quadra_id' => 'required|exists:quadra,id',
            'lote_status_id' => 'required|exists:lote_status,id',
            'frente' => 'nullable|numeric|min:0',
            'fundo' => 'nullable|numeric|min:0',
            'lateral_direita' => 'nullable|numeric|min:0',
            'lateral_esquerda' => 'nullable|numeric|min:0',
            'valor_m2' => 'required|numeric|min:0',
            'm2' => 'required|numeric|min:0',
            'm2_tipo' => 'required|in:1,2',
            'valor' => 'nullable|numeric|min:0',
            'observacao' => 'nullable|string',
        ]);

        // Validação condicional: se tipo for manual (2), m² deve ser preenchido manualmente
        // Se tipo for calculado (1), m² será calculado automaticamente se não fornecido

        // Calcular m² apenas se o tipo for calculado (1)
        if ($validated['m2_tipo'] == 1 && empty($validated['m2'])) {
            $area = 0;

            // Área base (frente × fundo)
            if (!empty($validated['frente']) && !empty($validated['fundo'])) {
                $area = $validated['frente'] * $validated['fundo'];
            }

            // Adicionar lateral direita (se preenchida)
            if (!empty($validated['lateral_direita']) && !empty($validated['fundo'])) {
                $area += $validated['lateral_direita'] * $validated['fundo'];
            }

            // Adicionar lateral esquerda (se preenchida)
            if (!empty($validated['lateral_esquerda']) && !empty($validated['fundo'])) {
                $area += $validated['lateral_esquerda'] * $validated['fundo'];
            }

            if ($area > 0) {
                $validated['m2'] = $area;
            }
        }

        // Calcular valor se não fornecido
        if (empty($validated['valor']) && !empty($validated['m2'])) {
            $valor_m2 = $validated['valor_m2'] ?? $lote->empreendimento->valor_m2;
            if ($valor_m2) {
                $validated['valor'] = $validated['m2'] * $valor_m2;
            }
        }

        $lote->update($validated);

        return redirect()->route('lotes.index', $lote->empreendimento_id)
            ->with('success', 'Lote atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lote $lote)
    {
        if (!Auth::user()->temPermissao('lote', 'deletar')) {
            abort(403, 'Você não tem permissão para deletar lotes.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        if (!$empresaPrincipal || $lote->empreendimento->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Lote não encontrado.');
        }

        // Verificar se o lote está vendido
        if ($lote->status && $lote->status->tipo == 2) {
            return redirect()->back()
                ->with('error', 'Não é possível deletar um lote com status de vendido.');
        }

        $empreendimentoId = $lote->empreendimento_id;
        $lote->delete();

        return redirect()->route('lotes.index', $empreendimentoId)
            ->with('success', 'Lote deletado com sucesso.');
    }

    /**
     * Delete multiple lots
     */
    public function destroyMassa(Request $request, Empreendimento $empreendimento)
    {
        if (!Auth::user()->temPermissao('lote', 'deletar')) {
            abort(403, 'Você não tem permissão para deletar lotes.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        if (!$empresaPrincipal || $empreendimento->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Empreendimento não encontrado.');
        }

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|uuid|exists:lote,id',
        ]);

        $ids = $request->input('ids');
        $lotes = Lote::whereIn('id', $ids)
            ->where('empreendimento_id', $empreendimento->id)
            ->with('status')
            ->get();

        if ($lotes->count() !== count($ids)) {
            return redirect()->back()
                ->with('error', 'Alguns lotes não foram encontrados.');
        }

        // Verificar se algum lote está vendido
        $lotesVendidos = $lotes->filter(function ($lote) {
            return $lote->status && $lote->status->tipo == 2;
        });

        if ($lotesVendidos->count() > 0) {
            $nomesLotesVendidos = $lotesVendidos->pluck('nome')->toArray();
            $mensagem = 'Não é possível deletar lotes com status de vendido.';

            if ($lotesVendidos->count() == 1) {
                $mensagem = "Não é possível deletar o lote \"{$nomesLotesVendidos[0]}\" pois ele está com status de vendido.";
            } else {
                $mensagem = 'Não é possível deletar lotes com status de vendido: ' . implode(', ', $nomesLotesVendidos) . '.';
            }

            return redirect()->back()
                ->with('error', $mensagem);
        }

        $count = $lotes->count();
        $lotes->each->delete();

        return redirect()->route('lotes.index', $empreendimento->id)
            ->with('success', "{$count} lote(s) deletado(s) com sucesso.");
    }

    /**
     * Download CSV example file
     */
    public function downloadExemploCsv(Empreendimento $empreendimento)
    {
        if (!Auth::user()->temPermissao('lote', 'criar')) {
            abort(403, 'Você não tem permissão para importar lotes.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        if (!$empresaPrincipal || $empreendimento->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Empreendimento não encontrado.');
        }

        // Buscar quadras para exemplo
        $quadras = Quadra::where('empreendimento_id', $empreendimento->id)->orderBy('nome')->get();

        // Criar CSV
        $filename = 'exemplo_lotes_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        // Adicionar BOM para UTF-8 (importante para Excel)
        $output = "\xEF\xBB\xBF";

        // Cabeçalhos (sem Status, Tipo de Cálculo e Valor Total)
        $output .= "Nome do Lote,Quadra,Frente (m),Fundo (m),Lateral Direita (m),Lateral Esquerda (m),Área (m²),Valor por m²,Observação\n";

        // Exemplo de dados
        $quadraNome = $quadras->first()->nome ?? 'Quadra A';
        $valorM2 = number_format($empreendimento->valor_m2 ?? 500, 2, '.', '');

        // Linha de exemplo 1 - com cálculo automático (área vazia)
        $output .= sprintf(
            "\"%s\",\"%s\",\"10.00\",\"20.00\",\"5.00\",\"5.00\",\"\",\"%s\",\"Exemplo de lote com cálculo automático de área\"\n",
            'Lote 001',
            $quadraNome,
            $valorM2
        );

        // Linha de exemplo 2 - com área manual (dimensões vazias)
        $output .= sprintf(
            "\"%s\",\"%s\",\"\",\"\",\"\",\"\",\"300.00\",\"%s\",\"Exemplo de lote com área manual\"\n",
            'Lote 002',
            $quadraNome,
            $valorM2
        );

        // Linha de exemplo 3 - apenas frente e fundo
        $output .= sprintf(
            "\"%s\",\"%s\",\"15.00\",\"25.00\",\"\",\"\",\"\",\"%s\",\"\"\n",
            'Lote 003',
            $quadraNome,
            $valorM2
        );

        return Response::make($output, 200, $headers);
    }

    /**
     * Show import CSV form
     */
    public function import(Empreendimento $empreendimento)
    {
        if (!Auth::user()->temPermissao('lote', 'criar')) {
            abort(403, 'Você não tem permissão para importar lotes.');
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        if (!$empresaPrincipal || $empreendimento->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Empreendimento não encontrado.');
        }

        return view('lote.import', compact('empreendimento'));
    }

    /**
     * Import lots from CSV file
     */
    public function importCsv(Request $request, Empreendimento $empreendimento)
    {
        if (!Auth::user()->temPermissao('lote', 'criar')) {
            return response()->json([
                'success' => false,
                'message' => 'Você não tem permissão para importar lotes.'
            ], 403);
        }

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        if (!$empresaPrincipal || $empreendimento->empresa_id !== $empresaPrincipal->id) {
            return response()->json([
                'success' => false,
                'message' => 'Empreendimento não encontrado.'
            ], 403);
        }

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        try {
            $file = $request->file('csv_file');
            $lines = file($file->getRealPath(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            if (count($lines) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'O arquivo CSV está vazio ou não possui dados válidos.',
                    'total_linhas' => 0,
                    'validas' => 0,
                    'erros' => []
                ], 400);
            }

            // Remover BOM se existir
            $lines[0] = preg_replace('/\xEF\xBB\xBF/', '', $lines[0]);

            // Remover cabeçalho
            array_shift($lines);
            $totalLinhas = count($lines);

            $empresaAtual = PermissionHelper::getEmpresaAtual();
            $quadrasExistentes = Quadra::where('empreendimento_id', $empreendimento->id)->pluck('nome', 'id')->toArray();
            // Buscar status "Disponível" para usar como padrão
            $statusDisponivel = LoteStatus::where('empresa_id', $empresaAtual->id)
                ->where('nome', 'Disponível')
                ->first();

            if (!$statusDisponivel) {
                // Se não encontrar "Disponível", buscar o primeiro status com tipo 1 (disponível/negociação)
                $statusDisponivel = LoteStatus::where('empresa_id', $empresaAtual->id)
                    ->where('tipo', 1)
                    ->first();
            }

            if (!$statusDisponivel) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status "Disponível" não encontrado. Por favor, crie um status antes de importar lotes.',
                    'total_linhas' => 0,
                    'validas' => 0,
                    'erros' => []
                ], 400);
            }

            $statusIdDisponivel = $statusDisponivel->id;

            $validas = 0;
            $erros = [];
            $dadosValidos = [];
            $quadrasACriar = []; // Para criar quadras que não existem

            // Buscar lotes existentes para verificar duplicados (nome + quadra)
            $lotesExistentes = Lote::where('empreendimento_id', $empreendimento->id)
                ->with('quadra')
                ->get()
                ->map(function($lote) {
                    return [
                        'nome' => strtolower(trim($lote->nome)),
                        'quadra_nome' => strtolower(trim($lote->quadra->nome ?? '')),
                        'lote' => $lote
                    ];
                })
                ->keyBy(function($item) {
                    return $item['nome'] . '|' . $item['quadra_nome'];
                });

            // Validar todas as linhas primeiro (não parar no primeiro erro)
            foreach ($lines as $linhaNum => $linha) {
                $numeroLinha = $linhaNum + 2; // +1 porque removemos cabeçalho, +1 porque começamos em 1
                $linhaOriginal = $linha;

                // Limpar linha
                $linha = trim($linha);
                if (empty($linha)) {
                    continue;
                }

                // Parse CSV considerando aspas
                $dados = str_getcsv($linha);

                if (count($dados) < 9) {
                    $erros[] = [
                        'linha' => $numeroLinha,
                        'dados' => $linhaOriginal,
                        'dados_formatados' => null,
                        'mensagem' => 'Número insuficiente de colunas no arquivo CSV. Esperado: 9 colunas (Nome do Lote, Quadra, Frente, Fundo, Lateral Direita, Lateral Esquerda, Área, Valor por m², Observação), mas encontrado apenas: ' . count($dados) . ' coluna(s). Verifique se todas as colunas estão presentes no arquivo.'
                    ];
                    continue;
                }

                // Mapear dados (sem Status, Tipo de Cálculo e Valor Total)
                $nome = trim($dados[0]);
                $quadraNome = trim($dados[1]);
                $frente = !empty($dados[2]) ? floatval(str_replace(',', '.', $dados[2])) : null;
                $fundo = !empty($dados[3]) ? floatval(str_replace(',', '.', $dados[3])) : null;
                $lateralDireita = !empty($dados[4]) ? floatval(str_replace(',', '.', $dados[4])) : null;
                $lateralEsquerda = !empty($dados[5]) ? floatval(str_replace(',', '.', $dados[5])) : null;
                $m2 = !empty($dados[6]) ? floatval(str_replace(',', '.', $dados[6])) : null;
                $valorM2 = !empty($dados[7]) ? floatval(str_replace(',', '.', $dados[7])) : ($empreendimento->valor_m2 ?? null);
                $observacao = isset($dados[8]) ? trim($dados[8]) : null;

                // Status sempre será "Disponível" (definido acima)
                $statusId = $statusIdDisponivel;
                $statusNome = $statusDisponivel->nome;

                // Validações (coletar todos os erros, não parar no primeiro)
                $erroLinha = null;

                if (empty($nome)) {
                    $erroLinha = 'O campo "Nome do Lote" é obrigatório e não pode estar vazio. Por favor, informe o nome do lote.';
                }

                // Buscar ou criar quadra (REGRA 1)
                $quadraId = null;
                if (!$erroLinha && !empty($quadraNome)) {
                    // Validar tipo de numeração da quadra conforme o empreendimento
                    $tipoNumeracao = $empreendimento->quadra_numeracao_tipo ?? 1; // 1: Numérica, 2: Alfanumérica
                    $quadraNomeLimpo = trim($quadraNome);

                    if ($tipoNumeracao == 1) {
                        // Numérica: deve conter apenas números
                        if (!preg_match('/^\d+$/', $quadraNomeLimpo)) {
                            $erroLinha = "A quadra informada ('{$quadraNomeLimpo}') contém letras, mas este empreendimento utiliza apenas numeração numérica. Por favor, use apenas números. Exemplos válidos: 1, 2, 3, 10, 25.";
                        }
                    } else {
                        // Alfanumérica: deve conter pelo menos uma letra
                        if (preg_match('/^\d+$/', $quadraNomeLimpo)) {
                            $erroLinha = "A quadra informada ('{$quadraNomeLimpo}') contém apenas números, mas este empreendimento utiliza numeração alfanumérica (com letras). Por favor, use letras ou alfanumérico. Exemplos válidos: A, B, AA, 1A, 2B.";
                        }
                    }

                    // Buscar quadra existente apenas se não houver erro de tipo
                    if (!$erroLinha) {
                        foreach ($quadrasExistentes as $id => $nomeQuadra) {
                            if (strcasecmp($nomeQuadra, $quadraNome) == 0) {
                                $quadraId = $id;
                                break;
                            }
                        }

                        // Se não encontrou, adicionar à lista para criar
                        if (!$quadraId) {
                            $quadrasACriar[$quadraNome] = $quadraNome;
                        }
                    }
                } elseif (!$erroLinha && empty($quadraNome)) {
                    $erroLinha = 'O campo "Quadra" é obrigatório e não pode estar vazio. Por favor, informe o nome da quadra onde o lote está localizado.';
                }

                // Status sempre será "Disponível" (já definido acima, não precisa validar)

                // Determinar tipo de cálculo e processar área
                $m2Tipo = null;

                // REGRA 3: Se Área (m²) estiver preenchida, zerar dimensões
                if (!$erroLinha && !empty($m2) && $m2 > 0) {
                    $frente = null;
                    $fundo = null;
                    $lateralDireita = null;
                    $lateralEsquerda = null;
                    $m2Tipo = 2; // Manual, já que área foi fornecida
                }

                // REGRA 2: Se Área (m²) estiver vazia, validar todas as dimensões e calcular
                if (!$erroLinha && (empty($m2) || $m2 == 0)) {
                    // Quando área está vazia, TODAS as dimensões são obrigatórias
                    $dimensoesFaltando = [];

                    if (empty($frente) || $frente <= 0) {
                        $dimensoesFaltando[] = 'Frente (m)';
                    }
                    if (empty($fundo) || $fundo <= 0) {
                        $dimensoesFaltando[] = 'Fundo (m)';
                    }
                    if (empty($lateralDireita) || $lateralDireita <= 0) {
                        $dimensoesFaltando[] = 'Lateral Direita (m)';
                    }
                    if (empty($lateralEsquerda) || $lateralEsquerda <= 0) {
                        $dimensoesFaltando[] = 'Lateral Esquerda (m)';
                    }

                    // Se alguma dimensão estiver faltando
                    if (count($dimensoesFaltando) > 0) {
                        if (count($dimensoesFaltando) == 1) {
                            $erroLinha = 'Como o campo "Área (m²)" está vazio, é obrigatório preencher todos os campos de dimensão para que a área possa ser calculada automaticamente. O campo que está faltando é: ' . $dimensoesFaltando[0] . '.';
                        } else {
                            $erroLinha = 'Como o campo "Área (m²)" está vazio, é obrigatório preencher todos os campos de dimensão para que a área possa ser calculada automaticamente. Os campos que estão faltando são: ' . implode(', ', $dimensoesFaltando) . '.';
                        }
                    } else {
                        // Todas as dimensões estão preenchidas, calcular área
                        $m2Tipo = 1; // Calculado automaticamente
                        $area = 0;

                        // Área base (frente × fundo)
                        $area = $frente * $fundo;

                        // Adicionar lateral direita
                        $area += $lateralDireita * $fundo;

                        // Adicionar lateral esquerda
                        $area += $lateralEsquerda * $fundo;

                        if ($area > 0) {
                            $m2 = $area;
                        } else {
                            $erroLinha = 'Não foi possível calcular a área (m²) automaticamente. Por favor, verifique se os valores das dimensões (Frente, Fundo, Lateral Direita e Lateral Esquerda) estão corretos e são maiores que zero.';
                        }
                    }
                }

                if (!$erroLinha && (empty($m2) || $m2 <= 0)) {
                    $erroLinha = 'O campo "Área (m²)" é obrigatório e deve conter um valor maior que zero. Se você não informou a área, preencha todos os campos de dimensão (Frente, Fundo, Lateral Direita e Lateral Esquerda) para que a área seja calculada automaticamente.';
                }

                if (!$erroLinha && (empty($valorM2) || $valorM2 <= 0)) {
                    $erroLinha = 'O campo "Valor por m²" é obrigatório e deve conter um valor maior que zero. Este valor representa o preço por metro quadrado do lote.';
                }

                // REGRA 4: Calcular Valor Total automaticamente
                if (!$erroLinha && $m2 && $valorM2) {
                    $valor = $m2 * $valorM2;
                }

                // Se houver erro, adicionar à lista de erros mas continuar validando
                if ($erroLinha) {
                    // Formatar dados para exibição amigável
                    $dadosFormatados = [
                        'Nome do Lote' => $nome ?: '(vazio)',
                        'Quadra' => $quadraNome ?: '(vazio)',
                        'Frente (m)' => $frente !== null ? number_format($frente, 2, ',', '.') : '(vazio)',
                        'Fundo (m)' => $fundo !== null ? number_format($fundo, 2, ',', '.') : '(vazio)',
                        'Lateral Direita (m)' => $lateralDireita !== null ? number_format($lateralDireita, 2, ',', '.') : '(vazio)',
                        'Lateral Esquerda (m)' => $lateralEsquerda !== null ? number_format($lateralEsquerda, 2, ',', '.') : '(vazio)',
                        'Área (m²)' => $m2 !== null ? number_format($m2, 2, ',', '.') : '(vazio)',
                        'Valor por m²' => $valorM2 !== null ? 'R$ ' . number_format($valorM2, 2, ',', '.') : '(vazio)',
                        'Observação' => $observacao ?: '(vazio)',
                    ];

                    $erros[] = [
                        'linha' => $numeroLinha,
                        'dados' => $linhaOriginal,
                        'dados_formatados' => $dadosFormatados,
                        'mensagem' => $erroLinha
                    ];
                    continue;
                }

                // Verificar se já existe lote com mesmo nome e quadra no banco de dados
                $nomeLower = strtolower(trim($nome));
                $quadraNomeLower = strtolower(trim($quadraNome));
                $chaveDuplicado = $nomeLower . '|' . $quadraNomeLower;

                // Verificar duplicado no banco de dados
                $ehDuplicado = isset($lotesExistentes[$chaveDuplicado]);

                // Dados válidos (serão usados apenas se não houver erros)
                $dadosValidos[] = [
                    'linha' => $numeroLinha,
                    'quadra_nome' => $quadraNome, // Para criar depois se necessário
                    'status_nome' => $statusNome, // Para exibir na view
                    'id' => Str::uuid()->toString(),
                    'empreendimento_id' => $empreendimento->id,
                    'quadra_id' => $quadraId, // Será definido ao criar quadras
                    'lote_status_id' => $statusId,
                    'nome' => trim($nome), // Garantir que o nome está limpo
                    'frente' => $frente,
                    'fundo' => $fundo,
                    'lateral_direita' => $lateralDireita,
                    'lateral_esquerda' => $lateralEsquerda,
                    'valor_m2' => $valorM2,
                    'm2' => $m2,
                    'm2_tipo' => $m2Tipo,
                    'valor' => $valor,
                    'observacao' => $observacao,
                    'duplicado' => $ehDuplicado, // Flag de duplicado
                ];

                // Contar como válida apenas se não for duplicado
                if (!$ehDuplicado) {
                    $validas++;
                }
            }

            // Se for apenas validação, retornar resultado
            if ($request->has('validar_only') && $request->input('validar_only') === 'true') {
                $mensagemQuadras = '';
                if (count($quadrasACriar) > 0) {
                    $mensagemQuadras = ' Serão criadas ' . count($quadrasACriar) . ' quadra(s) automaticamente: ' . implode(', ', array_keys($quadrasACriar)) . '.';
                }

                // Contar duplicados
                $duplicados = array_filter($dadosValidos, function($item) {
                    return isset($item['duplicado']) && $item['duplicado'];
                });
                $duplicadosCount = count($duplicados);

                return response()->json([
                    'success' => count($erros) === 0,
                    'total_linhas' => $totalLinhas,
                    'validas' => $validas,
                    'duplicados' => $duplicadosCount,
                    'dados_validos' => $dadosValidos, // Incluir dados válidos para exibir na tabela
                    'erros' => $erros,
                    'quadras_a_criar' => array_keys($quadrasACriar),
                    'message' => count($erros) === 0
                        ? 'Arquivo validado com sucesso!' . $mensagemQuadras
                        : 'Arquivo possui erros que precisam ser corrigidos.' . $mensagemQuadras
                ]);
            }

            // Se for importação e houver erros, não importar nada
            if ($request->has('importar') && $request->input('importar') === 'true') {
                if (count($erros) > 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Não é possível importar: o arquivo possui erros. Corrija os erros antes de importar.',
                        'total_linhas' => $totalLinhas,
                        'validas' => $validas,
                        'erros' => $erros
                    ], 400);
                }

                // Importar todos os dados válidos
                DB::beginTransaction();

                try {
                    // REGRA 1: Criar quadras que não existem
                    $quadrasCriadas = [];
                    foreach ($quadrasACriar as $nomeQuadra) {
                        // Verificar se já existe (pode ter sido criada em iteração anterior)
                        $quadraExistente = Quadra::where('empreendimento_id', $empreendimento->id)
                            ->where('nome', $nomeQuadra)
                            ->first();

                        if (!$quadraExistente) {
                            $quadra = Quadra::create([
                                'id' => Str::uuid()->toString(),
                                'nome' => $nomeQuadra,
                                'empreendimento_id' => $empreendimento->id,
                            ]);
                            $quadrasCriadas[$nomeQuadra] = $quadra->id;
                            $quadrasExistentes[$quadra->id] = $quadra->nome;
                        } else {
                            $quadrasCriadas[$nomeQuadra] = $quadraExistente->id;
                            $quadrasExistentes[$quadraExistente->id] = $quadraExistente->nome;
                        }
                    }

                    // Atualizar quadra_id nos dados válidos e preparar para inserção
                    $lotesParaInserir = [];
                    foreach ($dadosValidos as $dados) {
                        // Atualizar quadra_id se necessário
                        $quadraIdFinal = $dados['quadra_id'];
                        if (empty($quadraIdFinal) && isset($dados['quadra_nome'])) {
                            $quadraIdFinal = $quadrasCriadas[$dados['quadra_nome']] ?? null;
                        }

                        // Criar lotes (apenas os que não são duplicados)
                        if (!isset($dados['duplicado']) || !$dados['duplicado']) {
                            // Preparar dados do lote explicitamente para evitar referências
                            $dadosLote = [
                                'id' => $dados['id'],
                                'empreendimento_id' => $dados['empreendimento_id'],
                                'quadra_id' => $quadraIdFinal,
                                'lote_status_id' => $dados['lote_status_id'],
                                'nome' => $dados['nome'], // Usar o nome diretamente do array
                                'frente' => $dados['frente'],
                                'fundo' => $dados['fundo'],
                                'lateral_direita' => $dados['lateral_direita'],
                                'lateral_esquerda' => $dados['lateral_esquerda'],
                                'valor_m2' => $dados['valor_m2'],
                                'm2' => $dados['m2'],
                                'm2_tipo' => $dados['m2_tipo'],
                                'valor' => $dados['valor'],
                                'observacao' => $dados['observacao'],
                            ];

                            $lotesParaInserir[] = $dadosLote;
                        }
                    }

                    // Criar todos os lotes
                    foreach ($lotesParaInserir as $dadosLote) {
                        Lote::create($dadosLote);
                    }

                    DB::commit();

                    // Contar apenas os importados (sem duplicados)
                    $importadosCount = count(array_filter($dadosValidos, function($item) {
                        return !isset($item['duplicado']) || !$item['duplicado'];
                    }));

                    $duplicadosCount = count(array_filter($dadosValidos, function($item) {
                        return isset($item['duplicado']) && $item['duplicado'];
                    }));

                    $mensagem = $importadosCount . ' lote(s) importado(s) com sucesso.';
                    if ($duplicadosCount > 0) {
                        $mensagem .= ' ' . $duplicadosCount . ' lote(s) duplicado(s) foram ignorados.';
                    }
                    if (count($quadrasCriadas) > 0) {
                        $mensagem .= ' ' . count($quadrasCriadas) . ' quadra(s) criada(s) automaticamente: ' . implode(', ', array_keys($quadrasCriadas)) . '.';
                    }

                    return response()->json([
                        'success' => true,
                        'importados' => $importadosCount,
                        'duplicados' => $duplicadosCount,
                        'quadras_criadas' => count($quadrasCriadas),
                        'message' => $mensagem
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Erro ao importar: ' . $e->getMessage(),
                        'erros' => []
                    ], 500);
                }
            }

            // Comportamento padrão (retrocompatibilidade)
            if (count($erros) > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Arquivo possui erros. Use a tela de importação para validar antes de importar.',
                    'total_linhas' => $totalLinhas,
                    'validas' => $validas,
                    'erros' => $erros
                ], 400);
            }

            DB::beginTransaction();

            try {
                foreach ($dadosValidos as $dados) {
                    Lote::create($dados);
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'importados' => count($dadosValidos),
                    'erros' => [],
                    'message' => count($dadosValidos) . ' lote(s) importado(s) com sucesso.'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao processar arquivo: ' . $e->getMessage(),
                    'erros' => []
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar arquivo: ' . $e->getMessage(),
                'erros' => []
            ], 500);
        }
    }
}
