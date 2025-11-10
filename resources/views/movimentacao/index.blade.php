@extends('layouts.app')

@section('content')
<div>
    <div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $titulo }}</h2>
                    @php
                        // Verificar se há filtros aplicados (exceto o filtro do card que é padrão)
                        $temFiltros = !empty($filtroDescricao) || 
                                     $filtroSituacao !== 'todos' || 
                                     !empty($filtroVencimentoInicio) || 
                                     !empty($filtroVencimentoFim) || 
                                     !empty($filtroParcelaCodigo) || 
                                     !empty($filtroEntidadeTipo);
                    @endphp
                    @if($temFiltros)
                        <a href="{{ route($tipo == 1 ? 'contas-a-pagar.index' : 'contas-a-receber.index') }}"
                           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-times mr-2"></i>
                            Limpar Filtro
                        </a>
                    @else
                        <a href="{{ route($tipo == 1 ? 'contas-a-pagar.create' : 'contas-a-receber.create') }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-plus mr-2"></i>
                            Nova {{ $titulo }}
                        </a>
                    @endif
                </div>

                <!-- Cards de Resumo Clicáveis -->
                @php
                    // Preservar todos os filtros existentes, exceto o filtro do card
                    $filtrosPreservar = request()->except(['filtro', 'page']);
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-5 mb-6">
                    <!-- Vencidos -->
                    <a href="{{ route($tipo == 1 ? 'contas-a-pagar.index' : 'contas-a-receber.index', array_merge($filtrosPreservar, ['filtro' => 'vencidos'])) }}"
                       class="bg-white border-r border-gray-300 p-4 hover:bg-gray-50 transition-colors cursor-pointer {{ $filtroCard == 'vencidos' ? 'border-t-4 border-t-red-500' : '' }}">
                        <div class="text-sm font-medium text-gray-500 mb-1">Vencidos</div>
                        <div class="text-lg font-bold text-red-600">R$ {{ number_format($resumo['vencidos'], 2, ',', '.') }}</div>
                    </a>

                    <!-- Vence Hoje -->
                    <a href="{{ route($tipo == 1 ? 'contas-a-pagar.index' : 'contas-a-receber.index', array_merge($filtrosPreservar, ['filtro' => 'vence_hoje'])) }}"
                       class="bg-white border-r border-gray-300 p-4 hover:bg-gray-50 transition-colors cursor-pointer {{ $filtroCard == 'vence_hoje' ? 'border-t-4 border-t-orange-500' : '' }}">
                        <div class="text-sm font-medium text-gray-500 mb-1">Vence Hoje</div>
                        <div class="text-lg font-bold text-orange-600">R$ {{ number_format($resumo['vence_hoje'], 2, ',', '.') }}</div>
                    </a>

                    <!-- A Vencer -->
                    <a href="{{ route($tipo == 1 ? 'contas-a-pagar.index' : 'contas-a-receber.index', array_merge($filtrosPreservar, ['filtro' => 'a_vencer'])) }}"
                       class="bg-white border-r border-gray-300 p-4 hover:bg-gray-50 transition-colors cursor-pointer {{ $filtroCard == 'a_vencer' ? 'border-t-4 border-t-blue-500' : '' }}">
                        <div class="text-sm font-medium text-gray-500 mb-1">A Vencer</div>
                        <div class="text-lg font-bold text-blue-600">R$ {{ number_format($resumo['a_vencer'], 2, ',', '.') }}</div>
                    </a>

                    <!-- Pagos -->
                    <a href="{{ route($tipo == 1 ? 'contas-a-pagar.index' : 'contas-a-receber.index', array_merge($filtrosPreservar, ['filtro' => 'pagos'])) }}"
                       class="bg-white border-r border-gray-300 p-4 hover:bg-gray-50 transition-colors cursor-pointer {{ $filtroCard == 'pagos' ? 'border-t-4 border-t-teal-500' : '' }}">
                        <div class="text-sm font-medium text-gray-500 mb-1">Pagos</div>
                        <div class="text-lg font-bold text-teal-600">R$ {{ number_format($resumo['pagos'], 2, ',', '.') }}</div>
                    </a>

                    <!-- Total -->
                    <a href="{{ route($tipo == 1 ? 'contas-a-pagar.index' : 'contas-a-receber.index', array_merge($filtrosPreservar, ['filtro' => 'todos'])) }}"
                       class="bg-white p-4 hover:bg-gray-50 transition-colors cursor-pointer {{ $filtroCard == 'todos' ? 'border-t-4 border-t-gray-500' : '' }}">
                        <div class="text-sm font-medium text-gray-500 mb-1">Total</div>
                        <div class="text-lg font-bold text-gray-900">R$ {{ number_format($resumo['total'], 2, ',', '.') }}</div>
                    </a>
                </div>

                <!-- Filtros -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-filter text-blue-600 mr-2"></i>
                            Filtros
                        </h3>
                        @if($filtroDescricao)
                            <div class="text-sm text-gray-600">
                                <span class="text-blue-600">Filtro: "{{ $filtroDescricao }}"</span>
                            </div>
                        @endif
                    </div>

                    <form method="GET" action="{{ route($tipo == 1 ? 'contas-a-pagar.index' : 'contas-a-receber.index') }}" class="space-y-4">
                        <!-- Filtros Principais -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Filtro por Descrição -->
                            <div class="space-y-2">
                                <label for="descricao" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-search text-gray-400 mr-1"></i>
                                    Descrição
                                </label>
                                <input type="text"
                                       name="descricao"
                                       id="descricao"
                                       value="{{ $filtroDescricao }}"
                                       placeholder="Digite a descrição..."
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Filtro por Situação -->
                            <div class="space-y-2">
                                <label for="situacao" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-toggle-on text-gray-400 mr-1"></i>
                                    Situação
                                </label>
                                <select name="situacao" id="situacao" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="todos" {{ $filtroSituacao === 'todos' ? 'selected' : '' }}>Todas as situações</option>
                                    <option value="1" {{ $filtroSituacao === '1' ? 'selected' : '' }}>Pendentes</option>
                                    <option value="2" {{ $filtroSituacao === '2' ? 'selected' : '' }}>Pagas</option>
                                    <option value="3" {{ $filtroSituacao === '3' ? 'selected' : '' }}>Vencidas</option>
                                    <option value="4" {{ $filtroSituacao === '4' ? 'selected' : '' }}>Canceladas</option>
                                </select>
                            </div>

                            <!-- Filtro por Tipo de Entidade -->
                            <div class="space-y-2">
                                <label for="entidade_tipo" class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-tags text-gray-400 mr-1"></i>
                                    Tipo de Entidade
                                </label>
                                <select name="entidade_tipo" id="entidade_tipo" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Todos os tipos</option>
                                    <option value="1" {{ $filtroEntidadeTipo == '1' ? 'selected' : '' }}>Cliente</option>
                                    <option value="2" {{ $filtroEntidadeTipo == '2' ? 'selected' : '' }}>Fornecedor</option>
                                    <option value="3" {{ $filtroEntidadeTipo == '3' ? 'selected' : '' }}>Funcionário</option>
                                    <option value="4" {{ $filtroEntidadeTipo == '4' ? 'selected' : '' }}>Transportadora</option>
                                    <option value="{{ \App\Enums\EntidadeTipoEnum::LOTEAMENTO->value }}" {{ $filtroEntidadeTipo == \App\Enums\EntidadeTipoEnum::LOTEAMENTO->value ? 'selected' : '' }}>Loteamento</option>
                                </select>
                            </div>

                            <!-- Botões de Ação -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-cogs text-gray-400 mr-1"></i>
                                    Ações
                                </label>
                                <div class="flex space-x-2">
                                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                                        <i class="fas fa-search mr-2"></i>
                                        Filtrar
                                    </button>
                                    <a href="{{ route($tipo == 1 ? 'contas-a-pagar.index' : 'contas-a-receber.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 text-center">
                                        <i class="fas fa-times mr-2"></i>
                                        Limpar
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros de Lote (aparecem apenas quando tipo = Loteamento) -->
                        <div id="filtros_lote" class="hidden mt-4">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-center mb-3">
                                    <i class="fas fa-map-marked-alt text-blue-600 mr-2"></i>
                                    <h4 class="text-sm font-semibold text-blue-900">Filtros de Localização do Lote</h4>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- Filtro por Empreendimento -->
                                    <div class="space-y-2">
                                        <label for="filtro_empreendimento" class="block text-sm font-medium text-gray-700">
                                            <i class="fas fa-building text-blue-500 mr-1"></i>
                                            Empreendimento
                                        </label>
                                        <select name="filtro_empreendimento" id="filtro_empreendimento" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                            <option value="">Selecione o empreendimento</option>
                                            @foreach($empreendimentos ?? [] as $empreendimento)
                                                <option value="{{ $empreendimento->id }}" {{ (!empty($loteInfo) && $loteInfo['empreendimento_id'] == $empreendimento->id) || request('filtro_empreendimento') == $empreendimento->id ? 'selected' : '' }}>{{ $empreendimento->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Filtro por Quadra -->
                                    <div class="space-y-2">
                                        <label for="filtro_quadra" class="block text-sm font-medium text-gray-700">
                                            <i class="fas fa-th text-blue-500 mr-1"></i>
                                            Quadra
                                        </label>
                                        <select name="filtro_quadra" id="filtro_quadra" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ !empty($loteInfo) && !empty($quadrasParaFiltro) ? 'bg-white' : 'bg-gray-50' }}" {{ !empty($loteInfo) && !empty($quadrasParaFiltro) ? '' : 'disabled' }}>
                                            <option value="">Selecione primeiro o empreendimento</option>
                                            @if(!empty($quadrasParaFiltro))
                                                @foreach($quadrasParaFiltro as $quadra)
                                                    <option value="{{ $quadra->id }}" {{ !empty($loteInfo) && $loteInfo['quadra_id'] == $quadra->id ? 'selected' : '' }}>{{ $quadra->nome }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <!-- Filtro por Lote -->
                                    <div class="space-y-2">
                                        <label for="filtro_lote" class="block text-sm font-medium text-gray-700">
                                            <i class="fas fa-map-pin text-blue-500 mr-1"></i>
                                            Lote
                                        </label>
                                        <select name="filtro_lote" id="filtro_lote" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ !empty($loteInfo) && !empty($lotesParaFiltro) ? 'bg-white' : 'bg-gray-50' }}" {{ !empty($loteInfo) && !empty($lotesParaFiltro) ? '' : 'disabled' }}>
                                            <option value="">Selecione primeiro a quadra</option>
                                            @if(!empty($lotesParaFiltro))
                                                @foreach($lotesParaFiltro as $lote)
                                                    <option value="{{ $lote->id }}" {{ !empty($loteInfo) && $loteInfo['lote_id'] == $lote->id ? 'selected' : '' }}>{{ $lote->nome ?? $lote->id }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros Ativos -->
                        @if($filtroDescricao || $filtroSituacao !== 'todos' || $filtroParcelaCodigo || $filtroEntidadeTipo)
                            <div class="pt-4 border-t border-gray-200">
                                <div class="flex items-center space-x-2 flex-wrap gap-2">
                                    <span class="text-sm font-medium text-gray-700">Filtros ativos:</span>
                                    @if($filtroDescricao)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-search mr-1"></i>
                                            Descrição: "{{ $filtroDescricao }}"
                                            <button type="button" onclick="limparFiltroDescricao()" class="ml-1 text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </span>
                                    @endif
                                    @if($filtroSituacao !== 'todos')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-toggle-on mr-1"></i>
                                            Situação: {{ $filtroSituacao === '1' ? 'Pendentes' : ($filtroSituacao === '2' ? 'Pagas' : ($filtroSituacao === '3' ? 'Vencidas' : 'Canceladas')) }}
                                            <button type="button" onclick="limparFiltroSituacao()" class="ml-1 text-green-600 hover:text-green-800">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </span>
                                    @endif
                                    @if($filtroParcelaCodigo)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <i class="fas fa-list-ol mr-1"></i>
                                            Parcelas do mesmo grupo
                                            <a href="{{ route($tipo == 1 ? 'contas-a-pagar.index' : 'contas-a-receber.index') }}" class="ml-1 text-purple-600 hover:text-purple-800">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </span>
                                    @endif
                                    @if($filtroEntidadeTipo)
                                        @php
                                            $tiposEntidade = [
                                                '1' => 'Cliente',
                                                '2' => 'Fornecedor',
                                                '3' => 'Funcionário',
                                                '4' => 'Transportadora'
                                            ];
                                            $tipoLabel = $tiposEntidade[$filtroEntidadeTipo] ?? 'Tipo';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-tags mr-1"></i>
                                            Tipo: {{ $tipoLabel }}
                                            <button type="button" onclick="limparFiltroEntidadeTipo()" class="ml-1 text-yellow-600 hover:text-yellow-800">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </form>
                </div>



                <!-- Tabela -->
                @if($movimentacoes->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="max-width: 300px; width: 300px;">
                                    @php
                                        $isCol = request('sort_by') === 'descricao';
                                        $dir = request('sort_direction');
                                        $params = request()->query();
                                        if (!$isCol) { $params['sort_by'] = 'descricao'; $params['sort_direction'] = 'desc'; }
                                        elseif ($dir === 'desc') { $params['sort_direction'] = 'asc'; }
                                        else { unset($params['sort_by'], $params['sort_direction']); }
                                        $url = url()->current() . (count($params) ? ('?' . http_build_query($params)) : '');
                                    @endphp
                                    <a href="{{ $url }}" class="flex items-center space-x-1 hover:text-gray-700">
                                        <span>Descrição</span>
                                        @if($isCol)
                                            <i class="fas fa-sort-{{ $dir === 'asc' ? 'up' : 'down' }} text-indigo-600"></i>
                                        @else
                                            <i class="fas fa-sort text-gray-400"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    @php
                                        $isCol = request('sort_by') === 'entidade';
                                        $dir = request('sort_direction');
                                        $params = request()->query();
                                        if (!$isCol) { $params['sort_by'] = 'entidade'; $params['sort_direction'] = 'desc'; }
                                        elseif ($dir === 'desc') { $params['sort_direction'] = 'asc'; }
                                        else { unset($params['sort_by'], $params['sort_direction']); }
                                        $url = url()->current() . (count($params) ? ('?' . http_build_query($params)) : '');
                                    @endphp
                                    <a href="{{ $url }}" class="flex items-center space-x-1 hover:text-gray-700">
                                        <span>Entidade</span>
                                        @if($isCol)
                                            <i class="fas fa-sort-{{ $dir === 'asc' ? 'up' : 'down' }} text-indigo-600"></i>
                                        @else
                                            <i class="fas fa-sort text-gray-400"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    @php
                                        $isCol = request('sort_by') === 'pagamento';
                                        $dir = request('sort_direction');
                                        $params = request()->query();
                                        if (!$isCol) { $params['sort_by'] = 'pagamento'; $params['sort_direction'] = 'desc'; }
                                        elseif ($dir === 'desc') { $params['sort_direction'] = 'asc'; }
                                        else { unset($params['sort_by'], $params['sort_direction']); }
                                        $url = url()->current() . (count($params) ? ('?' . http_build_query($params)) : '');
                                    @endphp
                                    <a href="{{ $url }}" class="flex items-center space-x-1 hover:text-gray-700">
                                        <span>Pagamento</span>
                                        @if($isCol)
                                            <i class="fas fa-sort-{{ $dir === 'asc' ? 'up' : 'down' }} text-indigo-600"></i>
                                        @else
                                            <i class="fas fa-sort text-gray-400"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    @php
                                        $isCol = request('sort_by') === 'vencimento';
                                        $dir = request('sort_direction');
                                        $params = request()->query();
                                        if (!$isCol) { $params['sort_by'] = 'vencimento'; $params['sort_direction'] = 'desc'; }
                                        elseif ($dir === 'desc') { $params['sort_direction'] = 'asc'; }
                                        else { unset($params['sort_by'], $params['sort_direction']); }
                                        $url = url()->current() . (count($params) ? ('?' . http_build_query($params)) : '');
                                    @endphp
                                    <a href="{{ $url }}" class="flex items-center space-x-1 hover:text-gray-700">
                                        <span>Data Vencimento</span>
                                        @if($isCol)
                                            <i class="fas fa-sort-{{ $dir === 'asc' ? 'up' : 'down' }} text-indigo-600"></i>
                                        @else
                                            <i class="fas fa-sort text-gray-400"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    @php
                                        $isCol = request('sort_by') === 'situacao';
                                        $dir = request('sort_direction');
                                        $params = request()->query();
                                        if (!$isCol) { $params['sort_by'] = 'situacao'; $params['sort_direction'] = 'desc'; }
                                        elseif ($dir === 'desc') { $params['sort_direction'] = 'asc'; }
                                        else { unset($params['sort_by'], $params['sort_direction']); }
                                        $url = url()->current() . (count($params) ? ('?' . http_build_query($params)) : '');
                                    @endphp
                                    <a href="{{ $url }}" class="flex items-center space-x-1 hover:text-gray-700">
                                        <span>Situação</span>
                                        @if($isCol)
                                            <i class="fas fa-sort-{{ $dir === 'asc' ? 'up' : 'down' }} text-indigo-600"></i>
                                        @else
                                            <i class="fas fa-sort text-gray-400"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($movimentacoes as $movimentacao)
                                    <tr class="hover:bg-gray-50 {{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                                        <td class="px-6 py-4" style="max-width: 300px; width: 300px;">
                                            <div class="text-sm font-medium text-gray-900">
                                                <div class="break-words">
                                                    {{ $movimentacao->descricao }}
                                                </div>
                                                @if($movimentacao->parcela_codigo && isset($parcelasTotais[$movimentacao->parcela_codigo]))
                                                    @php
                                                        $totalParcelas = $parcelasTotais[$movimentacao->parcela_codigo];
                                                        $numeroParcela = $movimentacao->numero_parcela ?? 1;
                                                        // Preservar outros filtros ao filtrar por parcela
                                                        $paramsFiltro = request()->query();
                                                        $paramsFiltro['parcela_codigo'] = $movimentacao->parcela_codigo;
                                                        $urlFiltro = route($tipo == 1 ? 'contas-a-pagar.index' : 'contas-a-receber.index', $paramsFiltro);
                                                        $tooltipText = "Esta movimentação faz parte de um parcelamento. Parcela {$numeroParcela} de {$totalParcelas} parcelas. Clique para ver todas as parcelas deste grupo.";
                                                    @endphp
                                                    <span class="text-blue-600 relative group inline-block tooltip-container">
                                                        <a href="{{ $urlFiltro }}" class="hover:underline hover:text-blue-800 cursor-pointer">
                                                            ({{ $numeroParcela }}/{{ $totalParcelas }})
                                                        </a>
                                                        <!-- Tooltip -->
                                                        <span class="tooltip-content absolute z-50 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-opacity duration-200 bottom-full mb-2 px-3 py-2 text-xs font-medium text-white bg-gray-900 rounded-lg shadow-lg whitespace-normal w-64 text-left" style="left: 0; right: auto;">
                                                            {{ $tooltipText }}
                                                            <div class="tooltip-arrow absolute top-full -mt-1" style="left: 16px;">
                                                                <div class="w-2 h-2 bg-gray-900 transform rotate-45"></div>
                                                            </div>
                                                        </span>
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $movimentacao->entidade->nome ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $movimentacao->formaPagamento->nome }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $movimentacao->vencimento->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button onclick="abrirModalConfirmacao('{{ $movimentacao->id }}', '{{ $movimentacao->descricao }}', '{{ $movimentacao->valor_total }}', '{{ $movimentacao->situacao->value }}', '{{ $movimentacao->forma_pagamento_id }}', '{{ $movimentacao->conta_empresa_id }}', '{{ $movimentacao->data_pagamento }}', '{{ $movimentacao->formaPagamento->nome ?? '' }}', '{{ $movimentacao->observacoes_pagamento ?? '' }}')"
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium cursor-pointer hover:opacity-80 transition-opacity
                                                    @if($movimentacao->situacao->value === 1) bg-yellow-100 text-yellow-800 hover:bg-yellow-200
                                                    @elseif($movimentacao->situacao->value === 2) bg-green-100 text-green-800 hover:bg-green-200
                                                    @elseif($movimentacao->situacao->value === 3) bg-red-100 text-red-800 hover:bg-red-200
                                                    @else bg-gray-100 text-gray-800 hover:bg-gray-200 @endif">
                                                {{ $movimentacao->getSituacaoLabel() }}
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ number_format($movimentacao->valor_total, 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-3">
                                                <!-- Visualizar -->
                                                <div class="relative group">
                                                    <a href="{{ route($tipo == 1 ? 'contas-a-pagar.show' : 'contas-a-receber.show', $movimentacao) }}" class="text-indigo-600 hover:text-indigo-900 flex items-center">
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
                                                    <a href="{{ route($tipo == 1 ? 'contas-a-pagar.edit' : 'contas-a-receber.edit', $movimentacao) }}" class="text-yellow-600 hover:text-yellow-900 flex items-center">
                                                        <i class="fas fa-edit"></i>
                                                        <span class="sr-only">Editar</span>
                                                    </a>
                                                    <div class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-200 bg-gray-900 rounded shadow opacity-0 group-hover:visible group-hover:opacity-100 -top-8 left-1/2 -translate-x-1/2 whitespace-nowrap">
                                                        Editar
                                                        <div class="absolute w-2 h-2 bg-gray-900 rotate-45 left-1/2 -translate-x-1/2 top-full"></div>
                                                    </div>
                                                </div>

                                                <!-- Excluir -->
                                                <div class="relative group">
                                                    <button onclick="confirmarExclusaoSweetAlert('{{ $movimentacao->id }}', '{{ $movimentacao->descricao }}')" class="text-red-600 hover:text-red-900 flex items-center">
                                                        <i class="fas fa-trash"></i>
                                                        <span class="sr-only">Excluir</span>
                                                    </button>
                                                    <div class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-200 bg-gray-900 rounded shadow opacity-0 group-hover:visible group-hover:opacity-100 -top-8 left-1/2 -translate-x-1/2 whitespace-nowrap">
                                                        Excluir
                                                        <div class="absolute w-2 h-2 bg-gray-900 rotate-45 left-1/2 -translate-x-1/2 top-full"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-6">
                        @if($movimentacoes instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                <div class="text-sm text-gray-700">
                                    Mostrando {{ $movimentacoes->firstItem() }} até {{ $movimentacoes->lastItem() }} de {{ $movimentacoes->total() }} resultados
                                </div>
                                <div class="flex items-center space-x-1 flex-wrap justify-center">
                                    @php
                                        $currentPage = $movimentacoes->currentPage();
                                        $lastPage = $movimentacoes->lastPage();
                                        $onEachSide = 2; // Número de páginas a mostrar de cada lado da página atual
                                        
                                        // Calcular o range de páginas a mostrar
                                        $start = max(1, $currentPage - $onEachSide);
                                        $end = min($lastPage, $currentPage + $onEachSide);
                                        
                                        // Ajustar se estiver muito perto do início ou fim
                                        if ($start == 1) {
                                            $end = min($lastPage, $start + ($onEachSide * 2) + 1);
                                        }
                                        if ($end == $lastPage) {
                                            $start = max(1, $end - ($onEachSide * 2) - 1);
                                        }
                                    @endphp
                                    
                                    {{-- Botão Primeira Página --}}
                                    @if ($movimentacoes->onFirstPage())
                                        <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed" title="Primeira página">
                                            <i class="fas fa-angle-double-left"></i>
                                        </span>
                                    @else
                                        <a href="{{ $movimentacoes->url(1) }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900" title="Primeira página">
                                            <i class="fas fa-angle-double-left"></i>
                                        </a>
                                    @endif

                                    {{-- Botão Página Anterior --}}
                                    @if ($movimentacoes->onFirstPage())
                                        <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                                            <i class="fas fa-chevron-left"></i>
                                        </span>
                                    @else
                                        <a href="{{ $movimentacoes->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    @endif

                                    {{-- Primeira página --}}
                                    @if ($start > 1)
                                        <a href="{{ $movimentacoes->url(1) }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">1</a>
                                        @if ($start > 2)
                                            <span class="px-3 py-2 text-sm text-gray-500">...</span>
                                        @endif
                                    @endif

                                    {{-- Páginas do range --}}
                                    @for ($page = $start; $page <= $end; $page++)
                                        @if ($page == $currentPage)
                                            <span class="px-3 py-2 text-sm text-white bg-blue-600 border border-blue-600 rounded-md font-semibold">{{ $page }}</span>
                                        @else
                                            <a href="{{ $movimentacoes->url($page) }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">{{ $page }}</a>
                                        @endif
                                    @endfor

                                    {{-- Última página --}}
                                    @if ($end < $lastPage)
                                        @if ($end < $lastPage - 1)
                                            <span class="px-3 py-2 text-sm text-gray-500">...</span>
                                        @endif
                                        <a href="{{ $movimentacoes->url($lastPage) }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">{{ $lastPage }}</a>
                                    @endif

                                    {{-- Botão Próxima Página --}}
                                    @if ($movimentacoes->hasMorePages())
                                        <a href="{{ $movimentacoes->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    @else
                                        <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                                            <i class="fas fa-chevron-right"></i>
                                        </span>
                                    @endif

                                    {{-- Botão Última Página --}}
                                    @if ($movimentacoes->currentPage() == $lastPage)
                                        <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed" title="Última página">
                                            <i class="fas fa-angle-double-right"></i>
                                        </span>
                                    @else
                                        <a href="{{ $movimentacoes->url($lastPage) }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900" title="Última página">
                                            <i class="fas fa-angle-double-right"></i>
                                        </a>
                                    @endif
                                    
                                    {{-- Input para ir direto a uma página (opcional, mas útil para muitas páginas) --}}
                                    @if ($lastPage > 10)
                                        <div class="flex items-center space-x-2 ml-4 pl-4 border-l border-gray-300">
                                            <span class="text-sm text-gray-600">Ir para:</span>
                                            <form method="GET" action="{{ request()->url() }}" class="flex items-center space-x-1">
                                                @foreach(request()->except('page') as $key => $value)
                                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                                @endforeach
                                                <input type="number" 
                                                       name="page" 
                                                       min="1" 
                                                       max="{{ $lastPage }}" 
                                                       value="{{ $currentPage }}"
                                                       class="w-16 px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                       onchange="this.form.submit()">
                                                <button type="submit" class="px-3 py-1 text-sm text-white bg-blue-600 border border-blue-600 rounded-md hover:bg-blue-700 hover:border-blue-700">
                                                    <i class="fas fa-arrow-right"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700">
                                    Mostrando {{ $movimentacoes->count() }} resultado(s)
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma {{ strtolower($titulo) }} encontrada</h3>
                        <p class="text-gray-500 mb-4">Comece criando uma nova {{ strtolower($titulo) }}.</p>
                        <a href="{{ route($tipo == 1 ? 'contas-a-pagar.create' : 'contas-a-receber.create') }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-plus mr-2"></i>
                            Nova {{ $titulo }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Pagamento -->
<div id="modalConfirmacao" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-6 border w-[1000px] shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900" id="modalTitulo">Confirmar pagamento</h3>
                <button onclick="fecharModalConfirmacao()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Form -->
            <form id="formConfirmacao" method="POST">
                @csrf

                <!-- Primeira linha: Data e Forma de Pagamento -->
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <!-- Data da compensação -->
                    <div>
                        <label for="data_compensacao" class="block text-sm font-semibold text-gray-700 mb-2">
                            Data da compensação <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="data_compensacao" name="data_compensacao"
                               class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>

                    <!-- Forma de pagamento -->
                    <div>
                        <label for="forma_pagamento_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Forma de pagamento <span class="text-red-500">*</span>
                        </label>
                        <select id="forma_pagamento_id" name="forma_pagamento_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                            <option value="">Selecione uma forma de pagamento</option>
                            <!-- Será preenchido via JavaScript -->
                        </select>
                    </div>
                </div>

                <!-- Segunda linha: Conta bancária -->
                <div class="mb-6">
                    <label for="conta_empresa_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Conta bancária <span class="text-red-500">*</span>
                    </label>
                    <select id="conta_empresa_id" name="conta_empresa_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">Selecione uma conta bancária</option>
                        <!-- Será preenchido via JavaScript -->
                    </select>
                </div>

                <!-- Terceira linha: Valores em grid 2x2 -->
                <div class="mb-6">
                    <h4 class="text-md font-semibold text-gray-800 mb-4">Valores Financeiros</h4>
                    <div class="grid grid-cols-2 gap-6">
                        <!-- Valor bruto -->
                        <div>
                            <label for="valor_bruto" class="block text-sm font-semibold text-gray-700 mb-2">
                                Valor bruto <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="valor_bruto" name="valor_bruto" step="0.01" min="0"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   required>
                        </div>

                        <!-- Juros -->
                        <div>
                            <label for="juros" class="block text-sm font-semibold text-gray-700 mb-2">
                                Juros
                            </label>
                            <input type="number" id="juros" name="juros" step="0.01" min="0" value="0.00"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Desconto -->
                        <div>
                            <label for="desconto" class="block text-sm font-semibold text-gray-700 mb-2">
                                Desconto
                            </label>
                            <input type="number" id="desconto" name="desconto" step="0.01" min="0" value="0.00"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Total -->
                        <div>
                            <label for="valor_total" class="block text-sm font-semibold text-gray-700 mb-2">
                                Total
                            </label>
                            <input type="number" id="valor_total" name="valor_total" step="0.01" min="0" readonly
                                   class="w-full px-4 py-3 border border-gray-300 rounded-md bg-gray-100 text-gray-600 font-semibold">
                        </div>
                    </div>
                </div>

                <!-- Quarta linha: Observações -->
                <div class="mb-8">
                    <label for="observacoes" class="block text-sm font-semibold text-gray-700 mb-2">
                        Observações
                    </label>
                    <textarea id="observacoes" name="observacoes" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Observações adicionais sobre o pagamento..."></textarea>
                </div>

                <!-- Botões -->
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="fecharModalConfirmacao()"
                            class="px-6 py-3 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors font-medium">
                        Voltar
                    </button>
                    <button type="submit"
                            class="px-6 py-3 bg-teal-500 text-white rounded-md hover:bg-teal-600 transition-colors font-medium">
                        Confirmar pagamento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function confirmarExclusaoSweetAlert(id, nome) {
    Swal.fire({
        title: 'Confirmar Exclusão',
        text: `Tem certeza que deseja excluir "${nome}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Preservar query parameters da URL atual
            const urlParams = new URLSearchParams(window.location.search);
            const queryString = urlParams.toString();
            const actionUrl = `/{{ $tipo == 1 ? 'contas-a-pagar' : 'contas-a-receber' }}/${id}${queryString ? '?' + queryString : ''}`;

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = actionUrl;

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

function toggleStatus(id, nome, statusAtual) {
    const action = statusAtual === 1 ? 'pagar' : 'marcar como pendente';
    const icon = statusAtual === 1 ? 'warning' : 'success';
    const confirmColor = statusAtual === 1 ? '#d33' : '#28a745';

    Swal.fire({
        title: `Confirmar ${statusAtual === 1 ? 'Pagamento' : 'Marcar como Pendente'}`,
        text: `Tem certeza que deseja ${action} "${nome}"?`,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: confirmColor,
        cancelButtonColor: '#3085d6',
        confirmButtonText: `Sim, ${action}!`,
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/{{ $tipo == 1 ? 'contas-a-pagar' : 'contas-a-receber' }}/${id}/toggle-status`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'PATCH';

            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Função para abrir modal de confirmação
function abrirModalConfirmacao(id, descricao, valor, situacao, formaPagamentoId = null, contaEmpresaId = null, dataPagamento = null, formaPagamentoNome = null, observacoesPagamento = null) {
    // Se a situação for "Paga" (2), abrir modal de cancelar confirmação
    if (parseInt(situacao) === 2) {
        abrirModalCancelarConfirmacao(
            id,
            descricao,
            valor,
            dataPagamento,
            formaPagamentoNome,
            observacoesPagamento
        );
        return;
    }

    const modal = document.getElementById('modalConfirmacao');
    const form = document.getElementById('formConfirmacao');
    const titulo = document.getElementById('modalTitulo');

    // Definir ação baseada na situação atual
    let acao = '';
    let tituloModal = '';

    switch(parseInt(situacao)) {
        case 1: // Pendente
            acao = 'confirmar-pagamento';
            tituloModal = 'Confirmar pagamento';
            break;
        case 3: // Vencida
            acao = 'confirmar-pagamento';
            tituloModal = 'Confirmar pagamento';
            break;
        case 4: // Cancelada
            acao = 'reativar';
            tituloModal = 'Reativar movimentação';
            break;
        default:
            acao = 'confirmar-pagamento';
            tituloModal = 'Confirmar pagamento';
    }

    titulo.textContent = tituloModal;

    // Construir URL corretamente baseada no tipo
    const baseRoute = '{{ $tipo == 1 ? "contas-a-pagar" : "contas-a-receber" }}';
    form.action = `/${baseRoute}/${id}/${acao}`;

    // Preencher valores padrão
    document.getElementById('valor_bruto').value = parseFloat(valor).toFixed(2);
    document.getElementById('juros').value = '0.00';
    document.getElementById('desconto').value = '0.00';
    document.getElementById('valor_total').value = parseFloat(valor).toFixed(2);
    document.getElementById('data_compensacao').value = new Date().toISOString().split('T')[0];

    // Preencher formas de pagamento
    carregarFormasPagamento(formaPagamentoId);

    // Preencher contas bancárias
    carregarContasBancarias(contaEmpresaId);

    // Mostrar modal
    modal.classList.remove('hidden');
}

// Função para fechar modal
function fecharModalConfirmacao() {
    const modal = document.getElementById('modalConfirmacao');
    modal.classList.add('hidden');
}

// Função para carregar formas de pagamento
function carregarFormasPagamento(formaPagamentoIdSelecionada = null) {
    const select = document.getElementById('forma_pagamento_id');
    select.innerHTML = '<option value="">Selecione uma forma de pagamento</option>';

    // Dados das formas de pagamento vindos do backend
    const formasPagamento = @json($formasPagamento);

    formasPagamento.forEach(forma => {
        const option = document.createElement('option');
        option.value = forma.id;
        option.textContent = forma.nome;

        // Selecionar a forma de pagamento atual se fornecida
        if (formaPagamentoIdSelecionada && forma.id === formaPagamentoIdSelecionada) {
            option.selected = true;
        }

        select.appendChild(option);
    });
}

// Função para carregar contas bancárias
function carregarContasBancarias(contaEmpresaIdSelecionada = null) {
    const select = document.getElementById('conta_empresa_id');
    select.innerHTML = '<option value="">Selecione uma conta bancária</option>';

    // Dados das contas bancárias vindos do backend
    const contasBancarias = @json($contasBancarias);

    contasBancarias.forEach(conta => {
        const option = document.createElement('option');
        option.value = conta.id;
        option.textContent = conta.nome;

        // Selecionar a conta bancária atual se fornecida
        if (contaEmpresaIdSelecionada && conta.id === contaEmpresaIdSelecionada) {
            option.selected = true;
        }

        select.appendChild(option);
    });
}

// Função para calcular total automaticamente
function calcularTotal() {
    const valorBruto = parseFloat(document.getElementById('valor_bruto').value) || 0;
    const juros = parseFloat(document.getElementById('juros').value) || 0;
    const desconto = parseFloat(document.getElementById('desconto').value) || 0;

    const total = valorBruto + juros - desconto;
    document.getElementById('valor_total').value = total.toFixed(2);
}

// Event listeners para cálculo automático
document.addEventListener('DOMContentLoaded', function() {
    const valorBruto = document.getElementById('valor_bruto');
    const juros = document.getElementById('juros');
    const desconto = document.getElementById('desconto');

    if (valorBruto) valorBruto.addEventListener('input', calcularTotal);
    if (juros) juros.addEventListener('input', calcularTotal);
    if (desconto) desconto.addEventListener('input', calcularTotal);

    // Interceptar submit do formulário para fazer AJAX
    const formConfirmacao = document.getElementById('formConfirmacao');
    if (formConfirmacao) {
        formConfirmacao.addEventListener('submit', function(e) {
            e.preventDefault();
            confirmarPagamentoAjax();
        });
    }
});

// Função para confirmar pagamento via AJAX
function confirmarPagamentoAjax() {
    const form = document.getElementById('formConfirmacao');
    const formData = new FormData(form);

    // Mostrar loading no botão
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processando...';

    // Primeiro, gerar JWT temporário
    // Extrair ID da movimentação da URL do form.action
    const formAction = form.action;
    const movimentacaoId = formAction.split('/').slice(-2, -1)[0]; // Pega o penúltimo segmento da URL
    const baseRoute = '{{ $tipo == 1 ? "contas-a-pagar" : "contas-a-receber" }}';
    const jwtUrl = `/${baseRoute}/${movimentacaoId}/gerar-jwt-confirmacao`;

    fetch(jwtUrl, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(jwtData => {
        if (jwtData.success) {
            // Agora fazer a requisição de confirmação com o JWT
            return fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Confirmation-Token': jwtData.jwt_token
                }
            });
        } else {
            throw new Error(jwtData.message || 'Erro ao gerar token de confirmação');
        }
    })
    .then(response => response.json())
    .then(data => {
        // Restaurar botão
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;

        if (data.success) {
            // Sucesso
            Swal.fire({
                title: 'Sucesso!',
                text: data.message || 'Pagamento confirmado com sucesso!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                // Fechar modal e recarregar página
                fecharModalConfirmacao();
                window.location.reload();
            });
        } else {
            // Erro
            Swal.fire({
                title: 'Erro!',
                text: data.message || 'Ocorreu um erro ao confirmar o pagamento.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        // Restaurar botão
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;

        console.error('Erro:', error);
        Swal.fire({
            title: 'Erro!',
            text: error.message || 'Ocorreu um erro inesperado. Tente novamente.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
}

// Variáveis globais para o modal de cancelar
let movimentacaoAtualCancelar = null;

// Função para abrir modal de cancelar confirmação
function abrirModalCancelarConfirmacao(id, descricao, valor, dataPagamento, formaPagamentoNome, observacoes) {
    const modal = document.getElementById('modalCancelarConfirmacao');

    // Armazenar dados da movimentação
    movimentacaoAtualCancelar = {
        id: id,
        descricao: descricao,
        valor: valor,
        dataPagamento: dataPagamento,
        formaPagamentoNome: formaPagamentoNome,
        observacoes: observacoes
    };

    // Preencher dados no modal
    document.getElementById('dataConfirmacaoDisplay').textContent = formatarData(dataPagamento);
    document.getElementById('valorTotalDisplay').textContent = parseFloat(valor).toFixed(2);
    document.getElementById('formaPagamentoDisplay').textContent = formaPagamentoNome || 'Não informado';
    document.getElementById('observacoesDisplay').textContent = observacoes || 'Nenhuma observação';

    // Mostrar modal
    modal.classList.remove('hidden');
}

// Função para fechar modal de cancelar confirmação
function fecharModalCancelarConfirmacao() {
    const modal = document.getElementById('modalCancelarConfirmacao');
    modal.classList.add('hidden');
    movimentacaoAtualCancelar = null;
}

// Função para cancelar confirmação de pagamento
function cancelarConfirmacaoPagamento() {
    if (!movimentacaoAtualCancelar) return;

    // Mostrar confirmação
    Swal.fire({
        title: 'Cancelar confirmação?',
        text: 'Esta ação irá marcar a movimentação como pendente novamente.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f97316',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sim, cancelar',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            executarCancelamentoConfirmacao();
        }
    });
}

// Função para executar o cancelamento via AJAX
function executarCancelamentoConfirmacao() {
    if (!movimentacaoAtualCancelar) return;

    const baseRoute = '{{ $tipo == 1 ? "contas-a-pagar" : "contas-a-receber" }}';
    const url = `/${baseRoute}/${movimentacaoAtualCancelar.id}/marcar-pendente`;

    // Mostrar loading
    Swal.fire({
        title: 'Processando...',
        text: 'Cancelando confirmação de pagamento',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    // Fazer requisição AJAX
    fetch(url, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            observacoes: 'Confirmação cancelada pelo usuário'
        })
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();

        if (data.success) {
            Swal.fire({
                title: 'Sucesso!',
                text: data.message || 'Confirmação cancelada com sucesso!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                fecharModalCancelarConfirmacao();
                window.location.reload();
            });
        } else {
            Swal.fire({
                title: 'Erro!',
                text: data.message || 'Ocorreu um erro ao cancelar a confirmação.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        Swal.close();
        console.error('Erro:', error);
        Swal.fire({
            title: 'Erro!',
            text: 'Ocorreu um erro inesperado. Tente novamente.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
}

// Função para formatar data
function formatarData(data) {
    if (!data) return 'Não informado';
    const date = new Date(data);
    return date.toLocaleDateString('pt-BR');
}

// Fechar modal ao clicar fora dele
document.addEventListener('click', function(event) {
    const modal = document.getElementById('modalConfirmacao');
    if (event.target === modal) {
        fecharModalConfirmacao();
    }

    const modalCancelar = document.getElementById('modalCancelarConfirmacao');
    if (event.target === modalCancelar) {
        fecharModalCancelarConfirmacao();
    }
});

// Controle de filtros de lote em cascata
$(document).ready(function() {
    const $entidadeTipo = $('#entidade_tipo');
    const $filtrosLote = $('#filtros_lote');
    const $filtroEmpreendimento = $('#filtro_empreendimento');
    const $filtroQuadra = $('#filtro_quadra');
    const $filtroLote = $('#filtro_lote');

    // Mostrar/ocultar filtros de lote baseado no tipo de entidade
    function toggleFiltrosLote() {
        if ($entidadeTipo.val() === '{{ \App\Enums\EntidadeTipoEnum::LOTEAMENTO->value }}') {
            $filtrosLote.removeClass('hidden');
        } else {
            $filtrosLote.addClass('hidden');
            // Limpar valores quando ocultar (apenas se não houver loteInfo)
            @if(empty($loteInfo))
                $filtroEmpreendimento.val('');
                $filtroQuadra.val('').prop('disabled', true).addClass('bg-gray-50').removeClass('bg-white').html('<option value="">Selecione primeiro o empreendimento</option>');
                $filtroLote.val('').prop('disabled', true).addClass('bg-gray-50').removeClass('bg-white').html('<option value="">Selecione primeiro a quadra</option>');
            @endif
        }
    }

    // Carregar quadras quando selecionar empreendimento
    $filtroEmpreendimento.on('change', function() {
        const empreendimentoId = $(this).val();
        
        if (empreendimentoId) {
            $filtroQuadra.prop('disabled', true).addClass('bg-gray-50').html('<option value="">Carregando...</option>');
            $.ajax({
                url: `/api/quadras/${empreendimentoId}`,
                method: 'GET',
                success: function(quadras) {
                    $filtroQuadra.prop('disabled', false).removeClass('bg-gray-50').addClass('bg-white').html('<option value="">Selecione a quadra</option>');
                    quadras.forEach(function(quadra) {
                        $filtroQuadra.append(`<option value="${quadra.id}">${quadra.nome}</option>`);
                    });
                },
                error: function() {
                    $filtroQuadra.prop('disabled', true).addClass('bg-gray-50').html('<option value="">Erro ao carregar quadras</option>');
                }
            });
        } else {
            $filtroQuadra.val('').prop('disabled', true).addClass('bg-gray-50').removeClass('bg-white').html('<option value="">Selecione primeiro o empreendimento</option>');
            $filtroLote.val('').prop('disabled', true).addClass('bg-gray-50').removeClass('bg-white').html('<option value="">Selecione primeiro a quadra</option>');
        }
    });

    // Carregar lotes quando selecionar quadra
    $filtroQuadra.on('change', function() {
        const quadraId = $(this).val();
        
        if (quadraId) {
            $filtroLote.prop('disabled', true).addClass('bg-gray-50').html('<option value="">Carregando...</option>');
            $.ajax({
                url: `/api/lotes/${quadraId}`,
                method: 'GET',
                success: function(lotes) {
                    $filtroLote.prop('disabled', false).removeClass('bg-gray-50').addClass('bg-white').html('<option value="">Selecione o lote</option>');
                    lotes.forEach(function(lote) {
                        $filtroLote.append(`<option value="${lote.id}">${lote.nome || lote.id}</option>`);
                    });
                },
                error: function() {
                    $filtroLote.prop('disabled', true).addClass('bg-gray-50').html('<option value="">Erro ao carregar lotes</option>');
                }
            });
        } else {
            $filtroLote.val('').prop('disabled', true).addClass('bg-gray-50').removeClass('bg-white').html('<option value="">Selecione primeiro a quadra</option>');
        }
    });

    // Quando selecionar um lote, atualizar o campo entidade_id oculto
    $filtroLote.on('change', function() {
        const loteId = $(this).val();
        // Criar ou atualizar campo hidden para entidade_id
        let $entidadeIdInput = $('input[name="entidade_id"]');
        if ($entidadeIdInput.length === 0) {
            $entidadeIdInput = $('<input>').attr({
                type: 'hidden',
                name: 'entidade_id'
            });
            $('form').append($entidadeIdInput);
        }
        $entidadeIdInput.val(loteId);
    });

    // Garantir que entidade_id seja preenchido antes de submeter o formulário
    // E desabilitar campos desnecessários quando tipo for Loteamento
    $('form').on('submit', function(e) {
        if ($entidadeTipo.val() === '{{ \App\Enums\EntidadeTipoEnum::LOTEAMENTO->value }}') {
            const loteId = $filtroLote.val();
            if (loteId) {
                // Criar ou atualizar campo hidden para entidade_id com o valor do lote
                let $entidadeIdInput = $('input[name="entidade_id"]');
                if ($entidadeIdInput.length === 0) {
                    $entidadeIdInput = $('<input>').attr({
                        type: 'hidden',
                        name: 'entidade_id'
                    });
                    $(this).append($entidadeIdInput);
                }
                $entidadeIdInput.val(loteId);
                
                // Desabilitar campos desnecessários para não serem enviados na URL quando tipo for Loteamento
                $filtroEmpreendimento.prop('disabled', true);
                $filtroQuadra.prop('disabled', true);
                $filtroLote.prop('disabled', true);
            }
        } else {
            // Reabilitar campos se não for tipo Loteamento
            $filtroEmpreendimento.prop('disabled', false);
            $filtroQuadra.prop('disabled', false);
            $filtroLote.prop('disabled', false);
        }
    });

    // Inicializar ao carregar a página
    toggleFiltrosLote();
    $entidadeTipo.on('change', toggleFiltrosLote);

    // Se já houver entidade_id quando tipo for Loteamento, os selects já foram preenchidos no backend
    // Apenas garantir que os selects estejam habilitados e sincronizados
    @if(!empty($loteInfo))
        // Os selects já foram preenchidos no HTML, apenas garantir que estejam habilitados
        $filtroQuadra.prop('disabled', false).removeClass('bg-gray-50').addClass('bg-white');
        $filtroLote.prop('disabled', false).removeClass('bg-gray-50').addClass('bg-white');
        
        // Garantir que o campo hidden entidade_id esteja preenchido
        let $entidadeIdInput = $('input[name="entidade_id"]');
        if ($entidadeIdInput.length === 0) {
            $entidadeIdInput = $('<input>').attr({
                type: 'hidden',
                name: 'entidade_id'
            });
            $('form').append($entidadeIdInput);
        }
        $entidadeIdInput.val('{{ $loteInfo['lote_id'] }}');
    @endif
});

// Funções para limpar filtros
function limparFiltroDescricao() {
    document.getElementById('descricao').value = '';
    document.querySelector('form').submit();
}

function limparFiltroSituacao() {
    document.getElementById('situacao').value = 'todos';
    document.querySelector('form').submit();
}

function limparFiltroEntidadeTipo() {
    document.getElementById('entidade_tipo').value = '';
    document.querySelector('form').submit();
}

// Posicionar tooltips dinamicamente para evitar cortes
document.addEventListener('DOMContentLoaded', function() {
    const tooltipContainers = document.querySelectorAll('.tooltip-container');

    tooltipContainers.forEach(container => {
        const tooltip = container.querySelector('.tooltip-content');
        const arrow = container.querySelector('.tooltip-arrow');

        if (!tooltip) return;

        container.addEventListener('mouseenter', function() {
            // Aguardar um pouco para o tooltip aparecer
            setTimeout(() => {
                const rect = tooltip.getBoundingClientRect();
                const containerRect = container.getBoundingClientRect();
                const viewportWidth = window.innerWidth;
                const tooltipWidth = 256; // w-64 = 16rem = 256px

                let leftPosition = 0;
                let arrowLeft = 16; // left-4 = 1rem = 16px

                // Verificar se o tooltip está cortado à esquerda
                if (rect.left < 0) {
                    leftPosition = -rect.left + 10; // Adicionar um pouco de margem
                    arrowLeft = containerRect.left - rect.left + (containerRect.width / 2) - 4;
                }
                // Verificar se o tooltip está cortado à direita
                else if (rect.right > viewportWidth) {
                    const overflow = rect.right - viewportWidth;
                    leftPosition = -(overflow + 10); // Adicionar um pouco de margem
                    arrowLeft = containerRect.left - rect.left + (containerRect.width / 2) - 4;
                }

                tooltip.style.left = leftPosition + 'px';
                tooltip.style.transform = 'translateX(0)';
                arrow.style.left = arrowLeft + 'px';
            }, 10);
        });
    });
});
</script>

<!-- Modal de Cancelar Confirmação de Pagamento -->
<div id="modalCancelarConfirmacao" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-6 border w-[600px] shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900" id="modalTituloCancelar">Cancelar confirmação de pagamento</h3>
                <button onclick="fecharModalCancelarConfirmacao()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Detalhes do Pagamento -->
            <div class="mb-8">
                <h4 class="text-md font-semibold text-gray-800 mb-4">Detalhes da confirmação</h4>

                <div class="grid grid-cols-2 gap-6">
                    <!-- Data da confirmação -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Data da confirmação
                        </label>
                        <div class="text-sm text-gray-900" id="dataConfirmacaoDisplay">
                            <!-- Será preenchido via JavaScript -->
                        </div>
                    </div>

                    <!-- Valor total -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Valor total
                        </label>
                        <div class="text-sm text-gray-900" id="valorTotalDisplay">
                            <!-- Será preenchido via JavaScript -->
                        </div>
                    </div>

                    <!-- Forma de pagamento -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Forma de pagamento
                        </label>
                        <div class="text-sm text-gray-900" id="formaPagamentoDisplay">
                            <!-- Será preenchido via JavaScript -->
                        </div>
                    </div>

                    <!-- Observações -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Observações
                        </label>
                        <div class="text-sm text-gray-900" id="observacoesDisplay">
                            <!-- Será preenchido via JavaScript -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex justify-end space-x-4">
                <button type="button" onclick="fecharModalCancelarConfirmacao()"
                        class="px-6 py-3 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors font-medium">
                    Voltar
                </button>
                <button type="button" onclick="cancelarConfirmacaoPagamento()"
                        class="px-6 py-3 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors font-medium">
                    Cancelar confirmação
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
