@extends('layouts.app')

@section('content')
<div>
    <div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $titulo }}</h2>
                    <a href="{{ route($tipo == 1 ? 'contas-a-pagar.create' : 'contas-a-receber.create') }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-plus mr-2"></i>
                        Nova {{ $titulo }}
                    </a>
                </div>

                <!-- Cards de Resumo Clicáveis -->
                <div class="grid grid-cols-1 md:grid-cols-5 mb-6">
                    <!-- Vencidos -->
                    <a href="{{ route($tipo == 1 ? 'contas-a-pagar.index' : 'contas-a-receber.index', ['filtro' => 'vencidos']) }}"
                       class="bg-white border-r border-gray-300 p-4 hover:bg-gray-50 transition-colors cursor-pointer {{ $filtroCard == 'vencidos' ? 'border-t-4 border-t-red-500' : '' }}">
                        <div class="text-sm font-medium text-gray-500 mb-1">Vencidos</div>
                        <div class="text-lg font-bold text-red-600">R$ {{ number_format($resumo['vencidos'], 2, ',', '.') }}</div>
                    </a>

                    <!-- Vence Hoje -->
                    <a href="{{ route($tipo == 1 ? 'contas-a-pagar.index' : 'contas-a-receber.index', ['filtro' => 'vence_hoje']) }}"
                       class="bg-white border-r border-gray-300 p-4 hover:bg-gray-50 transition-colors cursor-pointer {{ $filtroCard == 'vence_hoje' ? 'border-t-4 border-t-orange-500' : '' }}">
                        <div class="text-sm font-medium text-gray-500 mb-1">Vence Hoje</div>
                        <div class="text-lg font-bold text-orange-600">R$ {{ number_format($resumo['vence_hoje'], 2, ',', '.') }}</div>
                    </a>

                    <!-- A Vencer -->
                    <a href="{{ route($tipo == 1 ? 'contas-a-pagar.index' : 'contas-a-receber.index', ['filtro' => 'a_vencer']) }}"
                       class="bg-white border-r border-gray-300 p-4 hover:bg-gray-50 transition-colors cursor-pointer {{ $filtroCard == 'a_vencer' ? 'border-t-4 border-t-blue-500' : '' }}">
                        <div class="text-sm font-medium text-gray-500 mb-1">A Vencer</div>
                        <div class="text-lg font-bold text-blue-600">R$ {{ number_format($resumo['a_vencer'], 2, ',', '.') }}</div>
                    </a>

                    <!-- Pagos -->
                    <a href="{{ route($tipo == 1 ? 'contas-a-pagar.index' : 'contas-a-receber.index', ['filtro' => 'pagos']) }}"
                       class="bg-white border-r border-gray-300 p-4 hover:bg-gray-50 transition-colors cursor-pointer {{ $filtroCard == 'pagos' ? 'border-t-4 border-t-teal-500' : '' }}">
                        <div class="text-sm font-medium text-gray-500 mb-1">Pagos</div>
                        <div class="text-lg font-bold text-teal-600">R$ {{ number_format($resumo['pagos'], 2, ',', '.') }}</div>
                    </a>

                    <!-- Total -->
                    <a href="{{ route($tipo == 1 ? 'contas-a-pagar.index' : 'contas-a-receber.index', ['filtro' => 'todos']) }}"
                       class="bg-white p-4 hover:bg-gray-50 transition-colors cursor-pointer {{ $filtroCard == 'todos' ? 'border-t-4 border-t-gray-500' : '' }}">
                        <div class="text-sm font-medium text-gray-500 mb-1">Total</div>
                        <div class="text-lg font-bold text-gray-900">R$ {{ number_format($resumo['total'], 2, ',', '.') }}</div>
                    </a>
                </div>

                <!-- Filtros -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-filter text-blue-600 mr-2"></i>
                            Filtros
                        </h3>
                        <div class="text-sm text-gray-600">
                            <span class="font-medium">{{ $movimentacoes->count() }}</span> movimentação(ões) encontrada(s)
                            @if($filtroDescricao)
                                <span class="text-blue-600">para "{{ $filtroDescricao }}"</span>
                            @endif
                        </div>
                    </div>

                    <form method="GET" action="{{ route($tipo == 1 ? 'contas-a-pagar.index' : 'contas-a-receber.index') }}" class="space-y-4">
                        <!-- Filtros Principais -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Filtro por Descrição -->
                            <div class="space-y-2">
                                <label for="descricao" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-search text-gray-400 mr-1"></i>
                                    Descrição
                                </label>
                                <input type="text"
                                       name="descricao"
                                       id="descricao"
                                       value="{{ $filtroDescricao }}"
                                       placeholder="Digite a descrição..."
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Filtro por Situação -->
                            <div class="space-y-2">
                                <label for="situacao" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-toggle-on text-gray-400 mr-1"></i>
                                    Situação
                                </label>
                                <select name="situacao" id="situacao" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="todos" {{ $filtroSituacao === 'todos' ? 'selected' : '' }}>Todas as situações</option>
                                    <option value="1" {{ $filtroSituacao === '1' ? 'selected' : '' }}>Pendentes</option>
                                    <option value="2" {{ $filtroSituacao === '2' ? 'selected' : '' }}>Pagas</option>
                                    <option value="3" {{ $filtroSituacao === '3' ? 'selected' : '' }}>Vencidas</option>
                                    <option value="4" {{ $filtroSituacao === '4' ? 'selected' : '' }}>Canceladas</option>
                                </select>
                            </div>

                            <!-- Botões de Ação -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-cogs text-gray-400 mr-1"></i>
                                    Ações
                                </label>
                                <div class="flex space-x-2">
                                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                                        <i class="fas fa-search mr-2"></i>
                                        Filtrar
                                    </button>
                                    <a href="{{ route($tipo == 1 ? 'contas-a-pagar.index' : 'contas-a-receber.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 text-center">
                                        <i class="fas fa-times mr-2"></i>
                                        Limpar
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros Ativos -->
                        @if($filtroDescricao || $filtroSituacao !== 'todos')
                            <div class="pt-4 border-t border-gray-200">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-medium text-gray-700">Filtros ativos:</span>
                                    @if($filtroDescricao)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-search mr-1"></i>
                                            Descrição: "{{ $filtroDescricao }}"
                                            <button type="button" onclick="limparFiltroDescricao()" class="ml-1 text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </span>
                                    @endif
                                    @if($filtroSituacao !== 'todos')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-toggle-on mr-1"></i>
                                            Situação: {{ $filtroSituacao === '1' ? 'Pendentes' : ($filtroSituacao === '2' ? 'Pagas' : ($filtroSituacao === '3' ? 'Vencidas' : 'Canceladas')) }}
                                            <button type="button" onclick="limparFiltroSituacao()" class="ml-1 text-green-600 hover:text-green-800">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </form>
                </div>

                <!-- Resumo -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">{{ $movimentacoes->total() }}</span> {{ strtolower($titulo) }} encontrada(s)
                        @if($filtroDescricao)
                            <span class="text-blue-600">para "{{ $filtroDescricao }}"</span>
                        @endif
                        @if($movimentacoes->total() > 0)
                            <span class="ml-4">
                                Total: <span class="font-bold text-{{ $tipoCor }}-600">R$ {{ number_format($movimentacoes->sum('valor_total'), 2, ',', '.') }}</span>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Tabela -->
                @if($movimentacoes->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entidade</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pagamento</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Vencimento</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Situação</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($movimentacoes as $movimentacao)
                                    <tr class="hover:bg-gray-50 {{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $movimentacao->descricao }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $movimentacao->entidade->nome ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $movimentacao->formaPagamento->nome }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $movimentacao->vencimento->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button onclick="abrirModalConfirmacao('{{ $movimentacao->id }}', '{{ $movimentacao->descricao }}', '{{ $movimentacao->valor_total }}', '{{ $movimentacao->situacao->value }}', '{{ $movimentacao->forma_pagamento_id }}', '{{ $movimentacao->conta_empresa_id }}', '{{ $movimentacao->data_pagamento }}', '{{ $movimentacao->formaPagamento->nome ?? '' }}', '{{ $movimentacao->observacoes_pagamento ?? '' }}')"
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium cursor-pointer hover:opacity-80 transition-opacity
                                                    @if($movimentacao->situacao->value === 1) bg-yellow-100 text-yellow-800 hover:bg-yellow-200
                                                    @elseif($movimentacao->situacao->value === 2) bg-green-100 text-green-800 hover:bg-green-200
                                                    @elseif($movimentacao->situacao->value === 3) bg-red-100 text-red-800 hover:bg-red-200
                                                    @else bg-gray-100 text-gray-800 hover:bg-gray-200 @endif">
                                                {{ $movimentacao->getSituacaoLabel() }}
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ number_format($movimentacao->valor_total, 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route($tipo == 1 ? 'contas-a-pagar.show' : 'contas-a-receber.show', $movimentacao) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route($tipo == 1 ? 'contas-a-pagar.edit' : 'contas-a-receber.edit', $movimentacao) }}" class="text-yellow-600 hover:text-yellow-900">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button onclick="confirmarExclusaoSweetAlert('{{ $movimentacao->id }}', '{{ $movimentacao->descricao }}')" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-6">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Mostrando {{ $movimentacoes->firstItem() }} até {{ $movimentacoes->lastItem() }} de {{ $movimentacoes->total() }} resultados
                            </div>
                            <div class="flex space-x-1">
                                @if ($movimentacoes->onFirstPage())
                                    <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                                        <i class="fas fa-chevron-left"></i>
                                    </span>
                                @else
                                    <a href="{{ $movimentacoes->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                @endif

                                @foreach ($movimentacoes->getUrlRange(1, $movimentacoes->lastPage()) as $page => $url)
                                    @if ($page == $movimentacoes->currentPage())
                                        <span class="px-3 py-2 text-sm text-white bg-blue-600 border border-blue-600 rounded-md">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">{{ $page }}</a>
                                    @endif
                                @endforeach

                                @if ($movimentacoes->hasMorePages())
                                    <a href="{{ $movimentacoes->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                @else
                                    <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma {{ strtolower($titulo) }} encontrada</h3>
                        <p class="text-gray-500 mb-4">Comece criando uma nova {{ strtolower($titulo) }}.</p>
                        <a href="{{ route($tipo == 1 ? 'contas-a-pagar.create' : 'contas-a-receber.create') }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-plus mr-2"></i>
                            Nova {{ $titulo }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Pagamento -->
<div id="modalConfirmacao" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-6 border w-[1000px] shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900" id="modalTitulo">Confirmar pagamento</h3>
                <button onclick="fecharModalConfirmacao()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Form -->
            <form id="formConfirmacao" method="POST">
                @csrf

                <!-- Primeira linha: Data e Forma de Pagamento -->
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <!-- Data da compensação -->
                    <div>
                        <label for="data_compensacao" class="block text-sm font-semibold text-gray-700 mb-2">
                            Data da compensação <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="data_compensacao" name="data_compensacao"
                               class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>

                    <!-- Forma de pagamento -->
                    <div>
                        <label for="forma_pagamento_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Forma de pagamento <span class="text-red-500">*</span>
                        </label>
                        <select id="forma_pagamento_id" name="forma_pagamento_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                            <option value="">Selecione uma forma de pagamento</option>
                            <!-- Será preenchido via JavaScript -->
                        </select>
                    </div>
                </div>

                <!-- Segunda linha: Conta bancária -->
                <div class="mb-6">
                    <label for="conta_empresa_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Conta bancária <span class="text-red-500">*</span>
                    </label>
                    <select id="conta_empresa_id" name="conta_empresa_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">Selecione uma conta bancária</option>
                        <!-- Será preenchido via JavaScript -->
                    </select>
                </div>

                <!-- Terceira linha: Valores em grid 2x2 -->
                <div class="mb-6">
                    <h4 class="text-md font-semibold text-gray-800 mb-4">Valores Financeiros</h4>
                    <div class="grid grid-cols-2 gap-6">
                        <!-- Valor bruto -->
                        <div>
                            <label for="valor_bruto" class="block text-sm font-semibold text-gray-700 mb-2">
                                Valor bruto <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="valor_bruto" name="valor_bruto" step="0.01" min="0"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   required>
                        </div>

                        <!-- Juros -->
                        <div>
                            <label for="juros" class="block text-sm font-semibold text-gray-700 mb-2">
                                Juros
                            </label>
                            <input type="number" id="juros" name="juros" step="0.01" min="0" value="0.00"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Desconto -->
                        <div>
                            <label for="desconto" class="block text-sm font-semibold text-gray-700 mb-2">
                                Desconto
                            </label>
                            <input type="number" id="desconto" name="desconto" step="0.01" min="0" value="0.00"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Total -->
                        <div>
                            <label for="valor_total" class="block text-sm font-semibold text-gray-700 mb-2">
                                Total
                            </label>
                            <input type="number" id="valor_total" name="valor_total" step="0.01" min="0" readonly
                                   class="w-full px-4 py-3 border border-gray-300 rounded-md bg-gray-100 text-gray-600 font-semibold">
                        </div>
                    </div>
                </div>

                <!-- Quarta linha: Observações -->
                <div class="mb-8">
                    <label for="observacoes" class="block text-sm font-semibold text-gray-700 mb-2">
                        Observações
                    </label>
                    <textarea id="observacoes" name="observacoes" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Observações adicionais sobre o pagamento..."></textarea>
                </div>

                <!-- Botões -->
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="fecharModalConfirmacao()"
                            class="px-6 py-3 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors font-medium">
                        Voltar
                    </button>
                    <button type="submit"
                            class="px-6 py-3 bg-teal-500 text-white rounded-md hover:bg-teal-600 transition-colors font-medium">
                        Confirmar pagamento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function confirmarExclusaoSweetAlert(id, nome) {
    Swal.fire({
        title: 'Confirmar Exclusão',
        text: `Tem certeza que deseja excluir "${nome}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/{{ $tipo == 1 ? 'contas-a-pagar' : 'contas-a-receber' }}/${id}`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';

            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function toggleStatus(id, nome, statusAtual) {
    const action = statusAtual === 1 ? 'pagar' : 'marcar como pendente';
    const icon = statusAtual === 1 ? 'warning' : 'success';
    const confirmColor = statusAtual === 1 ? '#d33' : '#28a745';

    Swal.fire({
        title: `Confirmar ${statusAtual === 1 ? 'Pagamento' : 'Marcar como Pendente'}`,
        text: `Tem certeza que deseja ${action} "${nome}"?`,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: confirmColor,
        cancelButtonColor: '#3085d6',
        confirmButtonText: `Sim, ${action}!`,
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/{{ $tipo == 1 ? 'contas-a-pagar' : 'contas-a-receber' }}/${id}/toggle-status`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'PATCH';

            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Função para abrir modal de confirmação
function abrirModalConfirmacao(id, descricao, valor, situacao, formaPagamentoId = null, contaEmpresaId = null, dataPagamento = null, formaPagamentoNome = null, observacoesPagamento = null) {
    // Se a situação for "Paga" (2), abrir modal de cancelar confirmação
    if (parseInt(situacao) === 2) {
        abrirModalCancelarConfirmacao(
            id,
            descricao,
            valor,
            dataPagamento,
            formaPagamentoNome,
            observacoesPagamento
        );
        return;
    }

    const modal = document.getElementById('modalConfirmacao');
    const form = document.getElementById('formConfirmacao');
    const titulo = document.getElementById('modalTitulo');

    // Definir ação baseada na situação atual
    let acao = '';
    let tituloModal = '';

    switch(parseInt(situacao)) {
        case 1: // Pendente
            acao = 'confirmar-pagamento';
            tituloModal = 'Confirmar pagamento';
            break;
        case 3: // Vencida
            acao = 'confirmar-pagamento';
            tituloModal = 'Confirmar pagamento';
            break;
        case 4: // Cancelada
            acao = 'reativar';
            tituloModal = 'Reativar movimentação';
            break;
        default:
            acao = 'confirmar-pagamento';
            tituloModal = 'Confirmar pagamento';
    }

    titulo.textContent = tituloModal;

    // Construir URL corretamente baseada no tipo
    const baseRoute = '{{ $tipo == 1 ? "contas-a-pagar" : "contas-a-receber" }}';
    form.action = `/${baseRoute}/${id}/${acao}`;

    // Preencher valores padrão
    document.getElementById('valor_bruto').value = parseFloat(valor).toFixed(2);
    document.getElementById('juros').value = '0.00';
    document.getElementById('desconto').value = '0.00';
    document.getElementById('valor_total').value = parseFloat(valor).toFixed(2);
    document.getElementById('data_compensacao').value = new Date().toISOString().split('T')[0];

    // Preencher formas de pagamento
    carregarFormasPagamento(formaPagamentoId);

    // Preencher contas bancárias
    carregarContasBancarias(contaEmpresaId);

    // Mostrar modal
    modal.classList.remove('hidden');
}

// Função para fechar modal
function fecharModalConfirmacao() {
    const modal = document.getElementById('modalConfirmacao');
    modal.classList.add('hidden');
}

// Função para carregar formas de pagamento
function carregarFormasPagamento(formaPagamentoIdSelecionada = null) {
    const select = document.getElementById('forma_pagamento_id');
    select.innerHTML = '<option value="">Selecione uma forma de pagamento</option>';

    // Dados das formas de pagamento vindos do backend
    const formasPagamento = @json($formasPagamento);

    formasPagamento.forEach(forma => {
        const option = document.createElement('option');
        option.value = forma.id;
        option.textContent = forma.nome;

        // Selecionar a forma de pagamento atual se fornecida
        if (formaPagamentoIdSelecionada && forma.id === formaPagamentoIdSelecionada) {
            option.selected = true;
        }

        select.appendChild(option);
    });
}

// Função para carregar contas bancárias
function carregarContasBancarias(contaEmpresaIdSelecionada = null) {
    const select = document.getElementById('conta_empresa_id');
    select.innerHTML = '<option value="">Selecione uma conta bancária</option>';

    // Dados das contas bancárias vindos do backend
    const contasBancarias = @json($contasBancarias);

    contasBancarias.forEach(conta => {
        const option = document.createElement('option');
        option.value = conta.id;
        option.textContent = conta.nome;

        // Selecionar a conta bancária atual se fornecida
        if (contaEmpresaIdSelecionada && conta.id === contaEmpresaIdSelecionada) {
            option.selected = true;
        }

        select.appendChild(option);
    });
}

// Função para calcular total automaticamente
function calcularTotal() {
    const valorBruto = parseFloat(document.getElementById('valor_bruto').value) || 0;
    const juros = parseFloat(document.getElementById('juros').value) || 0;
    const desconto = parseFloat(document.getElementById('desconto').value) || 0;

    const total = valorBruto + juros - desconto;
    document.getElementById('valor_total').value = total.toFixed(2);
}

// Event listeners para cálculo automático
document.addEventListener('DOMContentLoaded', function() {
    const valorBruto = document.getElementById('valor_bruto');
    const juros = document.getElementById('juros');
    const desconto = document.getElementById('desconto');

    if (valorBruto) valorBruto.addEventListener('input', calcularTotal);
    if (juros) juros.addEventListener('input', calcularTotal);
    if (desconto) desconto.addEventListener('input', calcularTotal);

    // Interceptar submit do formulário para fazer AJAX
    const formConfirmacao = document.getElementById('formConfirmacao');
    if (formConfirmacao) {
        formConfirmacao.addEventListener('submit', function(e) {
            e.preventDefault();
            confirmarPagamentoAjax();
        });
    }
});

// Função para confirmar pagamento via AJAX
function confirmarPagamentoAjax() {
    const form = document.getElementById('formConfirmacao');
    const formData = new FormData(form);

    // Mostrar loading no botão
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processando...';

    // Primeiro, gerar JWT temporário
    // Extrair ID da movimentação da URL do form.action
    const formAction = form.action;
    const movimentacaoId = formAction.split('/').slice(-2, -1)[0]; // Pega o penúltimo segmento da URL
    const baseRoute = '{{ $tipo == 1 ? "contas-a-pagar" : "contas-a-receber" }}';
    const jwtUrl = `/${baseRoute}/${movimentacaoId}/gerar-jwt-confirmacao`;

    fetch(jwtUrl, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(jwtData => {
        if (jwtData.success) {
            // Agora fazer a requisição de confirmação com o JWT
            return fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Confirmation-Token': jwtData.jwt_token
                }
            });
        } else {
            throw new Error(jwtData.message || 'Erro ao gerar token de confirmação');
        }
    })
    .then(response => response.json())
    .then(data => {
        // Restaurar botão
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;

        if (data.success) {
            // Sucesso
            Swal.fire({
                title: 'Sucesso!',
                text: data.message || 'Pagamento confirmado com sucesso!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                // Fechar modal e recarregar página
                fecharModalConfirmacao();
                window.location.reload();
            });
        } else {
            // Erro
            Swal.fire({
                title: 'Erro!',
                text: data.message || 'Ocorreu um erro ao confirmar o pagamento.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        // Restaurar botão
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;

        console.error('Erro:', error);
        Swal.fire({
            title: 'Erro!',
            text: error.message || 'Ocorreu um erro inesperado. Tente novamente.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
}

// Variáveis globais para o modal de cancelar
let movimentacaoAtualCancelar = null;

// Função para abrir modal de cancelar confirmação
function abrirModalCancelarConfirmacao(id, descricao, valor, dataPagamento, formaPagamentoNome, observacoes) {
    const modal = document.getElementById('modalCancelarConfirmacao');

    // Armazenar dados da movimentação
    movimentacaoAtualCancelar = {
        id: id,
        descricao: descricao,
        valor: valor,
        dataPagamento: dataPagamento,
        formaPagamentoNome: formaPagamentoNome,
        observacoes: observacoes
    };

    // Preencher dados no modal
    document.getElementById('dataConfirmacaoDisplay').textContent = formatarData(dataPagamento);
    document.getElementById('valorTotalDisplay').textContent = parseFloat(valor).toFixed(2);
    document.getElementById('formaPagamentoDisplay').textContent = formaPagamentoNome || 'Não informado';
    document.getElementById('observacoesDisplay').textContent = observacoes || 'Nenhuma observação';

    // Mostrar modal
    modal.classList.remove('hidden');
}

// Função para fechar modal de cancelar confirmação
function fecharModalCancelarConfirmacao() {
    const modal = document.getElementById('modalCancelarConfirmacao');
    modal.classList.add('hidden');
    movimentacaoAtualCancelar = null;
}

// Função para cancelar confirmação de pagamento
function cancelarConfirmacaoPagamento() {
    if (!movimentacaoAtualCancelar) return;

    // Mostrar confirmação
    Swal.fire({
        title: 'Cancelar confirmação?',
        text: 'Esta ação irá marcar a movimentação como pendente novamente.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f97316',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sim, cancelar',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            executarCancelamentoConfirmacao();
        }
    });
}

// Função para executar o cancelamento via AJAX
function executarCancelamentoConfirmacao() {
    if (!movimentacaoAtualCancelar) return;

    const baseRoute = '{{ $tipo == 1 ? "contas-a-pagar" : "contas-a-receber" }}';
    const url = `/${baseRoute}/${movimentacaoAtualCancelar.id}/marcar-pendente`;

    // Mostrar loading
    Swal.fire({
        title: 'Processando...',
        text: 'Cancelando confirmação de pagamento',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    // Fazer requisição AJAX
    fetch(url, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            observacoes: 'Confirmação cancelada pelo usuário'
        })
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();

        if (data.success) {
            Swal.fire({
                title: 'Sucesso!',
                text: data.message || 'Confirmação cancelada com sucesso!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                fecharModalCancelarConfirmacao();
                window.location.reload();
            });
        } else {
            Swal.fire({
                title: 'Erro!',
                text: data.message || 'Ocorreu um erro ao cancelar a confirmação.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        Swal.close();
        console.error('Erro:', error);
        Swal.fire({
            title: 'Erro!',
            text: 'Ocorreu um erro inesperado. Tente novamente.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
}

// Função para formatar data
function formatarData(data) {
    if (!data) return 'Não informado';
    const date = new Date(data);
    return date.toLocaleDateString('pt-BR');
}

// Fechar modal ao clicar fora dele
document.addEventListener('click', function(event) {
    const modal = document.getElementById('modalConfirmacao');
    if (event.target === modal) {
        fecharModalConfirmacao();
    }

    const modalCancelar = document.getElementById('modalCancelarConfirmacao');
    if (event.target === modalCancelar) {
        fecharModalCancelarConfirmacao();
    }
});

// Funções para limpar filtros
function limparFiltroDescricao() {
    document.getElementById('descricao').value = '';
    document.querySelector('form').submit();
}

function limparFiltroSituacao() {
    document.getElementById('situacao').value = 'todos';
    document.querySelector('form').submit();
}
</script>

<!-- Modal de Cancelar Confirmação de Pagamento -->
<div id="modalCancelarConfirmacao" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-6 border w-[600px] shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900" id="modalTituloCancelar">Cancelar confirmação de pagamento</h3>
                <button onclick="fecharModalCancelarConfirmacao()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Detalhes do Pagamento -->
            <div class="mb-8">
                <h4 class="text-md font-semibold text-gray-800 mb-4">Detalhes da confirmação</h4>

                <div class="grid grid-cols-2 gap-6">
                    <!-- Data da confirmação -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Data da confirmação
                        </label>
                        <div class="text-sm text-gray-900" id="dataConfirmacaoDisplay">
                            <!-- Será preenchido via JavaScript -->
                        </div>
                    </div>

                    <!-- Valor total -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Valor total
                        </label>
                        <div class="text-sm text-gray-900" id="valorTotalDisplay">
                            <!-- Será preenchido via JavaScript -->
                        </div>
                    </div>

                    <!-- Forma de pagamento -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Forma de pagamento
                        </label>
                        <div class="text-sm text-gray-900" id="formaPagamentoDisplay">
                            <!-- Será preenchido via JavaScript -->
                        </div>
                    </div>

                    <!-- Observações -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Observações
                        </label>
                        <div class="text-sm text-gray-900" id="observacoesDisplay">
                            <!-- Será preenchido via JavaScript -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex justify-end space-x-4">
                <button type="button" onclick="fecharModalCancelarConfirmacao()"
                        class="px-6 py-3 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors font-medium">
                    Voltar
                </button>
                <button type="button" onclick="cancelarConfirmacaoPagamento()"
                        class="px-6 py-3 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors font-medium">
                    Cancelar confirmação
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
