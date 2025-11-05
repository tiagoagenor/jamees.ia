@extends('layouts.app')

@section('content')
<div>
    <div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $titulo }}</h2>
                    <div class="flex space-x-3">
                        <a href="{{ route($tipo == 1 ? 'contas-a-pagar.show' : 'contas-a-receber.show', $movimentacao) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-eye mr-2"></i>
                            Visualizar
                        </a>
                        <a href="{{ route($tipo == 1 ? 'contas-a-pagar.index' : 'contas-a-receber.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Voltar
                        </a>
                    </div>
                </div>

                <form action="{{ route($tipo == 1 ? 'contas-a-pagar.update' : 'contas-a-receber.update', $movimentacao) }}" method="POST" class="space-y-6" id="movimentacaoForm">
                    @csrf
                    @method('PUT')

                    <!-- Tabs -->
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <button type="button"
                                    class="tab-button active border-b-2 border-blue-500 text-blue-600 py-4 px-1 text-sm font-medium whitespace-nowrap"
                                    data-tab="lancamento">
                                Lançamento financeiro
                            </button>
                            <button type="button"
                                    class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 text-sm font-medium whitespace-nowrap"
                                    data-tab="outras">
                                Outras informações
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Content: Lançamento financeiro -->
                    <div id="tab-lancamento" class="tab-content">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Container 1: Esquerda -->
                            <div class="space-y-4">
                                <!-- Linha 1: Descrição, Vencimento -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">
                                            Descrição <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                               name="descricao"
                                               id="descricao"
                                               value="{{ old('descricao', $movimentacao->descricao) }}"
                                               required
                                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('descricao') border-red-500 @enderror"
                                               placeholder="Digite a descrição">
                                        @error('descricao')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="vencimento" class="block text-sm font-medium text-gray-700 mb-2">
                                            Vencimento <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date"
                                               name="vencimento"
                                               id="vencimento"
                                               value="{{ old('vencimento', $movimentacao->vencimento->format('Y-m-d')) }}"
                                               required
                                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('vencimento') border-red-500 @enderror">
                                        @error('vencimento')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Linha 2: Plano de Contas, Centro de Custo -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="plano_conta_id" class="block text-sm font-medium text-gray-700 mb-2">
                                            Plano de Contas <span class="text-red-500">*</span>
                                        </label>
                                        <select name="plano_conta_id"
                                                id="plano_conta_id"
                                                required
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('plano_conta_id') border-red-500 @enderror">
                                            <option value="">Selecione o plano de conta</option>
                                            @foreach($planoContas as $planoConta)
                                                <option value="{{ $planoConta->id }}" {{ old('plano_conta_id', $movimentacao->plano_conta_id) == $planoConta->id ? 'selected' : '' }}>
                                                    {{ $planoConta->nome }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('plano_conta_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="centro_custo_id" class="block text-sm font-medium text-gray-700 mb-2">
                                            Centro de Custo
                                        </label>
                                        <select name="centro_custo_id"
                                                id="centro_custo_id"
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('centro_custo_id') border-red-500 @enderror">
                                            <option value="">Selecione o centro de custo</option>
                                            @foreach($centroCustos as $centroCusto)
                                                <option value="{{ $centroCusto->id }}" {{ old('centro_custo_id', $movimentacao->centro_custo_id) == $centroCusto->id ? 'selected' : '' }}>
                                                    {{ $centroCusto->nome }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('centro_custo_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Linha 3: Forma de Pagamento, Conta Bancária -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="forma_pagamento_id" class="block text-sm font-medium text-gray-700 mb-2">
                                            Forma de Pagamento <span class="text-red-500">*</span>
                                        </label>
                                        <select name="forma_pagamento_id"
                                                id="forma_pagamento_id"
                                                required
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('forma_pagamento_id') border-red-500 @enderror">
                                            <option value="">Selecione a forma de pagamento</option>
                                            @foreach($formasPagamento as $formaPagamento)
                                                <option value="{{ $formaPagamento->id }}" {{ old('forma_pagamento_id', $movimentacao->forma_pagamento_id) == $formaPagamento->id ? 'selected' : '' }}>
                                                    {{ $formaPagamento->nome }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('forma_pagamento_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="conta_empresa_id" class="block text-sm font-medium text-gray-700 mb-2">
                                            Conta Bancária <span class="text-red-500">*</span>
                                        </label>
                                        <select name="conta_empresa_id"
                                                id="conta_empresa_id"
                                                required
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('conta_empresa_id') border-red-500 @enderror">
                                            <option value="">Selecione a conta bancária</option>
                                            @foreach($contasEmpresa as $contaEmpresa)
                                                <option value="{{ $contaEmpresa->id }}" {{ old('conta_empresa_id', $movimentacao->conta_empresa_id) == $contaEmpresa->id ? 'selected' : '' }}>
                                                    {{ $contaEmpresa->nome }} - {{ $contaEmpresa->banco->nome_normalizado ?? 'Banco não encontrado' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('conta_empresa_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Linha 4: Pagamento Quitado, Data de Compensação -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="pagamento_quitado" class="block text-sm font-medium text-gray-700 mb-2">
                                            Pagamento Quitado
                                        </label>
                                        <select name="pagamento_quitado"
                                                id="pagamento_quitado"
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('pagamento_quitado') border-red-500 @enderror">
                                            <option value="0" {{ old('pagamento_quitado', $movimentacao->situacao->value == 2 ? '1' : '0') == '0' ? 'selected' : '' }}>Não</option>
                                            <option value="1" {{ old('pagamento_quitado', $movimentacao->situacao->value == 2 ? '1' : '0') == '1' ? 'selected' : '' }}>Sim</option>
                                        </select>
                                        @error('pagamento_quitado')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <!-- Campo hidden para situação -->
                                        <input type="hidden" name="situacao" id="situacao" value="{{ old('situacao', $movimentacao->situacao->value) }}">
                                    </div>
                                    <div>
                                        <label for="data_compensacao" class="block text-sm font-medium text-gray-700 mb-2">
                                            Data de Compensação
                                        </label>
                                        <input type="date"
                                               name="data_compensacao"
                                               id="data_compensacao"
                                               value="{{ old('data_compensacao', $movimentacao->data_compensacao ? $movimentacao->data_compensacao->format('Y-m-d') : '') }}"
                                               {{ $movimentacao->situacao->value == 2 ? '' : 'disabled' }}
                                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $movimentacao->situacao->value == 2 ? '' : 'bg-gray-100' }} @error('data_compensacao') border-red-500 @enderror">
                                        @error('data_compensacao')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Container 2: Direita -->
                            <div class="space-y-4">
                                <!-- Box Valores -->
                                <div class="border border-gray-300 rounded-lg shadow-sm overflow-hidden">
                                    <!-- Título do Box -->
                                    <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 rounded-t-lg">
                                        <h3 class="text-sm font-semibold text-gray-900">Valores</h3>
                                    </div>

                                    <!-- Conteúdo do Box -->
                                    <div class="p-4 space-y-4">
                                        <!-- Linha 1: Valor Bruto -->
                                        <div>
                                            <label for="valor" class="block text-sm font-medium text-gray-700 mb-2">
                                                Valor Bruto <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number"
                                                   name="valor"
                                                   id="valor"
                                                   value="{{ old('valor', $movimentacao->valor) }}"
                                                   step="0.01"
                                                   min="0.01"
                                                   required
                                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('valor') border-red-500 @enderror"
                                                   placeholder="0,00">
                                            @error('valor')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Linha 2: Juros -->
                                        <div>
                                            <label for="juros" class="block text-sm font-medium text-gray-700 mb-2">
                                                Juros
                                            </label>
                                            <input type="number"
                                                   name="juros"
                                                   id="juros"
                                                   value="{{ old('juros', $movimentacao->juros ?? 0) }}"
                                                   step="0.01"
                                                   min="0"
                                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('juros') border-red-500 @enderror"
                                                   placeholder="0,00">
                                            @error('juros')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Linha 3: Desconto -->
                                        <div>
                                            <label for="desconto" class="block text-sm font-medium text-gray-700 mb-2">
                                                Desconto
                                            </label>
                                            <input type="number"
                                                   name="desconto"
                                                   id="desconto"
                                                   value="{{ old('desconto', $movimentacao->desconto ?? 0) }}"
                                                   step="0.01"
                                                   min="0"
                                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('desconto') border-red-500 @enderror"
                                                   placeholder="0,00">
                                            @error('desconto')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Linha 4: Ativar Parcelamento/Recorrência -->
                                        <div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox"
                                                       name="ativar_parcelamento"
                                                       id="ativar_parcelamento"
                                                       value="1"
                                                       {{ old('ativar_parcelamento') ? 'checked' : '' }}
                                                       class="sr-only peer">
                                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                                <span class="ml-3 text-sm font-medium text-gray-700">Ativar Parcelamento/Recorrência</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Rodapé do Box - Total -->
                                    <div class="px-4 py-3 border-t border-gray-200 bg-gray-100 rounded-b-lg">
                                        <div class="text-center">
                                            <label for="valor_total" class="block text-sm font-medium text-gray-700 mb-2">
                                                Total
                                            </label>
                                            <input type="text"
                                                   id="valor_total"
                                                   readonly
                                                   value="R$ {{ number_format($movimentacao->valor_total ?? ($movimentacao->valor + ($movimentacao->juros ?? 0) - ($movimentacao->desconto ?? 0)), 2, ',', '.') }}"
                                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm bg-gray-200 font-semibold text-gray-900 text-center">
                                            <input type="hidden" name="valor_total" id="valor_total_hidden" value="{{ $movimentacao->valor_total ?? ($movimentacao->valor + ($movimentacao->juros ?? 0) - ($movimentacao->desconto ?? 0)) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Content: Outras informações -->
                    <div id="tab-outras" class="tab-content hidden">
                        <!-- Linha 1: Tipo de Entidade e Select de Entidade -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="entidade_tipo" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipo de Entidade
                                </label>
                                <select name="entidade_tipo"
                                        id="entidade_tipo"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('entidade_tipo') border-red-500 @enderror">
                                    <option value="">Selecione o tipo</option>
                                    <option value="1" {{ old('entidade_tipo', $movimentacao->entidade_tipo) == 1 ? 'selected' : '' }}>Cliente</option>
                                    <option value="2" {{ old('entidade_tipo', $movimentacao->entidade_tipo) == 2 ? 'selected' : '' }}>Fornecedor</option>
                                    <option value="3" {{ old('entidade_tipo', $movimentacao->entidade_tipo) == 3 ? 'selected' : '' }}>Funcionário</option>
                                    <option value="4" {{ old('entidade_tipo', $movimentacao->entidade_tipo) == 4 ? 'selected' : '' }}>Transportadora</option>
                                </select>
                                @error('entidade_tipo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div id="entidade_select_container" class="{{ old('entidade_tipo', $movimentacao->entidade_tipo) ? '' : 'hidden' }}">
                                <label for="entidade_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Entidade
                                </label>
                                <select name="entidade_id"
                                        id="entidade_id"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('entidade_id') border-red-500 @enderror">
                                    <option value="">Selecione a entidade</option>
                                </select>
                                @error('entidade_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Linha 2: Informação Complementar -->
                        <div>
                            <label for="informacao_complementar" class="block text-sm font-medium text-gray-700 mb-2">
                                Informação Complementar
                            </label>
                            <textarea name="informacao_complementar"
                                      id="informacao_complementar"
                                      rows="4"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('informacao_complementar') border-red-500 @enderror"
                                      placeholder="Digite informações complementares (opcional)">{{ old('informacao_complementar', $movimentacao->informacao_complementar) }}</textarea>
                            @error('informacao_complementar')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Informações de Sistema (Somente Leitura) -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-info-circle mr-2"></i>
                            Informações de Sistema
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">ID</label>
                                <div class="text-sm text-gray-900 font-mono bg-white border border-gray-300 rounded-md px-3 py-2">{{ $movimentacao->id }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Tipo</label>
                                <div class="text-sm text-gray-900 bg-white border border-gray-300 rounded-md px-3 py-2">{{ $movimentacao->getTipoLabel() }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Empresa</label>
                                <div class="text-sm text-gray-900 bg-white border border-gray-300 rounded-md px-3 py-2">{{ $movimentacao->empresa->nome_fantasia ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Criado em</label>
                                <div class="text-sm text-gray-900 bg-white border border-gray-300 rounded-md px-3 py-2">{{ $movimentacao->criado_em->format('d/m/Y H:i:s') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route($tipo == 1 ? 'contas-a-pagar.index' : 'contas-a-receber.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md text-sm font-medium">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-save mr-2"></i>
                            Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dados das entidades
    const entidades = {
        1: @json($clientes),
        2: @json($fornecedores),
        3: @json($funcionarios),
        4: @json($transportadoras)
    };

    // Sistema de Tabs
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');

            // Remover active de todos os botões
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });

            // Adicionar active ao botão clicado
            this.classList.add('active', 'border-blue-500', 'text-blue-600');
            this.classList.remove('border-transparent', 'text-gray-500');

            // Ocultar todos os conteúdos
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });

            // Mostrar conteúdo da tab selecionada
            document.getElementById('tab-' + targetTab).classList.remove('hidden');
        });
    });

    // Controle de select de entidades
    const entidadeTipoSelect = document.getElementById('entidade_tipo');
    const entidadeSelectContainer = document.getElementById('entidade_select_container');
    const entidadeSelect = document.getElementById('entidade_id');

    function carregarEntidades() {
        const tipo = entidadeTipoSelect.value;

        if (tipo && entidades[tipo]) {
            // Limpar select
            entidadeSelect.innerHTML = '<option value="">Selecione a entidade</option>';

            // Preencher com entidades do tipo selecionado
            entidades[tipo].forEach(entidade => {
                const option = document.createElement('option');
                option.value = entidade.id;
                option.textContent = entidade.nome || entidade.nome_fantasia || entidade.razao_social;

                // Marcar como selecionado se for a entidade atual
                @if($movimentacao->entidade_id)
                    if (entidade.id === '{{ $movimentacao->entidade_id }}') {
                        option.selected = true;
                    }
                @endif

                entidadeSelect.appendChild(option);
            });

            // Mostrar container
            entidadeSelectContainer.classList.remove('hidden');
        } else {
            // Ocultar container
            entidadeSelectContainer.classList.add('hidden');
            entidadeSelect.innerHTML = '<option value="">Selecione a entidade</option>';
        }
    }

    entidadeTipoSelect.addEventListener('change', carregarEntidades);

    // Carregar entidades se já existir tipo selecionado
    @if($movimentacao->entidade_tipo)
        carregarEntidades();
    @endif

    // Pagamento Quitado - habilitar/desabilitar Data de Compensação e atualizar situação
    const pagamentoQuitado = document.getElementById('pagamento_quitado');
    const dataCompensacao = document.getElementById('data_compensacao');
    const situacaoInput = document.getElementById('situacao');

    pagamentoQuitado.addEventListener('change', function() {
        if (this.value === '1') { // Sim
            dataCompensacao.disabled = false;
            dataCompensacao.classList.remove('bg-gray-100');
            situacaoInput.value = '2'; // PAGA
            if (!dataCompensacao.value) {
                dataCompensacao.value = new Date().toISOString().split('T')[0];
            }
        } else {
            dataCompensacao.disabled = true;
            dataCompensacao.classList.add('bg-gray-100');
            situacaoInput.value = '1'; // PENDENTE
        }
    });

    // Calcular Total automaticamente
    const valorInput = document.getElementById('valor');
    const jurosInput = document.getElementById('juros');
    const descontoInput = document.getElementById('desconto');
    const totalInput = document.getElementById('valor_total');
    const totalHiddenInput = document.getElementById('valor_total_hidden');

    function formatarMoeda(valor) {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(valor);
    }

    function calcularTotal() {
        const valor = parseFloat(valorInput.value) || 0;
        const juros = parseFloat(jurosInput.value) || 0;
        const desconto = parseFloat(descontoInput.value) || 0;

        const total = valor + juros - desconto;

        totalInput.value = formatarMoeda(total);
        totalHiddenInput.value = total.toFixed(2);
    }

    valorInput.addEventListener('input', calcularTotal);
    jurosInput.addEventListener('input', calcularTotal);
    descontoInput.addEventListener('input', calcularTotal);

    // Calcular total inicial
    calcularTotal();
});
</script>
@endsection
