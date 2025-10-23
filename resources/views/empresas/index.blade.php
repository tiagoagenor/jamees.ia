@extends('layouts.app')

@section('title', 'Empresas - Jamees')
@section('page-title', 'Empresas')

@section('content')
<div id="empresas-app" class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Empresas</h1>
            <p class="text-gray-600">Gerencie as empresas do sistema</p>
        </div>
        <a href="{{ route('empresas.create') }}"
           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-plus mr-2"></i>
            Nova Empresa
        </a>
    </div>

    <!-- Filtros -->
    <div class="mb-6 bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Filtros</h3>
            @if(request()->hasAny(['nome_fantasia', 'razao_social', 'cnpj', 'status', 'tipo']))
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">Filtros ativos:</span>
                    @if(request('nome_fantasia'))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Nome: {{ request('nome_fantasia') }}
                        </span>
                    @endif
                    @if(request('razao_social'))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Razão: {{ request('razao_social') }}
                        </span>
                    @endif
                    @if(request('cnpj'))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            CNPJ: {{ request('cnpj') }}
                        </span>
                    @endif
                    @if(request('status'))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Status: {{ request('status') ? \App\Enums\EmpresaStatusEnum::fromValue((int)request('status'))?->label() : 'Todos' }}
                        </span>
                    @endif
                    @if(request('tipo'))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Tipo: {{ request('tipo') == 'PJ' ? 'Pessoa Jurídica' : 'Pessoa Física' }}
                        </span>
                    @endif
                </div>
            @endif
        </div>
        <form method="GET" action="{{ route('empresas.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
            <!-- Nome Fantasia -->
            <div>
                <label for="nome_fantasia" class="block text-sm font-medium text-gray-700 mb-1">Nome Fantasia</label>
                <input type="text"
                       id="nome_fantasia"
                       name="nome_fantasia"
                       value="{{ request('nome_fantasia') }}"
                       placeholder="Digite o nome fantasia"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>

            <!-- Razão Social -->
            <div>
                <label for="razao_social" class="block text-sm font-medium text-gray-700 mb-1">Razão Social</label>
                <input type="text"
                       id="razao_social"
                       name="razao_social"
                       value="{{ request('razao_social') }}"
                       placeholder="Digite a razão social"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>

            <!-- CNPJ -->
            <div>
                <label for="cnpj" class="block text-sm font-medium text-gray-700 mb-1">CNPJ</label>
                <input type="text"
                       id="cnpj"
                       name="cnpj"
                       value="{{ request('cnpj') }}"
                       placeholder="Digite o CNPJ"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="status"
                        name="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    <option value="">Todos</option>
                    @foreach(\App\Enums\EmpresaStatusEnum::options() as $value => $label)
                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tipo -->
            <div>
                <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                <select id="tipo"
                        name="tipo"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    <option value="">Todos</option>
                    <option value="PJ" {{ request('tipo') == 'PJ' ? 'selected' : '' }}>Pessoa Jurídica</option>
                    <option value="PF" {{ request('tipo') == 'PF' ? 'selected' : '' }}>Pessoa Física</option>
                </select>
            </div>

            <!-- UF -->
            <div>
                <label for="uf" class="block text-sm font-medium text-gray-700 mb-1">UF</label>
                <select id="uf"
                        name="uf"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    <option value="">Todas</option>
                    <option value="AC" {{ request('uf') == 'AC' ? 'selected' : '' }}>AC</option>
                    <option value="AL" {{ request('uf') == 'AL' ? 'selected' : '' }}>AL</option>
                    <option value="AP" {{ request('uf') == 'AP' ? 'selected' : '' }}>AP</option>
                    <option value="AM" {{ request('uf') == 'AM' ? 'selected' : '' }}>AM</option>
                    <option value="BA" {{ request('uf') == 'BA' ? 'selected' : '' }}>BA</option>
                    <option value="CE" {{ request('uf') == 'CE' ? 'selected' : '' }}>CE</option>
                    <option value="DF" {{ request('uf') == 'DF' ? 'selected' : '' }}>DF</option>
                    <option value="ES" {{ request('uf') == 'ES' ? 'selected' : '' }}>ES</option>
                    <option value="GO" {{ request('uf') == 'GO' ? 'selected' : '' }}>GO</option>
                    <option value="MA" {{ request('uf') == 'MA' ? 'selected' : '' }}>MA</option>
                    <option value="MT" {{ request('uf') == 'MT' ? 'selected' : '' }}>MT</option>
                    <option value="MS" {{ request('uf') == 'MS' ? 'selected' : '' }}>MS</option>
                    <option value="MG" {{ request('uf') == 'MG' ? 'selected' : '' }}>MG</option>
                    <option value="PA" {{ request('uf') == 'PA' ? 'selected' : '' }}>PA</option>
                    <option value="PB" {{ request('uf') == 'PB' ? 'selected' : '' }}>PB</option>
                    <option value="PR" {{ request('uf') == 'PR' ? 'selected' : '' }}>PR</option>
                    <option value="PE" {{ request('uf') == 'PE' ? 'selected' : '' }}>PE</option>
                    <option value="PI" {{ request('uf') == 'PI' ? 'selected' : '' }}>PI</option>
                    <option value="RJ" {{ request('uf') == 'RJ' ? 'selected' : '' }}>RJ</option>
                    <option value="RN" {{ request('uf') == 'RN' ? 'selected' : '' }}>RN</option>
                    <option value="RS" {{ request('uf') == 'RS' ? 'selected' : '' }}>RS</option>
                    <option value="RO" {{ request('uf') == 'RO' ? 'selected' : '' }}>RO</option>
                    <option value="RR" {{ request('uf') == 'RR' ? 'selected' : '' }}>RR</option>
                    <option value="SC" {{ request('uf') == 'SC' ? 'selected' : '' }}>SC</option>
                    <option value="SP" {{ request('uf') == 'SP' ? 'selected' : '' }}>SP</option>
                    <option value="SE" {{ request('uf') == 'SE' ? 'selected' : '' }}>SE</option>
                    <option value="TO" {{ request('uf') == 'TO' ? 'selected' : '' }}>TO</option>
                </select>
            </div>

            <!-- Botões -->
            <div class="xl:col-span-6 flex items-end space-x-2">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-search mr-2"></i>
                    Filtrar
                </button>
                <a href="{{ route('empresas.index') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-times mr-2"></i>
                    Limpar
                </a>
            </div>
        </form>
    </div>

    @if (session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:p-6">
            @if($empresas->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'nome_fantasia', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}"
                                       class="flex items-center space-x-1 hover:text-gray-700">
                                        <span>Nome Fantasia</span>
                                        @if(request('sort_by') == 'nome_fantasia')
                                            <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }} text-indigo-600"></i>
                                        @else
                                            <i class="fas fa-sort text-gray-400"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'razao_social', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}"
                                       class="flex items-center space-x-1 hover:text-gray-700">
                                        <span>Razão Social</span>
                                        @if(request('sort_by') == 'razao_social')
                                            <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }} text-indigo-600"></i>
                                        @else
                                            <i class="fas fa-sort text-gray-400"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'cnpj', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}"
                                       class="flex items-center space-x-1 hover:text-gray-700">
                                        <span>CNPJ</span>
                                        @if(request('sort_by') == 'cnpj')
                                            <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }} text-indigo-600"></i>
                                        @else
                                            <i class="fas fa-sort text-gray-400"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tipo
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}"
                                       class="flex items-center space-x-1 hover:text-gray-700">
                                        <span>Status</span>
                                        @if(request('sort_by') == 'status')
                                            <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }} text-indigo-600"></i>
                                        @else
                                            <i class="fas fa-sort text-gray-400"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'criado_em', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}"
                                       class="flex items-center space-x-1 hover:text-gray-700">
                                        <span>Criado em</span>
                                        @if(request('sort_by') == 'criado_em')
                                            <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }} text-indigo-600"></i>
                                        @else
                                            <i class="fas fa-sort text-gray-400"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($empresas as $empresa)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                                <i class="fas fa-building text-gray-600"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $empresa->nome_fantasia }}
                                                </div>
                                                @if($empresaPrincipal && $empresa->id == $empresaPrincipal->id)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-crown mr-1"></i>
                                                        Principal
                                                    </span>
                                                @elseif($empresaPrincipal && $empresa->empresa_id == $empresaPrincipal->id)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        <i class="fas fa-building mr-1"></i>
                                                        Filial
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $empresa->razao_social }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $empresa->cnpj }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $empresa->tipo == 'PJ' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $empresa->tipo == 'PJ' ? 'Pessoa Jurídica' : 'Pessoa Física' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $empresa->status->color() == 'green' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $empresa->status->label() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $empresa->criado_em ? $empresa->criado_em->format('d/m/Y H:i') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('empresas.show', $empresa) }}"
                                               class="text-indigo-600 hover:text-indigo-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('empresas.edit', $empresa) }}"
                                               class="text-yellow-600 hover:text-yellow-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                                    @click="deleteEmpresa('{{ $empresa->id }}', '{{ $empresa->nome_fantasia }}')"
                                                    class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                            <form :id="`delete-form-{{ $empresa->id }}`"
                                                  method="POST"
                                                  action="{{ route('empresas.destroy', $empresa) }}"
                                                  style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Informações e Paginação -->
                <div class="mt-6 flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Mostrando {{ $empresas->firstItem() ?? 0 }} até {{ $empresas->lastItem() ?? 0 }} de {{ $empresas->total() }} resultados
                    </div>

                    @if($empresas->hasPages())
                        <div class="flex items-center space-x-2">
                            {{ $empresas->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-building text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma empresa encontrada</h3>
                    <p class="text-gray-500 mb-4">Comece criando sua primeira empresa</p>
                    <a href="{{ route('empresas.create') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        <i class="fas fa-plus mr-2"></i>
                        Criar Empresa
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script src="{{ asset('js/vue-components.js') }}"></script>
<script>
const { createApp } = Vue;
createApp(window.EmpresasIndex).mount('#empresas-app');
</script>
@endsection
