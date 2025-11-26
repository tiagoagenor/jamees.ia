<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\ConciliacaoBancaria;
use App\Models\TransacaoOfx;
use App\Models\ContaEmpresa;
use App\Models\Movimentacao;
use App\Models\PlanoConta;
use App\Models\FormaPagamento;
use App\Models\CentroCusto;
use App\Helpers\PermissionHelper;
use App\Enums\MovimentacaoTipoEnum;
use App\Enums\MovimentacaoSituacaoEnum;
use Illuminate\Support\Str;
use App\Services\AuditService;
use Carbon\Carbon;

class ConciliacaoBancariaController extends Controller
{
    /**
     * Lista todas as conciliações bancárias
     */
    public function index()
    {
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        
        if (!$empresaPrincipal) {
            abort(403, 'Empresa principal não encontrada.');
        }

        $conciliacoes = ConciliacaoBancaria::daEmpresa($empresaPrincipal->id)
            ->with(['contaEmpresa.banco', 'usuario'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('conciliacao-bancaria.index', compact('conciliacoes'));
    }

    /**
     * Exibe o formulário para criar nova conciliação
     */
    public function create()
    {
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        
        if (!$empresaPrincipal) {
            abort(403, 'Empresa principal não encontrada.');
        }

        $contas = ContaEmpresa::daEmpresa($empresaPrincipal->id)
            ->ativas()
            ->with('banco')
            ->orderBy('nome')
            ->get();

        return view('conciliacao-bancaria.create', compact('contas'));
    }

    /**
     * Salva uma nova conciliação e processa o arquivo OFX
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'conta_empresa_id' => 'required|exists:conta_empresa,id',
                'arquivo_ofx' => 'required|file|max:10240',
            ], [
                'conta_empresa_id.required' => 'Por favor, selecione uma conta bancária.',
                'conta_empresa_id.exists' => 'A conta bancária selecionada não existe.',
                'arquivo_ofx.required' => 'Por favor, selecione um arquivo OFX.',
                'arquivo_ofx.file' => 'O arquivo enviado não é válido.',
                'arquivo_ofx.max' => 'O arquivo é muito grande. Tamanho máximo: 10MB.',
            ]);

            $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
            
            if (!$empresaPrincipal) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Empresa principal não encontrada.');
            }

            // Verificar se a conta pertence à empresa
            $conta = ContaEmpresa::where('id', $request->conta_empresa_id)
                ->where('empresa_id', $empresaPrincipal->id)
                ->first();

            if (!$conta) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Conta bancária não encontrada.');
            }

            // Upload do arquivo OFX
            $arquivo = $request->file('arquivo_ofx');
            
            // Verificar extensão do arquivo
            $extensao = strtolower($arquivo->getClientOriginalExtension());
            if ($extensao !== 'ofx') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'O arquivo deve ter extensão .ofx');
            }

            $arquivoPath = $arquivo->store('ofx', 'public');

            // Processar arquivo OFX primeiro para obter as datas
            $conteudo = file_get_contents(Storage::disk('public')->path($arquivoPath));
            $transacoes = $this->parsearOfx($conteudo);

            if (empty($transacoes)) {
                // Deletar arquivo se não houver transações
                Storage::disk('public')->delete($arquivoPath);
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Nenhuma transação foi encontrada no arquivo OFX. Verifique se o arquivo está no formato correto.');
            }

            // Calcular data início e fim a partir das transações
            $datas = array_column($transacoes, 'data');
            $dataInicio = !empty($datas) ? min($datas) : Carbon::now()->format('Y-m-d');
            $dataFim = !empty($datas) ? max($datas) : Carbon::now()->format('Y-m-d');

            // Criar conciliação
            $conciliacao = ConciliacaoBancaria::create([
                'conta_empresa_id' => $request->conta_empresa_id,
                'empresa_id' => $empresaPrincipal->id,
                'usuario_id' => Auth::id(),
                'data_inicio' => $dataInicio,
                'data_fim' => $dataFim,
                'descricao' => null,
                'arquivo_ofx_path' => $arquivoPath,
                'conciliado' => false,
            ]);

            // Processar arquivo OFX e salvar transações
            $this->processarOfx($conciliacao, Storage::disk('public')->path($arquivoPath));

            return redirect()->route('conciliacao-bancaria.show', $conciliacao)
                ->with('success', 'Conciliação criada e arquivo OFX importado com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            \Log::error('Erro ao criar conciliação bancária: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao processar o arquivo OFX: ' . $e->getMessage());
        }
    }

    /**
     * Exibe os detalhes de uma conciliação
     */
    public function show(ConciliacaoBancaria $conciliacaoBancaria)
    {
        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        
        if (!$empresaPrincipal) {
            abort(403, 'Empresa principal não encontrada.');
        }

        // Verificar se a conciliação pertence à empresa
        if ($conciliacaoBancaria->empresa_id !== $empresaPrincipal->id) {
            abort(403, 'Você não tem permissão para visualizar esta conciliação.');
        }

        $conciliacaoBancaria->load([
            'contaEmpresa.banco',
            'usuario',
            'transacoes' => function($query) {
                $query->orderBy('data', 'desc');
            },
            'transacoes.contaPagar',
            'transacoes.contaReceber'
        ]);

        // Buscar dados para o modal de adicionar conta
        $planoContas = PlanoConta::daEmpresa($empresaPrincipal->id)->orderBy('nome')->get();
        $centroCustos = CentroCusto::daEmpresa($empresaPrincipal->id)->ativos()->orderBy('nome')->get();
        $formasPagamento = FormaPagamento::daEmpresa($empresaPrincipal->id)->disponiveis()->orderBy('nome')->get();
        $contasEmpresa = ContaEmpresa::daEmpresa($empresaPrincipal->id)->ativas()->orderBy('nome')->get();

        // Buscar entidades separadas por tipo
        $clientes = \App\Models\Cliente::daEmpresa($empresaPrincipal->id)->ativos()->orderBy('nome')->get();
        $fornecedores = \App\Models\Fornecedor::daEmpresa($empresaPrincipal->id)->ativos()->orderBy('nome')->get();
        $funcionarios = \App\Models\Funcionario::daEmpresa($empresaPrincipal->id)->ativos()->orderBy('nome')->get();
        $transportadoras = \App\Models\Transportadora::daEmpresa($empresaPrincipal->id)->ativos()->orderBy('nome')->get();
        $lotes = \App\Models\Lote::whereHas('empreendimento', function($q) use ($empresaPrincipal) {
            $q->where('empresa_id', $empresaPrincipal->id);
        })->orderBy('nome')->get();

        return view('conciliacao-bancaria.show', compact(
            'conciliacaoBancaria', 
            'planoContas', 
            'centroCustos',
            'formasPagamento', 
            'contasEmpresa',
            'clientes',
            'fornecedores',
            'funcionarios',
            'transportadoras',
            'lotes'
        ));
    }

    /**
     * Processa o arquivo OFX e importa as transações
     */
    private function processarOfx(ConciliacaoBancaria $conciliacao, string $arquivoPath)
    {
        $conteudo = file_get_contents($arquivoPath);
        
        // Parser básico de OFX
        $transacoes = $this->parsearOfx($conteudo);

        foreach ($transacoes as $transacao) {
            TransacaoOfx::create([
                'conciliacao_bancaria_id' => $conciliacao->id,
                'tipo' => $transacao['tipo'],
                'data' => $transacao['data'],
                'valor' => abs($transacao['valor']),
                'descricao' => $transacao['descricao'] ?? '',
                'numero_documento' => $transacao['numero_documento'] ?? null,
                'fitid' => $transacao['fitid'] ?? null,
                'conciliado' => false,
            ]);
        }
    }

    /**
     * Parseia o conteúdo do arquivo OFX
     */
    private function parsearOfx(string $conteudo): array
    {
        $transacoes = [];
        
        // Limpar conteúdo OFX (remover headers SGML se existirem)
        $conteudo = preg_replace('/^.*?<\?OFX/is', '<?OFX', $conteudo);
        
        // Tentar usar SimpleXML primeiro
        libxml_use_internal_errors(true);
        $xml = @simplexml_load_string($conteudo);
        
        if ($xml !== false) {
            // Processar XML válido
            $namespaces = $xml->getNamespaces(true);
            $bankmsgsrsv1 = $xml->BANKMSGSRSV1 ?? null;
            
            if ($bankmsgsrsv1) {
                $stmttrnrs = $bankmsgsrsv1->STMTTRNRS ?? null;
                if ($stmttrnrs) {
                    $stmtrs = $stmttrnrs->STMTRS ?? null;
                    if ($stmtrs) {
                        $banktranlist = $stmtrs->BANKTRANLIST ?? null;
                        if ($banktranlist) {
                            foreach ($banktranlist->STMTTRN as $stmttrn) {
                                $tipo = (string) $stmttrn->TRNTYPE;
                                $dataStr = (string) $stmttrn->DTPOSTED;
                                $valor = floatval((string) $stmttrn->TRNAMT);
                                $fitid = (string) $stmttrn->FITID;
                                $memo = (string) ($stmttrn->MEMO ?? '');
                                $checknum = (string) ($stmttrn->CHECKNUM ?? '');
                                
                                // Converter data (YYYYMMDD ou YYYYMMDDHHMMSS)
                                $ano = substr($dataStr, 0, 4);
                                $mes = substr($dataStr, 4, 2);
                                $dia = substr($dataStr, 6, 2);
                                
                                $transacoes[] = [
                                    'tipo' => strtoupper($tipo) === 'CREDIT' ? 'CREDIT' : 'DEBIT',
                                    'data' => Carbon::createFromDate($ano, $mes, $dia)->format('Y-m-d'),
                                    'valor' => abs($valor),
                                    'descricao' => !empty($memo) ? $memo : 'Transação bancária',
                                    'numero_documento' => !empty($checknum) ? $checknum : null,
                                    'fitid' => $fitid,
                                ];
                            }
                        }
                    }
                }
            }
        } else {
            // Fallback: parser manual para OFX não-XML
            $transacoes = $this->parsearOfxManual($conteudo);
        }
        
        return $transacoes;
    }

    /**
     * Parser manual para arquivos OFX em formato SGML
     */
    private function parsearOfxManual(string $conteudo): array
    {
        $transacoes = [];
        
        // Extrair transações usando regex
        preg_match_all('/<STMTTRN>.*?<\/STMTTRN>/is', $conteudo, $matches);
        
        foreach ($matches[0] as $stmttrn) {
            $tipo = 'DEBIT';
            $data = null;
            $valor = 0;
            $descricao = '';
            $fitid = null;
            $checknum = null;
            
            // Extrair TRNTYPE
            if (preg_match('/<TRNTYPE>(.*?)<\/TRNTYPE>/is', $stmttrn, $m)) {
                $tipo = strtoupper(trim($m[1])) === 'CREDIT' ? 'CREDIT' : 'DEBIT';
            }
            
            // Extrair DTPOSTED
            if (preg_match('/<DTPOSTED>(.*?)<\/DTPOSTED>/is', $stmttrn, $m)) {
                $dataStr = trim($m[1]);
                $ano = substr($dataStr, 0, 4);
                $mes = substr($dataStr, 4, 2);
                $dia = substr($dataStr, 6, 2);
                $data = Carbon::createFromDate($ano, $mes, $dia)->format('Y-m-d');
            }
            
            // Extrair TRNAMT
            if (preg_match('/<TRNAMT>(.*?)<\/TRNAMT>/is', $stmttrn, $m)) {
                $valor = abs(floatval(trim($m[1])));
            }
            
            // Extrair MEMO
            if (preg_match('/<MEMO>(.*?)<\/MEMO>/is', $stmttrn, $m)) {
                $descricao = trim($m[1]);
            }
            
            // Extrair FITID
            if (preg_match('/<FITID>(.*?)<\/FITID>/is', $stmttrn, $m)) {
                $fitid = trim($m[1]);
            }
            
            // Extrair CHECKNUM
            if (preg_match('/<CHECKNUM>(.*?)<\/CHECKNUM>/is', $stmttrn, $m)) {
                $checknum = trim($m[1]);
            }
            
            if ($data) {
                $transacoes[] = [
                    'tipo' => $tipo,
                    'data' => $data,
                    'valor' => $valor,
                    'descricao' => !empty($descricao) ? $descricao : 'Transação bancária',
                    'numero_documento' => $checknum,
                    'fitid' => $fitid,
                ];
            }
        }
        
        return $transacoes;
    }

    /**
     * Busca contas a pagar ou receber para vincular à transação (com filtros e paginação)
     */
    public function buscarContas(Request $request, $transacaoId)
    {
        $request->validate([
            'tipo' => 'required|in:1,2', // 1 = pagar, 2 = receber
            'descricao' => 'nullable|string|max:255',
            'vencimento_inicio' => 'nullable|date',
            'vencimento_fim' => 'nullable|date',
            'situacao' => 'nullable|in:1,2,3,4',
            'page' => 'nullable|integer|min:1',
        ]);

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        
        if (!$empresaPrincipal) {
            return response()->json([
                'success' => false,
                'message' => 'Empresa principal não encontrada.'
            ], 404);
        }

        $transacaoOfx = TransacaoOfx::find($transacaoId);
        if (!$transacaoOfx) {
            return response()->json([
                'success' => false,
                'message' => 'Transação não encontrada.'
            ], 404);
        }

        $tipo = $request->tipo;
        $filtroDescricao = $request->get('descricao', '');
        $filtroVencimentoInicio = $request->get('vencimento_inicio', '');
        $filtroVencimentoFim = $request->get('vencimento_fim', '');
        $filtroSituacao = $request->get('situacao', '');

        // Query base
        $query = Movimentacao::where('empresa_id', $empresaPrincipal->id)
            ->where('tipo', $tipo)
            ->where('situacao', '!=', MovimentacaoSituacaoEnum::CANCELADA->value)
            ->with(['entidade', 'planoConta', 'formaPagamento']);

        // Aplicar filtros
        if (!empty($filtroDescricao)) {
            $query->where('descricao', 'LIKE', '%' . $filtroDescricao . '%');
        }

        if (!empty($filtroVencimentoInicio)) {
            $query->where('vencimento', '>=', $filtroVencimentoInicio);
        }

        if (!empty($filtroVencimentoFim)) {
            $query->where('vencimento', '<=', $filtroVencimentoFim);
        }

        if (!empty($filtroSituacao)) {
            $situacaoEnum = MovimentacaoSituacaoEnum::tryFrom($filtroSituacao);
            if ($situacaoEnum) {
                $query->where('situacao', $situacaoEnum->value);
            }
        }

        // Ordenar por vencimento
        $query->orderBy('vencimento', 'desc');

        // Paginação
        $perPage = 15;
        $contas = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'contas' => $contas->map(function($conta) {
                return [
                    'id' => $conta->id,
                    'descricao' => $conta->descricao,
                    'vencimento' => $conta->vencimento ? $conta->vencimento->format('d/m/Y') : 'N/A',
                    'valor' => number_format($conta->valor_total, 2, ',', '.'),
                    'entidade' => $conta->entidade ? $conta->entidade->nome : 'N/A',
                    'situacao' => $conta->situacao->getLabel(),
                    'situacao_value' => $conta->situacao->value,
                    'situacao_color' => $conta->situacao->getColor(),
                ];
            }),
            'pagination' => [
                'current_page' => $contas->currentPage(),
                'last_page' => $contas->lastPage(),
                'per_page' => $contas->perPage(),
                'total' => $contas->total(),
                'from' => $contas->firstItem() ?? 0,
                'to' => $contas->lastItem() ?? 0,
            ]
        ]);
    }

