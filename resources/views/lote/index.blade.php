@extends('layouts.app')

@section('title', 'Lotes - ' . $empreendimento->nome)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-map-marked-alt text-blue-600 mr-3"></i>
                    Lotes - {{ $empreendimento->nome }}
                </h1>
                <p class="mt-2 text-gray-600">Gerencie os lotes do empreendimento</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('lotes.create', $empreendimento->id) }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Novo Lote
                </a>
                <!-- Botão com Dropdown para Importar CSV -->
                <div class="relative inline-block text-left">
                    <div class="flex">
                        <a href="{{ route('lotes.import', $empreendimento->id) }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-l-md text-sm font-medium flex items-center">
                            <i class="fas fa-upload mr-2"></i>
                            Importar CSV
                        </a>
                        <button type="button" onclick="toggleCsvDropdown()"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-2 rounded-r-md border-l border-blue-500 text-sm font-medium">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <div id="csv-dropdown" class="hidden absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                        <div class="py-1" role="menu" aria-orientation="vertical">
                            <a href="{{ route('lotes.download-exemplo-csv', $empreendimento->id) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 flex items-center"
                               role="menuitem">
                                <i class="fas fa-download mr-2 text-green-600"></i>
                                Baixar Exemplo CSV
                            </a>
                        </div>
                    </div>
                </div>
                <a href="{{ route('quadras.index', $empreendimento->id) }}"
                   class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                    <i class="fas fa-th mr-2"></i>
                    Ver Quadras
                </a>
                <a href="{{ route('empreendimentos.mapa', $empreendimento->id) }}"
                   class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                    <i class="fas fa-map mr-2"></i>
                    Mapa
                </a>
                <a href="{{ route('empreendimentos.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-filter text-blue-600 mr-2"></i>
                    Filtros
                </h3>
                <div class="text-sm text-gray-600">
                    <span class="font-medium">{{ $lotes->total() }}</span> lote(s) encontrado(s)
                    @if($filtroNome)
                        <span class="text-blue-600">para "{{ $filtroNome }}"</span>
                    @endif
                    @if($filtroQuadra)
                        <span class="text-blue-600">na quadra "{{ $filtroQuadra }}"</span>
                    @endif
                    @if($filtroStatus)
                        <span class="text-blue-600">com status "{{ $filtroStatus }}"</span>
                    @endif
                    @if($filtroValorMin || $filtroValorMax)
                        <span class="text-blue-600">
                            @if($filtroValorMin && $filtroValorMax)
                                com valor entre R$ {{ number_format($filtroValorMin, 2, ',', '.') }} e R$ {{ number_format($filtroValorMax, 2, ',', '.') }}
                            @elseif($filtroValorMin)
                                com valor mínimo R$ {{ number_format($filtroValorMin, 2, ',', '.') }}
                            @elseif($filtroValorMax)
                                com valor máximo R$ {{ number_format($filtroValorMax, 2, ',', '.') }}
                            @endif
                        </span>
                    @endif
                    @if($lotes->total() > 0)
                        <span class="text-gray-500">(página {{ $lotes->currentPage() }} de {{ $lotes->lastPage() }})</span>
                    @endif
                </div>
            </div>

            <form id="filtros-form" method="GET" action="{{ route('lotes.index', $empreendimento->id) }}" class="space-y-4">
                <!-- Filtros Principais -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Filtro por Nome -->
                    <div class="space-y-2">
                        <label for="nome" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-search text-gray-400 mr-1"></i>
                            Nome do Lote
                        </label>
                        <input type="text"
                               name="nome"
                               id="nome"
                               value="{{ $filtroNome }}"
                               placeholder="Digite o nome do lote..."
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Filtro por Quadra -->
                    <div class="space-y-2">
                        <label for="quadra" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-th text-gray-400 mr-1"></i>
                            Quadra
                        </label>
                        <select name="quadra" id="quadra" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todas as quadras</option>
                            @foreach($quadras as $quadra)
                                <option value="{{ $quadra->id }}" {{ $filtroQuadra == $quadra->id ? 'selected' : '' }}>{{ $quadra->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro por Status -->
                    <div class="space-y-2">
                        <label for="status" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-tags text-gray-400 mr-1"></i>
                            Status
                        </label>
                        <select name="status" id="status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos os status</option>
                            @foreach($statusDisponiveis as $status)
                                <option value="{{ $status->id }}" {{ $filtroStatus == $status->id ? 'selected' : '' }}>{{ $status->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro por Valor Mínimo -->
                    <div class="space-y-2">
                        <label for="valor_min" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-dollar-sign text-gray-400 mr-1"></i>
                            Valor Mínimo
                        </label>
                        <input type="number"
                               name="valor_min"
                               id="valor_min"
                               value="{{ $filtroValorMin }}"
                               step="0.01"
                               min="0"
                               placeholder="R$ 0,00"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Filtro por Valor Máximo -->
                    <div class="space-y-2">
                        <label for="valor_max" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-dollar-sign text-gray-400 mr-1"></i>
                            Valor Máximo
                        </label>
                        <input type="number"
                               name="valor_max"
                               id="valor_max"
                               value="{{ $filtroValorMax }}"
                               step="0.01"
                               min="0"
                               placeholder="R$ 0,00"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="flex justify-end space-x-2 pt-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-search mr-2"></i>
                        Filtrar
                    </button>
                    <a href="{{ route('lotes.index', $empreendimento->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Limpar
                    </a>
                </div>

                <!-- Filtros Ativos -->
                @if($filtroNome || $filtroQuadra || $filtroStatus || $filtroValorMin || $filtroValorMax)
                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium text-gray-700">Filtros ativos:</span>
                            @if($filtroNome)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-search mr-1"></i>
                                    Nome: "{{ $filtroNome }}"
                                    <button type="button" onclick="limparFiltroNome(event)" class="ml-1 text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </span>
                            @endif
                            @if($filtroQuadra)
                                @php
                                    $quadraNome = $quadras->firstWhere('id', $filtroQuadra)->nome ?? '';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-th mr-1"></i>
                                    Quadra: "{{ $quadraNome }}"
                                    <button type="button" onclick="limparFiltroQuadra(event)" class="ml-1 text-purple-600 hover:text-purple-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </span>
                            @endif
                            @if($filtroStatus)
                                @php
                                    $statusNome = $statusDisponiveis->firstWhere('id', $filtroStatus)->nome ?? '';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-tags mr-1"></i>
                                    Status: "{{ $statusNome }}"
                                    <button type="button" onclick="limparFiltroStatus(event)" class="ml-1 text-green-600 hover:text-green-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </span>
                            @endif
                            @if($filtroValorMin)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    <i class="fas fa-dollar-sign mr-1"></i>
                                    Valor Mín: R$ {{ number_format($filtroValorMin, 2, ',', '.') }}
                                    <button type="button" onclick="limparFiltroValorMin(event)" class="ml-1 text-orange-600 hover:text-orange-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </span>
                            @endif
                            @if($filtroValorMax)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-dollar-sign mr-1"></i>
                                    Valor Máx: R$ {{ number_format($filtroValorMax, 2, ',', '.') }}
                                    <button type="button" onclick="limparFiltroValorMax(event)" class="ml-1 text-yellow-600 hover:text-yellow-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Lista de Lotes -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-8 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">Lotes</h3>
            <button type="button" onclick="deletarEmMassa()" id="btn-deletar-massa" disabled
               class="bg-red-600 hover:bg-red-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                <i class="fas fa-trash mr-2"></i>
                Deletar em Massa (<span id="count-selecionados">0</span>)
            </button>
        </div>

        @if($lotes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-8 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">
                                <input type="checkbox" id="select-all" onchange="toggleSelectAll()"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="px-8 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                @php
                                    $isCol = request('sort_by') === 'nome';
                                    $dir = request('sort_direction');
                                    $params = request()->query();
                                    if (!$isCol) { $params['sort_by'] = 'nome'; $params['sort_direction'] = 'desc'; }
                                    elseif ($dir === 'desc') { $params['sort_direction'] = 'asc'; }
                                    else { unset($params['sort_by'], $params['sort_direction']); }
                                    $url = url()->current() . (count($params) ? ('?' . http_build_query($params)) : '');
                                @endphp
                                <a href="{{ $url }}" class="flex items-center space-x-1 hover:text-gray-700">
                                    <span>Nome</span>
                                    @if($isCol)
                                        <i class="fas fa-sort-{{ $dir === 'asc' ? 'up' : 'down' }} text-indigo-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="px-8 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                @php
                                    $isCol = request('sort_by') === 'quadra';
                                    $dir = request('sort_direction');
                                    $params = request()->query();
                                    if (!$isCol) { $params['sort_by'] = 'quadra'; $params['sort_direction'] = 'desc'; }
                                    elseif ($dir === 'desc') { $params['sort_direction'] = 'asc'; }
                                    else { unset($params['sort_by'], $params['sort_direction']); }
                                    $url = url()->current() . (count($params) ? ('?' . http_build_query($params)) : '');
                                @endphp
                                <a href="{{ $url }}" class="flex items-center space-x-1 hover:text-gray-700">
                                    <span>Quadra</span>
                                    @if($isCol)
                                        <i class="fas fa-sort-{{ $dir === 'asc' ? 'up' : 'down' }} text-indigo-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="px-8 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-8 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dimensões</th>
                            <th class="px-8 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                @php
                                    $isCol = request('sort_by') === 'm2';
                                    $dir = request('sort_direction');
                                    $params = request()->query();
                                    if (!$isCol) { $params['sort_by'] = 'm2'; $params['sort_direction'] = 'desc'; }
                                    elseif ($dir === 'desc') { $params['sort_direction'] = 'asc'; }
                                    else { unset($params['sort_by'], $params['sort_direction']); }
                                    $url = url()->current() . (count($params) ? ('?' . http_build_query($params)) : '');
                                @endphp
                                <a href="{{ $url }}" class="flex items-center space-x-1 hover:text-gray-700">
                                    <span>Área (m²)</span>
                                    @if($isCol)
                                        <i class="fas fa-sort-{{ $dir === 'asc' ? 'up' : 'down' }} text-indigo-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="px-8 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                @php
                                    $isCol = request('sort_by') === 'valor';
                                    $dir = request('sort_direction');
                                    $params = request()->query();
                                    if (!$isCol) { $params['sort_by'] = 'valor'; $params['sort_direction'] = 'desc'; }
                                    elseif ($dir === 'desc') { $params['sort_direction'] = 'asc'; }
                                    else { unset($params['sort_by'], $params['sort_direction']); }
                                    $url = url()->current() . (count($params) ? ('?' . http_build_query($params)) : '');
                                @endphp
                                <a href="{{ $url }}" class="flex items-center space-x-1 hover:text-gray-700">
                                    <span>Valor</span>
                                    @if($isCol)
                                        <i class="fas fa-sort-{{ $dir === 'asc' ? 'up' : 'down' }} text-indigo-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="px-8 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <span>Mapa</span>
                                    <div class="relative">
                                        <i id="info-mapa-icon" class="fas fa-info-circle text-gray-400 hover:text-gray-600 cursor-help"
                                           onmouseenter="showMapaTooltip(event)"
                                           onmouseleave="hideMapaTooltip()"></i>
                                        <div id="mapa-tooltip" class="fixed z-50 hidden px-3 py-2 text-xs font-normal text-white bg-gray-900 rounded shadow-lg whitespace-normal w-56 text-center pointer-events-none">
                                            Indica se o lote já foi posicionado no mapa interativo do empreendimento.
                                        </div>
                                    </div>
                                </div>
                            </th>
                            <th class="px-8 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($lotes as $lote)
                            @php
                                $isVendido = $lote->status && $lote->status->tipo == 2;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-8 py-4 whitespace-nowrap">
                                    <input type="checkbox"
                                        class="lote-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500 {{ $isVendido ? 'disabled opacity-50 cursor-not-allowed' : '' }}"
                                        value="{{ $lote->id }}"
                                        onchange="atualizarContador()"
                                        data-lote-nome="{{ $lote->nome }}"
                                        data-is-vendido="{{ $isVendido ? '1' : '0' }}"
                                        {{ $isVendido ? 'disabled' : '' }}>
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $lote->nome ?? 'N/A' }}</div>
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $lote->quadra->nome ?? '-' }}
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap">
                                    @if($lote->status)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full text-white" style="background-color: {{ $lote->status->cor }}">
                                            {{ $lote->status->nome }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($lote->frente && $lote->fundo)
                                        {{ number_format($lote->frente, 2, ',', '.') }} x {{ number_format($lote->fundo, 2, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $lote->m2 ? number_format($lote->m2, 2, ',', '.') . ' m²' : '-' }}
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $lote->valor ? 'R$ ' . number_format($lote->valor, 2, ',', '.') : '-' }}
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @php
                                        $posicaoPino = $lote->posicao_pino;
                                        $temPinoNoMapa = !empty($posicaoPino) &&
                                                       is_array($posicaoPino) &&
                                                       isset($posicaoPino['x']) &&
                                                       isset($posicaoPino['y']) &&
                                                       $posicaoPino['x'] !== null &&
                                                       $posicaoPino['y'] !== null &&
                                                       $posicaoPino['x'] !== '' &&
                                                       $posicaoPino['y'] !== '';
                                    @endphp
                                    @if($temPinoNoMapa)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Sim
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            Não
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-3">
                                        <!-- Visualizar -->
                                        <div class="relative group">
                                            <a href="{{ route('lotes.show', $lote->id) }}" class="text-blue-600 hover:text-blue-900 flex items-center">
                                                <i class="fas fa-eye"></i>
                                                <span class="sr-only">Visualizar</span>
                                            </a>
                                            <div class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-200 bg-gray-900 rounded shadow opacity-0 group-hover:visible group-hover:opacity-100 -top-8 left-1/2 -translate-x-1/2 whitespace-nowrap">
                                                Visualizar
                                                <div class="absolute w-2 h-2 bg-gray-900 rotate-45 left-1/2 -translate-x-1/2 top-full"></div>
                                            </div>
                                        </div>

                                        <!-- Editar -->
                                        <div class="relative group">
                                            <a href="{{ route('lotes.edit', $lote->id) }}" class="text-indigo-600 hover:text-indigo-900 flex items-center">
                                                <i class="fas fa-edit"></i>
                                                <span class="sr-only">Editar</span>
                                            </a>
                                            <div class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-200 bg-gray-900 rounded shadow opacity-0 group-hover:visible group-hover:opacity-100 -top-8 left-1/2 -translate-x-1/2 whitespace-nowrap">
                                                Editar
                                                <div class="absolute w-2 h-2 bg-gray-900 rotate-45 left-1/2 -translate-x-1/2 top-full"></div>
                                            </div>
                                        </div>

                                        <!-- Excluir -->
                                        @if($isVendido)
                                            <div class="relative group">
                                                <button disabled class="text-gray-400 cursor-not-allowed flex items-center" title="Não é possível deletar lotes vendidos">
                                                    <i class="fas fa-trash"></i>
                                                    <span class="sr-only">Excluir (desabilitado)</span>
                                                </button>
                                                <div class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-200 bg-gray-900 rounded shadow opacity-0 group-hover:visible group-hover:opacity-100 -top-8 left-1/2 -translate-x-1/2 whitespace-nowrap">
                                                    Não é possível deletar lotes vendidos
                                                    <div class="absolute w-2 h-2 bg-gray-900 rotate-45 left-1/2 -translate-x-1/2 top-full"></div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="relative group">
                                                <button onclick="confirmarExclusao('{{ $lote->id }}', '{{ $lote->nome ?? 'N/A' }}')" class="text-red-600 hover:text-red-900 flex items-center">
                                                    <i class="fas fa-trash"></i>
                                                    <span class="sr-only">Excluir</span>
                                                </button>
                                                <div class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-200 bg-gray-900 rounded shadow opacity-0 group-hover:visible group-hover:opacity-100 -top-8 left-1/2 -translate-x-1/2 whitespace-nowrap">
                                                    Excluir
                                                    <div class="absolute w-2 h-2 bg-gray-900 rotate-45 left-1/2 -translate-x-1/2 top-full"></div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <div class="px-8 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex-1 flex justify-between sm:hidden">
                        @if($lotes->onFirstPage())
                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-500 bg-gray-50 cursor-not-allowed">
                                Anterior
                            </span>
                        @else
                            <a href="{{ $lotes->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Anterior
                            </a>
                        @endif

                        @if($lotes->hasMorePages())
                            <a href="{{ $lotes->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
                                <span class="font-medium">{{ $lotes->firstItem() }}</span>
                                até
                                <span class="font-medium">{{ $lotes->lastItem() }}</span>
                                de
                                <span class="font-medium">{{ $lotes->total() }}</span>
                                resultados
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                @if($lotes->onFirstPage())
                                    <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-50 text-sm font-medium text-gray-500 cursor-not-allowed">
                                        <i class="fas fa-chevron-left"></i>
                                    </span>
                                @else
                                    <a href="{{ $lotes->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                @endif

                                @foreach($lotes->getUrlRange(1, $lotes->lastPage()) as $page => $url)
                                    @if($page == $lotes->currentPage())
                                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-indigo-50 text-sm font-medium text-indigo-600">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach

                                @if($lotes->hasMorePages())
                                    <a href="{{ $lotes->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
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
            </div>
        @else
            <div class="p-12 text-center">
                <i class="fas fa-inbox text-gray-400 text-6xl mb-4"></i>
                @if($filtroNome || $filtroQuadra || $filtroStatus || $filtroValorMin)
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum lote encontrado</h3>
                    <p class="text-gray-600 mb-6">Não foram encontrados lotes com os filtros aplicados.</p>
                @else
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum lote cadastrado</h3>
                    <p class="text-gray-600 mb-6">Comece criando o primeiro lote deste empreendimento.</p>
                @endif
                <div class="flex justify-center space-x-3">
                    <a href="{{ route('lotes.create', $empreendimento->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-plus mr-2"></i>
                        Novo Lote
                    </a>
                    @if($filtroNome || $filtroQuadra || $filtroStatus || $filtroValorMin || $filtroValorMax)
                        <a href="{{ route('lotes.index', $empreendimento->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-times mr-2"></i>
                            Limpar Filtros
                        </a>
                    @endif
                </div>
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
function confirmarExclusao(loteId, nomeLote) {
    Swal.fire({
        title: 'Confirmar Exclusão',
        text: `Tem certeza que deseja excluir o lote "${nomeLote}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Criar formulário para exclusão
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/lotes/${loteId}`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';

            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Limpar filtros
function limparFiltroNome(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    const nomeInput = document.getElementById('nome');
    if (nomeInput) {
        nomeInput.value = '';
    }
    const filterForm = document.getElementById('filtros-form');
    if (filterForm) {
        filterForm.submit();
    }
}

function limparFiltroQuadra(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    const quadraSelect = document.getElementById('quadra');
    if (quadraSelect) {
        quadraSelect.value = '';
    }
    const filterForm = document.getElementById('filtros-form');
    if (filterForm) {
        filterForm.submit();
    }
}

function limparFiltroStatus(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    const statusSelect = document.getElementById('status');
    if (statusSelect) {
        statusSelect.value = '';
    }
    const filterForm = document.getElementById('filtros-form');
    if (filterForm) {
        filterForm.submit();
    }
}

function limparFiltroValorMin(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    const valorMinInput = document.getElementById('valor_min');
    if (valorMinInput) {
        valorMinInput.value = '';
    }
    const filterForm = document.getElementById('filtros-form');
    if (filterForm) {
        filterForm.submit();
    }
}

function limparFiltroValorMax(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    const valorMaxInput = document.getElementById('valor_max');
    if (valorMaxInput) {
        valorMaxInput.value = '';
    }
    const filterForm = document.getElementById('filtros-form');
    if (filterForm) {
        filterForm.submit();
    }
}

// Toggle CSV Dropdown
function toggleCsvDropdown() {
    const dropdown = document.getElementById('csv-dropdown');
    dropdown.classList.toggle('hidden');
}

// Fechar dropdown ao clicar fora
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('csv-dropdown');
    const button = event.target.closest('[onclick="toggleCsvDropdown()"]');
    const dropdownElement = event.target.closest('#csv-dropdown');

    if (!button && !dropdownElement && !dropdown.classList.contains('hidden')) {
        dropdown.classList.add('hidden');
    }
});

// Selecionar/Deselecionar Todos
function toggleSelectAll() {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.lote-checkbox:not([disabled])');

    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });

    atualizarContador();
}

