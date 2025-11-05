@extends('layouts.app')

@section('content')
<div>
    <div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $titulo }}</h2>
                    <div class="flex space-x-3">
                        <a href="{{ route($tipo == 1 ? 'contas-a-pagar.index' : 'contas-a-receber.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Voltar
                        </a>
                    </div>
                </div>

                <form action="{{ route($tipo == 1 ? 'contas-a-pagar.store' : 'contas-a-receber.store') }}" method="POST" class="space-y-6" id="movimentacaoForm">
                    @csrf

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
                        <!-- Modo Normal (quando parcelamento está inativo) -->
                        <div id="modo-normal" class="space-y-6">
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
                                   value="{{ old('descricao') }}"
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
                                                   value="{{ old('vencimento') }}"
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
                                    <option value="{{ $planoConta->id }}" {{ old('plano_conta_id') == $planoConta->id ? 'selected' : '' }}>
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
                                    <option value="{{ $centroCusto->id }}" {{ old('centro_custo_id') == $centroCusto->id ? 'selected' : '' }}>
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
                                    <option value="{{ $formaPagamento->id }}" {{ old('forma_pagamento_id') == $formaPagamento->id ? 'selected' : '' }}>
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
                                    <option value="{{ $contaEmpresa->id }}" {{ old('conta_empresa_id') == $contaEmpresa->id ? 'selected' : '' }}>
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
                                                <option value="0" {{ old('pagamento_quitado', '0') == '0' ? 'selected' : '' }}>Não</option>
                                                <option value="1" {{ old('pagamento_quitado') == '1' ? 'selected' : '' }}>Sim</option>
                            </select>
                                            @error('pagamento_quitado')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                                            <label for="data_compensacao" class="block text-sm font-medium text-gray-700 mb-2">
                                                Data de Compensação
                            </label>
                            <input type="date"
                                                   name="data_compensacao"
                                                   id="data_compensacao"
                                                   value="{{ old('data_compensacao') }}"
                                                   disabled
                                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-100 @error('data_compensacao') border-red-500 @enderror">
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
                                   value="{{ old('valor') }}"
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
                                                       value="{{ old('juros', 0) }}"
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
                                                       value="{{ old('desconto', 0) }}"
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
                                                           value="1"
                                                           {{ old('ativar_parcelamento') ? 'checked' : '' }}
                                                           class="toggle-parcelamento sr-only peer">
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
                                                       value="R$ 0,00"
                                                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm bg-gray-200 font-semibold text-gray-900 text-center">
                                                <input type="hidden" name="valor_total" id="valor_total_hidden" value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modo Parcelamento (quando parcelamento está ativo) -->
                        <div id="modo-parcelamento" class="hidden space-y-6">
                            <!-- Linha 1: Descrição, Plano de Contas, Centro de Custo -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="descricao_parcelamento" class="block text-sm font-medium text-gray-700 mb-2">
                                        Descrição <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           name="descricao"
                                           id="descricao_parcelamento"
                                           value="{{ old('descricao') }}"
                                           required
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('descricao') border-red-500 @enderror"
                                           placeholder="Digite a descrição">
                                    @error('descricao')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="plano_conta_id_parcelamento" class="block text-sm font-medium text-gray-700 mb-2">
                                        Plano de Contas <span class="text-red-500">*</span>
                                    </label>
                                    <select name="plano_conta_id"
                                            id="plano_conta_id_parcelamento"
                                            required
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('plano_conta_id') border-red-500 @enderror">
                                        <option value="">Selecione o plano de conta</option>
                                        @foreach($planoContas as $planoConta)
                                            <option value="{{ $planoConta->id }}" {{ old('plano_conta_id') == $planoConta->id ? 'selected' : '' }}>
                                                {{ $planoConta->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('plano_conta_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="centro_custo_id_parcelamento" class="block text-sm font-medium text-gray-700 mb-2">
                                        Centro de Custo
                                    </label>
                                    <select name="centro_custo_id"
                                            id="centro_custo_id_parcelamento"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('centro_custo_id') border-red-500 @enderror">
                                        <option value="">Selecione o centro de custo</option>
                                        @foreach($centroCustos as $centroCusto)
                                            <option value="{{ $centroCusto->id }}" {{ old('centro_custo_id') == $centroCusto->id ? 'selected' : '' }}>
                                                {{ $centroCusto->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('centro_custo_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Linha 2: Conta Bancária (obrigatório para parcelamento) -->
                            <div>
                                <label for="conta_empresa_id_parcelamento" class="block text-sm font-medium text-gray-700 mb-2">
                                    Conta Bancária <span class="text-red-500">*</span>
                                </label>
                                <select name="conta_empresa_id"
                                        id="conta_empresa_id_parcelamento"
                                        required
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('conta_empresa_id') border-red-500 @enderror">
                                    <option value="">Selecione a conta bancária</option>
                                    @foreach($contasEmpresa as $contaEmpresa)
                                        <option value="{{ $contaEmpresa->id }}" {{ old('conta_empresa_id') == $contaEmpresa->id ? 'selected' : '' }}>
                                            {{ $contaEmpresa->nome }} - {{ $contaEmpresa->banco->nome_normalizado ?? 'Banco não encontrado' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('conta_empresa_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Container Valores para Parcelamento -->
                            <div class="border border-gray-300 rounded-lg shadow-sm overflow-hidden">
                                <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 rounded-t-lg">
                                    <h3 class="text-sm font-semibold text-gray-900">Valores</h3>
                                </div>

                                <div class="p-4 space-y-4">
                                    <!-- Linha 1: Toggle Ativar Parcelamento/Recorrência -->
                                    <div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox"
                                                   name="ativar_parcelamento"
                                                   value="1"
                                                   checked
                                                   class="toggle-parcelamento sr-only peer">
                                            <div class="w-11 h-6 bg-blue-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                            <span class="ml-3 text-sm font-medium text-gray-700">Ativar Parcelamento/Recorrência</span>
                                        </label>
                                    </div>

                                    <!-- Linha 2: Valor Bruto, Juros, Desconto -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label for="valor_parcelamento" class="block text-sm font-medium text-gray-700 mb-2">
                                                Valor Bruto <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number"
                                                   name="valor"
                                                   id="valor_parcelamento"
                                                   value="{{ old('valor') }}"
                                                   step="0.01"
                                                   min="0.01"
                                                   required
                                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('valor') border-red-500 @enderror"
                                                   placeholder="0,00">
                                            @error('valor')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="juros_parcelamento" class="block text-sm font-medium text-gray-700 mb-2">
                                                Juros
                                            </label>
                                            <input type="number"
                                                   name="juros"
                                                   id="juros_parcelamento"
                                                   value="{{ old('juros', 0) }}"
                                                   step="0.01"
                                                   min="0"
                                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('juros') border-red-500 @enderror"
                                                   placeholder="0,00">
                                            @error('juros')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="desconto_parcelamento" class="block text-sm font-medium text-gray-700 mb-2">
                                                Desconto
                                            </label>
                                            <input type="number"
                                                   name="desconto"
                                                   id="desconto_parcelamento"
                                                   value="{{ old('desconto', 0) }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('desconto') border-red-500 @enderror"
                                   placeholder="0,00">
                            @error('desconto')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                                    <!-- Linha 3: Tabela de Configuração de Parcelas -->
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo de parcela</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Repetição</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data 1ª parcela</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ação</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <tr>
                                                    <td class="px-4 py-3 whitespace-nowrap">
                                                        <select id="tipo_parcela" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                            <option value="dividir">Dividir o valor do lançamento entre as parcelas</option>
                                                            <option value="multiplicar">Multiplicar o valor do lançamento pelas parcelas</option>
                                                        </select>
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap">
                                                        <select id="repeticao" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                            <option value="">Selecione</option>
                                                            <option value="quinzenal">Quinzenal</option>
                                                            <option value="mensal">Mensal</option>
                                                            <option value="trimestral">Trimestral</option>
                                                            <option value="semestral">Semestral</option>
                                                            <option value="anual">Anual</option>
                                                            <option value="intervalo">Intervalo</option>
                                                        </select>
                                                        <div id="intervalo_dias_container" class="mt-2 hidden">
                                                            <input type="number"
                                                                   id="intervalo_dias"
                                                                   min="1"
                                                                   placeholder="Dias"
                                                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap">
                                                        <input type="number"
                                                               id="quantidade_parcelas"
                                                               min="1"
                                                               placeholder="Quantidade"
                                                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap">
                                                        <input type="date"
                                                               id="data_primeira_parcela"
                                                               value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap">
                                                        <button type="button"
                                                                id="btn_gerar_parcelas"
                                                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                                            Gerar
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Linha 4: Tabela de Parcelas Geradas -->
                                    <div id="tabela_parcelas_container" class="hidden">
                                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Parcelas Geradas</h4>
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Forma de pagamento</th>
                                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pago</th>
                                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Observação</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="parcelas_tbody" class="bg-white divide-y divide-gray-200">
                                                    <!-- Parcelas serão inseridas aqui via JavaScript -->
                                                </tbody>
                                            </table>
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
                                    <option value="1" {{ old('entidade_tipo') == '1' ? 'selected' : '' }}>Cliente</option>
                                    <option value="2" {{ old('entidade_tipo') == '2' ? 'selected' : '' }}>Fornecedor</option>
                                    <option value="3" {{ old('entidade_tipo') == '3' ? 'selected' : '' }}>Funcionário</option>
                                    <option value="4" {{ old('entidade_tipo') == '4' ? 'selected' : '' }}>Transportadora</option>
                                </select>
                                @error('entidade_tipo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div id="entidade_select_container" class="hidden">
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
                                  placeholder="Digite informações complementares (opcional)">{{ old('informacao_complementar') }}</textarea>
                        @error('informacao_complementar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route($tipo == 1 ? 'contas-a-pagar.index' : 'contas-a-receber.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md text-sm font-medium">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-save mr-2"></i>
                            Salvar {{ $titulo }}
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

    // Formas de pagamento - declarar no início para estar disponível em todo o escopo
    const formasPagamento = @json($formasPagamento);

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

    entidadeTipoSelect.addEventListener('change', function() {
        const tipo = this.value;
        const entidadeSelectDiv = entidadeSelectContainer.querySelector('div') || entidadeSelectContainer;

        if (tipo && entidades[tipo]) {
            // Limpar select
            entidadeSelect.innerHTML = '<option value="">Selecione a entidade</option>';

            // Preencher com entidades do tipo selecionado
            entidades[tipo].forEach(entidade => {
                const option = document.createElement('option');
                option.value = entidade.id;
                option.textContent = entidade.nome || entidade.nome_fantasia || entidade.razao_social;
                entidadeSelect.appendChild(option);
            });

            // Mostrar container
            entidadeSelectContainer.classList.remove('hidden');
        } else {
            // Ocultar container
            entidadeSelectContainer.classList.add('hidden');
            entidadeSelect.innerHTML = '<option value="">Selecione a entidade</option>';
        }
    });

    // Carregar entidade se já existir no old
    @if(old('entidade_tipo'))
        entidadeTipoSelect.value = '{{ old('entidade_tipo') }}';
        entidadeTipoSelect.dispatchEvent(new Event('change'));
        @if(old('entidade_id'))
            setTimeout(() => {
                entidadeSelect.value = '{{ old('entidade_id') }}';
            }, 100);
        @endif
    @endif

    // Pagamento Quitado - habilitar/desabilitar Data de Compensação quando for "Sim"
    const pagamentoQuitado = document.getElementById('pagamento_quitado');
    const dataCompensacao = document.getElementById('data_compensacao');

    pagamentoQuitado.addEventListener('change', function() {
        if (this.value === '1') { // Sim
            dataCompensacao.disabled = false;
            dataCompensacao.classList.remove('bg-gray-100');
            if (!dataCompensacao.value) {
                dataCompensacao.value = new Date().toISOString().split('T')[0];
            }
        } else {
            dataCompensacao.disabled = true;
            dataCompensacao.classList.add('bg-gray-100');
        }
    });

    // Verificar valor inicial
    if (pagamentoQuitado.value === '1') {
        dataCompensacao.disabled = false;
        dataCompensacao.classList.remove('bg-gray-100');
    }

    // Toggle Parcelamento/Recorrência - Controlar visibilidade dos modos
    const togglesParcelamento = document.querySelectorAll('.toggle-parcelamento');
    const modoNormal = document.getElementById('modo-normal');
    const modoParcelamento = document.getElementById('modo-parcelamento');

    // Pegar o primeiro toggle (modo normal) e o segundo (modo parcelamento) para referência
    const toggleParcelamento = togglesParcelamento[0] || null;
    const toggleParcelamentoParcelamento = togglesParcelamento[1] || null;

    // Flag para evitar loop de eventos
    let sincronizandoToggle = false;

    function alternarModo(isAtivo) {
        if (isAtivo) {
            modoNormal.classList.add('hidden');
            modoParcelamento.classList.remove('hidden');

            // Sincronizar todos os toggles
            sincronizandoToggle = true;
            togglesParcelamento.forEach(toggle => {
                toggle.checked = true;
            });
            sincronizandoToggle = false;

            // Remover atributo name dos campos do modo normal para não serem enviados
            // NÃO desabilitar os campos valor, juros e desconto pois eles são compartilhados
            const camposModoNormalParaDesabilitar = [
                'descricao', 'plano_conta_id', 'centro_custo_id',
                'vencimento', 'forma_pagamento_id', 'conta_empresa_id', 'pagamento_quitado', 'data_compensacao'
            ];

            camposModoNormalParaDesabilitar.forEach(campoId => {
                const campo = document.getElementById(campoId);
                if (campo) {
                    campo.dataset.originalName = campo.name;
                    campo.removeAttribute('name');
                    campo.disabled = true;
                }
            });

            // Remover atributo name dos campos valor, juros, desconto mas NÃO desabilitar
            const camposValoresCompartilhados = ['valor', 'juros', 'desconto'];
            camposValoresCompartilhados.forEach(campoId => {
                const campo = document.getElementById(campoId);
                if (campo) {
                    campo.dataset.originalName = campo.name;
                    campo.removeAttribute('name');
                    // NÃO desabilitar - campos permanecem editáveis
                }
            });

            // Sincronizar valores dos campos
            sincronizarCamposParaParcelamento();

            // Anotar listener do botão gerar parcelas quando o modo parcelamento for ativado
            setTimeout(() => {
                anexarListenerGerarParcelas();
            }, 100);
        } else {
            modoNormal.classList.remove('hidden');
            modoParcelamento.classList.add('hidden');

            // Sincronizar todos os toggles
            sincronizandoToggle = true;
            togglesParcelamento.forEach(toggle => {
                toggle.checked = false;
            });
            sincronizandoToggle = false;

            // Restaurar atributo name dos campos do modo normal
            const camposModoNormalParaRestaurar = [
                'descricao', 'plano_conta_id', 'centro_custo_id',
                'vencimento', 'forma_pagamento_id', 'conta_empresa_id', 'pagamento_quitado', 'data_compensacao'
            ];

            camposModoNormalParaRestaurar.forEach(campoId => {
                const campo = document.getElementById(campoId);
                if (campo) {
                    if (campo.dataset.originalName) {
                        campo.name = campo.dataset.originalName;
                        delete campo.dataset.originalName;
                    }
                    // Restaurar required se necessário
                    if (campoId === 'vencimento' || campoId === 'forma_pagamento_id' || campoId === 'conta_empresa_id') {
                        campo.required = true;
                    }
                    campo.disabled = false;
                }
            });

            // Restaurar atributo name dos campos valor, juros, desconto
            const camposValoresCompartilhados = ['valor', 'juros', 'desconto'];
            camposValoresCompartilhados.forEach(campoId => {
                const campo = document.getElementById(campoId);
                if (campo && campo.dataset.originalName) {
                    campo.name = campo.dataset.originalName;
                    delete campo.dataset.originalName;
                    // Campos já estão habilitados (não foram desabilitados)
                }
            });

            // Remover atributo name dos campos do modo parcelamento
            // NÃO desabilitar os campos valor_parcelamento, juros_parcelamento, desconto_parcelamento
            const camposModoParcelamentoParaDesabilitar = [
                'descricao_parcelamento', 'plano_conta_id_parcelamento', 'centro_custo_id_parcelamento',
                'conta_empresa_id_parcelamento'
            ];

            camposModoParcelamentoParaDesabilitar.forEach(campoId => {
                const campo = document.getElementById(campoId);
                if (campo) {
                    campo.dataset.originalName = campo.name;
                    campo.removeAttribute('name');
                    campo.disabled = true;
                }
            });

            // Remover atributo name dos campos valor, juros, desconto do modo parcelamento mas NÃO desabilitar
            const camposValoresParcelamento = ['valor_parcelamento', 'juros_parcelamento', 'desconto_parcelamento'];
            camposValoresParcelamento.forEach(campoId => {
                const campo = document.getElementById(campoId);
                if (campo) {
                    campo.dataset.originalName = campo.name;
                    campo.removeAttribute('name');
                    // NÃO desabilitar - campos permanecem editáveis
                }
            });

            // Limpar tabela de parcelas geradas se existir
            const parcelasTbody = document.getElementById('parcelas_tbody');
            const tabelaParcelasContainer = document.getElementById('tabela_parcelas_container');

            if (parcelasTbody) {
                parcelasTbody.innerHTML = '';
            }
            if (tabelaParcelasContainer) {
                tabelaParcelasContainer.classList.add('hidden');
            }

            // Sincronizar valores dos campos de volta
            sincronizarCamposParaNormal();
        }
    }

    function sincronizarCamposParaParcelamento() {
        const descricao = document.getElementById('descricao').value;
        const planoContaId = document.getElementById('plano_conta_id').value;
        const centroCustoId = document.getElementById('centro_custo_id').value;
        const contaEmpresaId = document.getElementById('conta_empresa_id').value;
        const valor = document.getElementById('valor').value;
        const juros = document.getElementById('juros').value;
        const desconto = document.getElementById('desconto').value;

        document.getElementById('descricao_parcelamento').value = descricao;
        document.getElementById('plano_conta_id_parcelamento').value = planoContaId;
        document.getElementById('centro_custo_id_parcelamento').value = centroCustoId;
        document.getElementById('conta_empresa_id_parcelamento').value = contaEmpresaId;
        document.getElementById('valor_parcelamento').value = valor;
        document.getElementById('juros_parcelamento').value = juros;
        document.getElementById('desconto_parcelamento').value = desconto;
    }

    function sincronizarCamposParaNormal() {
        const descricao = document.getElementById('descricao_parcelamento').value;
        const planoContaId = document.getElementById('plano_conta_id_parcelamento').value;
        const centroCustoId = document.getElementById('centro_custo_id_parcelamento').value;
        const contaEmpresaId = document.getElementById('conta_empresa_id_parcelamento').value;
        const valor = document.getElementById('valor_parcelamento').value;
        const juros = document.getElementById('juros_parcelamento').value;
        const desconto = document.getElementById('desconto_parcelamento').value;

        document.getElementById('descricao').value = descricao;
        document.getElementById('plano_conta_id').value = planoContaId;
        document.getElementById('centro_custo_id').value = centroCustoId;
        document.getElementById('conta_empresa_id').value = contaEmpresaId;
        document.getElementById('valor').value = valor;
        document.getElementById('juros').value = juros;
        document.getElementById('desconto').value = desconto;
        calcularTotal();
    }

    // Event listener para o toggle principal (modo normal)
    if (toggleParcelamento) {
        toggleParcelamento.addEventListener('change', function() {
            // Ignorar se estiver sincronizando
            if (sincronizandoToggle) {
                return;
            }

            // Sincronizar o toggle do modo parcelamento
            sincronizandoToggle = true;
            if (toggleParcelamentoParcelamento) {
                toggleParcelamentoParcelamento.checked = this.checked;
            }
            sincronizandoToggle = false;

            if (!this.checked) {
                // Se desativar o toggle principal, limpar todos os campos e voltar ao estado inicial
                limparCamposParaEstadoInicial();
            }

            // Alternar o modo baseado no estado do toggle
            alternarModo(this.checked);
        });

        // Verificar estado inicial
        alternarModo(toggleParcelamento.checked);
    }

    // Event listener para o toggle do modo parcelamento
    if (toggleParcelamentoParcelamento) {
        toggleParcelamentoParcelamento.addEventListener('change', function() {
            // Ignorar se estiver sincronizando
            if (sincronizandoToggle) {
                return;
            }

            // Sincronizar o toggle principal
            sincronizandoToggle = true;
            if (toggleParcelamento) {
                toggleParcelamento.checked = this.checked;
            }
            sincronizandoToggle = false;

            if (!this.checked) {
                // Se desativar o toggle do modo parcelamento, limpar todos os campos e voltar ao estado inicial
                limparCamposParaEstadoInicial();
            }

            // Alternar o modo baseado no estado do toggle
            alternarModo(this.checked);
        });
    }

    // Função para limpar campos e voltar ao estado inicial
    function limparCamposParaEstadoInicial() {
        // Limpar campos do modo normal
        const camposModoNormal = [
            'descricao', 'vencimento', 'plano_conta_id', 'centro_custo_id',
            'forma_pagamento_id', 'conta_empresa_id', 'pagamento_quitado', 'data_compensacao'
        ];

        camposModoNormal.forEach(campoId => {
            const campo = document.getElementById(campoId);
            if (campo) {
                if (campo.tagName === 'SELECT') {
                    campo.value = '';
                } else if (campo.type === 'checkbox') {
                    campo.checked = false;
                } else {
                    campo.value = '';
                }
            }
        });

        // Limpar campos de valores (valor, juros, desconto)
        const camposValores = ['valor', 'juros', 'desconto'];
        camposValores.forEach(campoId => {
            const campo = document.getElementById(campoId);
            if (campo) {
                campo.value = '';
            }
        });

        // Limpar campos do modo parcelamento
        const camposModoParcelamento = [
            'descricao_parcelamento', 'plano_conta_id_parcelamento', 'centro_custo_id_parcelamento',
            'conta_empresa_id_parcelamento', 'valor_parcelamento', 'juros_parcelamento', 'desconto_parcelamento',
            'tipo_parcela', 'repeticao', 'quantidade_parcelas', 'data_primeira_parcela', 'intervalo_dias'
        ];

        camposModoParcelamento.forEach(campoId => {
            const campo = document.getElementById(campoId);
            if (campo) {
                if (campo.tagName === 'SELECT') {
                    campo.value = '';
                } else {
                    campo.value = '';
                }
            }
        });

        // Limpar tabela de parcelas geradas
        const parcelasTbody = document.getElementById('parcelas_tbody');
        const tabelaParcelasContainer = document.getElementById('tabela_parcelas_container');

        if (parcelasTbody) {
            parcelasTbody.innerHTML = '';
        }
        if (tabelaParcelasContainer) {
            tabelaParcelasContainer.classList.add('hidden');
        }

        // Resetar o total
        const totalInput = document.getElementById('valor_total');
        const totalHiddenInput = document.getElementById('valor_total_hidden');
        if (totalInput) {
            totalInput.value = 'R$ 0,00';
        }
        if (totalHiddenInput) {
            totalHiddenInput.value = '0.00';
        }

        // Resetar campo intervalo_dias
        const intervaloDiasContainer = document.getElementById('intervalo_dias_container');
        if (intervaloDiasContainer) {
            intervaloDiasContainer.classList.add('hidden');
        }

        // Restaurar data padrão para "Data 1ª parcela"
        const dataPrimeiraParcelaInput = document.getElementById('data_primeira_parcela');
        if (dataPrimeiraParcelaInput) {
            const hoje = new Date();
            const ano = hoje.getFullYear();
            const mes = String(hoje.getMonth() + 1).padStart(2, '0');
            const dia = String(hoje.getDate()).padStart(2, '0');
            dataPrimeiraParcelaInput.value = `${ano}-${mes}-${dia}`;
        }

        // Recalcular total
        calcularTotal();
    }

    // Definir data padrão para "Data 1ª parcela" como hoje (usando timezone local do navegador)
    const dataPrimeiraParcelaInput = document.getElementById('data_primeira_parcela');
    if (dataPrimeiraParcelaInput) {
        // Sempre definir a data de hoje usando o timezone local do navegador
        const hoje = new Date();
        const ano = hoje.getFullYear();
        const mes = String(hoje.getMonth() + 1).padStart(2, '0');
        const dia = String(hoje.getDate()).padStart(2, '0');
        dataPrimeiraParcelaInput.value = `${ano}-${mes}-${dia}`;
    }

    // Controlar campo intervalo_dias quando repetição for "intervalo"
    const repeticaoSelect = document.getElementById('repeticao');
    const intervaloDiasContainer = document.getElementById('intervalo_dias_container');

    if (repeticaoSelect && intervaloDiasContainer) {
        repeticaoSelect.addEventListener('change', function() {
            if (this.value === 'intervalo') {
                intervaloDiasContainer.classList.remove('hidden');
            } else {
                intervaloDiasContainer.classList.add('hidden');
            }
        });
    }

    // Calcular Total automaticamente (modo normal)
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

    if (valorInput && jurosInput && descontoInput) {
    valorInput.addEventListener('input', calcularTotal);
    jurosInput.addEventListener('input', calcularTotal);
    descontoInput.addEventListener('input', calcularTotal);
        calcularTotal();
    }

    // Gerar Parcelas
    function renderizarParcelas(parcelas) {
        // Buscar elementos diretamente quando a função for chamada
        const parcelasTbody = document.getElementById('parcelas_tbody');
        const tabelaParcelasContainer = document.getElementById('tabela_parcelas_container');

        if (!parcelasTbody || !tabelaParcelasContainer) {
            console.error('Elementos da tabela de parcelas não encontrados');
            return;
        }

        // Verificar se formasPagamento está disponível
        if (!formasPagamento || !Array.isArray(formasPagamento)) {
            console.error('Formas de pagamento não disponíveis');
            return;
        }

        parcelasTbody.innerHTML = '';

        parcelas.forEach((parcela, index) => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50';

            const dataTd = document.createElement('td');
            dataTd.className = 'px-4 py-3 whitespace-nowrap';
            const dataInput = document.createElement('input');
            dataInput.type = 'date';
            dataInput.name = `parcelas[${index}][data]`;
            dataInput.value = parcela.data;
            dataInput.className = 'w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500';
            dataTd.appendChild(dataInput);

            const valorTd = document.createElement('td');
            valorTd.className = 'px-4 py-3 whitespace-nowrap';
            const valorInput = document.createElement('input');
            valorInput.type = 'number';
            valorInput.step = '0.01';
            valorInput.min = '0.01';
            valorInput.name = `parcelas[${index}][valor]`;
            valorInput.value = parcela.valor;
            valorInput.className = 'w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500';
            valorTd.appendChild(valorInput);

            const formaPagamentoTd = document.createElement('td');
            formaPagamentoTd.className = 'px-4 py-3 whitespace-nowrap';
            const formaPagamentoSelect = document.createElement('select');
            formaPagamentoSelect.name = `parcelas[${index}][forma_pagamento_id]`;
            formaPagamentoSelect.className = 'w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500';
            formaPagamentoSelect.innerHTML = '<option value="">Selecione</option>';
            formasPagamento.forEach(forma => {
                const option = document.createElement('option');
                option.value = forma.id;
                option.textContent = forma.nome;
                if (parcela.forma_pagamento_id && parcela.forma_pagamento_id === forma.id) {
                    option.selected = true;
                }
                formaPagamentoSelect.appendChild(option);
            });
            formaPagamentoTd.appendChild(formaPagamentoSelect);

            const pagoTd = document.createElement('td');
            pagoTd.className = 'px-4 py-3 whitespace-nowrap';
            const pagoCheckbox = document.createElement('input');
            pagoCheckbox.type = 'checkbox';
            pagoCheckbox.name = `parcelas[${index}][pago]`;
            pagoCheckbox.value = '1';
            pagoCheckbox.checked = parcela.pago || false;
            pagoCheckbox.className = 'h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded';
            pagoTd.appendChild(pagoCheckbox);

            const observacaoTd = document.createElement('td');
            observacaoTd.className = 'px-4 py-3 whitespace-nowrap';
            const observacaoInput = document.createElement('input');
            observacaoInput.type = 'text';
            observacaoInput.name = `parcelas[${index}][observacao]`;
            observacaoInput.value = parcela.observacao || '';
            observacaoInput.className = 'w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500';
            observacaoInput.placeholder = 'Observação';
            observacaoTd.appendChild(observacaoInput);

            tr.appendChild(dataTd);
            tr.appendChild(valorTd);
            tr.appendChild(formaPagamentoTd);
            tr.appendChild(pagoTd);
            tr.appendChild(observacaoTd);

            parcelasTbody.appendChild(tr);
        });

        tabelaParcelasContainer.classList.remove('hidden');
    }

    function anexarListenerGerarParcelas() {
        const btnGerarParcelas = document.getElementById('btn_gerar_parcelas');
        if (btnGerarParcelas && !btnGerarParcelas.hasAttribute('data-listener-anexado')) {
            btnGerarParcelas.setAttribute('data-listener-anexado', 'true');
            btnGerarParcelas.addEventListener('click', async function(e) {
                e.preventDefault();
                e.stopPropagation();

                const valor = parseFloat(document.getElementById('valor_parcelamento').value) || 0;
                const juros = parseFloat(document.getElementById('juros_parcelamento').value) || 0;
                const desconto = parseFloat(document.getElementById('desconto_parcelamento').value) || 0;
                const tipoParcela = document.getElementById('tipo_parcela').value;
                const repeticao = document.getElementById('repeticao').value;
                const quantidade = parseInt(document.getElementById('quantidade_parcelas').value) || 0;
                const dataPrimeiraParcela = document.getElementById('data_primeira_parcela').value;
                const intervaloDias = document.getElementById('intervalo_dias').value;

                // Validações
                if (!valor || valor <= 0) {
                    alert('Por favor, informe o Valor Bruto.');
                    return;
                }

                if (!repeticao) {
                    alert('Por favor, selecione a Repetição.');
                    return;
                }

                if (repeticao === 'intervalo' && (!intervaloDias || intervaloDias <= 0)) {
                    alert('Por favor, informe o intervalo em dias.');
                    return;
                }

                if (!quantidade || quantidade <= 0) {
                    alert('Por favor, informe a Quantidade de parcelas.');
                    return;
                }

                if (!dataPrimeiraParcela) {
                    alert('Por favor, informe a Data da 1ª parcela.');
                    return;
                }

                try {
                    btnGerarParcelas.disabled = true;
                    btnGerarParcelas.textContent = 'Gerando...';

                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        throw new Error('Token CSRF não encontrado');
                    }

                    const response = await fetch('{{ route("movimentacao.gerar-parcelas") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken.content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            valor: valor,
                            juros: juros,
                            desconto: desconto,
                            tipo_parcela: tipoParcela,
                            repeticao: repeticao,
                            quantidade: quantidade,
                            data_primeira_parcela: dataPrimeiraParcela,
                            intervalo_dias: repeticao === 'intervalo' ? parseInt(intervaloDias) : null
                        })
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        throw new Error(result.message || 'Erro ao gerar parcelas');
                    }

                    // Renderizar tabela de parcelas
                    renderizarParcelas(result.parcelas);

                } catch (error) {
                    console.error('Erro ao gerar parcelas:', error);
                    alert('Erro ao gerar parcelas: ' + error.message);
                } finally {
                    btnGerarParcelas.disabled = false;
                    btnGerarParcelas.textContent = 'Gerar';
                }
            });
        }
    }

    // Usar event delegation para garantir que o botão funcione
    document.addEventListener('click', function(e) {
        // Verificar se o clique foi no botão gerar parcelas ou em um elemento filho dele
        const btnGerarParcelas = e.target.id === 'btn_gerar_parcelas' ? e.target : e.target.closest('button[id="btn_gerar_parcelas"]');
        if (btnGerarParcelas) {
            e.preventDefault();
            e.stopPropagation();

            // Executar a função de gerar parcelas
            const valor = parseFloat(document.getElementById('valor_parcelamento').value) || 0;
            const juros = parseFloat(document.getElementById('juros_parcelamento').value) || 0;
            const desconto = parseFloat(document.getElementById('desconto_parcelamento').value) || 0;
            const tipoParcela = document.getElementById('tipo_parcela').value;
            const repeticao = document.getElementById('repeticao').value;
            const quantidade = parseInt(document.getElementById('quantidade_parcelas').value) || 0;
            const dataPrimeiraParcela = document.getElementById('data_primeira_parcela').value;
            const intervaloDias = document.getElementById('intervalo_dias').value;

            // Validações
            if (!valor || valor <= 0) {
                alert('Por favor, informe o Valor Bruto.');
                return;
            }

            if (!repeticao) {
                alert('Por favor, selecione a Repetição.');
                return;
            }

            if (repeticao === 'intervalo' && (!intervaloDias || intervaloDias <= 0)) {
                alert('Por favor, informe o intervalo em dias.');
                return;
            }

            if (!quantidade || quantidade <= 0) {
                alert('Por favor, informe a Quantidade de parcelas.');
                return;
            }

            if (!dataPrimeiraParcela) {
                alert('Por favor, informe a Data da 1ª parcela.');
                return;
            }

            (async function() {
                try {
                    btnGerarParcelas.disabled = true;
                    const textoOriginal = btnGerarParcelas.textContent;
                    btnGerarParcelas.textContent = 'Gerando...';

                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        throw new Error('Token CSRF não encontrado');
                    }

                    const response = await fetch('{{ route("movimentacao.gerar-parcelas") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken.content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            valor: valor,
                            juros: juros,
                            desconto: desconto,
                            tipo_parcela: tipoParcela,
                            repeticao: repeticao,
                            quantidade: quantidade,
                            data_primeira_parcela: dataPrimeiraParcela,
                            intervalo_dias: repeticao === 'intervalo' ? parseInt(intervaloDias) : null
                        })
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        throw new Error(result.message || 'Erro ao gerar parcelas');
                    }

                    // Renderizar tabela de parcelas
                    renderizarParcelas(result.parcelas);

                } catch (error) {
                    console.error('Erro ao gerar parcelas:', error);
                    alert('Erro ao gerar parcelas: ' + error.message);
                } finally {
                    btnGerarParcelas.disabled = false;
                    btnGerarParcelas.textContent = 'Gerar';
                }
            })();
        }
    });

    // Coletar dados das parcelas antes do submit
    const form = document.getElementById('movimentacaoForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Buscar elementos diretamente quando o formulário for submetido
            const parcelasTbody = document.getElementById('parcelas_tbody');

            // Se estiver no modo parcelamento e tiver parcelas geradas, garantir que os dados estão corretos
            if (toggleParcelamento && toggleParcelamento.checked && parcelasTbody && parcelasTbody.children.length > 0) {
                // Validar se todas as parcelas têm forma de pagamento
                let todasParcelasValidas = true;
                const parcelasRows = parcelasTbody.querySelectorAll('tr');

                parcelasRows.forEach((row, index) => {
                    const formaPagamentoSelect = row.querySelector('select[name^="parcelas"][name$="[forma_pagamento_id]"]');
                    if (!formaPagamentoSelect || !formaPagamentoSelect.value) {
                        todasParcelasValidas = false;
                    }
                });

                if (!todasParcelasValidas) {
                    e.preventDefault();
                    alert('Por favor, selecione a forma de pagamento para todas as parcelas.');
                    return false;
                }
            } else if (toggleParcelamento && toggleParcelamento.checked) {
                // Se estiver no modo parcelamento mas não tiver parcelas geradas
                e.preventDefault();
                alert('Por favor, gere as parcelas antes de salvar.');
                return false;
            }
        });
    }
});
</script>
@endsection
