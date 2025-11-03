@extends('layouts.app')

@section('title', 'Histórico de Alterações')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Histórico de Alterações</h1>
            <p class="text-gray-600">Rastreamento completo de todas as mudanças no sistema</p>
        </div>
    </div>

    <!-- Filtros -->
    <div class="mb-6 bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Filtros</h3>
                    </div>
            @if(request()->hasAny(['periodo', 'usuario', 'acao', 'tipo', 'data_inicio', 'data_fim']) && (request('periodo') != '5_dias' || request()->hasAny(['usuario', 'acao', 'tipo', 'data_inicio', 'data_fim'])))
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">Filtros ativos:</span>
                    @if(request('periodo') && request('periodo') != '5_dias')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            Período: {{ match(request('periodo')) {
                                'hoje' => 'Hoje',
                                'ontem' => 'Ontem',
                                '3_dias' => 'Últimos 3 dias',
                                '5_dias' => 'Últimos 5 dias',
                                '7_dias' => 'Últimos 7 dias',
                                '15_dias' => 'Últimos 15 dias',
                                '30_dias' => 'Últimos 30 dias',
                                'todos' => 'Todos',
                                default => request('periodo')
                            } }}
                        </span>
                    @endif
                    @if(request('usuario'))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Usuário: {{ request('usuario') }}
                        </span>
                    @endif
                    @if(request('acao'))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Ação: {{ request('acao') }}
                        </span>
                    @endif
                    @if(request('data_inicio'))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            De: {{ request('data_inicio') }}
                        </span>
                    @endif
                    @if(request('data_fim'))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            Até: {{ request('data_fim') }}
                        </span>
                    @endif
                </div>
            @endif
        </div>

        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            <!-- Período -->
            <div class="min-w-0">
                <label for="periodo" class="block text-sm font-medium text-gray-700 mb-1">Período</label>
                <select name="periodo" id="periodo" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    <option value="hoje" {{ $filtroPeriodo == 'hoje' ? 'selected' : '' }}>Hoje</option>
                    <option value="ontem" {{ $filtroPeriodo == 'ontem' ? 'selected' : '' }}>Ontem</option>
                    <option value="3_dias" {{ $filtroPeriodo == '3_dias' ? 'selected' : '' }}>Últimos 3 dias</option>
                    <option value="5_dias" {{ $filtroPeriodo == '5_dias' ? 'selected' : '' }}>Últimos 5 dias</option>
                    <option value="7_dias" {{ $filtroPeriodo == '7_dias' ? 'selected' : '' }}>Últimos 7 dias</option>
                    <option value="15_dias" {{ $filtroPeriodo == '15_dias' ? 'selected' : '' }}>Últimos 15 dias</option>
                    <option value="30_dias" {{ $filtroPeriodo == '30_dias' ? 'selected' : '' }}>Últimos 30 dias</option>
                    <option value="todos" {{ $filtroPeriodo == 'todos' ? 'selected' : '' }}>Todos</option>
                </select>
            </div>

            <!-- Usuário -->
            <div class="min-w-0">
                <label for="usuario" class="block text-sm font-medium text-gray-700 mb-1">Usuário</label>
                <select name="usuario" id="usuario" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    <option value="">Todos os usuários</option>
                    @foreach($usuarios as $usuario)
                        <option value="{{ $usuario }}" {{ $filtroUsuario == $usuario ? 'selected' : '' }}>
                            {{ $usuario }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Ação -->
            <div class="min-w-0">
                <label for="acao" class="block text-sm font-medium text-gray-700 mb-1">Ação</label>
                <select name="acao" id="acao" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    <option value="">Todas as ações</option>
                    @foreach($acoes as $acaoKey => $acaoLabel)
                        <option value="{{ $acaoKey }}" {{ $filtroAcao == $acaoKey ? 'selected' : '' }}>
                            {{ $acaoLabel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tipo -->
            <div class="min-w-0">
                <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                <select name="tipo" id="tipo" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    <option value="">Todos os tipos</option>
                    @foreach($modelos as $modelo)
                        <option value="{{ $modelo }}" {{ $filtroTipo == $modelo ? 'selected' : '' }}>
                            {{ \App\Models\AuditLog::make(['model_type' => $modelo])->getModelLabel() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- ID -->
            <div class="min-w-0">
                <label for="id" class="block text-sm font-medium text-gray-700 mb-1">ID</label>
                <input type="text"
                       name="id"
                       id="id"
                       value="{{ $filtroId }}"
                       placeholder="Digite o ID"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>

            <!-- Empresa -->
            <div class="min-w-0">
                <label for="empresa" class="block text-sm font-medium text-gray-700 mb-1">Empresa</label>
                <select name="empresa" id="empresa" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    <option value="">Todas as empresas</option>
                    @foreach($empresas as $empresa)
                        <option value="{{ $empresa['id'] }}" {{ $filtroEmpresa == $empresa['id'] ? 'selected' : '' }}>
                            {{ $empresa['nome'] }}{{ $empresa['is_principal'] ? ' (Principal)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Data Início -->
            <div id="data-inicio-container" class="min-w-0" style="display: {{ $filtroPeriodo == 'todos' ? 'block' : 'none' }};">
                <label for="data_inicio" class="block text-sm font-medium text-gray-700 mb-1">Data Início</label>
                <input type="date"
                       name="data_inicio"
                       id="data_inicio"
                       value="{{ $filtroDataInicio }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>

            <!-- Data Fim -->
            <div id="data-fim-container" class="min-w-0" style="display: {{ $filtroPeriodo == 'todos' ? 'block' : 'none' }};">
                <label for="data_fim" class="block text-sm font-medium text-gray-700 mb-1">Data Fim</label>
                <input type="date"
                       name="data_fim"
                       id="data_fim"
                       value="{{ $filtroDataFim }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>

            <!-- Botões -->
            <div class="xl:col-span-4 flex items-end space-x-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-search mr-2"></i>
                    Filtrar
                </button>
                <a href="{{ route('audit.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-times mr-2"></i>
                    Limpar
                </a>
            </div>
        </form>
    </div>

    <!-- Tabela de Logs -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Registros de Alteração</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Lista de todas as alterações realizadas no sistema</p>
        </div>

        @if($logs->count() > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($logs as $log)
                    <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="flex items-center">
                                        <p class="text-sm font-medium text-gray-900">{{ $log->user_name ?? 'Sistema' }}</p>
                                        @if($log->empresa_nome)
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <i class="fas fa-building mr-1"></i>
                                                {{ $log->empresa_nome }}
                                            </span>
                                        @endif
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $log->getActionColor() }}-100 text-{{ $log->getActionColor() }}-800">
                                            <i class="fas fa-{{ $log->getActionIcon() }} mr-1"></i>
                                            {{ $log->getActionLabel() }}
                                        </span>
                                    </div>
                                    <div class="mt-1">
                                        <p class="text-sm text-gray-500">{{ $log->description }}</p>
                                        @if($log->hasAuditChanges())
                                            <p class="text-xs text-blue-600 mt-1">
                                                <i class="fas fa-exchange-alt mr-1"></i>
                                                {{ $log->getChangesSummary() }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="text-right">
                                    <p class="text-sm text-gray-900">{{ $log->getFormattedDate() }}</p>
                                    <p class="text-xs text-gray-500">{{ $log->getRelativeDate() }}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $log->getModelLabel() }}
                                    </span>
                                    <a href="{{ route('audit.show', $log) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                        <i class="fas fa-eye mr-1"></i>
                                        Ver detalhes
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-center py-12">
                <div class="mx-auto h-12 w-12 text-gray-400">
                    <i class="fas fa-history text-4xl"></i>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum registro encontrado</h3>
                <p class="mt-1 text-sm text-gray-500">Não há alterações registradas para os filtros selecionados.</p>
            </div>
        @endif
    </div>

    <!-- Paginação -->
    @if($logs->hasPages())
        <div class="mt-6 bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 rounded-lg shadow">
            <div class="flex-1 flex justify-between sm:hidden">
                @if($logs->onFirstPage())
                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-500 bg-gray-50 cursor-not-allowed">
                        Anterior
                    </span>
                @else
                    <a href="{{ $logs->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Anterior
                    </a>
                @endif

                @if($logs->hasMorePages())
                    <a href="{{ $logs->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Próximo
                    </a>
                @else
                    <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-500 bg-gray-50 cursor-not-allowed">
                        Próximo
                    </span>
                @endif
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Mostrando
                        <span class="font-medium">{{ $logs->firstItem() }}</span>
                        até
                        <span class="font-medium">{{ $logs->lastItem() }}</span>
                        de
                        <span class="font-medium">{{ $logs->total() }}</span>
                        resultados
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        @if($logs->onFirstPage())
                            <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-50 text-sm font-medium text-gray-500 cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $logs->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        @foreach($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)
                            @if($page == $logs->currentPage())
                                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-indigo-50 text-sm font-medium text-indigo-600">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        @if($logs->hasMorePages())
                            <a href="{{ $logs->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-50 text-sm font-medium text-gray-500 cursor-not-allowed">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif
                    </nav>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const periodoSelect = document.getElementById('periodo');
    const dataInicioContainer = document.getElementById('data-inicio-container');
    const dataFimContainer = document.getElementById('data-fim-container');
    const dataInicioInput = document.getElementById('data_inicio');
    const dataFimInput = document.getElementById('data_fim');

    function toggleDateFields() {
        if (periodoSelect.value === 'todos') {
            // Mostrar campos de data
            dataInicioContainer.style.display = 'block';
            dataFimContainer.style.display = 'block';
        } else {
            // Ocultar campos de data e limpar valores
            dataInicioContainer.style.display = 'none';
            dataFimContainer.style.display = 'none';
            dataInicioInput.value = '';
            dataFimInput.value = '';
        }
    }

    // Aplicar estado inicial
    toggleDateFields();

    // Escutar mudanças no select
    periodoSelect.addEventListener('change', toggleDateFields);
});
</script>
@endsection