// Atualizar contador de selecionados
function atualizarContador() {
    const checkboxes = document.querySelectorAll('.lote-checkbox:checked');
    const count = checkboxes.length;
    const countSpan = document.getElementById('count-selecionados');
    const btnDeletar = document.getElementById('btn-deletar-massa');
    const selectAll = document.getElementById('select-all');

    countSpan.textContent = count;

    // Habilitar/desabilitar botão
    if (count > 0) {
        btnDeletar.disabled = false;
    } else {
        btnDeletar.disabled = true;
    }

    // Atualizar checkbox "Selecionar Todos"
    const totalCheckboxes = document.querySelectorAll('.lote-checkbox').length;
    if (totalCheckboxes > 0) {
        selectAll.checked = count === totalCheckboxes;
        selectAll.indeterminate = count > 0 && count < totalCheckboxes;
    }
}

// Deletar em massa
function deletarEmMassa() {
    const checkboxes = document.querySelectorAll('.lote-checkbox:checked');
    const ids = Array.from(checkboxes).map(cb => cb.value);

    if (ids.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Selecione pelo menos um lote para deletar.'
        });
        return;
    }

    // Verificar se algum lote selecionado está vendido
    const lotesVendidos = [];
    checkboxes.forEach(checkbox => {
        if (checkbox.dataset.isVendido === '1') {
            lotesVendidos.push(checkbox.dataset.loteNome || 'Lote desconhecido');
        }
    });

    if (lotesVendidos.length > 0) {
        let mensagem = 'Não é possível deletar lotes com status de vendido.';
        if (lotesVendidos.length === 1) {
            mensagem = `Não é possível deletar o lote "${lotesVendidos[0]}" pois ele está com status de vendido.`;
        } else {
            mensagem = 'Não é possível deletar lotes com status de vendido: ' + lotesVendidos.join(', ') + '.';
        }

        Swal.fire({
            icon: 'error',
            title: 'Erro ao Deletar',
            html: mensagem,
            confirmButtonColor: '#6b7280',
        });
        return;
    }

    Swal.fire({
        title: 'Confirmar Exclusão',
        html: `Tem certeza que deseja deletar <strong>${ids.length}</strong> lote(s) selecionado(s)?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sim, deletar!',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('lotes.destroy-massa', $empreendimento->id) }}';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';

            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                form.appendChild(input);
            });

            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Inicializar contador ao carregar
document.addEventListener('DOMContentLoaded', function() {
    atualizarContador();
});

// Tooltip do mapa
function showMapaTooltip(event) {
    const icon = event.target;
    const tooltip = document.getElementById('mapa-tooltip');
    const rect = icon.getBoundingClientRect();

    tooltip.classList.remove('hidden');

    // Posicionar acima do ícone
    const top = rect.top + window.scrollY - tooltip.offsetHeight - 8;
    const left = rect.left + window.scrollX + (rect.width / 2) - (tooltip.offsetWidth / 2);

    tooltip.style.top = top + 'px';
    tooltip.style.left = left + 'px';
}

function hideMapaTooltip() {
    const tooltip = document.getElementById('mapa-tooltip');
    tooltip.classList.add('hidden');
}
</script>
@endsection
