@extends('layouts.app')

@section('title', 'Detalhes da DRE')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-chart-pie text-blue-600 mr-3"></i>
                    {{ $dre->nome }}
                </h1>
                <div class="flex items-center space-x-4 mt-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($dre->isReceita()) bg-green-100 text-green-800
                        @elseif($dre->isDespesa()) bg-red-100 text-red-800
                        @else bg-blue-100 text-blue-800 @endif">
                        <i class="{{ $dre->getTipoIcon() }} mr-2"></i>
                        {{ $dre->getTipoLabel() }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $dre->isAtivo() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <i class="fas fa-toggle-{{ $dre->isAtivo() ? 'on' : 'off' }} mr-2"></i>
                        {{ $dre->isAtivo() ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('dre.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar
                </a>
                <a href="{{ route('dre.edit', $dre) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-edit mr-2"></i>
                    Editar
                </a>
            </div>
        </div>
    </div>

    <!-- DRE Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informações da DRE</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nome</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $dre->nome }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tipo</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($dre->isReceita()) bg-green-100 text-green-800
                                    @elseif($dre->isDespesa()) bg-red-100 text-red-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    <i class="{{ $dre->getTipoIcon() }} mr-1"></i>
                                    {{ $dre->getTipoLabel() }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $dre->isAtivo() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas fa-toggle-{{ $dre->isAtivo() ? 'on' : 'off' }} mr-1"></i>
                                    {{ $dre->isAtivo() ? 'Ativo' : 'Inativo' }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nível</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $dre->getLevel() }}</dd>
                        </div>
                        @if($dre->parent)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">DRE Pai</dt>
                            <dd class="mt-1">
                                <a href="{{ route('dre.show', $dre->parent) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                    {{ $dre->parent->nome }}
                                </a>
                            </dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $dre->criado_em ? $dre->criado_em->format('d/m/Y H:i') : 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Atualizado em</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $dre->atualizado_em ? $dre->atualizado_em->format('d/m/Y H:i') : 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Ações Rápidas</h3>
                </div>
                <div class="p-6 space-y-3">
                    <button onclick="confirmarToggleStatus('{{ $dre->id }}', '{{ $dre->nome }}', {{ $dre->isAtivo() ? 'true' : 'false' }})"
                            class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-toggle-{{ $dre->isAtivo() ? 'on' : 'off' }} mr-2"></i>
                        {{ $dre->isAtivo() ? 'Desativar' : 'Ativar' }}
                    </button>
                    <button onclick="confirmarExclusao('{{ $dre->id }}', '{{ $dre->nome }}')"
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-trash mr-2"></i>
                        Excluir
                    </button>
                </div>
            </div>

            <!-- Hierarchy -->
            @if($dre->parent || $dre->children->count() > 0)
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Hierarquia</h3>
                </div>
                <div class="p-6">
                    @if($dre->parent)
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">DRE Pai:</h4>
                        <a href="{{ route('dre.show', $dre->parent) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                            {{ $dre->parent->nome }}
                        </a>
                    </div>
                    @endif

                    @if($dre->children->count() > 0)
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Subitens ({{ $dre->children->count() }}):</h4>
                        <div class="space-y-2">
                            @foreach($dre->children as $child)
                            <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded-md">
                                <div class="flex items-center space-x-2">
                                    <i class="{{ $child->getTipoIcon() }} text-sm
                                        @if($child->isReceita()) text-green-600
                                        @elseif($child->isDespesa()) text-red-600
                                        @else text-blue-600 @endif"></i>
                                    <span class="text-sm text-gray-900">{{ $child->nome }}</span>
                                </div>
                                <a href="{{ route('dre.show', $child) }}" class="text-blue-600 hover:text-blue-900 text-xs">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
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
