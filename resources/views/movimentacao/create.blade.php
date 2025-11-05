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
                                            class="toggle-parcelamento sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
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
$(document).ready(function() {
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
    $('.tab-button').on('click', function() {
        const targetTab = $(this).data('tab');

        // Remover active de todos os botões
        $('.tab-button').removeClass('active border-blue-500 text-blue-600').addClass('border-transparent text-gray-500');

        // Adicionar active ao botão clicado
        $(this).addClass('active border-blue-500 text-blue-600').removeClass('border-transparent text-gray-500');

        // Ocultar todos os conteúdos
        $('.tab-content').addClass('hidden');

        // Mostrar conteúdo da tab selecionada
        $('#tab-' + targetTab).removeClass('hidden');
    });

    // Controle de select de entidades
    const $entidadeTipoSelect = $('#entidade_tipo');
    const $entidadeSelectContainer = $('#entidade_select_container');
    const $entidadeSelect = $('#entidade_id');

    $entidadeTipoSelect.on('change', function() {
        const tipo = $(this).val();

        if (tipo && entidades[tipo]) {
            // Limpar select
            $entidadeSelect.html('<option value="">Selecione a entidade</option>');

            // Preencher com entidades do tipo selecionado
            entidades[tipo].forEach(entidade => {
                const $option = $('<option></option>');
                $option.val(entidade.id);
                $option.text(entidade.nome || entidade.nome_fantasia || entidade.razao_social);
                $entidadeSelect.append($option);
            });

            // Mostrar container
            $entidadeSelectContainer.removeClass('hidden');
        } else {
            // Ocultar container
            $entidadeSelectContainer.addClass('hidden');
            $entidadeSelect.html('<option value="">Selecione a entidade</option>');
        }
    });

    // Carregar entidade se já existir no old
    @if(old('entidade_tipo'))
        $entidadeTipoSelect.val('{{ old('entidade_tipo') }}');
        $entidadeTipoSelect.trigger('change');
        @if(old('entidade_id'))
            setTimeout(() => {
                $entidadeSelect.val('{{ old('entidade_id') }}');
            }, 100);
        @endif
    @endif

    // Pagamento Quitado - habilitar/desabilitar Data de Compensação quando for "Sim"
    const $pagamentoQuitado = $('#pagamento_quitado');
    const $dataCompensacao = $('#data_compensacao');

    $pagamentoQuitado.on('change', function() {
        if ($(this).val() === '1') { // Sim
            $dataCompensacao.prop('disabled', false).removeClass('bg-gray-100');
            if (!$dataCompensacao.val()) {
                $dataCompensacao.val(new Date().toISOString().split('T')[0]);
            }
        } else {
            $dataCompensacao.prop('disabled', true).addClass('bg-gray-100');
        }
    });

    // Verificar valor inicial
    if ($pagamentoQuitado.val() === '1') {
        $dataCompensacao.prop('disabled', false).removeClass('bg-gray-100');
    }

    // Toggle Parcelamento/Recorrência - Controlar visibilidade dos modos
    const $togglesParcelamento = $('.toggle-parcelamento');
    const $modoNormal = $('#modo-normal');
    const $modoParcelamento = $('#modo-parcelamento');

    // Pegar o primeiro toggle (modo normal) e o segundo (modo parcelamento) para referência
    const $toggleParcelamento = $togglesParcelamento.eq(0);
    const $toggleParcelamentoParcelamento = $togglesParcelamento.eq(1);

    // Flag para evitar loop de eventos
    let sincronizandoToggle = false;

    function alternarModo(isAtivo) {
        if (isAtivo) {
            $modoNormal.addClass('hidden');
            $modoParcelamento.removeClass('hidden');

            // Sincronizar todos os toggles
            sincronizandoToggle = true;
            $togglesParcelamento.prop('checked', true);
            sincronizandoToggle = false;

            // Remover atributo name dos campos do modo normal para não serem enviados
            // NÃO desabilitar os campos valor, juros e desconto pois eles são compartilhados
            const camposModoNormalParaDesabilitar = [
                'descricao', 'plano_conta_id', 'centro_custo_id',
                'vencimento', 'forma_pagamento_id', 'conta_empresa_id', 'pagamento_quitado', 'data_compensacao'
            ];

            camposModoNormalParaDesabilitar.forEach(campoId => {
                const $campo = $('#' + campoId);
                if ($campo.length) {
                    $campo.data('originalName', $campo.attr('name'));
                    $campo.removeAttr('name');
                    $campo.prop('disabled', true);
                }
            });

            // Remover atributo name dos campos valor, juros, desconto mas NÃO desabilitar
            const camposValoresCompartilhados = ['valor', 'juros', 'desconto'];
            camposValoresCompartilhados.forEach(campoId => {
                const $campo = $('#' + campoId);
                if ($campo.length) {
                    $campo.data('originalName', $campo.attr('name'));
                    $campo.removeAttr('name');
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
            $modoNormal.removeClass('hidden');
            $modoParcelamento.addClass('hidden');

            // Sincronizar todos os toggles
            sincronizandoToggle = true;
            $togglesParcelamento.prop('checked', false);
            sincronizandoToggle = false;

            // Restaurar atributo name dos campos do modo normal
            const camposModoNormalParaRestaurar = [
                'descricao', 'plano_conta_id', 'centro_custo_id',
                'vencimento', 'forma_pagamento_id', 'conta_empresa_id', 'pagamento_quitado', 'data_compensacao'
            ];

            camposModoNormalParaRestaurar.forEach(campoId => {
                const $campo = $('#' + campoId);
                if ($campo.length) {
                    const originalName = $campo.data('originalName');
                    if (originalName) {
                        $campo.attr('name', originalName);
                        $campo.removeData('originalName');
                    }
                    // Restaurar required se necessário
                    if (campoId === 'vencimento' || campoId === 'forma_pagamento_id' || campoId === 'conta_empresa_id') {
                        $campo.prop('required', true);
                    }
                    $campo.prop('disabled', false);
                }
            });

            // Restaurar atributo name dos campos valor, juros, desconto
            const camposValoresCompartilhados = ['valor', 'juros', 'desconto'];
            camposValoresCompartilhados.forEach(campoId => {
                const $campo = $('#' + campoId);
                if ($campo.length && $campo.data('originalName')) {
                    $campo.attr('name', $campo.data('originalName'));
                    $campo.removeData('originalName');
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
                const $campo = $('#' + campoId);
                if ($campo.length) {
                    $campo.data('originalName', $campo.attr('name'));
                    $campo.removeAttr('name');
                    $campo.prop('disabled', true);
                }
            });

            // Remover atributo name dos campos valor, juros, desconto do modo parcelamento mas NÃO desabilitar
            const camposValoresParcelamento = ['valor_parcelamento', 'juros_parcelamento', 'desconto_parcelamento'];
            camposValoresParcelamento.forEach(campoId => {
                const $campo = $('#' + campoId);
                if ($campo.length) {
                    $campo.data('originalName', $campo.attr('name'));
                    $campo.removeAttr('name');
                    // NÃO desabilitar - campos permanecem editáveis
                }
            });

            // Limpar tabela de parcelas geradas se existir
            const $parcelasTbody = $('#parcelas_tbody');
            const $tabelaParcelasContainer = $('#tabela_parcelas_container');

            $parcelasTbody.html('');
            $tabelaParcelasContainer.addClass('hidden');

            // Sincronizar valores dos campos de volta
            sincronizarCamposParaNormal();
        }
    }

    function sincronizarCamposParaParcelamento() {
        const descricao = $('#descricao').val();
        const planoContaId = $('#plano_conta_id').val();
        const centroCustoId = $('#centro_custo_id').val();
        const contaEmpresaId = $('#conta_empresa_id').val();
        const valor = $('#valor').val();
        const juros = $('#juros').val();
        const desconto = $('#desconto').val();

        $('#descricao_parcelamento').val(descricao);
        $('#plano_conta_id_parcelamento').val(planoContaId);
        $('#centro_custo_id_parcelamento').val(centroCustoId);
        $('#conta_empresa_id_parcelamento').val(contaEmpresaId);
        $('#valor_parcelamento').val(valor);
        $('#juros_parcelamento').val(juros);
        $('#desconto_parcelamento').val(desconto);
    }

    function sincronizarCamposParaNormal() {
        const descricao = $('#descricao_parcelamento').val();
        const planoContaId = $('#plano_conta_id_parcelamento').val();
        const centroCustoId = $('#centro_custo_id_parcelamento').val();
        const contaEmpresaId = $('#conta_empresa_id_parcelamento').val();
        const valor = $('#valor_parcelamento').val();
        const juros = $('#juros_parcelamento').val();
        const desconto = $('#desconto_parcelamento').val();

        $('#descricao').val(descricao);
        $('#plano_conta_id').val(planoContaId);
        $('#centro_custo_id').val(centroCustoId);
        $('#conta_empresa_id').val(contaEmpresaId);
        $('#valor').val(valor);
        $('#juros').val(juros);
        $('#desconto').val(desconto);
        calcularTotal();
    }

    // Event listener para o toggle principal (modo normal)
    console.log('toggleParcelamentoParcelamento length', $toggleParcelamentoParcelamento.length);
    if ($toggleParcelamento.length) {
        $toggleParcelamento.on('change', function() {
            console.log('toggleParcelamento changed');
            // Ignorar se estiver sincronizando
            if (sincronizandoToggle) {
                return;
            }

            const isChecked = $(this).prop('checked');

            // Sincronizar o toggle do modo parcelamento
            sincronizandoToggle = true;
            if ($toggleParcelamentoParcelamento.length) {
                $toggleParcelamentoParcelamento.prop('checked', isChecked);
            }
            sincronizandoToggle = false;

            // Alternar o modo baseado no estado do toggle PRIMEIRO
            alternarModo(isChecked);

            if (!isChecked) {
                // Se desativar o toggle principal, limpar todos os campos e voltar ao estado inicial
                limparCamposParaEstadoInicial();
            }
        });

        // Verificar estado inicial
        alternarModo($toggleParcelamento.prop('checked'));
    }

    // Event listener para o toggle do modo parcelamento
    console.log('toggleParcelamentoParcelamento length', $toggleParcelamentoParcelamento.length);
    if ($toggleParcelamentoParcelamento.length) {
        $toggleParcelamentoParcelamento.on('change', function(e) {
            console.log('toggleParcelamentoParcelamento changed');
            // Ignorar se estiver sincronizando
            if (sincronizandoToggle) {
                return;
            }

            // Obter o estado atual do toggle ANTES de qualquer mudança
            const isChecked = $(this).prop('checked');

            // Se estiver desativando, garantir que vai voltar ao modo normal
            if (!isChecked) {
                // Primeiro, sincronizar o toggle principal
                sincronizandoToggle = true;
                if ($toggleParcelamento.length) {
                    $toggleParcelamento.prop('checked', false);
                }
                sincronizandoToggle = false;

                // Alternar para modo normal
                alternarModo(false);

                // Limpar todos os campos e voltar ao estado inicial
                limparCamposParaEstadoInicial();
            } else {
                // Se estiver ativando, sincronizar e alternar para modo parcelamento
                sincronizandoToggle = true;
                if ($toggleParcelamento.length) {
                    $toggleParcelamento.prop('checked', true);
                }
                sincronizandoToggle = false;

                // Alternar para modo parcelamento
                alternarModo(true);
            }
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
            const $campo = $('#' + campoId);
            if ($campo.length) {
                if ($campo.is('select')) {
                    $campo.val('');
                } else if ($campo.is(':checkbox')) {
                    $campo.prop('checked', false);
                } else {
                    $campo.val('');
                }
            }
        });

        // Limpar campos de valores (valor, juros, desconto)
        const camposValores = ['valor', 'juros', 'desconto'];
        camposValores.forEach(campoId => {
            $('#' + campoId).val('');
        });

        // Limpar campos do modo parcelamento
        const camposModoParcelamento = [
            'descricao_parcelamento', 'plano_conta_id_parcelamento', 'centro_custo_id_parcelamento',
            'conta_empresa_id_parcelamento', 'valor_parcelamento', 'juros_parcelamento', 'desconto_parcelamento',
            'tipo_parcela', 'repeticao', 'quantidade_parcelas', 'data_primeira_parcela', 'intervalo_dias'
        ];

        camposModoParcelamento.forEach(campoId => {
            const $campo = $('#' + campoId);
            if ($campo.length) {
                if ($campo.is('select')) {
                    $campo.val('');
                } else {
                    $campo.val('');
                }
            }
        });

        // Limpar tabela de parcelas geradas
        $('#parcelas_tbody').html('');
        $('#tabela_parcelas_container').addClass('hidden');

        // Resetar o total
        $('#valor_total').val('R$ 0,00');
        $('#valor_total_hidden').val('0.00');

        // Resetar campo intervalo_dias
        $('#intervalo_dias_container').addClass('hidden');

        // Restaurar data padrão para "Data 1ª parcela"
        const hoje = new Date();
        const ano = hoje.getFullYear();
        const mes = String(hoje.getMonth() + 1).padStart(2, '0');
        const dia = String(hoje.getDate()).padStart(2, '0');
        $('#data_primeira_parcela').val(`${ano}-${mes}-${dia}`);

        // Recalcular total
        calcularTotal();
    }

    // Definir data padrão para "Data 1ª parcela" como hoje (usando timezone local do navegador)
    const $dataPrimeiraParcelaInput = $('#data_primeira_parcela');
    if ($dataPrimeiraParcelaInput.length) {
        // Sempre definir a data de hoje usando o timezone local do navegador
        const hoje = new Date();
        const ano = hoje.getFullYear();
        const mes = String(hoje.getMonth() + 1).padStart(2, '0');
        const dia = String(hoje.getDate()).padStart(2, '0');
        $dataPrimeiraParcelaInput.val(`${ano}-${mes}-${dia}`);
    }

    // Controlar campo intervalo_dias quando repetição for "intervalo"
    const $repeticaoSelect = $('#repeticao');
    const $intervaloDiasContainer = $('#intervalo_dias_container');

    if ($repeticaoSelect.length && $intervaloDiasContainer.length) {
        $repeticaoSelect.on('change', function() {
            if ($(this).val() === 'intervalo') {
                $intervaloDiasContainer.removeClass('hidden');
            } else {
                $intervaloDiasContainer.addClass('hidden');
            }
        });
    }

    // Calcular Total automaticamente (modo normal)
    const $valorInput = $('#valor');
    const $jurosInput = $('#juros');
    const $descontoInput = $('#desconto');
    const $totalInput = $('#valor_total');
    const $totalHiddenInput = $('#valor_total_hidden');

    function formatarMoeda(valor) {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(valor);
    }

    function calcularTotal() {
        const valor = parseFloat($valorInput.val()) || 0;
        const juros = parseFloat($jurosInput.val()) || 0;
        const desconto = parseFloat($descontoInput.val()) || 0;

        const total = valor + juros - desconto;

        $totalInput.val(formatarMoeda(total));
        $totalHiddenInput.val(total.toFixed(2));
    }

    if ($valorInput.length && $jurosInput.length && $descontoInput.length) {
        $valorInput.on('input', calcularTotal);
        $jurosInput.on('input', calcularTotal);
        $descontoInput.on('input', calcularTotal);
        calcularTotal();
    }

    // Gerar Parcelas
    function renderizarParcelas(parcelas) {
        // Buscar elementos diretamente quando a função for chamada
        const $parcelasTbody = $('#parcelas_tbody');
        const $tabelaParcelasContainer = $('#tabela_parcelas_container');

        if (!$parcelasTbody.length || !$tabelaParcelasContainer.length) {
            console.error('Elementos da tabela de parcelas não encontrados');
            return;
        }

        // Verificar se formasPagamento está disponível
        if (!formasPagamento || !Array.isArray(formasPagamento)) {
            console.error('Formas de pagamento não disponíveis');
            return;
        }

        $parcelasTbody.html('');

        parcelas.forEach((parcela, index) => {
            const $tr = $('<tr></tr>').addClass('hover:bg-gray-50');

            const $dataTd = $('<td></td>').addClass('px-4 py-3 whitespace-nowrap');
            const $dataInput = $('<input>').attr({
                type: 'date',
                name: `parcelas[${index}][data]`
            }).val(parcela.data).addClass('w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500');
            $dataTd.append($dataInput);

            const $valorTd = $('<td></td>').addClass('px-4 py-3 whitespace-nowrap');
            const $valorInput = $('<input>').attr({
                type: 'number',
                step: '0.01',
                min: '0.01',
                name: `parcelas[${index}][valor]`
            }).val(parcela.valor).addClass('w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500');
            $valorTd.append($valorInput);

            const $formaPagamentoTd = $('<td></td>').addClass('px-4 py-3 whitespace-nowrap');
            const $formaPagamentoSelect = $('<select></select>').attr({
                name: `parcelas[${index}][forma_pagamento_id]`
            }).addClass('w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500');
            $formaPagamentoSelect.html('<option value="">Selecione</option>');
            formasPagamento.forEach(forma => {
                const $option = $('<option></option>').val(forma.id).text(forma.nome);
                if (parcela.forma_pagamento_id && parcela.forma_pagamento_id === forma.id) {
                    $option.prop('selected', true);
                }
                $formaPagamentoSelect.append($option);
            });
            $formaPagamentoTd.append($formaPagamentoSelect);

            const $pagoTd = $('<td></td>').addClass('px-4 py-3 whitespace-nowrap');
            const $pagoCheckbox = $('<input>').attr({
                type: 'checkbox',
                name: `parcelas[${index}][pago]`,
                value: '1'
            }).prop('checked', parcela.pago || false).addClass('h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded');
            $pagoTd.append($pagoCheckbox);

            const $observacaoTd = $('<td></td>').addClass('px-4 py-3 whitespace-nowrap');
            const $observacaoInput = $('<input>').attr({
                type: 'text',
                name: `parcelas[${index}][observacao]`,
                placeholder: 'Observação'
            }).val(parcela.observacao || '').addClass('w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500');
            $observacaoTd.append($observacaoInput);

            $tr.append($dataTd).append($valorTd).append($formaPagamentoTd).append($pagoTd).append($observacaoTd);
            $parcelasTbody.append($tr);
        });

        $tabelaParcelasContainer.removeClass('hidden');
    }

    function anexarListenerGerarParcelas() {
        const $btnGerarParcelas = $('#btn_gerar_parcelas');
        if ($btnGerarParcelas.length && !$btnGerarParcelas.data('listener-anexado')) {
            $btnGerarParcelas.data('listener-anexado', true);
            $btnGerarParcelas.on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const valor = parseFloat($('#valor_parcelamento').val()) || 0;
                const juros = parseFloat($('#juros_parcelamento').val()) || 0;
                const desconto = parseFloat($('#desconto_parcelamento').val()) || 0;
                const tipoParcela = $('#tipo_parcela').val();
                const repeticao = $('#repeticao').val();
                const quantidade = parseInt($('#quantidade_parcelas').val()) || 0;
                const dataPrimeiraParcela = $('#data_primeira_parcela').val();
                const intervaloDias = $('#intervalo_dias').val();

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

                $btnGerarParcelas.prop('disabled', true).text('Gerando...');

                $.ajax({
                    url: '{{ route("movimentacao.gerar-parcelas") }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    contentType: 'application/json',
                    data: JSON.stringify({
                        valor: valor,
                        juros: juros,
                        desconto: desconto,
                        tipo_parcela: tipoParcela,
                        repeticao: repeticao,
                        quantidade: quantidade,
                        data_primeira_parcela: dataPrimeiraParcela,
                        intervalo_dias: repeticao === 'intervalo' ? parseInt(intervaloDias) : null
                    }),
                    success: function(result) {
                        // Renderizar tabela de parcelas
                        renderizarParcelas(result.parcelas);
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON && xhr.responseJSON.message
                            ? xhr.responseJSON.message
                            : 'Erro ao gerar parcelas';
                        console.error('Erro ao gerar parcelas:', errorMsg);
                        alert('Erro ao gerar parcelas: ' + errorMsg);
                    },
                    complete: function() {
                        $btnGerarParcelas.prop('disabled', false).text('Gerar');
                    }
                });
            });
        }
    }

    // Usar event delegation para garantir que o botão funcione
    $(document).on('click', '#btn_gerar_parcelas', function(e) {
        e.preventDefault();
        e.stopPropagation();

        // Executar a função de gerar parcelas
        const valor = parseFloat($('#valor_parcelamento').val()) || 0;
        const juros = parseFloat($('#juros_parcelamento').val()) || 0;
        const desconto = parseFloat($('#desconto_parcelamento').val()) || 0;
        const tipoParcela = $('#tipo_parcela').val();
        const repeticao = $('#repeticao').val();
        const quantidade = parseInt($('#quantidade_parcelas').val()) || 0;
        const dataPrimeiraParcela = $('#data_primeira_parcela').val();
        const intervaloDias = $('#intervalo_dias').val();

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

        const $btnGerarParcelas = $(this);
        $btnGerarParcelas.prop('disabled', true).text('Gerando...');

        $.ajax({
            url: '{{ route("movimentacao.gerar-parcelas") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            contentType: 'application/json',
            data: JSON.stringify({
                valor: valor,
                juros: juros,
                desconto: desconto,
                tipo_parcela: tipoParcela,
                repeticao: repeticao,
                quantidade: quantidade,
                data_primeira_parcela: dataPrimeiraParcela,
                intervalo_dias: repeticao === 'intervalo' ? parseInt(intervaloDias) : null
            }),
            success: function(result) {
                // Renderizar tabela de parcelas
                renderizarParcelas(result.parcelas);
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : 'Erro ao gerar parcelas';
                console.error('Erro ao gerar parcelas:', errorMsg);
                alert('Erro ao gerar parcelas: ' + errorMsg);
            },
            complete: function() {
                $btnGerarParcelas.prop('disabled', false).text('Gerar');
            }
        });
    });

    // Coletar dados das parcelas antes do submit
    $('#movimentacaoForm').on('submit', function(e) {
        // Buscar elementos diretamente quando o formulário for submetido
        const $parcelasTbody = $('#parcelas_tbody');

        // Se estiver no modo parcelamento e tiver parcelas geradas, garantir que os dados estão corretos
        if ($toggleParcelamento.length && $toggleParcelamento.prop('checked') && $parcelasTbody.length && $parcelasTbody.children().length > 0) {
            // Validar se todas as parcelas têm forma de pagamento
            let todasParcelasValidas = true;
            $parcelasTbody.find('tr').each(function() {
                const $formaPagamentoSelect = $(this).find('select[name^="parcelas"][name$="[forma_pagamento_id]"]');
                if (!$formaPagamentoSelect.length || !$formaPagamentoSelect.val()) {
                    todasParcelasValidas = false;
                }
            });

            if (!todasParcelasValidas) {
                e.preventDefault();
                alert('Por favor, selecione a forma de pagamento para todas as parcelas.');
                return false;
            }
        } else if ($toggleParcelamento.length && $toggleParcelamento.prop('checked')) {
            // Se estiver no modo parcelamento mas não tiver parcelas geradas
            e.preventDefault();
            alert('Por favor, gere as parcelas antes de salvar.');
            return false;
        }
    });
});
</script>
@endsection
