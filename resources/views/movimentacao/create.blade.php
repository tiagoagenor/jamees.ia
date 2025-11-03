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

                <form action="{{ route($tipo == 1 ? 'contas-a-pagar.store' : 'contas-a-receber.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Descrição -->
                        <div class="md:col-span-2">
                            <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">
                                Descrição <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="descricao"
                                   id="descricao"
                                   value="{{ old('descricao') }}"
                                   required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('descricao') border-red-500 @enderror"
                                   placeholder="Digite a descrição da movimentação">
                            @error('descricao')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Plano de Conta -->
                        <div>
                            <label for="plano_conta_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Plano de Conta <span class="text-red-500">*</span>
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

                        <!-- Centro de Custo -->
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

                        <!-- Forma de Pagamento -->
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

                        <!-- Conta Empresa -->
                        <div>
                            <label for="conta_empresa_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Conta Empresa <span class="text-red-500">*</span>
                            </label>
                            <select name="conta_empresa_id"
                                    id="conta_empresa_id"
                                    required
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('conta_empresa_id') border-red-500 @enderror">
                                <option value="">Selecione a conta empresa</option>
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

                        <!-- Entidade -->
                        <div>
                            <label for="entidade_id" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $tipo == 1 ? 'Fornecedor' : 'Cliente' }}
                            </label>
                            <select name="entidade_id"
                                    id="entidade_id"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('entidade_id') border-red-500 @enderror">
                                <option value="">Selecione {{ $tipo == 1 ? 'o fornecedor' : 'o cliente' }}</option>
                                @foreach($entidades as $entidade)
                                    <option value="{{ $entidade->id }}" {{ old('entidade_id') == $entidade->id ? 'selected' : '' }}>
                                        {{ $entidade->nome }}
                                    </option>
                                @endforeach
                            </select>
                            @error('entidade_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Vencimento -->
                        <div>
                            <label for="vencimento" class="block text-sm font-medium text-gray-700 mb-2">
                                Data de Vencimento <span class="text-red-500">*</span>
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

                        <!-- Valor -->
                        <div>
                            <label for="valor" class="block text-sm font-medium text-gray-700 mb-2">
                                Valor <span class="text-red-500">*</span>
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

                        <!-- Juros -->
                        <div>
                            <label for="juros" class="block text-sm font-medium text-gray-700 mb-2">
                                Juros
                            </label>
                            <input type="number"
                                   name="juros"
                                   id="juros"
                                   value="{{ old('juros') }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('juros') border-red-500 @enderror"
                                   placeholder="0,00">
                            @error('juros')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Desconto -->
                        <div>
                            <label for="desconto" class="block text-sm font-medium text-gray-700 mb-2">
                                Desconto
                            </label>
                            <input type="number"
                                   name="desconto"
                                   id="desconto"
                                   value="{{ old('desconto') }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('desconto') border-red-500 @enderror"
                                   placeholder="0,00">
                            @error('desconto')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Observação -->
                    <div>
                        <label for="observacao" class="block text-sm font-medium text-gray-700 mb-2">
                            Observação
                        </label>
                        <textarea name="observacao"
                                  id="observacao"
                                  rows="3"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('observacao') border-red-500 @enderror"
                                  placeholder="Digite uma observação (opcional)">{{ old('observacao') }}</textarea>
                        @error('observacao')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Informação Complementar -->
                    <div>
                        <label for="informacao_complementar" class="block text-sm font-medium text-gray-700 mb-2">
                            Informação Complementar
                        </label>
                        <textarea name="informacao_complementar"
                                  id="informacao_complementar"
                                  rows="3"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('informacao_complementar') border-red-500 @enderror"
                                  placeholder="Digite informações complementares (opcional)">{{ old('informacao_complementar') }}</textarea>
                        @error('informacao_complementar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
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
// Calcular valor total automaticamente
document.addEventListener('DOMContentLoaded', function() {
    const valorInput = document.getElementById('valor');
    const jurosInput = document.getElementById('juros');
    const descontoInput = document.getElementById('desconto');

    function calcularTotal() {
        const valor = parseFloat(valorInput.value) || 0;
        const juros = parseFloat(jurosInput.value) || 0;
        const desconto = parseFloat(descontoInput.value) || 0;

        const total = valor + juros - desconto;

        // Mostrar o total calculado (opcional)
        if (total !== valor) {
            console.log('Valor total calculado:', total);
        }
    }

    valorInput.addEventListener('input', calcularTotal);
    jurosInput.addEventListener('input', calcularTotal);
    descontoInput.addEventListener('input', calcularTotal);
});
</script>
@endsection
