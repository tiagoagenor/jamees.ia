@extends('layouts.app')

@section('title', 'Nova DRE')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-plus-circle text-blue-600 mr-3"></i>
                    Nova DRE
                </h1>
                <p class="text-gray-600 mt-2">Crie uma nova estrutura para o demonstrativo de resultado</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('dre.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('dre.store') }}" method="POST" id="dreForm">
            @csrf

            <!-- Informações da DRE -->
            <div class="mb-8">
                <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-indigo-600 mr-2"></i>
                    Informações da DRE
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nome -->
                    <div>
                        <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">Nome</label>
                        <input type="text"
                               id="nome"
                               name="nome"
                               value="{{ old('nome') }}"
                               placeholder="Ex: Receita Bruta, Despesas Operacionais..."
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('nome') border-red-500 @enderror"
                               required>
                        @error('nome')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipo -->
                    <div>
                        <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                        <select id="tipo"
                                name="tipo"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('tipo') border-red-500 @enderror"
                                required>
                            <option value="">Selecione o tipo</option>
                            @foreach($tipos as $tipo)
                                <option value="{{ $tipo->value }}"
                                        {{ old('tipo') == $tipo->value ? 'selected' : '' }}
                                        data-color="{{ $tipo->getColor() }}"
                                        data-icon="{{ $tipo->getIcon() }}">
                                    {{ $tipo->getLabel() }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipo')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- DRE Pai -->
                    <div>
                        <label for="dre_id" class="block text-sm font-medium text-gray-700 mb-2">DRE Pai (Opcional)</label>
                        <select id="dre_id"
                                name="dre_id"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('dre_id') border-red-500 @enderror">
                            <option value="">Nenhuma (DRE Raiz)</option>
                            @foreach($dresPai as $drePai)
                                <option value="{{ $drePai->id }}"
                                        {{ old('dre_id') == $drePai->id ? 'selected' : '' }}>
                                    {{ $drePai->nome }} ({{ $drePai->getTipoLabel() }})
                                </option>
                            @endforeach
                        </select>
                        @error('dre_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">
                            Deixe vazio para criar uma DRE raiz ou selecione uma DRE existente para criar um subitem.
                        </p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="radio"
                                       name="status"
                                       value="1"
                                       {{ old('status', '1') == '1' ? 'checked' : '' }}
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Ativo</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio"
                                       name="status"
                                       value="0"
                                       {{ old('status') == '0' ? 'checked' : '' }}
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Inativo</span>
                            </label>
                        </div>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('dre.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Salvar DRE
                </button>
            </div>
        </form>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Validação do formulário
document.getElementById('dreForm').addEventListener('submit', function(e) {
    const nome = document.getElementById('nome').value.trim();
    const tipo = document.getElementById('tipo').value;

    if (!nome) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'O nome é obrigatório.',
            timer: 3000,
            showConfirmButton: false
        });
        return;
    }

    if (!tipo) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'O tipo é obrigatório.',
            timer: 3000,
            showConfirmButton: false
        });
        return;
    }

    // Mostrar loading
    Swal.fire({
        title: 'Salvando...',
        text: 'Criando nova DRE',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
});

// Exibir mensagens de sessão
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Sucesso!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Erro!',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif
</script>
@endsection
