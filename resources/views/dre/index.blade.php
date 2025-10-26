@extends('layouts.app')

@section('title', 'DRE - Demonstrativo de Resultado do Exercício')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-chart-line text-blue-600 mr-3"></i>
                    DRE - Demonstrativo de Resultado do Exercício
                </h1>
                <p class="text-gray-600 mt-2">Gerencie a estrutura do seu demonstrativo de resultado</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('dre.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-plus mr-2"></i>
                    Nova DRE
                </a>
            </div>
        </div>
    </div>

    <!-- DRE List -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Estrutura DRE</h3>
        </div>

        @if($dres->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($dres as $dre)
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center
                                        @if($dre->isReceita()) bg-green-100 text-green-600
                                        @elseif($dre->isDespesa()) bg-red-100 text-red-600
                                        @else bg-blue-100 text-blue-600 @endif">
                                        <i class="{{ $dre->getTipoIcon() }}"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-lg font-medium text-gray-900">{{ $dre->nome }}</h4>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($dre->isReceita()) bg-green-100 text-green-800
                                            @elseif($dre->isDespesa()) bg-red-100 text-red-800
                                            @else bg-blue-100 text-blue-800 @endif">
                                            {{ $dre->getTipoLabel() }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $dre->isAtivo() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $dre->isAtivo() ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('dre.show', $dre) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                    <i class="fas fa-eye mr-1"></i>
                                    Ver
                                </a>
                                <a href="{{ route('dre.edit', $dre) }}" class="text-yellow-600 hover:text-yellow-900 text-sm font-medium">
                                    <i class="fas fa-edit mr-1"></i>
                                    Editar
                                </a>
                                <button onclick="confirmarToggleStatus('{{ $dre->id }}', '{{ $dre->nome }}', {{ $dre->isAtivo() ? 'true' : 'false' }})"
                                        class="text-purple-600 hover:text-purple-900 text-sm font-medium">
                                    <i class="fas fa-toggle-{{ $dre->isAtivo() ? 'on' : 'off' }} mr-1"></i>
                                    {{ $dre->isAtivo() ? 'Desativar' : 'Ativar' }}
                                </button>
                                <button onclick="confirmarExclusao('{{ $dre->id }}', '{{ $dre->nome }}')"
                                        class="text-red-600 hover:text-red-900 text-sm font-medium">
                                    <i class="fas fa-trash mr-1"></i>
                                    Excluir
                                </button>
                            </div>
                        </div>

                        @if($dre->children->count() > 0)
                            <div class="mt-4 ml-14">
                                <div class="border-l-2 border-gray-200 pl-4">
                                    <h5 class="text-sm font-medium text-gray-700 mb-2">Subitens:</h5>
                                    <div class="space-y-2">
                                        @foreach($dre->children as $child)
                                            <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded-md">
                                                <div class="flex items-center space-x-2">
                                                    <i class="{{ $child->getTipoIcon() }} text-sm
                                                        @if($child->isReceita()) text-green-600
                                                        @elseif($child->isDespesa()) text-red-600
                                                        @else text-blue-600 @endif"></i>
                                                    <span class="text-sm text-gray-900">{{ $child->nome }}</span>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                        @if($child->isReceita()) bg-green-100 text-green-800
                                                        @elseif($child->isDespesa()) bg-red-100 text-red-800
                                                        @else bg-blue-100 text-blue-800 @endif">
                                                        {{ $child->getTipoLabel() }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ route('dre.show', $child) }}" class="text-blue-600 hover:text-blue-900 text-xs">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('dre.edit', $child) }}" class="text-yellow-600 hover:text-yellow-900 text-xs">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-12 text-center">
                <i class="fas fa-chart-line text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma DRE encontrada</h3>
                <p class="text-gray-600 mb-6">Comece criando uma nova estrutura DRE para sua empresa.</p>
                <a href="{{ route('dre.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-plus mr-2"></i>
                    Criar Primeira DRE
                </a>
            </div>
        @endif
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
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

// Confirmar exclusão
function confirmarExclusao(dreId, dreNome) {
    Swal.fire({
        title: 'Confirmar Exclusão',
        text: `Tem certeza que deseja excluir "${dreNome}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Criar formulário para exclusão
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/dre/${dreId}`;

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';

            const tokenField = document.createElement('input');
            tokenField.type = 'hidden';
            tokenField.name = '_token';
            tokenField.value = '{{ csrf_token() }}';

            form.appendChild(methodField);
            form.appendChild(tokenField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Confirmar toggle de status
function confirmarToggleStatus(dreId, dreNome, isAtivo) {
    const action = isAtivo ? 'desativar' : 'ativar';
    const icon = isAtivo ? 'warning' : 'success';

    Swal.fire({
        title: `Confirmar ${action.charAt(0).toUpperCase() + action.slice(1)}`,
        text: `Tem certeza que deseja ${action} "${dreNome}"?`,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: isAtivo ? '#d33' : '#28a745',
        cancelButtonColor: '#3085d6',
        confirmButtonText: `Sim, ${action}!`,
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Criar formulário para toggle status
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/dre/${dreId}/toggle-status`;

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'PATCH';

            const tokenField = document.createElement('input');
            tokenField.type = 'hidden';
            tokenField.name = '_token';
            tokenField.value = '{{ csrf_token() }}';

            form.appendChild(methodField);
            form.appendChild(tokenField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endsection