    /**
     * Vincula uma transação OFX a uma conta a pagar/receber
     */
    public function vincularConta(Request $request, $transacaoId)
    {
        $request->validate([
            'conta_id' => 'required|exists:movimentacao,id',
            'tipo' => 'required|in:1,2',
        ]);

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        
        if (!$empresaPrincipal) {
            return response()->json([
                'success' => false,
                'message' => 'Empresa principal não encontrada.'
            ], 404);
        }

        $transacaoOfx = TransacaoOfx::find($transacaoId);
        if (!$transacaoOfx) {
            return response()->json([
                'success' => false,
                'message' => 'Transação não encontrada.'
            ], 404);
        }

        $conta = Movimentacao::where('id', $request->conta_id)
            ->where('empresa_id', $empresaPrincipal->id)
            ->where('tipo', $request->tipo)
            ->first();

        if (!$conta) {
            return response()->json([
                'success' => false,
                'message' => 'Conta não encontrada.'
            ], 404);
        }

        // Vincular transação
        if ($request->tipo == 1) {
            $transacaoOfx->conta_pagar_id = $conta->id;
        } else {
            $transacaoOfx->conta_receber_id = $conta->id;
        }
        
        $transacaoOfx->conciliado = true;
        $transacaoOfx->save();

        return response()->json([
            'success' => true,
            'message' => 'Transação vinculada com sucesso!'
        ]);
    }

