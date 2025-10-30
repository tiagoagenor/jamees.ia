@extends('layouts.app')

@section('title', 'Usuários - Jamees')
@section('page-title', 'Usuários')

@section('content')
<div id="usuarios-app" class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Usuários</h1>
            <p class="text-gray-600">Gerencie os usuários do sistema</p>
        </div>
        <a href="{{ route('usuarios.create') }}"
           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-plus mr-2"></i>
            Novo Usuário
        </a>
    </div>

    <!-- Filtros -->
    <div class="mb-6 bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Filtros</h3>
            @if(request()->hasAny(['nome', 'email', 'status', 'empresa_id', 'cpf', 'uf']))
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">Filtros ativos:</span>
                    @if(request('nome'))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Nome: {{ request('nome') }}
                        </span>
                    @endif
                    @if(request('email'))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Email: {{ request('email') }}
                        </span>
                    @endif
                    @if(request('status'))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Status: {{ request('status') ? \App\Enums\UsuarioStatusEnum::fromValue((int)request('status'))?->label() : 'Todos' }}
                        </span>
                    @endif
                    @if(request('empresa_id'))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Empresa: {{ $empresas->find(request('empresa_id'))->nome_fantasia ?? 'N/A' }}
                        </span>
                    @endif
                    @if(request('cpf'))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            CPF: {{ request('cpf') }}
                        </span>
                    @endif
                    @if(request('uf'))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            UF: {{ request('uf') }}
                        </span>
                    @endif
                </div>
            @endif
        </div>
        <form method="GET" action="{{ route('usuarios.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
            <!-- Nome -->
            <div>
                <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                <input type="text"
                       id="nome"
                       name="nome"
                       value="{{ request('nome') }}"
                       placeholder="Digite o nome"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ request('email') }}"
                       placeholder="Digite o email"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="status"
                        name="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    <option value="">Todos</option>
                    @foreach(\App\Enums\UsuarioStatusEnum::options() as $value => $label)
                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Empresa -->
            <div>
                <label for="empresa_id" class="block text-sm font-medium text-gray-700 mb-1">Empresa</label>
                <select id="empresa_id"
                        name="empresa_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    <option value="">Todas</option>
                    @foreach($empresas as $empresa)
                        <option value="{{ $empresa->id }}" {{ request('empresa_id') == $empresa->id ? 'selected' : '' }}>
                            {{ $empresa->nome_fantasia ?: $empresa->razao_social ?: $empresa->nome_referencia }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- CPF -->
            <div>
                <label for="cpf" class="block text-sm font-medium text-gray-700 mb-1">CPF</label>
                <input type="text"
                       id="cpf"
                       name="cpf"
                       value="{{ request('cpf') }}"
                       placeholder="Digite o CPF"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm">
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
                <a href="{{ route('usuarios.index') }}"
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

    <!-- Lista de Usuários (design alinhado à página de Empresas/Grupos) -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Usuários</h3>
        </div>
        <div class="px-0 pt-0 pb-6">
            @if($usuarios->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    @php
                                        $isCol = request('sort_by') === 'email';
                                        $dir = request('sort_direction');
                                        $params = request()->query();
                                        if (!$isCol) { $params['sort_by'] = 'email'; $params['sort_direction'] = 'desc'; }
                                        elseif ($dir === 'desc') { $params['sort_direction'] = 'asc'; }
                                        else { unset($params['sort_by'], $params['sort_direction']); }
                                        $url = url()->current() . (count($params) ? ('?' . http_build_query($params)) : '');
                                    @endphp
                                    <a href="{{ $url }}" class="flex items-center space-x-1 hover:text-gray-700">
                                        <span>Email</span>
                                        @if($isCol)
                                            <i class="fas fa-sort-{{ $dir === 'asc' ? 'up' : 'down' }} text-indigo-600"></i>
                                        @else
                                            <i class="fas fa-sort text-gray-400"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Empresas
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    @php
                                        $isCol = request('sort_by') === 'status';
                                        $dir = request('sort_direction');
                                        $params = request()->query();
                                        if (!$isCol) { $params['sort_by'] = 'status'; $params['sort_direction'] = 'desc'; }
                                        elseif ($dir === 'desc') { $params['sort_direction'] = 'asc'; }
                                        else { unset($params['sort_by'], $params['sort_direction']); }
                                        $url = url()->current() . (count($params) ? ('?' . http_build_query($params)) : '');
                                    @endphp
                                    <a href="{{ $url }}" class="flex items-center space-x-1 hover:text-gray-700">
                                        <span>Status</span>
                                        @if($isCol)
                                            <i class="fas fa-sort-{{ $dir === 'asc' ? 'up' : 'down' }} text-indigo-600"></i>
                                        @else
                                            <i class="fas fa-sort text-gray-400"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tipo
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    @php
                                        $isCol = request('sort_by') === 'criado_em';
                                        $dir = request('sort_direction');
                                        $params = request()->query();
                                        if (!$isCol) { $params['sort_by'] = 'criado_em'; $params['sort_direction'] = 'desc'; }
                                        elseif ($dir === 'desc') { $params['sort_direction'] = 'asc'; }
                                        else { unset($params['sort_by'], $params['sort_direction']); }
                                        $url = url()->current() . (count($params) ? ('?' . http_build_query($params)) : '');
                                    @endphp
                                    <a href="{{ $url }}" class="flex items-center space-x-1 hover:text-gray-700">
                                        <span>Criado em</span>
                                        @if($isCol)
                                            <i class="fas fa-sort-{{ $dir === 'asc' ? 'up' : 'down' }} text-indigo-600"></i>
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
                            @foreach($usuarios as $usuario)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user text-gray-600"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $usuario->nome }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $usuario->email }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($usuario->empresas as $empresa)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mb-1">
                                                    {{ $empresa->nome_fantasia ?: $empresa->razao_social ?: $empresa->nome_referencia }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $usuario->status->color() == 'green' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $usuario->status->label() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($usuario->isPrincipal())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <i class="fas fa-crown mr-1"></i>
                                                Principal
                                            </span>
                                        @elseif($usuario->isAdmin())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-user-shield mr-1"></i>
                                                Admin
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-user mr-1"></i>
                                                Usuário
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $usuario->criado_em ? $usuario->criado_em->format('d/m/Y H:i') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-3">
                                            <!-- Visualizar -->
                                            <div class="relative group">
                                                <a href="{{ route('usuarios.show', $usuario) }}" class="text-indigo-600 hover:text-indigo-900 flex items-center">
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
                                                <a href="{{ route('usuarios.edit', $usuario) }}" class="text-yellow-600 hover:text-yellow-900 flex items-center">
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
                                                <button type="button"
                                                        @click="deleteUsuario('{{ $usuario->id }}', '{{ $usuario->nome }}')"
                                                        class="text-red-600 hover:text-red-900 flex items-center">
                                                    <i class="fas fa-trash"></i>
                                                    <span class="sr-only">Excluir</span>
                                                </button>
                                                <div class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-200 bg-gray-900 rounded shadow opacity-0 group-hover:visible group-hover:opacity-100 -top-8 left-1/2 -translate-x-1/2 whitespace-nowrap">
                                                    Excluir
                                                    <div class="absolute w-2 h-2 bg-gray-900 rotate-45 left-1/2 -translate-x-1/2 top-full"></div>
                                                </div>
                                            </div>

                                            <form :id="`delete-form-{{ $usuario->id }}`"
                                                  method="POST"
                                                  action="{{ route('usuarios.destroy', $usuario) }}"
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
                        Mostrando {{ $usuarios->firstItem() ?? 0 }} até {{ $usuarios->lastItem() ?? 0 }} de {{ $usuarios->total() }} resultados
                    </div>

                    @if($usuarios->hasPages())
                        <div class="flex items-center space-x-2">
                            {{ $usuarios->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-users text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum usuário encontrado</h3>
                    <p class="text-gray-500 mb-4">Comece criando seu primeiro usuário</p>
                    <a href="{{ route('usuarios.create') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        <i class="fas fa-plus mr-2"></i>
                        Criar Usuário
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script src="{{ asset('js/vue-components.js') }}"></script>
<script>
const { createApp } = Vue;
createApp(window.UsuariosIndex).mount('#usuarios-app');
</script>
@endsection
