@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Novo Centro de Custo</h2>
                    <a href="{{ route('centro-custo.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Voltar
                    </a>
                </div>

                <form action="{{ route('centro-custo.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nome -->
                        <div>
                            <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                                Nome do Centro de Custo <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="nome"
                                   id="nome"
                                   value="{{ old('nome') }}"
                                   required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nome') border-red-500 @enderror"
                                   placeholder="Digite o nome do centro de custo">
                            @error('nome')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status"
                                    id="status"
                                    required
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                                <option value="">Selecione o status</option>
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Ativo</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inativo</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('centro-custo.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md text-sm font-medium">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-save mr-2"></i>
                            Salvar Centro de Custo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
