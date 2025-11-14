@extends('layouts.app')

@section('title', 'Nova Forma de Pagamento')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-credit-card text-blue-600 mr-3"></i>
                    Nova Forma de Pagamento
                </h1>
                <p class="text-gray-600 mt-2">Crie uma nova forma de pagamento para sua empresa</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('forma-pagamento.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Dados da Forma de Pagamento</h3>
        </div>

        <form method="POST" action="{{ route('forma-pagamento.store') }}" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nome -->
                <div class="md:col-span-2">
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="nome"
                           id="nome"
                           value="{{ old('nome') }}"
                           placeholder="Ex: Cartão de Crédito - 3x"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nome') border-red-500 @enderror"
                           required>
                    @error('nome')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Modalidade -->
                <div>
                    <label for="modalidade" class="block text-sm font-medium text-gray-700 mb-2">
                        Modalidade <span class="text-red-500">*</span>
                    </label>
                    <select name="modalidade"
                            id="modalidade"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('modalidade') border-red-500 @enderror"
                            required>
                        <option value="">Selecione uma modalidade</option>
                        @foreach($modalidades as $modalidade)
                            <option value="{{ $modalidade->value }}" {{ old('modalidade') == $modalidade->value ? 'selected' : '' }}>
                                {{ $modalidade->getLabel() }}
                            </option>
                        @endforeach
                    </select>
                    @error('modalidade')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Conta Bancária -->
                <div>
                    <label for="conta_empresa_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Conta Bancária
                    </label>
                    {{-- <select name="conta_empresa_id"
                            id="conta_empresa_id"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('conta_empresa_id') border-red-500 @enderror">
                        <option value="">Selecione uma conta (opcional)</option>
                        @foreach($contasEmpresa as $conta)
                            <option value="{{ $conta->id }}" {{ old('conta_empresa_id') == $conta->id ? 'selected' : '' }}>
                                {{ $conta->nome }}
                                @if($conta->banco)
                                    ({{ $conta->banco->nome_normalizado }})
                                @endif
                            </option>
                        @endforeach
                    </select> --}}
                    @error('conta_empresa_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Número de Parcelas -->
                <div>
                    <label for="numero_parcelas" class="block text-sm font-medium text-gray-700 mb-2">
                        Número de Parcelas <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                           name="numero_parcelas"
                           id="numero_parcelas"
                           value="{{ old('numero_parcelas', 1) }}"
                           min="1"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('numero_parcelas') border-red-500 @enderror"
                           required>
                    @error('numero_parcelas')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Intervalo entre Parcelas -->
                <div>
                    <label for="intercalo_parcelas" class="block text-sm font-medium text-gray-700 mb-2">
                        Intervalo entre Parcelas (dias) <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                           name="intercalo_parcelas"
                           id="intercalo_parcelas"
                           value="{{ old('intercalo_parcelas', 30) }}"
                           min="1"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('intercalo_parcelas') border-red-500 @enderror"
                           required>
                    @error('intercalo_parcelas')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Primeira Parcela -->
                <div>
                    <label for="primeira_parcela" class="block text-sm font-medium text-gray-700 mb-2">
                        Primeira Parcela (dias) <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                           name="primeira_parcela"
                           id="primeira_parcela"
                           value="{{ old('primeira_parcela', 0) }}"
                           min="0"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('primeira_parcela') border-red-500 @enderror"
                           required>
                    @error('primeira_parcela')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Taxa do Banco -->
                <div>
                    <label for="taxa_banco" class="block text-sm font-medium text-gray-700 mb-2">
                        Taxa do Banco (R$) <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                           name="taxa_banco"
                           id="taxa_banco"
                           value="{{ old('taxa_banco', 0.00) }}"
                           step="0.01"
                           min="0"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('taxa_banco') border-red-500 @enderror"
                           required>
                    @error('taxa_banco')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Taxa da Operadora -->
                <div>
                    <label for="taxa_operadora" class="block text-sm font-medium text-gray-700 mb-2">
                        Taxa da Operadora (R$) <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                           name="taxa_operadora"
                           id="taxa_operadora"
                           value="{{ old('taxa_operadora', 0.00) }}"
                           step="0.01"
                           min="0"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('taxa_operadora') border-red-500 @enderror"
                           required>
                    @error('taxa_operadora')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Juros/Multa -->
                <div>
                    <label for="juros_multa" class="block text-sm font-medium text-gray-700 mb-2">
                        Juros/Multa (R$) <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                           name="juros_multa"
                           id="juros_multa"
                           value="{{ old('juros_multa', 0.00) }}"
                           step="0.01"
                           min="0"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('juros_multa') border-red-500 @enderror"
                           required>
                    @error('juros_multa')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Juros/Mora -->
                <div>
                    <label for="juros_mora" class="block text-sm font-medium text-gray-700 mb-2">
                        Juros/Mora (R$) <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                           name="juros_mora"
                           id="juros_mora"
                           value="{{ old('juros_mora', 0.00) }}"
                           step="0.01"
                           min="0"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('juros_mora') border-red-500 @enderror"
                           required>
                    @error('juros_mora')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Opções -->
            <div class="mt-6 border-t border-gray-200 pt-6">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Opções</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-center">
                        <input type="checkbox"
                               name="disponivel"
                               id="disponivel"
                               value="1"
                               {{ old('disponivel', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="disponivel" class="ml-2 block text-sm text-gray-900">
                            Disponível para uso
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox"
                               name="confirmacao_automatica"
                               id="confirmacao_automatica"
                               value="1"
                               {{ old('confirmacao_automatica') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="confirmacao_automatica" class="ml-2 block text-sm text-gray-900">
                            Confirmação automática
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox"
                               name="gerar_boleto"
                               id="gerar_boleto"
                               value="1"
                               {{ old('gerar_boleto') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="gerar_boleto" class="ml-2 block text-sm text-gray-900">
                            Gerar boleto automaticamente
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox"
                               name="permite_deletar"
                               id="permite_deletar"
                               value="1"
                               {{ old('permite_deletar', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="permite_deletar" class="ml-2 block text-sm text-gray-900">
                            Permitir exclusão
                        </label>
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('forma-pagamento.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Salvar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Validação JavaScript básica
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const numeroParcelas = document.getElementById('numero_parcelas');
    const intervaloParcelas = document.getElementById('intercalo_parcelas');
    const primeiraParcela = document.getElementById('primeira_parcela');

    // Validação do número de parcelas
    numeroParcelas.addEventListener('change', function() {
        if (this.value < 1) {
            this.value = 1;
        }
    });

    // Validação do intervalo entre parcelas
    intervaloParcelas.addEventListener('change', function() {
        if (this.value < 1) {
            this.value = 1;
        }
    });

    // Validação da primeira parcela
    primeiraParcela.addEventListener('change', function() {
        if (this.value < 0) {
            this.value = 0;
        }
    });

    // Validação do formulário
    form.addEventListener('submit', function(e) {
        const nome = document.getElementById('nome').value.trim();
        const modalidade = document.getElementById('modalidade').value;

        if (!nome) {
            e.preventDefault();
            alert('Por favor, preencha o nome da forma de pagamento.');
            document.getElementById('nome').focus();
            return;
        }

        if (!modalidade) {
            e.preventDefault();
            alert('Por favor, selecione uma modalidade.');
            document.getElementById('modalidade').focus();
            return;
        }
    });
});
</script>
@endsection