    /**
     * Criar uma nova conta a pagar/receber e vincular à transação OFX
     */
    public function criarEConciliarConta(Request $request, $transacaoId)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
            'vencimento' => 'required|date',
            'valor' => 'required|numeric|min:0.01',
            'plano_conta_id' => 'required|exists:plano_conta,id',
            'centro_custo_id' => 'nullable|exists:centro_custo,id',
            'forma_pagamento_id' => 'required|exists:forma_pagamento,id',
            'conta_empresa_id' => 'nullable|exists:conta_empresa,id',
            'pagamento_quitado' => 'nullable|integer|in:0,1',
            'data_compensacao' => 'nullable|date',
            'juros_tipo' => 'nullable|in:fixo,por_dia',
            'juros_forma' => 'nullable|in:valor,porcentagem',
            'juros' => 'nullable|numeric|min:0',
            'multa_forma' => 'nullable|in:valor,porcentagem',
            'multa' => 'nullable|numeric|min:0',
            'desconto' => 'nullable|numeric|min:0',
            'valor_total' => 'nullable|numeric',
            'entidade_tipo' => 'nullable|integer',
            'entidade_id' => 'nullable|string',
            'informacao_complementar' => 'nullable|string',
            'observacao' => 'nullable|string',
            'tipo' => 'required|in:1,2',
        ]);

        $empresaPrincipal = PermissionHelper::getEmpresaPrincipal();
        
        if (!$empresaPrincipal) {
            return response()->json([
                'success' => false,
                'message' => 'Empresa principal não encontrada.'
            ], 404);
        }

        $transacaoOfx = TransacaoOfx::find($transacaoId);
        if (!$transacaoOfx) {
            return response()->json([
                'success' => false,
                'message' => 'Transação não encontrada.'
            ], 404);
        }

        try {
            \DB::beginTransaction();

            $tipoEnum = MovimentacaoTipoEnum::tryFrom($request->tipo);
            $valorBruto = floatval($request->valor);
            $jurosForma = $request->juros_forma ?? 'valor';
            $multaForma = $request->multa_forma ?? 'valor';

            // Calcular juros baseado na forma
            $jurosCalculado = floatval($request->juros ?? 0);
            if ($jurosForma === 'porcentagem' && $jurosCalculado > 0) {
                $jurosCalculado = ($valorBruto * $jurosCalculado) / 100;
            }

            // Calcular multa baseado na forma
            $multaCalculado = floatval($request->multa ?? 0);
            if ($multaForma === 'porcentagem' && $multaCalculado > 0) {
                $multaCalculado = ($valorBruto * $multaCalculado) / 100;
            }

            // Aplicar juros apenas se:
            // - Tipo for "fixo" (sempre aplica)
            // - Tipo for "por_dia" E a movimentação estiver vencida
            $jurosTipo = $request->juros_tipo ?? 'fixo';
            $vencimento = Carbon::parse($request->vencimento);
            $hoje = Carbon::now()->startOfDay();
            $estaVencida = $vencimento->lt($hoje);

            $jurosFinal = 0;
            if ($jurosCalculado > 0) {
                if ($jurosTipo === 'fixo' || ($jurosTipo === 'por_dia' && $estaVencida)) {
                    $jurosFinal = $jurosCalculado;
                }
            }

            $multaFinal = $multaCalculado;
            $descontoFinal = floatval($request->desconto ?? 0);

            // Calcular valor total
            $valorTotal = $valorBruto + $jurosFinal + $multaFinal - $descontoFinal;
            
            // Se valor_total foi enviado, usar ele (já calculado no frontend)
            if ($request->has('valor_total') && $request->valor_total) {
                $valorTotal = floatval($request->valor_total);
            }

            // Determinar situação baseado em pagamento_quitado
            $pagamentoQuitado = $request->pagamento_quitado ?? 0;
            $situacao = ($pagamentoQuitado == 1) 
                ? MovimentacaoSituacaoEnum::PAGA 
                : MovimentacaoSituacaoEnum::PENDENTE;

            // Data de compensação
            $dataCompensacao = null;
            if ($pagamentoQuitado == 1) {
                $dataCompensacao = $request->data_compensacao 
                    ? Carbon::parse($request->data_compensacao)->format('Y-m-d')
                    : Carbon::now()->format('Y-m-d');
            }

            // Criar a movimentação
            $movimentacao = Movimentacao::create([
                'id' => Str::uuid(),
                'empresa_id' => $empresaPrincipal->id,
                'plano_conta_id' => $request->plano_conta_id,
                'centro_custo_id' => $request->centro_custo_id,
                'forma_pagamento_id' => $request->forma_pagamento_id,
                'conta_empresa_id' => $request->conta_empresa_id,
                'situacao' => $situacao,
                'tipo' => $tipoEnum,
                'entidade_tipo' => $request->entidade_tipo,
                'entidade_id' => $request->entidade_id,
                'descricao' => $request->descricao,
                'vencimento' => $request->vencimento,
                'observacao' => $request->observacao,
                'informacao_complementar' => $request->informacao_complementar,
                'valor' => $valorBruto,
                'juros' => $jurosFinal,
                'juros_tipo' => $jurosTipo,
                'juros_forma' => $jurosForma,
                'multa' => $multaFinal,
                'multa_forma' => $multaForma,
                'desconto' => $descontoFinal,
                'valor_total' => $valorTotal,
                'data_compensacao' => $dataCompensacao,
                'criado_em' => now(),
                'atualizado_em' => now(),
            ]);

            // Vincular transação OFX
            if ($request->tipo == 1) {
                $transacaoOfx->conta_pagar_id = $movimentacao->id;
            } else {
                $transacaoOfx->conta_receber_id = $movimentacao->id;
            }
            
            $transacaoOfx->conciliado = true;
            $transacaoOfx->save();

            // Registrar no audit log
            AuditService::logCreate($movimentacao, "Criada {$tipoEnum->getLabel()} via conciliação bancária: {$movimentacao->descricao}");

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Conta criada e conciliada com sucesso!',
                'movimentacao_id' => $movimentacao->id,
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar conta: ' . $e->getMessage()
            ], 500);
        }
    }
}
