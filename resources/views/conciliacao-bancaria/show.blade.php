@extends('layouts.app')

@section('title', 'Conciliação Bancária')
@section('page-title', 'Conciliação Bancária')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
            <a href="{{ route('conciliacao-bancaria.index') }}" class="hover:text-blue-600">Conciliações</a>
            <i class="fas fa-chevron-right text-gray-400"></i>
            <span class="text-gray-900 font-medium">Detalhes</span>
        </div>
        <h1 class="text-xl font-bold text-gray-900">
            <i class="fas fa-balance-scale text-blue-600 mr-3"></i>
            Conciliação Bancária
        </h1>
    </div>

    <!-- Layout de Conciliação -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Coluna Esquerda: Tabela de Transações OFX -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-list mr-2 text-blue-600"></i>
                        Transações do Extrato ({{ $conciliacaoBancaria->transacoes->count() }})
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-xs">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                                <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase">Descrição</th>
                                <th class="px-2 py-2 text-right text-xs font-medium text-gray-500 uppercase">Valor</th>
                                <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($conciliacaoBancaria->transacoes as $transacao)
                                <tr class="transacao-row hover:bg-gray-50 {{ $transacao->conciliado ? 'bg-green-50' : '' }} cursor-pointer" 
                                    data-transacao-id="{{ $transacao->id }}"
                                    onclick="selecionarTransacao('{{ $transacao->id }}', '{{ $transacao->tipo }}', '{{ $transacao->data }}', '{{ $transacao->valor }}', '{{ addslashes($transacao->descricao) }}', '{{ $transacao->numero_documento ?? '' }}', '{{ $transacao->conciliado ? '1' : '0' }}', '{{ $transacao->conta_pagar_id ?? '' }}', '{{ $transacao->conta_receber_id ?? '' }}', '{{ $transacao->observacoes ?? '' }}')">
                                    <td class="px-2 py-2 whitespace-nowrap text-xs text-gray-900">
                                        {{ \Carbon\Carbon::parse($transacao->data)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-2 py-2 text-xs text-gray-900">
                                        {{ $transacao->descricao }}
                                    </td>
                                    <td class="px-2 py-2 whitespace-nowrap text-xs font-medium text-right {{ $transacao->isCredito() ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transacao->isCredito() ? '+' : '-' }}{{ $transacao->getValorFormatado() }}
                                    </td>
                                    <td class="px-2 py-2 whitespace-nowrap text-center">
                                        @if($transacao->conciliado)
                                            <span class="px-1.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-check mr-1"></i>
                                                Conciliado
                                            </span>
                                        @else
                                            <span class="px-1.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pendente
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                        Nenhuma transação encontrada no arquivo OFX.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Coluna Direita: Informações, Detalhes da Transação ou Descrição e Estatísticas -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
                <!-- Conta -->
                <div>
                    <h3 class="text-base font-semibold text-gray-900 mb-3">
                        <i class="fas fa-university text-blue-600 mr-2"></i>
                        Conta
                    </h3>
                    <div class="space-y-2 text-xs">
                        <div>
                            <p class="text-gray-900">{{ $conciliacaoBancaria->contaEmpresa->nome }}</p>
                        </div>
                    </div>
                </div>

                <!-- Detalhes da Transação Selecionada -->
                <div id="detalhes-transacao" class="hidden">
                    <div class="flex items-center justify-between mb-4 pt-4 border-t border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Detalhes da Transação
                        </h3>
                        <button onclick="fecharDetalhes()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="space-y-4 text-xs">
                        <!-- Valor e Tipo -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <span class="block text-xs font-medium text-gray-500 mb-1">Valor</span>
                                <p class="text-sm font-semibold" id="detalhe-valor"></p>
                            </div>
                            <div>
                                <span class="block text-xs font-medium text-gray-500 mb-1">Tipo</span>
                                <p id="detalhe-tipo"></p>
                            </div>
                        </div>

                        <!-- Data e Status -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <span class="block text-xs font-medium text-gray-500 mb-1">Data</span>
                                <p class="text-gray-900" id="detalhe-data"></p>
                            </div>
                            <div>
                                <span class="block text-xs font-medium text-gray-500 mb-1">Status</span>
                                <p id="detalhe-status"></p>
                            </div>
                        </div>

                        <!-- Descrição -->
                        <div>
                            <span class="block text-xs font-medium text-gray-500 mb-1">Descrição</span>
                            <p class="text-gray-900" id="detalhe-descricao"></p>
                        </div>

                        <!-- Número do Documento -->
                        <div id="detalhe-numero-documento" class="hidden">
                            <span class="block text-xs font-medium text-gray-500 mb-1">Número do Documento</span>
                            <p class="text-gray-900" id="detalhe-numero-doc"></p>
                        </div>

                        <!-- Observações -->
                        <div id="detalhe-observacoes" class="hidden">
                            <span class="block text-xs font-medium text-gray-500 mb-1">Observações</span>
                            <p class="text-gray-900 whitespace-pre-wrap" id="detalhe-obs"></p>
                        </div>
                    </div>

                    <!-- Botões de Ação (se não estiver conciliada) -->
                    <div id="botoes-acao" class="mt-4 pt-4 border-t border-gray-200 space-y-2">
                        <button onclick="abrirModalBuscarContaFromDetalhes()" 
                                class="w-full px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-xs font-medium"
                                title="Buscar e associar a um lançamento que já existe">
                            <i class="fas fa-search mr-2"></i>
                            Buscar Lançamento
                        </button>
                        <button id="btn-adicionar-conta" onclick="abrirModalAdicionarContaFromDetalhes()" 
                                class="w-full px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-xs font-medium">
                            <i class="fas fa-plus mr-2"></i>
                            <span id="texto-adicionar-conta">Adicionar</span>
                        </button>
                    </div>
                </div>

                <!-- Descrição (padrão quando nenhuma transação está selecionada) -->
                <div id="box-descricao">
                    <div class="pt-4 border-t border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900 mb-3">
                            <i class="fas fa-file-alt text-blue-600 mr-2"></i>
                            Descrição
                        </h3>
                        <div class="text-xs text-gray-700">
                            @if($conciliacaoBancaria->descricao)
                                <p>{{ $conciliacaoBancaria->descricao }}</p>
                            @else
                                <p class="text-gray-500 italic">Nenhuma descrição informada.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Estatísticas -->
                <div class="pt-4 border-t border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900 mb-3">
                        <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                        Estatísticas
                    </h3>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between">
                            <span class="text-gray-700">Total de Transações:</span>
                            <span class="font-medium text-gray-900">{{ $conciliacaoBancaria->getTotalTransacoes() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-700">Conciliadas:</span>
                            <span class="font-medium text-green-600">{{ $conciliacaoBancaria->getTotalConciliadas() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-700">Pendentes:</span>
                            <span class="font-medium text-yellow-600">{{ $conciliacaoBancaria->getTotalTransacoes() - $conciliacaoBancaria->getTotalConciliadas() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Buscar/Adicionar Conta -->
<div id="modal-buscar-conta" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 shadow-lg rounded-md bg-white max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-search text-blue-600 mr-2"></i>
                Buscar e Associar Lançamento
            </h3>
            <button onclick="fecharModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Filtros -->
        <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <form id="form-filtros" onsubmit="event.preventDefault(); buscarLancamentos(1);" class="space-y-3">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Descrição</label>
                        <input type="text" id="filtro-descricao" name="descricao" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Vencimento Início</label>
                        <input type="date" id="filtro-vencimento-inicio" name="vencimento_inicio"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Vencimento Fim</label>
                        <input type="date" id="filtro-vencimento-fim" name="vencimento_fim"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Situação</label>
                        <select id="filtro-situacao" name="situacao"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Todas</option>
                            <option value="1">Pendentes</option>
                            <option value="2">Pagas</option>
                            <option value="3">Vencidas</option>
                            <option value="4">Canceladas</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="limparFiltros()" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-times mr-1"></i>
                        Limpar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-medium">
                        <i class="fas fa-search mr-1"></i>
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        <div id="modal-content" class="flex-1 overflow-y-auto space-y-4">
            <!-- Loading -->
            <div id="loading" class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-3xl text-blue-600"></i>
                <p class="mt-2 text-gray-600">Buscando lançamentos...</p>
            </div>

            <!-- Resultados -->
            <div id="resultados" class="hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descrição</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vencimento</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Valor</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entidade</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Situação</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Ações</th>
                            </tr>
                        </thead>
                        <tbody id="contas-list" class="bg-white divide-y divide-gray-200">
                            <!-- Preenchido via JavaScript -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Paginação -->
                <div id="paginacao" class="mt-4 flex items-center justify-between border-t border-gray-200 pt-4">
                    <!-- Preenchido via JavaScript -->
                </div>
            </div>

            <!-- Sem resultados -->
            <div id="sem-resultados" class="hidden text-center py-8">
                <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600 mb-4">Nenhum lançamento encontrado.</p>
                <button id="btn-adicionar-modal" onclick="abrirModalAdicionarConta()" 
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm font-medium">
                    <i class="fas fa-plus mr-2"></i>
                    <span id="texto-adicionar-modal">Adicionar</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Adicionar Conta -->
<div id="modal-adicionar-conta" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 shadow-lg rounded-md bg-white max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-plus text-green-600 mr-2"></i>
                <span id="modal-adicionar-titulo">Adicionar Conta</span>
            </h3>
            <button onclick="fecharModalAdicionar()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form id="form-adicionar-conta" onsubmit="event.preventDefault(); salvarConta();" class="flex-1 overflow-y-auto">
            <input type="hidden" id="adicionar-transacao-id" name="transacao_id">
            <input type="hidden" id="adicionar-tipo" name="tipo">

            <!-- Tabs -->
            <div class="border-b border-gray-200 mb-4">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button type="button"
                            class="tab-adicionar active border-b-2 border-blue-500 text-blue-600 py-2 px-1 text-sm font-medium whitespace-nowrap"
                            data-tab="lancamento">
                        Lançamento financeiro
                    </button>
                    <button type="button"
                            class="tab-adicionar border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap"
                            data-tab="outras">
                        Outras informações
                    </button>
                </nav>
            </div>

            <!-- Tab Content: Lançamento financeiro -->
            <div id="tab-adicionar-lancamento" class="tab-adicionar-content space-y-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Container 1: Esquerda -->
                    <div class="space-y-4">
                        <!-- Linha 1: Descrição, Vencimento -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="adicionar-descricao" class="block text-sm font-medium text-gray-700 mb-2">
                                    Descrição <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="adicionar-descricao"
                                       name="descricao"
                                       required
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="adicionar-vencimento" class="block text-sm font-medium text-gray-700 mb-2">
                                    Vencimento <span class="text-red-500">*</span>
                                </label>
                                <input type="date"
                                       id="adicionar-vencimento"
                                       name="vencimento"
                                       required
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Linha 2: Plano de Contas, Centro de Custo -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="adicionar-plano-conta" class="block text-sm font-medium text-gray-700 mb-2">
                                    Plano de Contas <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="adicionar-plano-conta"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Digite para buscar plano de conta..."
                                       autocomplete="off"
                                       required>
                            </div>
                            <div>
                                <label for="adicionar-centro-custo" class="block text-sm font-medium text-gray-700 mb-2">
                                    Centro de Custo
                                </label>
                                <input type="text"
                                       id="adicionar-centro-custo"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Digite para buscar centro de custo..."
                                       autocomplete="off">
                            </div>
                        </div>

                        <!-- Linha 3: Forma de Pagamento, Conta Bancária -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="adicionar-forma-pagamento" class="block text-sm font-medium text-gray-700 mb-2">
                                    Forma de Pagamento <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="adicionar-forma-pagamento"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Digite para buscar forma de pagamento..."
                                       autocomplete="off"
                                       required>
                            </div>
                            <div>
                                <label for="adicionar-conta-empresa" class="block text-sm font-medium text-gray-700 mb-2">
                                    Conta Bancária
                                </label>
                                <input type="text"
                                       id="adicionar-conta-empresa"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Digite para buscar conta bancária..."
                                       autocomplete="off">
                            </div>
                        </div>

                        <!-- Linha 4: Pagamento Quitado, Data de Compensação -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="adicionar-pagamento-quitado" class="block text-sm font-medium text-gray-700 mb-2">
                                    Pagamento Quitado
                                </label>
                                <select id="adicionar-pagamento-quitado"
                                        name="pagamento_quitado"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="0">Não</option>
                                    <option value="1">Sim</option>
                                </select>
                            </div>
                            <div>
                                <label for="adicionar-data-compensacao" class="block text-sm font-medium text-gray-700 mb-2">
                                    Data de Compensação
                                </label>
                                <input type="date"
                                       id="adicionar-data-compensacao"
                                       name="data_compensacao"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-100"
                                       disabled>
                            </div>
                        </div>
                    </div>

                    <!-- Container 2: Direita - Valores -->
                    <div class="space-y-4">
                        <div class="border border-gray-300 rounded-lg shadow-sm overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 rounded-t-lg">
                                <h3 class="text-sm font-semibold text-gray-900">Valores</h3>
                            </div>
                            <div class="p-4 space-y-4">
                                <!-- Valor Bruto -->
                                <div>
                                    <label for="adicionar-valor" class="block text-sm font-medium text-gray-700 mb-2">
                                        Valor Bruto <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number"
                                           id="adicionar-valor"
                                           name="valor"
                                           step="0.01"
                                           min="0.01"
                                           required
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="0,00">
                                </div>

                                <!-- Tipo de Juros, Forma de Juros e Juros -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="adicionar-juros-tipo" class="block text-sm font-medium text-gray-700 mb-2">
                                            Tipo de Juros
                                        </label>
                                        <select id="adicionar-juros-tipo"
                                                name="juros_tipo"
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="fixo" selected>Fixo</option>
                                            <option value="por_dia">Por Dia</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="adicionar-juros-forma" class="block text-sm font-medium text-gray-700 mb-2">
                                            Forma de Juros
                                        </label>
                                        <select id="adicionar-juros-forma"
                                                name="juros_forma"
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="valor" selected>Valor</option>
                                            <option value="porcentagem">Porcentagem</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="adicionar-juros" class="block text-sm font-medium text-gray-700 mb-2">
                                            Juros
                                        </label>
                                        <input type="number"
                                               id="adicionar-juros"
                                               name="juros"
                                               step="0.01"
                                               min="0"
                                               value="0"
                                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="0,00">
                                    </div>
                                </div>

                                <!-- Forma de Multa, Multa e Desconto -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="adicionar-multa-forma" class="block text-sm font-medium text-gray-700 mb-2">
                                            Forma de Multa
                                        </label>
                                        <select id="adicionar-multa-forma"
                                                name="multa_forma"
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="valor" selected>Valor</option>
                                            <option value="porcentagem">Porcentagem</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="adicionar-multa" class="block text-sm font-medium text-gray-700 mb-2">
                                            Multa por Atraso
                                        </label>
                                        <input type="number"
                                               id="adicionar-multa"
                                               name="multa"
                                               step="0.01"
                                               min="0"
                                               value="0"
                                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="0,00">
                                    </div>
                                    <div>
                                        <label for="adicionar-desconto" class="block text-sm font-medium text-gray-700 mb-2">
                                            Desconto
                                        </label>
                                        <input type="number"
                                               id="adicionar-desconto"
                                               name="desconto"
                                               step="0.01"
                                               min="0"
                                               value="0"
                                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="0,00">
                                    </div>
                                </div>

                                <!-- Total -->
                                <div class="pt-4 border-t border-gray-200">
                                    <label for="adicionar-valor-total" class="block text-sm font-medium text-gray-700 mb-2">
                                        Total
                                    </label>
                                    <input type="text"
                                           id="adicionar-valor-total"
                                           readonly
                                           value="R$ 0,00"
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm bg-gray-200 font-semibold text-gray-900 text-center">
                                    <input type="hidden" name="valor_total" id="adicionar-valor-total-hidden" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Content: Outras informações -->
            <div id="tab-adicionar-outras" class="tab-adicionar-content hidden space-y-4">
                <!-- Tipo de Entidade e Entidade -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="adicionar-entidade-tipo" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Entidade
                        </label>
                        <select id="adicionar-entidade-tipo"
                                name="entidade_tipo"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecione o tipo</option>
                            <option value="1">Cliente</option>
                            <option value="2">Fornecedor</option>
                            <option value="3">Funcionário</option>
                            <option value="4">Transportadora</option>
                            @if(\App\Helpers\AplicativoHelper::temAplicativo('loteamento'))
                                <option value="{{ \App\Enums\EntidadeTipoEnum::LOTEAMENTO->value }}">Loteamento</option>
                            @endif
                        </select>
                    </div>
                    <div id="adicionar-entidade-container" class="hidden">
                        <label for="adicionar-entidade-id" class="block text-sm font-medium text-gray-700 mb-2">
                            Entidade
                        </label>
                        <select id="adicionar-entidade-id"
                                name="entidade_id"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecione a entidade</option>
                        </select>
                    </div>
                </div>

                <!-- Informação Complementar -->
                <div>
                    <label for="adicionar-informacao-complementar" class="block text-sm font-medium text-gray-700 mb-2">
                        Informação Complementar
                    </label>
                    <textarea id="adicionar-informacao-complementar"
                              name="informacao_complementar"
                              rows="4"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Digite informações complementares (opcional)"></textarea>
                </div>

                <!-- Observação -->
                <div>
                    <label for="adicionar-observacao" class="block text-sm font-medium text-gray-700 mb-2">
                        Observação
                    </label>
                    <textarea id="adicionar-observacao"
                              name="observacao"
                              rows="3"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Digite observações (opcional)"></textarea>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button"
                        onclick="fecharModalAdicionar()"
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md text-sm font-medium">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Salvar e Conciliar
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.transacao-row.selected {
    background-color: #dbeafe !important;
    border-left: 4px solid #3b82f6;
}
</style>

<!-- CSS do CSelect -->
<link rel="stylesheet" href="{{ asset('css/cselect.css') }}">

<!-- Script do Custom Select -->
<script src="{{ asset('js/custom-select.js') }}"></script>
<script src="{{ asset('js/cselect.js') }}"></script>

<script>
let transacaoAtual = null;
let tipoConta = null; // 1 = pagar, 2 = receber
let transacaoSelecionada = null;

function selecionarTransacao(id, tipo, data, valor, descricao, numeroDocumento, conciliado, contaPagarId, contaReceberId, observacoes) {
    // Remover seleção anterior
    document.querySelectorAll('.transacao-row').forEach(row => {
        row.classList.remove('selected');
    });
    
    // Adicionar seleção na linha clicada
    const row = document.querySelector(`[data-transacao-id="${id}"]`);
    if (row) {
        row.classList.add('selected');
    }
    
    // Salvar dados da transação
    transacaoSelecionada = {
        id: id,
        tipo: tipo,
        data: data,
        valor: valor,
        descricao: descricao,
        numeroDocumento: numeroDocumento,
        conciliado: conciliado === '1',
        contaPagarId: contaPagarId,
        contaReceberId: contaReceberId,
        observacoes: observacoes
    };
    
    // Atualizar transacaoAtual para os botões
    transacaoAtual = id;
    tipoConta = tipo === 'CREDIT' ? 2 : 1;
    
    // Atualizar texto do botão de adicionar
    const textoAdicionar = tipoConta === 1 ? 'Adicionar Contas a Pagar' : 'Adicionar Contas a Receber';
    const textoAdicionarElement = document.getElementById('texto-adicionar-conta');
    if (textoAdicionarElement) {
        textoAdicionarElement.textContent = textoAdicionar;
    }
    
    // Preencher detalhes
    document.getElementById('detalhe-data').textContent = new Date(data).toLocaleDateString('pt-BR');
    document.getElementById('detalhe-descricao').textContent = descricao || 'N/A';
    document.getElementById('detalhe-tipo').innerHTML = tipo === 'CREDIT' 
        ? '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Crédito</span>'
        : '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Débito</span>';
    
    const valorFormatado = parseFloat(valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    document.getElementById('detalhe-valor').textContent = (tipo === 'CREDIT' ? '+' : '-') + valorFormatado;
    document.getElementById('detalhe-valor').className = tipo === 'CREDIT' ? 'text-gray-900 font-semibold text-green-600' : 'text-gray-900 font-semibold text-red-600';
    
    if (numeroDocumento) {
        document.getElementById('detalhe-numero-documento').classList.remove('hidden');
        document.getElementById('detalhe-numero-doc').textContent = numeroDocumento;
    } else {
        document.getElementById('detalhe-numero-documento').classList.add('hidden');
    }
    
    if (conciliado === '1') {
        document.getElementById('detalhe-status').innerHTML = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"><i class="fas fa-check mr-1"></i> Conciliado</span>';
        document.getElementById('botoes-acao').classList.add('hidden');
    } else {
        document.getElementById('detalhe-status').innerHTML = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendente</span>';
        document.getElementById('botoes-acao').classList.remove('hidden');
    }
    
    if (observacoes) {
        document.getElementById('detalhe-observacoes').classList.remove('hidden');
        document.getElementById('detalhe-obs').textContent = observacoes;
    } else {
        document.getElementById('detalhe-observacoes').classList.add('hidden');
    }
    
    // Mostrar detalhes e esconder descrição
    document.getElementById('detalhes-transacao').classList.remove('hidden');
    document.getElementById('box-descricao').classList.add('hidden');
}

function fecharDetalhes() {
    // Remover seleção
    document.querySelectorAll('.transacao-row').forEach(row => {
        row.classList.remove('selected');
    });
    
    // Esconder detalhes e mostrar descrição
    document.getElementById('detalhes-transacao').classList.add('hidden');
    document.getElementById('box-descricao').classList.remove('hidden');
    
    transacaoSelecionada = null;
    transacaoAtual = null;
}

function abrirModalBuscarContaFromDetalhes() {
    if (!transacaoSelecionada) return;
    abrirModalBuscarConta(
        transacaoSelecionada.id,
        transacaoSelecionada.tipo,
        transacaoSelecionada.data,
        transacaoSelecionada.valor
    );
}

function abrirModalAdicionarContaFromDetalhes() {
    if (!transacaoSelecionada) return;
    abrirModalAdicionarConta();
}

// Dados das entidades para o modal
const entidadesModal = {
    1: @json($clientes),
    2: @json($fornecedores),
    3: @json($funcionarios),
    4: @json($transportadoras)@if(\App\Helpers\AplicativoHelper::temAplicativo('loteamento')),
    {{ \App\Enums\EntidadeTipoEnum::LOTEAMENTO->value }}: @json($lotes)@endif
};

// Variáveis globais para cSelect do modal
let planoContaModal = null;
let centroCustoModal = null;
let formaPagamentoModal = null;
let contaEmpresaModal = null;

function abrirModalAdicionarConta() {
    if (!transacaoSelecionada) return;
    
    const tipo = transacaoSelecionada.tipo === 'CREDIT' ? 2 : 1;
    const tipoTexto = tipo === 1 ? 'Contas a Pagar' : 'Contas a Receber';
    
    // Preencher campos do formulário
    document.getElementById('adicionar-transacao-id').value = transacaoSelecionada.id;
    document.getElementById('adicionar-tipo').value = tipo;
    document.getElementById('adicionar-descricao').value = transacaoSelecionada.descricao || '';
    
    // Formatar data para YYYY-MM-DD (formato do input date)
    let dataFormatada = transacaoSelecionada.data || '';
    if (dataFormatada) {
        // Se a data estiver no formato DD/MM/YYYY, converter para YYYY-MM-DD
        if (dataFormatada.includes('/')) {
            const partes = dataFormatada.split('/');
            if (partes.length === 3) {
                dataFormatada = `${partes[2]}-${partes[1]}-${partes[0]}`;
            }
        }
        // Se a data estiver no formato YYYY-MM-DD, usar diretamente
        // Se vier como objeto Date ou timestamp, converter
        if (dataFormatada instanceof Date) {
            dataFormatada = dataFormatada.toISOString().split('T')[0];
        }
    }
    
    // Preencher data de vencimento com a data da transação
    document.getElementById('adicionar-vencimento').value = dataFormatada;
    
    // Preencher pagamento quitado como "Sim" e data de compensação com a mesma data
    document.getElementById('adicionar-pagamento-quitado').value = '1';
    document.getElementById('adicionar-data-compensacao').value = dataFormatada;
    document.getElementById('adicionar-data-compensacao').disabled = false;
    document.getElementById('adicionar-data-compensacao').classList.remove('bg-gray-100');
    
    const valor = Math.abs(parseFloat(transacaoSelecionada.valor) || 0);
    document.getElementById('adicionar-valor').value = valor.toFixed(2);
    document.getElementById('modal-adicionar-titulo').textContent = `Adicionar ${tipoTexto}`;
    
    // Limpar cSelects
    if (planoContaModal) planoContaModal.clear();
    if (centroCustoModal) centroCustoModal.clear();
    if (formaPagamentoModal) formaPagamentoModal.clear();
    if (contaEmpresaModal) contaEmpresaModal.clear();
    
    // Limpar outros campos
    document.getElementById('adicionar-juros').value = '0';
    document.getElementById('adicionar-multa').value = '0';
    document.getElementById('adicionar-desconto').value = '0';
    document.getElementById('adicionar-entidade-tipo').value = '';
    document.getElementById('adicionar-entidade-container').classList.add('hidden');
    document.getElementById('adicionar-entidade-id').innerHTML = '<option value="">Selecione a entidade</option>';
    document.getElementById('adicionar-informacao-complementar').value = '';
    document.getElementById('adicionar-observacao').value = '';
    
    // Calcular total inicial
    calcularTotalAdicionar();
    
    // Resetar para primeira tab
    document.querySelectorAll('.tab-adicionar').forEach(btn => {
        btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    document.querySelector('.tab-adicionar[data-tab="lancamento"]').classList.add('active', 'border-blue-500', 'text-blue-600');
    document.querySelector('.tab-adicionar[data-tab="lancamento"]').classList.remove('border-transparent', 'text-gray-500');
    document.querySelectorAll('.tab-adicionar-content').forEach(content => {
        content.classList.add('hidden');
    });
    document.getElementById('tab-adicionar-lancamento').classList.remove('hidden');
    
    // Inicializar cSelects se ainda não foram inicializados
    inicializarCSelectsModal();
    
    // Preencher conta bancária automaticamente com a conta da conciliação (após inicializar cSelects)
    const contaEmpresaId = '{{ $conciliacaoBancaria->conta_empresa_id }}';
    if (contaEmpresaId) {
        // Aguardar um pouco para garantir que o cSelect está pronto
        setTimeout(() => {
            if (contaEmpresaModal) {
                contaEmpresaModal.setValue(contaEmpresaId);
            }
        }, 300);
    }
    
    // Mostrar modal
    document.getElementById('modal-adicionar-conta').classList.remove('hidden');
}

function inicializarCSelectsModal() {
    // Verificar se cSelect está disponível
    if (typeof window.cSelect === 'undefined') {
        console.error('cSelect não está disponível. Certifique-se de que os scripts foram carregados.');
        return;
    }
    
    // Inicializar cSelect para Plano de Contas
    if (!planoContaModal) {
        planoContaModal = window.cSelect('#adicionar-plano-conta', {
            name: 'plano_conta_id',
            debug: false,
            itemValue: 'id',
            itemTitle: 'nome',
            itemSubtitle: 'categoria',
            minSearchLength: 2,
            http: {
                url: '{{ route("api.custom-select.search") }}',
                method: 'GET',
                searchParam: 'search',
                useJwt: true
            },
            addButton: {
                text: 'Adicionar novo plano de conta',
                class: ''
            },
            onClickButton: (selectId) => {
                window.openModal('plano-conta-modal', selectId);
            },
            onSelect: (value, label) => {
                console.log('Plano de conta selecionado:', { value, label });
            }
        });
    }
    
    // Inicializar cSelect para Centro de Custo
    if (!centroCustoModal) {
        centroCustoModal = window.cSelect('#adicionar-centro-custo', {
            name: 'centro_custo_id',
            debug: false,
            itemValue: 'id',
            itemTitle: 'nome',
            minSearchLength: 2,
            http: {
                url: '{{ route("api.centro-custo.search") }}',
                method: 'GET',
                searchParam: 'search'
            },
            addButton: {
                text: 'Adicionar novo centro de custo',
                class: ''
            },
            onClickButton: (selectId) => {
                window.openModal('centro-custo-modal', selectId);
            },
            onSelect: (value, label) => {
                console.log('Centro de custo selecionado:', { value, label });
            }
        });
    }
    
    // Inicializar cSelect para Forma de Pagamento
    if (!formaPagamentoModal) {
        formaPagamentoModal = window.cSelect('#adicionar-forma-pagamento', {
            name: 'forma_pagamento_id',
            debug: false,
            itemValue: 'id',
            itemTitle: 'nome',
            itemSubtitle: 'modalidade',
            minSearchLength: 2,
            http: {
                url: '{{ route("api.forma-pagamento.search") }}',
                method: 'GET',
                searchParam: 'search'
            },
            addButton: {
                text: 'Adicionar nova forma de pagamento',
                class: ''
            },
            onClickButton: (selectId) => {
                window.openModal('forma-pagamento-modal', selectId);
            },
            onSelect: (value, label) => {
                console.log('Forma de pagamento selecionada:', { value, label });
            }
        });
    }
    
    // Inicializar cSelect para Conta Bancária
    if (!contaEmpresaModal) {
        contaEmpresaModal = window.cSelect('#adicionar-conta-empresa', {
            name: 'conta_empresa_id',
            debug: false,
            itemValue: 'id',
            itemTitle: 'nome',
            minSearchLength: 2,
            http: {
                url: '{{ route("api.conta-empresa.search") }}',
                method: 'GET',
                searchParam: 'search'
            },
            addButton: {
                text: 'Adicionar nova conta bancária',
                class: ''
            },
            onClickButton: (selectId) => {
                window.openModal('conta-empresa-modal', selectId);
            },
            onSelect: (value, label) => {
                console.log('Conta bancária selecionada:', { value, label });
            }
        });
    }
}

// Sistema de Tabs para o modal
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.tab-adicionar').forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remover active de todos os botões
            document.querySelectorAll('.tab-adicionar').forEach(btn => {
                btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Adicionar active ao botão clicado
            this.classList.add('active', 'border-blue-500', 'text-blue-600');
            this.classList.remove('border-transparent', 'text-gray-500');
            
            // Ocultar todos os conteúdos
            document.querySelectorAll('.tab-adicionar-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Mostrar conteúdo da tab selecionada
            document.getElementById('tab-adicionar-' + targetTab).classList.remove('hidden');
        });
    });
    
    // Seleção de entidade
    const entidadeTipoSelect = document.getElementById('adicionar-entidade-tipo');
    const entidadeContainer = document.getElementById('adicionar-entidade-container');
    const entidadeSelect = document.getElementById('adicionar-entidade-id');
    
    if (entidadeTipoSelect) {
        entidadeTipoSelect.addEventListener('change', function() {
            const tipo = this.value;
            
            if (tipo && entidadesModal[tipo]) {
                entidadeSelect.innerHTML = '<option value="">Selecione a entidade</option>';
                entidadesModal[tipo].forEach(entidade => {
                    const option = document.createElement('option');
                    option.value = entidade.id;
                    option.textContent = entidade.nome;
                    entidadeSelect.appendChild(option);
                });
                entidadeContainer.classList.remove('hidden');
            } else {
                entidadeContainer.classList.add('hidden');
                entidadeSelect.innerHTML = '<option value="">Selecione a entidade</option>';
            }
        });
    }
    
    // Pagamento Quitado - habilitar/desabilitar data de compensação
    const pagamentoQuitadoSelect = document.getElementById('adicionar-pagamento-quitado');
    const dataCompensacaoInput = document.getElementById('adicionar-data-compensacao');
    
    if (pagamentoQuitadoSelect && dataCompensacaoInput) {
        pagamentoQuitadoSelect.addEventListener('change', function() {
            if (this.value === '1') {
                dataCompensacaoInput.disabled = false;
                dataCompensacaoInput.classList.remove('bg-gray-100');
                if (!dataCompensacaoInput.value) {
                    const hoje = new Date().toISOString().split('T')[0];
                    dataCompensacaoInput.value = hoje;
                }
            } else {
                dataCompensacaoInput.disabled = true;
                dataCompensacaoInput.classList.add('bg-gray-100');
                dataCompensacaoInput.value = '';
            }
        });
    }
    
    // Calcular total quando valores mudarem
    const camposValor = ['adicionar-valor', 'adicionar-juros', 'adicionar-multa', 'adicionar-desconto'];
    camposValor.forEach(campoId => {
        const campo = document.getElementById(campoId);
        if (campo) {
            campo.addEventListener('input', calcularTotalAdicionar);
        }
    });
});

function calcularTotalAdicionar() {
    const valor = parseFloat(document.getElementById('adicionar-valor').value) || 0;
    const juros = parseFloat(document.getElementById('adicionar-juros').value) || 0;
    const multa = parseFloat(document.getElementById('adicionar-multa').value) || 0;
    const desconto = parseFloat(document.getElementById('adicionar-desconto').value) || 0;
    
    const total = valor + juros + multa - desconto;
    
    const totalFormatado = total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    document.getElementById('adicionar-valor-total').value = totalFormatado;
    document.getElementById('adicionar-valor-total-hidden').value = total.toFixed(2);
}

function fecharModalAdicionar() {
    document.getElementById('modal-adicionar-conta').classList.add('hidden');
    document.getElementById('form-adicionar-conta').reset();
    
    // Limpar cSelects
    if (planoContaModal) planoContaModal.clear();
    if (centroCustoModal) centroCustoModal.clear();
    if (formaPagamentoModal) formaPagamentoModal.clear();
    if (contaEmpresaModal) contaEmpresaModal.clear();
}

function salvarConta() {
    const form = document.getElementById('form-adicionar-conta');
    const transacaoId = document.getElementById('adicionar-transacao-id').value;
    
    // Obter valores dos cSelects
    const planoContaId = planoContaModal ? planoContaModal.getValue() : null;
    const centroCustoId = centroCustoModal ? centroCustoModal.getValue() : null;
    const formaPagamentoId = formaPagamentoModal ? formaPagamentoModal.getValue() : null;
    const contaEmpresaId = contaEmpresaModal ? contaEmpresaModal.getValue() : null;
    
    // Validar campos obrigatórios
    if (!planoContaId) {
        alert('Por favor, selecione um Plano de Contas.');
        return;
    }
    if (!formaPagamentoId) {
        alert('Por favor, selecione uma Forma de Pagamento.');
        return;
    }
    
    // Mostrar loading
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Salvando...';
    
    // Preparar dados
    const dados = {
        descricao: document.getElementById('adicionar-descricao').value,
        vencimento: document.getElementById('adicionar-vencimento').value,
        valor: document.getElementById('adicionar-valor').value,
        plano_conta_id: planoContaId,
        centro_custo_id: centroCustoId || null,
        forma_pagamento_id: formaPagamentoId,
        conta_empresa_id: contaEmpresaId || null,
        pagamento_quitado: document.getElementById('adicionar-pagamento-quitado').value || '0',
        data_compensacao: document.getElementById('adicionar-data-compensacao').value || null,
        juros_tipo: document.getElementById('adicionar-juros-tipo').value || 'fixo',
        juros_forma: document.getElementById('adicionar-juros-forma').value || 'valor',
        juros: document.getElementById('adicionar-juros').value || '0',
        multa_forma: document.getElementById('adicionar-multa-forma').value || 'valor',
        multa: document.getElementById('adicionar-multa').value || '0',
        desconto: document.getElementById('adicionar-desconto').value || '0',
        valor_total: document.getElementById('adicionar-valor-total-hidden').value || document.getElementById('adicionar-valor').value,
        entidade_tipo: document.getElementById('adicionar-entidade-tipo').value || null,
        entidade_id: document.getElementById('adicionar-entidade-id').value || null,
        informacao_complementar: document.getElementById('adicionar-informacao-complementar').value || null,
        observacao: document.getElementById('adicionar-observacao').value || null,
        tipo: document.getElementById('adicionar-tipo').value
    };
    
    fetch(`{{ route('conciliacao-bancaria.criar-e-conciliar', ':id') }}`.replace(':id', transacaoId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(dados)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Fechar modal
            fecharModalAdicionar();
            
            // Recarregar página para atualizar dados
            window.location.reload();
        } else {
            alert('Erro: ' + (data.message || 'Erro ao salvar conta'));
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao salvar conta. Tente novamente.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}

let paginaAtual = 1;

function abrirModalBuscarConta(transacaoId, tipo, data, valor) {
    transacaoAtual = transacaoId;
    tipoConta = tipo === 'CREDIT' ? 2 : 1; // Crédito = receber, Débito = pagar
    
    // Atualizar texto do botão no modal
    const textoAdicionar = tipoConta === 1 ? 'Adicionar Contas a Pagar' : 'Adicionar Contas a Receber';
    const textoAdicionarModal = document.getElementById('texto-adicionar-modal');
    if (textoAdicionarModal) {
        textoAdicionarModal.textContent = textoAdicionar;
    }
    
    // Limpar todos os filtros
    document.getElementById('filtro-descricao').value = '';
    document.getElementById('filtro-vencimento-inicio').value = '';
    document.getElementById('filtro-vencimento-fim').value = '';
    document.getElementById('filtro-situacao').value = '';
    
    document.getElementById('modal-buscar-conta').classList.remove('hidden');
    paginaAtual = 1;
    buscarLancamentos(1);
}

function buscarLancamentos(page = 1) {
    if (!transacaoAtual) return;
    
    paginaAtual = page;
    
    document.getElementById('loading').classList.remove('hidden');
    document.getElementById('resultados').classList.add('hidden');
    document.getElementById('sem-resultados').classList.add('hidden');

    const filtros = {
        tipo: tipoConta,
        descricao: document.getElementById('filtro-descricao').value,
        vencimento_inicio: document.getElementById('filtro-vencimento-inicio').value,
        vencimento_fim: document.getElementById('filtro-vencimento-fim').value,
        situacao: document.getElementById('filtro-situacao').value,
        page: page
    };

    // Buscar lançamentos
    fetch(`{{ url('/conciliacao-bancaria/transacoes') }}/${transacaoAtual}/buscar-contas`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify(filtros)
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('loading').classList.add('hidden');
        
        if (data.success && data.contas && data.contas.length > 0) {
            document.getElementById('resultados').classList.remove('hidden');
            const tbody = document.getElementById('contas-list');
            tbody.innerHTML = '';
            
            data.contas.forEach(conta => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50';
                
                // Cor da situação (classes completas do Tailwind)
                let situacaoClasses = '';
                if (conta.situacao_value == 1) {
                    situacaoClasses = 'bg-yellow-100 text-yellow-800';
                } else if (conta.situacao_value == 2) {
                    situacaoClasses = 'bg-green-100 text-green-800';
                } else if (conta.situacao_value == 3) {
                    situacaoClasses = 'bg-red-100 text-red-800';
                } else {
                    situacaoClasses = 'bg-gray-100 text-gray-800';
                }
                
                tr.innerHTML = `
                    <td class="px-4 py-3 text-sm text-gray-900">${conta.descricao}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">${conta.vencimento}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900 text-right">R$ ${conta.valor}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">${conta.entidade}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${situacaoClasses}">
                            ${conta.situacao}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <button onclick="vincularConta('${conta.id}')" 
                                class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm">
                            <i class="fas fa-link mr-1"></i>
                            Vincular
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
            
            // Atualizar paginação
            atualizarPaginacao(data.pagination);
        } else {
            document.getElementById('sem-resultados').classList.remove('hidden');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        document.getElementById('loading').classList.add('hidden');
        alert('Erro ao buscar lançamentos. Tente novamente.');
    });
}

function atualizarPaginacao(pagination) {
    const paginacaoDiv = document.getElementById('paginacao');
    paginacaoDiv.innerHTML = '';
    
    if (pagination.total === 0) return;
    
    const totalPages = pagination.last_page;
    const currentPage = pagination.current_page;
    
    // Informações de resultados
    const infoDiv = document.createElement('div');
    infoDiv.className = 'text-sm text-gray-700';
    infoDiv.innerHTML = `Mostrando ${pagination.from} até ${pagination.to} de ${pagination.total} resultados`;
    paginacaoDiv.appendChild(infoDiv);
    
    // Botões de navegação
    const navDiv = document.createElement('div');
    navDiv.className = 'flex items-center space-x-1';
    
    // Primeira página
    const firstBtn = document.createElement('button');
    firstBtn.className = `px-3 py-2 text-sm rounded-md ${currentPage === 1 ? 'text-gray-400 bg-gray-100 cursor-not-allowed' : 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50'}`;
    firstBtn.disabled = currentPage === 1;
    firstBtn.innerHTML = '<i class="fas fa-angle-double-left"></i>';
    firstBtn.onclick = () => currentPage !== 1 && buscarLancamentos(1);
    navDiv.appendChild(firstBtn);
    
    // Página anterior
    const prevBtn = document.createElement('button');
    prevBtn.className = `px-3 py-2 text-sm rounded-md ${currentPage === 1 ? 'text-gray-400 bg-gray-100 cursor-not-allowed' : 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50'}`;
    prevBtn.disabled = currentPage === 1;
    prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
    prevBtn.onclick = () => currentPage !== 1 && buscarLancamentos(currentPage - 1);
    navDiv.appendChild(prevBtn);
    
    // Números das páginas
    const startPage = Math.max(1, currentPage - 2);
    const endPage = Math.min(totalPages, currentPage + 2);
    
    for (let i = startPage; i <= endPage; i++) {
        const pageBtn = document.createElement('button');
        pageBtn.className = `px-3 py-2 text-sm rounded-md ${i === currentPage ? 'text-white bg-blue-600 border border-blue-600 font-semibold' : 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50'}`;
        pageBtn.textContent = i;
        pageBtn.onclick = () => buscarLancamentos(i);
        navDiv.appendChild(pageBtn);
    }
    
    // Próxima página
    const nextBtn = document.createElement('button');
    nextBtn.className = `px-3 py-2 text-sm rounded-md ${currentPage === totalPages ? 'text-gray-400 bg-gray-100 cursor-not-allowed' : 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50'}`;
    nextBtn.disabled = currentPage === totalPages;
    nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
    nextBtn.onclick = () => currentPage !== totalPages && buscarLancamentos(currentPage + 1);
    navDiv.appendChild(nextBtn);
    
    // Última página
    const lastBtn = document.createElement('button');
    lastBtn.className = `px-3 py-2 text-sm rounded-md ${currentPage === totalPages ? 'text-gray-400 bg-gray-100 cursor-not-allowed' : 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50'}`;
    lastBtn.disabled = currentPage === totalPages;
    lastBtn.innerHTML = '<i class="fas fa-angle-double-right"></i>';
    lastBtn.onclick = () => currentPage !== totalPages && buscarLancamentos(totalPages);
    navDiv.appendChild(lastBtn);
    
    paginacaoDiv.appendChild(navDiv);
}

function limparFiltros() {
    document.getElementById('filtro-descricao').value = '';
    document.getElementById('filtro-vencimento-inicio').value = '';
    document.getElementById('filtro-vencimento-fim').value = '';
    document.getElementById('filtro-situacao').value = '';
    buscarLancamentos(1);
}

function fecharModal() {
    document.getElementById('modal-buscar-conta').classList.add('hidden');
    document.getElementById('loading').classList.add('hidden');
    document.getElementById('resultados').classList.add('hidden');
    document.getElementById('sem-resultados').classList.add('hidden');
    
    // Limpar filtros sem buscar
    document.getElementById('filtro-descricao').value = '';
    document.getElementById('filtro-vencimento-inicio').value = '';
    document.getElementById('filtro-vencimento-fim').value = '';
    document.getElementById('filtro-situacao').value = '';
    
    transacaoAtual = null;
    tipoConta = null;
    paginaAtual = 1;
}

function vincularConta(contaId) {
    if (!transacaoAtual) return;

    fetch(`{{ url('/conciliacao-bancaria/transacoes') }}/${transacaoAtual}/vincular-conta`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            conta_id: contaId,
            tipo: tipoConta
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Conta vinculada com sucesso!');
            location.reload();
        } else {
            alert(data.message || 'Erro ao vincular conta.');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao vincular conta. Tente novamente.');
    });
}

</script>
@endsection

