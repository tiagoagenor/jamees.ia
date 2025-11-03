@extends('layouts.app')

@section('title', 'Criar Status de Lote')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-tags text-blue-600 mr-2"></i>
                Novo Status de Lote
            </h1>
            <p class="text-gray-600 mt-1">Configure um novo status para os lotes</p>
        </div>

        <form method="POST" action="{{ route('lote-status.store') }}">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                    <input type="text" id="nome" name="nome" value="{{ old('nome') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('nome')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
                    <select id="tipo" name="tipo" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="1" {{ old('tipo', '1') == '1' ? 'selected' : '' }}>Disponível/Negociação/Reserva</option>
                        <option value="2" {{ old('tipo') == '2' ? 'selected' : '' }}>Vendido</option>
                    </select>
                    @error('tipo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="cor" class="block text-sm font-medium text-gray-700 mb-1">Cor *</label>
                    <div class="flex items-center space-x-3">
                        <input type="color" id="cor" name="cor" value="{{ old('cor', '#10b981') }}" required
                            class="h-10 w-20 border border-gray-300 rounded cursor-pointer">
                        <input type="text" id="cor-text" value="{{ old('cor', '#10b981') }}"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="#10b981">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Selecione uma cor para identificar este status</p>
                    @error('cor')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('lote-status.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md text-sm font-medium">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Salvar Status
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('cor').addEventListener('input', function() {
    document.getElementById('cor-text').value = this.value;
});

document.getElementById('cor-text').addEventListener('input', function() {
    if (this.value.match(/^#[0-9A-F]{6}$/i)) {
        document.getElementById('cor').value = this.value;
    }
});
</script>
@endsection

