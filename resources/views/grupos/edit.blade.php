@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Editar Grupo: {{ $grupo->nome }}</h2>
                        <p class="text-gray-600">Configure as permissões do grupo</p>
                    </div>
                    <a href="{{ route('grupos.index') }}"
                       class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Voltar
                    </a>
                </div>

                <!-- Form -->
                <form action="{{ route('grupos.update', $grupo) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Básicas</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nome do Grupo <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="nome"
                                       name="nome"
                                       value="{{ old('nome', $grupo->nome) }}"
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('nome') border-red-500 @enderror">
                                @error('nome')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">
                                    Descrição
                                </label>
                                <textarea id="descricao"
                                          name="descricao"
                                          rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('descricao') border-red-500 @enderror">{{ old('descricao', $grupo->descricao) }}</textarea>
                                @error('descricao')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        @if($grupo->administrativo)
                            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-md">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-crown text-red-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800">
                                            Grupo Administrativo
                                        </h3>
                                        <div class="mt-2 text-sm text-red-700">
                                            <p>Este é um grupo administrativo e possui todas as permissões do sistema.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Permissions -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Permissões</h3>
                            @if(!$grupo->administrativo)
                                <div class="flex space-x-2">
                                    <button type="button"
                                            onclick="selectAllPermissions()"
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm">
                                        <i class="fas fa-check mr-1"></i>
                                        Liberar Todas
                                    </button>
                                    <button type="button"
                                            onclick="deselectAllPermissions()"
                                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm">
                                        <i class="fas fa-times mr-1"></i>
                                        Bloquear Todas
                                    </button>
                                </div>
                            @endif
                        </div>

                        @if($grupo->administrativo)
                            <div class="p-4 bg-blue-50 border border-blue-200 rounded-md">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-blue-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">
                                            Acesso Total
                                        </h3>
                                        <div class="mt-2 text-sm text-blue-700">
                                            <p>O grupo administrativo possui acesso total a todas as funcionalidades do sistema.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($permissoes as $modulo => $permissoesModulo)
                                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                                        <h4 class="font-medium text-gray-900 mb-3 capitalize">
                                            {{ str_replace('_', ' ', $modulo) }}
                                        </h4>
                                        <div class="space-y-2 max-h-48 overflow-y-auto">
                                            @foreach($permissoesModulo as $permissao)
                                                <label class="flex items-center space-x-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                                    <input type="checkbox"
                                                           name="permissoes[]"
                                                           value="{{ $permissao->id }}"
                                                           {{ in_array($permissao->id, old('permissoes', $grupo->permissoes->pluck('id')->toArray())) ? 'checked' : '' }}
                                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded permission-checkbox">
                                                    <span class="text-sm text-gray-700">{{ $permissao->nome }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('grupos.index') }}"
                           class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded-lg">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                            <i class="fas fa-save mr-2"></i>
                            Atualizar Grupo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function selectAllPermissions() {
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.checked = true;
    });
}

function deselectAllPermissions() {
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
}
</script>
@endsection



