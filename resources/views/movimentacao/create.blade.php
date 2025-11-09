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
                                                   value="{{ old('vencimento', \Carbon\Carbon::now()->format('Y-m-d')) }}"
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
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('valor') border-red-500 @enderror"
                                   placeholder="0,00">
                            @error('valor')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                                            <!-- Linha 2: Tipo de Juros, Forma de Juros e Juros -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="juros_tipo" class="block text-sm font-medium text-gray-700 mb-2">
                                    <span class="flex items-center">
                                        Tipo de Juros
                                        <div class="relative group ml-2">
                                            <i class="fas fa-info-circle text-gray-400 hover:text-gray-600 cursor-help"></i>
                                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 text-xs font-medium text-white bg-gray-900 rounded-lg shadow-lg whitespace-normal w-64 text-left invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-50">
                                                <strong>Fixo:</strong> Juros aplicados sempre, independente da data de vencimento.<br><br>
                                                <strong>Por Dia:</strong> Juros aplicados apenas quando a movimentação estiver vencida (após a data de vencimento).
                                                <div class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-1">
                                                    <div class="w-2 h-2 bg-gray-900 transform rotate-45"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </span>
                                </label>
                                <select name="juros_tipo"
                                        id="juros_tipo"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('juros_tipo') border-red-500 @enderror">
                                    <option value="fixo" {{ old('juros_tipo', 'fixo') == 'fixo' ? 'selected' : '' }}>Fixo</option>
                                    <option value="por_dia" {{ old('juros_tipo') == 'por_dia' ? 'selected' : '' }}>Por Dia</option>
                                </select>
                                @error('juros_tipo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="juros_forma" class="block text-sm font-medium text-gray-700 mb-2">
                                    <span class="flex items-center">
                                        Forma de Juros
                                        <div class="relative group ml-2">
                                            <i class="fas fa-info-circle text-gray-400 hover:text-gray-600 cursor-help"></i>
                                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 text-xs font-medium text-white bg-gray-900 rounded-lg shadow-lg whitespace-normal w-64 text-left invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-50">
                                                <strong>Valor:</strong> Juros aplicado como valor fixo (ex: R$ 50,00).<br><br>
                                                <strong>Porcentagem:</strong> Juros aplicado como percentual do valor principal (ex: 5% sobre R$ 1.000,00 = R$ 50,00).
                                                <div class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-1">
                                                    <div class="w-2 h-2 bg-gray-900 transform rotate-45"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </span>
                                </label>
                                <select name="juros_forma"
                                        id="juros_forma"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('juros_forma') border-red-500 @enderror">
                                    <option value="valor" {{ old('juros_forma', 'valor') == 'valor' ? 'selected' : '' }}>Valor</option>
                                    <option value="porcentagem" {{ old('juros_forma') == 'porcentagem' ? 'selected' : '' }}>Porcentagem</option>
                                </select>
                                @error('juros_forma')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="juros" class="block text-sm font-medium text-gray-700 mb-2">
                                    Juros
                                </label>
                                <input type="number"
                                       name="juros"
                                       id="juros"
                                       value="{{ old('juros', 0) }}"
                                       step="any"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('juros') border-red-500 @enderror"
                                       placeholder="0,00">
                                @error('juros')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                                            <!-- Linha 3: Forma de Multa, Multa por Atraso e Desconto -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="multa_forma" class="block text-sm font-medium text-gray-700 mb-2">
                                    <span class="flex items-center">
                                        Forma de Multa
                                        <div class="relative group ml-2">
                                            <i class="fas fa-info-circle text-gray-400 hover:text-gray-600 cursor-help"></i>
                                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 text-xs font-medium text-white bg-gray-900 rounded-lg shadow-lg whitespace-normal w-64 text-left invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-50">
                                                <strong>Valor:</strong> Multa aplicada como valor fixo (ex: R$ 30,00).<br><br>
                                                <strong>Porcentagem:</strong> Multa aplicada como percentual do valor principal (ex: 2% sobre R$ 1.000,00 = R$ 20,00).
                                                <div class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-1">
                                                    <div class="w-2 h-2 bg-gray-900 transform rotate-45"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </span>
                                </label>
                                <select name="multa_forma"
                                        id="multa_forma"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('multa_forma') border-red-500 @enderror">
                                    <option value="valor" {{ old('multa_forma', 'valor') == 'valor' ? 'selected' : '' }}>Valor</option>
                                    <option value="porcentagem" {{ old('multa_forma') == 'porcentagem' ? 'selected' : '' }}>Porcentagem</option>
                                </select>
                                @error('multa_forma')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="multa" class="block text-sm font-medium text-gray-700 mb-2">
                                    <span class="flex items-center">
                                        Multa por Atraso
                                        <div class="relative group ml-2">
                                            <i class="fas fa-info-circle text-gray-400 hover:text-gray-600 cursor-help"></i>
                                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 text-xs font-medium text-white bg-gray-900 rounded-lg shadow-lg whitespace-normal w-64 text-left invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-50">
                                                Valor da multa aplicada quando o pagamento é realizado após a data de vencimento.
                                                <div class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-1">
                                                    <div class="w-2 h-2 bg-gray-900 transform rotate-45"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </span>
                                </label>
                                <input type="number"
                                       name="multa"
                                       id="multa"
                                       value="{{ old('multa', 0) }}"
                                       step="any"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('multa') border-red-500 @enderror"
                                       placeholder="0,00">
                                @error('multa')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
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

                                    <!-- Linha 2: Valor Bruto, Juros, Tipo de Juros, Desconto -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('valor') border-red-500 @enderror"
                                                   placeholder="0,00">
                                            @error('valor')
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
                                    <!-- Linha 3: Tipo de Juros, Forma de Juros, Juros, Forma de Multa e Multa por Atraso -->
                                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                        <div>
                                            <label for="juros_tipo_parcelamento" class="block text-sm font-medium text-gray-700 mb-2">
                                                <span class="flex items-center">
                                                    Tipo de Juros
                                                    <div class="relative group ml-2">
                                                        <i class="fas fa-info-circle text-gray-400 hover:text-gray-600 cursor-help"></i>
                                                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 text-xs font-medium text-white bg-gray-900 rounded-lg shadow-lg whitespace-normal w-64 text-left invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-50">
                                                            <strong>Fixo:</strong> Juros aplicados sempre, independente da data de vencimento.<br><br>
                                                            <strong>Por Dia:</strong> Juros aplicados apenas quando a parcela estiver vencida (após a data de vencimento).
                                                            <div class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-1">
                                                                <div class="w-2 h-2 bg-gray-900 transform rotate-45"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </span>
                                            </label>
                                            <select name="juros_tipo"
                                                    id="juros_tipo_parcelamento"
                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('juros_tipo') border-red-500 @enderror">
                                                <option value="fixo" {{ old('juros_tipo', 'fixo') == 'fixo' ? 'selected' : '' }}>Fixo</option>
                                                <option value="por_dia" {{ old('juros_tipo') == 'por_dia' ? 'selected' : '' }}>Por Dia</option>
                                            </select>
                                            @error('juros_tipo')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="juros_forma_parcelamento" class="block text-sm font-medium text-gray-700 mb-2">
                                                <span class="flex items-center">
                                                    Forma de Juros
                                                    <div class="relative group ml-2">
                                                        <i class="fas fa-info-circle text-gray-400 hover:text-gray-600 cursor-help"></i>
                                                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 text-xs font-medium text-white bg-gray-900 rounded-lg shadow-lg whitespace-normal w-64 text-left invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-50">
                                                            <strong>Valor:</strong> Juros aplicado como valor fixo (ex: R$ 50,00).<br><br>
                                                            <strong>Porcentagem:</strong> Juros aplicado como percentual do valor principal (ex: 5% sobre R$ 1.000,00 = R$ 50,00).
                                                            <div class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-1">
                                                                <div class="w-2 h-2 bg-gray-900 transform rotate-45"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </span>
                                            </label>
                                            <select name="juros_forma"
                                                    id="juros_forma_parcelamento"
                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('juros_forma') border-red-500 @enderror">
                                                <option value="valor" {{ old('juros_forma', 'valor') == 'valor' ? 'selected' : '' }}>Valor</option>
                                                <option value="porcentagem" {{ old('juros_forma') == 'porcentagem' ? 'selected' : '' }}>Porcentagem</option>
                                            </select>
                                            @error('juros_forma')
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
                                                   step="any"
                                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('juros') border-red-500 @enderror"
                                                   placeholder="0,00">
                                            @error('juros')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="multa_forma_parcelamento" class="block text-sm font-medium text-gray-700 mb-2">
                                                <span class="flex items-center">
                                                    Forma de Multa
                                                    <div class="relative group ml-2">
                                                        <i class="fas fa-info-circle text-gray-400 hover:text-gray-600 cursor-help"></i>
                                                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 text-xs font-medium text-white bg-gray-900 rounded-lg shadow-lg whitespace-normal w-64 text-left invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-50">
                                                            <strong>Valor:</strong> Multa aplicada como valor fixo (ex: R$ 30,00).<br><br>
                                                            <strong>Porcentagem:</strong> Multa aplicada como percentual do valor principal (ex: 2% sobre R$ 1.000,00 = R$ 20,00).
                                                            <div class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-1">
                                                                <div class="w-2 h-2 bg-gray-900 transform rotate-45"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </span>
                                            </label>
                                            <select name="multa_forma"
                                                    id="multa_forma_parcelamento"
                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('multa_forma') border-red-500 @enderror">
                                                <option value="valor" {{ old('multa_forma', 'valor') == 'valor' ? 'selected' : '' }}>Valor</option>
                                                <option value="porcentagem" {{ old('multa_forma') == 'porcentagem' ? 'selected' : '' }}>Porcentagem</option>
                                            </select>
                                            @error('multa_forma')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="multa_parcelamento" class="block text-sm font-medium text-gray-700 mb-2">
                                                <span class="flex items-center">
                                                    Multa por Atraso
                                                    <div class="relative group ml-2">
                                                        <i class="fas fa-info-circle text-gray-400 hover:text-gray-600 cursor-help"></i>
                                                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 text-xs font-medium text-white bg-gray-900 rounded-lg shadow-lg whitespace-normal w-64 text-left invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-50">
                                                            Valor da multa aplicada quando o pagamento é realizado após a data de vencimento.
                                                            <div class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-1">
                                                                <div class="w-2 h-2 bg-gray-900 transform rotate-45"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </span>
                                            </label>
                                            <input type="number"
                                                   name="multa"
                                                   id="multa_parcelamento"
                                                   value="{{ old('multa', 0) }}"
                                                   step="any"
                                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('multa') border-red-500 @enderror"
                                                   placeholder="0,00">
                                            @error('multa')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Linha 5: Tabela de Configuração de Parcelas -->
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
    const $modoNormal = $('#modo-normal');
    const $modoParcelamento = $('#modo-parcelamento');

    // Pegar os toggles - buscar novamente quando necessário
    function getToggleParcelamento() {
        return $('.toggle-parcelamento').eq(0);
    }

    function getToggleParcelamentoParcelamento() {
        return $('.toggle-parcelamento').eq(1);
    }

    // Flag para evitar loop de eventos
    let sincronizandoToggle = false;

    // Calcular Total automaticamente (modo normal) - Declarar variáveis ANTES das funções que as usam
    const $valorInput = $('#valor');
    const $jurosInput = $('#juros');
    const $multaInput = $('#multa');
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
        const multa = parseFloat($multaInput.val()) || 0;
        const desconto = parseFloat($descontoInput.val()) || 0;
        const jurosTipo = $('#juros_tipo').val() || 'fixo';
        const jurosForma = $('#juros_forma').val() || 'valor';
        const multaForma = $('#multa_forma').val() || 'valor';
        const vencimento = $('#vencimento').val();

        // Calcular juros baseado na forma
        let jurosAplicar = 0;
        if (juros > 0) {
            let valorJuros = juros;

            // Se for porcentagem, calcular o valor baseado no valor principal
            if (jurosForma === 'porcentagem') {
                valorJuros = (valor * juros) / 100;
            }

            // Aplicar juros apenas se:
            // - Tipo for "fixo" (sempre aplica)
            // - Tipo for "por_dia" E a movimentação estiver vencida
            if (jurosTipo === 'fixo') {
                jurosAplicar = valorJuros;
            } else if (jurosTipo === 'por_dia' && vencimento) {
                // Verificar se está vencida
                const dataVencimento = new Date(vencimento);
                const hoje = new Date();
                hoje.setHours(0, 0, 0, 0);
                dataVencimento.setHours(0, 0, 0, 0);

                if (dataVencimento < hoje) {
                    jurosAplicar = valorJuros;
                }
            }
        }

        // Calcular multa baseado na forma
        let multaAplicar = 0;
        if (multa > 0) {
            if (multaForma === 'porcentagem') {
                multaAplicar = (valor * multa) / 100;
            } else {
                multaAplicar = multa;
            }
        }

        const total = valor + jurosAplicar + multaAplicar - desconto;

        $totalInput.val(formatarMoeda(total));
        $totalHiddenInput.val(total.toFixed(2));
    }

    // Configurar event listeners para calcular total
    if ($valorInput.length && $jurosInput.length && $multaInput.length && $descontoInput.length) {
        $valorInput.on('input', calcularTotal);
        $jurosInput.on('input', calcularTotal);
        $multaInput.on('input', calcularTotal);
        $descontoInput.on('input', calcularTotal);
        $('#juros_tipo').on('change', calcularTotal);
        $('#juros_forma').on('change', calcularTotal);
        $('#multa_forma').on('change', calcularTotal);
        $('#vencimento').on('change', calcularTotal);
        calcularTotal();
    }

    function alternarModo(isAtivo) {
        if (isAtivo) {
            // Sincronizar valores dos campos ANTES de desabilitar os campos do modo normal
            // Isso garante que os valores sejam copiados corretamente
            sincronizarCamposParaParcelamento();

            $modoNormal.addClass('hidden');
            $modoParcelamento.removeClass('hidden');

            // Sincronizar todos os toggles
            sincronizandoToggle = true;
            $('.toggle-parcelamento').prop('checked', true);
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

            // Remover atributo name dos campos valor, juros, multa, desconto, juros_tipo, juros_forma, multa_forma mas NÃO desabilitar
            const camposValoresCompartilhados = ['valor', 'juros', 'multa', 'desconto', 'juros_tipo', 'juros_forma', 'multa_forma'];
            camposValoresCompartilhados.forEach(campoId => {
                const $campo = $('#' + campoId);
                if ($campo.length) {
                    $campo.data('originalName', $campo.attr('name'));
                    $campo.removeAttr('name');
                    // NÃO desabilitar - campos permanecem editáveis
                }
            });

            // Garantir que os campos do modo parcelamento estejam habilitados
            const camposModoParcelamentoParaHabilitar = [
                'descricao_parcelamento', 'plano_conta_id_parcelamento',
                'centro_custo_id_parcelamento', 'conta_empresa_id_parcelamento'
            ];

            camposModoParcelamentoParaHabilitar.forEach(campoId => {
                const $campo = $('#' + campoId);
                if ($campo.length) {
                    $campo.prop('disabled', false);
                }
            });

            // Anotar listener do botão gerar parcelas quando o modo parcelamento for ativado
            setTimeout(() => {
                anexarListenerGerarParcelas();
            }, 100);
        } else {
            // Sincronizar valores dos campos ANTES de desabilitar os campos do modo parcelamento
            // Isso garante que os valores sejam copiados corretamente
            sincronizarCamposParaNormal();

            $modoNormal.removeClass('hidden');
            $modoParcelamento.addClass('hidden');

            // Sincronizar todos os toggles
            sincronizandoToggle = true;
            $('.toggle-parcelamento').prop('checked', false);
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
                    $campo.prop('disabled', false);
                }
            });

            // Restaurar atributo name dos campos valor, juros, multa, desconto, juros_tipo
            const camposValoresCompartilhados = ['valor', 'juros', 'multa', 'desconto', 'juros_tipo'];
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

            // Remover atributo name dos campos valor, juros, multa, desconto, juros_tipo do modo parcelamento mas NÃO desabilitar
            const camposValoresParcelamento = ['valor_parcelamento', 'juros_parcelamento', 'multa_parcelamento', 'desconto_parcelamento', 'juros_tipo_parcelamento'];
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
        }
    }

    function sincronizarCamposParaParcelamento() {
        const descricao = $('#descricao').val();
        const planoContaId = $('#plano_conta_id').val();
        const centroCustoId = $('#centro_custo_id').val();
        const contaEmpresaId = $('#conta_empresa_id').val();
        const valor = $('#valor').val();
        const juros = $('#juros').val();
        const multa = $('#multa').val();
        const desconto = $('#desconto').val();
        const jurosTipo = $('#juros_tipo').val();
        const jurosForma = $('#juros_forma').val();
        const multaForma = $('#multa_forma').val();

        $('#descricao_parcelamento').val(descricao);
        $('#plano_conta_id_parcelamento').val(planoContaId);
        $('#centro_custo_id_parcelamento').val(centroCustoId);
        $('#conta_empresa_id_parcelamento').val(contaEmpresaId);
        $('#valor_parcelamento').val(valor);
        $('#juros_parcelamento').val(juros);
        $('#multa_parcelamento').val(multa);
        $('#juros_tipo_parcelamento').val(jurosTipo);
        $('#juros_forma_parcelamento').val(jurosForma);
        $('#multa_forma_parcelamento').val(multaForma);
        $('#desconto_parcelamento').val(desconto);
    }

    function sincronizarCamposParaNormal() {
        const descricao = $('#descricao_parcelamento').val();
        const planoContaId = $('#plano_conta_id_parcelamento').val();
        const centroCustoId = $('#centro_custo_id_parcelamento').val();
        const contaEmpresaId = $('#conta_empresa_id_parcelamento').val();
        const valor = $('#valor_parcelamento').val();
        const juros = $('#juros_parcelamento').val();
        const multa = $('#multa_parcelamento').val();
        const desconto = $('#desconto_parcelamento').val();
        const jurosTipo = $('#juros_tipo_parcelamento').val();
        const jurosForma = $('#juros_forma_parcelamento').val();
        const multaForma = $('#multa_forma_parcelamento').val();

        $('#descricao').val(descricao);
        $('#plano_conta_id').val(planoContaId);
        $('#centro_custo_id').val(centroCustoId);
        $('#conta_empresa_id').val(contaEmpresaId);
        $('#valor').val(valor);
        $('#juros').val(juros);
        $('#multa').val(multa);
        $('#juros_tipo').val(jurosTipo);
        $('#juros_forma').val(jurosForma);
        $('#multa_forma').val(multaForma);
        $('#desconto').val(desconto);
        calcularTotal();
    }

    // Usar event delegation para garantir que os eventos funcionem mesmo quando os elementos estão ocultos
    $(document).on('change', '.toggle-parcelamento', function() {
        // Ignorar se estiver sincronizando
        if (sincronizandoToggle) {
            return;
        }

        const $toggle = $(this);
        const isChecked = $toggle.prop('checked');
        const toggleIndex = $('.toggle-parcelamento').index($toggle);

        console.log('Toggle changed:', toggleIndex, 'isChecked:', isChecked);

        // Sincronizar todos os toggles
        sincronizandoToggle = true;
        $('.toggle-parcelamento').prop('checked', isChecked);
        sincronizandoToggle = false;

        // Alternar o modo baseado no estado do toggle
        alternarModo(isChecked);

        // Não limpar campos automaticamente - a sincronização já foi feita em alternarModo()
        // A função limparCamposParaEstadoInicial() pode ser chamada manualmente se necessário
    });

    // Verificar estado inicial
    const $toggleParcelamento = getToggleParcelamento();
    if ($toggleParcelamento.length) {
        alternarModo($toggleParcelamento.prop('checked'));
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

        // Limpar campos de valores (valor, juros, multa, desconto, juros_tipo)
        const camposValores = ['valor', 'juros', 'multa', 'desconto', 'juros_tipo'];
        camposValores.forEach(campoId => {
            $('#' + campoId).val('');
        });

        // Limpar campos do modo parcelamento
        const camposModoParcelamento = [
            'descricao_parcelamento', 'plano_conta_id_parcelamento', 'centro_custo_id_parcelamento',
            'conta_empresa_id_parcelamento', 'valor_parcelamento', 'juros_parcelamento', 'multa_parcelamento', 'juros_tipo_parcelamento', 'desconto_parcelamento',
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

    // Definir data padrão para "Vencimento" como hoje (usando timezone local do navegador)
    const $vencimentoInput = $('#vencimento');
    if ($vencimentoInput.length && !$vencimentoInput.val()) {
        const hoje = new Date();
        const ano = hoje.getFullYear();
        const mes = String(hoje.getMonth() + 1).padStart(2, '0');
        const dia = String(hoje.getDate()).padStart(2, '0');
        $vencimentoInput.val(`${ano}-${mes}-${dia}`);
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


    // Flag para controlar se já foi replicada a forma de pagamento (apenas primeira vez)
    let formaPagamentoReplicada = false;

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

        // Resetar flag quando gerar novas parcelas
        formaPagamentoReplicada = false;

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
                name: `parcelas[${index}][forma_pagamento_id]`,
                'data-parcela-index': index
            }).addClass('w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 forma-pagamento-parcela');
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

        // Adicionar listener para replicar forma de pagamento (apenas primeira vez)
        adicionarListenerReplicacaoFormaPagamento();
    }

    // Função para adicionar listener de replicação de forma de pagamento
    function adicionarListenerReplicacaoFormaPagamento() {
        // Remover listeners anteriores para evitar duplicação
        $(document).off('change', '.forma-pagamento-parcela');

        // Adicionar listener usando event delegation
        $(document).on('change', '.forma-pagamento-parcela', function() {
            // Apenas replicar se ainda não foi replicado (primeira vez)
            if (!formaPagamentoReplicada) {
                const $selectAtual = $(this);
                const formaPagamentoId = $selectAtual.val();

                // Se uma forma de pagamento foi selecionada (não vazia)
                if (formaPagamentoId && formaPagamentoId !== '') {
                    // Replicar para todos os outros selects de forma de pagamento
                    $('.forma-pagamento-parcela').each(function() {
                        // Não alterar o select que foi clicado
                        if ($(this).attr('name') !== $selectAtual.attr('name')) {
                            $(this).val(formaPagamentoId);
                        }
                    });

                    // Marcar como replicado para não replicar novamente
                    formaPagamentoReplicada = true;
                }
            }
        });
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
                const jurosTipo = $('#juros_tipo_parcelamento').val() || 'fixo';
                const jurosForma = $('#juros_forma_parcelamento').val() || 'valor';
                const multa = parseFloat($('#multa_parcelamento').val()) || 0;
                const multaForma = $('#multa_forma_parcelamento').val() || 'valor';
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
                        juros_tipo: jurosTipo,
                        juros_forma: jurosForma,
                        multa: multa,
                        multa_forma: multaForma,
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
        const multa = parseFloat($('#multa_parcelamento').val()) || 0;
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
                multa: multa,
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

    // Validação completa antes do submit
    $('#movimentacaoForm').on('submit', function(e) {
        e.preventDefault();

        const $toggleParcelamento = getToggleParcelamento();
        const isParcelamento = $toggleParcelamento.length && $toggleParcelamento.prop('checked');
        let erros = [];

        // Garantir que os campos corretos estejam habilitados e com name antes de validar
        if (isParcelamento) {
            // Garantir que os campos do modo parcelamento tenham name e estejam habilitados
            $('#descricao_parcelamento').prop('disabled', false).attr('name', 'descricao');
            $('#plano_conta_id_parcelamento').prop('disabled', false).attr('name', 'plano_conta_id');
            $('#centro_custo_id_parcelamento').prop('disabled', false).attr('name', 'centro_custo_id');
            $('#conta_empresa_id_parcelamento').prop('disabled', false).attr('name', 'conta_empresa_id');

            // Garantir que os campos do modo normal NÃO tenham name
            $('#descricao').removeAttr('name');
            $('#plano_conta_id').removeAttr('name');
            $('#centro_custo_id').removeAttr('name');
            $('#conta_empresa_id').removeAttr('name');
            $('#vencimento').removeAttr('name');
            $('#forma_pagamento_id').removeAttr('name');

            // Validação para modo parcelamento
            const descricao = $('#descricao_parcelamento').val();
            const planoContaId = $('#plano_conta_id_parcelamento').val();
            const contaEmpresaId = $('#conta_empresa_id_parcelamento').val();
            const $parcelasTbody = $('#parcelas_tbody');

            // Campos compartilhados obrigatórios (usados em todas as parcelas)
            if (!descricao || descricao.trim() === '') {
                erros.push('Descrição é obrigatória.');
            }

            if (!planoContaId || planoContaId === '') {
                erros.push('Plano de Contas é obrigatório.');
            }

            if (!contaEmpresaId || contaEmpresaId === '') {
                erros.push('Conta Bancária é obrigatória.');
            }

            // Validar se há parcelas geradas
            if (!$parcelasTbody.length || $parcelasTbody.children().length === 0) {
                erros.push('Por favor, gere as parcelas antes de salvar.');
            } else {
                // Validar cada parcela individualmente
                let parcelasComErro = [];

                $parcelasTbody.find('tr').each(function(index) {
                    const $linha = $(this);
                    const $dataInput = $linha.find('input[name^="parcelas"][name$="[data]"]');
                    const $valorInput = $linha.find('input[name^="parcelas"][name$="[valor]"]');
                    const $formaPagamentoSelect = $linha.find('select[name^="parcelas"][name$="[forma_pagamento_id]"]');

                    const numeroParcela = index + 1;
                    let errosParcela = [];

                    // Validar se os campos existem e têm valores
                    if (!$dataInput.length || !$dataInput.val() || $dataInput.val().trim() === '') {
                        errosParcela.push('Data');
                    }

                    const valorParcela = parseFloat($valorInput.val()) || 0;
                    if (!$valorInput.length || isNaN(valorParcela) || valorParcela <= 0) {
                        errosParcela.push('Valor');
                    }

                    if (!$formaPagamentoSelect.length || !$formaPagamentoSelect.val() || $formaPagamentoSelect.val() === '') {
                        errosParcela.push('Forma de Pagamento');
                    }

                    if (errosParcela.length > 0) {
                        parcelasComErro.push(`Parcela ${numeroParcela}: ${errosParcela.join(', ')} ${errosParcela.length === 1 ? 'é obrigatório' : 'são obrigatórios'}.`);
                    }
                });

                if (parcelasComErro.length > 0) {
                    erros = erros.concat(parcelasComErro);
                }
            }
        } else {
            // Garantir que os campos do modo normal tenham name e estejam habilitados
            $('#descricao').prop('disabled', false).attr('name', 'descricao');
            $('#plano_conta_id').prop('disabled', false).attr('name', 'plano_conta_id');
            $('#centro_custo_id').prop('disabled', false).attr('name', 'centro_custo_id');
            $('#conta_empresa_id').prop('disabled', false).attr('name', 'conta_empresa_id');
            $('#vencimento').prop('disabled', false).attr('name', 'vencimento');
            $('#forma_pagamento_id').prop('disabled', false).attr('name', 'forma_pagamento_id');

            // Garantir que os campos do modo parcelamento NÃO tenham name
            $('#descricao_parcelamento').removeAttr('name');
            $('#plano_conta_id_parcelamento').removeAttr('name');
            $('#centro_custo_id_parcelamento').removeAttr('name');
            $('#conta_empresa_id_parcelamento').removeAttr('name');

            // Validação para modo normal
            const descricao = $('#descricao').val();
            const vencimento = $('#vencimento').val();
            const planoContaId = $('#plano_conta_id').val();
            const formaPagamentoId = $('#forma_pagamento_id').val();
            const contaEmpresaId = $('#conta_empresa_id').val();
            const valor = parseFloat($('#valor').val()) || 0;

            if (!descricao || descricao.trim() === '') {
                erros.push('Descrição é obrigatória.');
            }

            if (!vencimento || vencimento === '') {
                erros.push('Vencimento é obrigatório.');
            }

            if (!planoContaId || planoContaId === '') {
                erros.push('Plano de Contas é obrigatório.');
            }

            if (!formaPagamentoId || formaPagamentoId === '') {
                erros.push('Forma de Pagamento é obrigatória.');
            }

            if (!contaEmpresaId || contaEmpresaId === '') {
                erros.push('Conta Bancária é obrigatória.');
            }

            if (!valor || valor <= 0) {
                erros.push('Valor Bruto deve ser maior que zero.');
            }
        }

        // Se houver erros, exibir e não submeter
        if (erros.length > 0) {
            const mensagemErro = 'Por favor, corrija os seguintes erros:\n\n' + erros.join('\n');
            alert(mensagemErro);
            return false;
        }

        // Se não houver erros, submeter o formulário
        this.submit();
    });
});
</script>
@endsection

