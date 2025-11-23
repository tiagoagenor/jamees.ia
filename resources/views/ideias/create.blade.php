@extends('layouts.app')

@section('title', 'Compartilhar Ideia - Portal de Ideias')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
            <li><i class="fas fa-chevron-right text-gray-400"></i></li>
            <li><a href="{{ route('ideias.index') }}" class="hover:text-blue-600">Portal de Ideias</a></li>
            <li><i class="fas fa-chevron-right text-gray-400"></i></li>
            <li class="text-gray-900 font-medium">Compartilhar Ideia</li>
        </ol>
    </nav>

    <!-- Botão Voltar -->
    <div class="mb-6">
        <a href="{{ route('ideias.index') }}" 
           class="inline-flex items-center text-blue-600 hover:text-blue-700">
            <i class="fas fa-arrow-left mr-2"></i>
            Voltar para Portal de Ideias
        </a>
    </div>

    <!-- Formulário -->
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Compartilhar Nova Ideia</h1>

            <form action="{{ route('ideias.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <!-- Título -->
                    <div>
                        <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">
                            Título da Ideia <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="titulo" 
                               name="titulo" 
                               value="{{ old('titulo') }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Ex: Adicionar chat interno entre funcionários">
                        @error('titulo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descrição -->
                    <div>
                        <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">
                            Descrição <span class="text-red-500">*</span>
                        </label>
                        <textarea id="descricao" 
                                  name="descricao" 
                                  rows="8"
                                  required
                                  class="w-full px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Descreva sua ideia em detalhes...">{{ old('descricao') }}</textarea>
                        @error('descricao')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Categoria -->
                    <div>
                        <label for="categoria" class="block text-sm font-medium text-gray-700 mb-2">
                            Categoria <span class="text-red-500">*</span>
                        </label>
                        <select id="categoria" 
                                name="categoria" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Selecione uma categoria</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria }}" {{ old('categoria') === $categoria ? 'selected' : '' }}>
                                    {{ $categoria }}
                                </option>
                            @endforeach
                        </select>
                        @error('categoria')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo para categoria customizada -->
                    <div id="categoria-custom" class="hidden">
                        <label for="categoria_outros" class="block text-sm font-medium text-gray-700 mb-2">
                            Especifique a categoria <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="categoria_outros" 
                               name="categoria_outros" 
                               value="{{ old('categoria_outros') }}"
                               class="w-full px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Digite o nome da categoria">
                    </div>


                    <!-- Botões -->
                    <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('ideias.index') }}" 
                           class="px-6 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-green-600 text-white hover:bg-green-700">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Compartilhar Ideia
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('categoria').addEventListener('change', function() {
    const categoriaCustom = document.getElementById('categoria-custom');
    const categoriaOutros = document.getElementById('categoria_outros');
    
    if (this.value === 'Outros') {
        categoriaCustom.classList.remove('hidden');
        categoriaOutros.required = true;
    } else {
        categoriaCustom.classList.add('hidden');
        categoriaOutros.required = false;
        categoriaOutros.value = '';
    }
});

// Se categoria for "Outros", usar o valor do campo customizado
document.querySelector('form').addEventListener('submit', function(e) {
    const categoria = document.getElementById('categoria');
    const categoriaOutros = document.getElementById('categoria_outros');
    
    if (categoria.value === 'Outros') {
        if (!categoriaOutros.value.trim()) {
            e.preventDefault();
            alert('Por favor, especifique a categoria.');
            return false;
        }
        // Criar um input hidden com o valor correto
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'categoria';
        hiddenInput.value = categoriaOutros.value.trim();
        this.appendChild(hiddenInput);
        categoria.disabled = true;
    }
});
</script>
@endsection

