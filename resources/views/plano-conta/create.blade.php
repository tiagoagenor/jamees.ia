@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Nova Conta</h2>
                    <a href="{{ route('plano-conta.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Voltar
                    </a>
                </div>

                <form action="{{ route('plano-conta.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nome -->
                        <div>
                            <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                                Nome da Conta <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="nome"
                                   id="nome"
                                   value="{{ old('nome') }}"
                                   required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nome') border-red-500 @enderror"
                                   placeholder="Digite o nome da conta">
                            @error('nome')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Movimentação -->
                        <div>
                            <label for="movimentacao" class="block text-sm font-medium text-gray-700 mb-2">
                                Movimentação <span class="text-red-500">*</span>
                            </label>
                            <select name="movimentacao"
                                    id="movimentacao"
                                    required
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('movimentacao') border-red-500 @enderror">
                                <option value="">Selecione a movimentação</option>
                                @foreach($movimentacoes as $movimentacao)
                                    <option value="{{ $movimentacao->value }}" {{ old('movimentacao') == $movimentacao->value ? 'selected' : '' }}>
                                        {{ $movimentacao->getLabel() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('movimentacao')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ordem Pai -->
                        <div>
                            <label for="ordem_pai" class="block text-sm font-medium text-gray-700 mb-2">
                                Ordem Pai <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                   name="ordem_pai"
                                   id="ordem_pai"
                                   value="{{ old('ordem_pai') }}"
                                   required
                                   min="1"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('ordem_pai') border-red-500 @enderror"
                                   placeholder="Ex: 1, 2, 3...">
                            @error('ordem_pai')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ordem Filho -->
                        <div>
                            <label for="ordem_filho" class="block text-sm font-medium text-gray-700 mb-2">
                                Ordem Filho
                            </label>
                            <input type="number"
                                   name="ordem_filho"
                                   id="ordem_filho"
                                   value="{{ old('ordem_filho') }}"
                                   min="1"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('ordem_filho') border-red-500 @enderror"
                                   placeholder="Ex: 1, 2, 3... (opcional)">
                            <p class="mt-1 text-xs text-gray-500">Deixe vazio para criar uma conta pai</p>
                            @error('ordem_filho')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Conta Pai -->
                        <div>
                            <label for="plano_conta_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Conta Pai
                            </label>
                            <select name="plano_conta_id"
                                    id="plano_conta_id"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('plano_conta_id') border-red-500 @enderror">
                                <option value="">Selecione uma conta pai (opcional)</option>
                                @foreach($planoContasPai as $contaPai)
                                    <option value="{{ $contaPai->id }}" {{ old('plano_conta_id') == $contaPai->id ? 'selected' : '' }}>
                                        {{ $contaPai->getCodigoCompleto() }} - {{ $contaPai->nome }}
                                    </option>
                                @endforeach
                            </select>
                            @error('plano_conta_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- DRE -->
                        <div>
                            <label for="dre_id" class="block text-sm font-medium text-gray-700 mb-2">
                                DRE
                            </label>
                            <select name="dre_id"
                                    id="dre_id"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('dre_id') border-red-500 @enderror">
                                <option value="">Selecione um item DRE (opcional)</option>
                                @foreach($dres as $dre)
                                    <option value="{{ $dre->id }}" {{ old('dre_id') == $dre->id ? 'selected' : '' }}>
                                        {{ $dre->nome }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dre_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('plano-conta.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md text-sm font-medium">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-save mr-2"></i>
                            Salvar Conta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// JavaScript para melhorar a experiência do usuário
document.addEventListener('DOMContentLoaded', function() {
    const ordemFilhoInput = document.getElementById('ordem_filho');
    const contaPaiSelect = document.getElementById('plano_conta_id');

    // Se selecionar uma conta pai, sugerir ordem filho
    contaPaiSelect.addEventListener('change', function() {
        if (this.value) {
            ordemFilhoInput.placeholder = 'Ex: 1, 2, 3... (obrigatório para subconta)';
            ordemFilhoInput.required = true;
        } else {
            ordemFilhoInput.placeholder = 'Ex: 1, 2, 3... (opcional)';
            ordemFilhoInput.required = false;
        }
    });
});
</script>
@endsection
