<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Jamees')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/menu.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
</head>
<body class="bg-gray-100">
    <!-- Notificação de Plano -->
    @include('components.plano-notification')

    <div class="flex h-screen">
        <!-- Overlay para mobile -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>

        <!-- Sidebar -->
        <div id="sidebar" class="fixed md:static inset-y-0 left-0 z-50 w-64 bg-white text-slate-700 border-r border-gray-200 flex flex-col transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
            <!-- Logo e Botão Fechar (Mobile) -->
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div class="flex-1 text-center text-xl">
                    <span id="brand-logo" style="font-family: 'Roboto'; font-weight: bold; font-style: italic; color: #1E40AF;">JAMEES</span>
                </div>
                <!-- Botão fechar (apenas mobile) -->
                <button onclick="toggleSidebar()" class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Menu -->
            <nav class="flex-1 px-6 py-4 overflow-y-auto">
                <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Menu</p>
                <ul class="space-y-1">
                    <!-- Início -->
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded-xl text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }} transition-colors duration-200">
                            <i class="fas fa-home mr-3 {{ request()->routeIs('dashboard') ? 'text-blue-700' : 'text-slate-400' }}"></i>
                            Início
                        </a>
                    </li>

                    <!-- Cadastro -->
                    <li>
                        <div class="flex items-center justify-between px-3 py-2 text-sm font-medium text-slate-600 hover:bg-gray-50 hover:text-slate-900 cursor-pointer rounded-xl transition-colors duration-200" onclick="toggleSubmenu('cadastro')">
                            <div class="flex items-center">
                                <i class="fas fa-user-plus mr-3 text-slate-400"></i>
                                Cadastro
                            </div>
                            <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200" id="cadastro-arrow"></i>
                        </div>
                        <ul id="cadastro-submenu" class="ml-4 mt-2 space-y-1 hidden">
                            <li>
                                <a href="{{ route('clientes.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm {{ request()->routeIs('clientes.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }} transition-colors duration-200">
                                    <i class="fas fa-user-tie mr-3 {{ request()->routeIs('clientes.*') ? 'text-blue-700' : 'text-slate-400' }}"></i>
                                    Cliente
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('fornecedores.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm {{ request()->routeIs('fornecedores.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }} transition-colors duration-200">
                                    <i class="fas fa-truck mr-3 {{ request()->routeIs('fornecedores.*') ? 'text-blue-700' : 'text-slate-400' }}"></i>
                                    Fornecedor
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('funcionarios.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm {{ request()->routeIs('funcionarios.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }} transition-colors duration-200">
                                    <i class="fas fa-user mr-3 {{ request()->routeIs('funcionarios.*') ? 'text-blue-700' : 'text-slate-400' }}"></i>
                                    Funcionário
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('transportadoras.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm {{ request()->routeIs('transportadoras.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }} transition-colors duration-200">
                                    <i class="fas fa-shipping-fast mr-3 {{ request()->routeIs('transportadoras.*') ? 'text-blue-700' : 'text-slate-400' }}"></i>
                                    Transportadora
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Financeiro -->
                    <li>
                        <div class="flex items-center justify-between px-3 py-2 text-sm font-medium text-slate-600 hover:bg-gray-50 hover:text-slate-900 cursor-pointer rounded-xl transition-colors duration-200" onclick="toggleSubmenu('financeiro')">
                            <div class="flex items-center">
                                <i class="fas fa-dollar-sign mr-3 text-slate-400"></i>
                                Financeiro
                            </div>
                            <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200" id="financeiro-arrow"></i>
                        </div>
                        <ul id="financeiro-submenu" class="ml-4 mt-2 space-y-1 hidden">
                            <li>
                                <a href="{{ route('dashboard.financeiro') }}" class="flex items-center px-3 py-2 rounded-lg text-sm {{ request()->routeIs('dashboard.financeiro') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }} transition-colors duration-200">
                                    <i class="fas fa-chart-line mr-3 {{ request()->routeIs('dashboard.financeiro') ? 'text-emerald-700' : 'text-slate-400' }}"></i>
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dre.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm {{ request()->routeIs('dre.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }} transition-colors duration-200">
                                    <i class="fas fa-chart-pie mr-3 {{ request()->routeIs('dre.*') ? 'text-emerald-700' : 'text-slate-400' }}"></i>
                                    Gerenciar DRE
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('contas-a-pagar.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm {{ request()->routeIs('contas-a-pagar.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }} transition-colors duration-200">
                                    <i class="fas fa-credit-card mr-3 {{ request()->routeIs('contas-a-pagar.*') ? 'text-emerald-700' : 'text-slate-400' }}"></i>
                                    Contas a Pagar
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('contas-a-receber.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm {{ request()->routeIs('contas-a-receber.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }} transition-colors duration-200">
                                    <i class="fas fa-money-bill-wave mr-3 {{ request()->routeIs('contas-a-receber.*') ? 'text-emerald-700' : 'text-slate-400' }}"></i>
                                    Contas a Receber
                                </a>
                            </li>
                            <li>
                                    <div class="flex items-center justify-between px-3 py-2 text-sm font-medium text-slate-500 hover:bg-gray-50 hover:text-slate-900 cursor-pointer rounded-xl transition-colors duration-200" onclick="toggleSubmenu('opcoes-auxiliares')">
                                    <div class="flex items-center">
                                            <i class="fas fa-cogs mr-3 text-slate-400"></i>
                                        Opções auxiliares
                                    </div>
                                        <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200" id="opcoes-auxiliares-arrow"></i>
                                </div>
                                <ul id="opcoes-auxiliares-submenu" class="ml-4 mt-2 space-y-1 hidden">
                                    <li>
                                        <a href="{{ route('conta-empresa.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm {{ request()->routeIs('conta-empresa.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }} transition-colors duration-200">
                                            <i class="fas fa-university mr-3 {{ request()->routeIs('conta-empresa.*') ? 'text-blue-700' : 'text-slate-400' }}"></i>
                                            Contas bancárias
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('forma-pagamento.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm {{ request()->routeIs('forma-pagamento.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }} transition-colors duration-200">
                                            <i class="fas fa-credit-card mr-3 {{ request()->routeIs('forma-pagamento.*') ? 'text-blue-700' : 'text-slate-400' }}"></i>
                                            Formas de pagamento
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('plano-conta.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm {{ request()->routeIs('plano-conta.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }} transition-colors duration-200">
                                            <i class="fas fa-list-alt mr-3 {{ request()->routeIs('plano-conta.*') ? 'text-blue-700' : 'text-slate-400' }}"></i>
                                            Plano de conta
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('centro-custo.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm {{ request()->routeIs('centro-custo.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }} transition-colors duration-200">
                                            <i class="fas fa-building mr-3 {{ request()->routeIs('centro-custo.*') ? 'text-blue-700' : 'text-slate-400' }}"></i>
                                            Centro de custo
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <!-- Configurações -->
                    <li>
                        <div class="flex items-center justify-between px-3 py-2 text-sm font-medium text-slate-600 hover:bg-gray-50 hover:text-slate-900 cursor-pointer rounded-xl transition-colors duration-200" onclick="toggleSubmenu('configuracoes')">
                            <div class="flex items-center">
                                <i class="fas fa-cog mr-3 text-slate-400"></i>
                                Configurações
                            </div>
                            <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200" id="configuracoes-arrow"></i>
                        </div>
                        <ul id="configuracoes-submenu" class="ml-4 mt-2 space-y-1 hidden">
                            <li>
                                <a href="{{ route('configuracoes.gerais.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm {{ request()->routeIs('configuracoes.gerais.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }} transition-colors duration-200">
                                    <i class="fas fa-sliders-h mr-3 {{ request()->routeIs('configuracoes.gerais.*') ? 'text-blue-700' : 'text-slate-400' }}"></i>
                                    Geral
                                </a>
                            </li>
                            @if(\App\Helpers\PermissionHelper::can('audit', 'listar'))
                            <li>
                                <a href="{{ route('audit.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm {{ request()->routeIs('audit.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }} transition-colors duration-200">
                                    <i class="fas fa-history mr-3 {{ request()->routeIs('audit.*') ? 'text-blue-700' : 'text-slate-400' }}"></i>
                                    Histórico de Alterações
                                </a>
                            </li>
                            @endif
                            <li>
                                <a href="{{ route('planos.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm {{ request()->routeIs('planos.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }} transition-colors duration-200">
                                    <i class="fas fa-crown mr-3 {{ request()->routeIs('planos.*') ? 'text-blue-700' : 'text-slate-400' }}"></i>
                                    Meu Plano
                                </a>
                            </li>
                            @if(\App\Helpers\PermissionHelper::can('usuarios', 'listar'))
                            <li>
                                <a href="{{ route('usuarios.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm {{ request()->routeIs('usuarios.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }} transition-colors duration-200">
                                    <i class="fas fa-user mr-3 {{ request()->routeIs('usuarios.*') ? 'text-blue-700' : 'text-slate-400' }}"></i>
                                    Usuário
                                </a>
                            </li>
                            @endif
                            @if(\App\Helpers\PermissionHelper::can('grupos', 'listar'))
                            <li>
                                <a href="{{ route('grupos.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm {{ request()->routeIs('grupos.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }} transition-colors duration-200">
                                    <i class="fas fa-users-cog mr-3 {{ request()->routeIs('grupos.*') ? 'text-blue-700' : 'text-slate-400' }}"></i>
                                    Grupo de Usuário
                                </a>
                            </li>
                            @endif
                            @if(\App\Helpers\PermissionHelper::can('empresas', 'listar'))
                            <li>
                                <a href="{{ route('empresas.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm {{ request()->routeIs('empresas.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }} transition-colors duration-200">
                                    <i class="fas fa-building mr-3 {{ request()->routeIs('empresas.*') ? 'text-blue-700' : 'text-slate-400' }}"></i>
                                    Empresa/Loja
                                </a>
                            </li>
                            @endif
                            <li>
                                <a href="#" class="flex items-center px-3 py-2 rounded-lg text-sm text-slate-500 hover:bg-gray-50 hover:text-slate-900">
                                    <i class="fas fa-envelope mr-3 text-slate-400"></i>
                                    Modelos de Emails
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>

            <!-- Company Selector -->
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="relative">
                    <button id="company-selector" class="w-full flex items-center justify-between px-3 py-2 rounded-xl border border-gray-200 bg-white text-sm font-medium text-slate-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center bg-blue-50 text-blue-600">
                                <i class="fas fa-building text-sm"></i>
                            </div>
                            <div class="ml-3 text-left">
                                <p class="text-sm font-medium" id="current-company-name">
                                    @if($currentCompany)
                                        {{ $currentCompany->nome_fantasia ?? $currentCompany->razao_social }}
                                    @else
                                        Nenhuma empresa selecionada
                                    @endif
                                </p>
                                <p class="text-xs text-slate-400" id="current-company-type">
                                    @if($currentCompany)
                                        {{ $currentCompany->tipo }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                    </button>

                </div>
            </div>

            <!-- Logout Button -->
            <div class="px-6 py-4 border-t border-gray-200">
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="w-full flex items-center px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-gray-50 hover:text-slate-900 transition-colors duration-200">
                    <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white">
                        <i class="fas fa-sign-out-alt text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">Sair do Sistema</p>
                        <p class="text-xs text-slate-400">{{ Auth::user()->nome }}</p>
                    </div>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navbar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <!-- Botão hambúrguer (apenas mobile) -->
                        <button onclick="toggleSidebar()" class="md:hidden mr-3 text-gray-500 hover:text-gray-700 focus:outline-none">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <!-- Botão compactar/expandir (desktop) -->
                        <button onclick="toggleSidebarCompact()" class="hidden md:inline-flex mr-3 text-gray-500 hover:text-gray-700 focus:outline-none" title="Alternar menu compacto">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="text-gray-500 hover:text-gray-700 focus:outline-none">
                            <i class="fas fa-bell text-xl"></i>
                        </button>

                        <!-- User Dropdown -->
                        <div class="relative">
                            <button class="flex items-center text-gray-500 hover:text-gray-700 focus:outline-none group" onclick="toggleUserDropdown()">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-lg hover:shadow-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300 transform hover:scale-105">
                                    <span class="text-white font-bold text-sm">{{ strtoupper(substr(Auth::user()->nome ?: 'Usuário', 0, 1)) }}</span>
                                </div>
                                <i class="fas fa-chevron-down ml-2 text-sm group-hover:text-blue-600 transition-all duration-200" id="user-dropdown-arrow"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="user-dropdown-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50" style="display: none;">
                                <a href="{{ route('meus-dados.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-edit mr-2"></i>
                                    Meus Dados
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Perfil</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Configurações</a>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>
                                        Sair
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container mx-auto px-6 py-8">
                    <!-- Mensagem de Bloqueio por Plano -->
                    @include('components.plano-blocked')

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Company Selection Modal -->
    <div id="company-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Selecionar Empresa</h3>
                    <button onclick="closeCompanyModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="space-y-3">
                    @if($empresaPrincipal && $empresaPrincipal->empresasFilhas->count() > 0)
                        <!-- Empresa Principal -->
                        <div class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer {{ $currentCompany && $currentCompany->id == $empresaPrincipal->id ? 'bg-blue-50 border-blue-200' : '' }}"
                             onclick="selectCompany('{{ $empresaPrincipal->id }}', '{{ $empresaPrincipal->nome_fantasia ?? $empresaPrincipal->razao_social }}', '{{ $empresaPrincipal->tipo }}')">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-building text-green-600"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">{{ $empresaPrincipal->nome_fantasia ?? $empresaPrincipal->razao_social }}</div>
                                <div class="text-sm text-gray-500">{{ $empresaPrincipal->tipo }} - Principal</div>
                            </div>
                            @if($currentCompany && $currentCompany->id == $empresaPrincipal->id)
                                <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Empresas Filhas -->
                        @foreach($empresaPrincipal->empresasFilhas as $empresa)
                            <div class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer {{ $currentCompany && $currentCompany->id == $empresa->id ? 'bg-blue-50 border-blue-200' : '' }}"
                                 onclick="selectCompany('{{ $empresa->id }}', '{{ $empresa->nome_fantasia ?? $empresa->razao_social }}', '{{ $empresa->tipo }}')">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-building text-blue-600"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900">{{ $empresa->nome_fantasia ?? $empresa->razao_social }}</div>
                                    <div class="text-sm text-gray-500">{{ $empresa->tipo }}</div>
                                </div>
                                @if($currentCompany && $currentCompany->id == $empresa->id)
                                    <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @elseif(Auth::user()->empresas->count() > 0)
                        @foreach(Auth::user()->empresas as $empresa)
                            <div class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer {{ $currentCompany && $currentCompany->id == $empresa->id ? 'bg-blue-50 border-blue-200' : '' }}"
                                 onclick="selectCompany('{{ $empresa->id }}', '{{ $empresa->nome_fantasia ?? $empresa->razao_social }}', '{{ $empresa->tipo }}')">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-building text-blue-600"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900">{{ $empresa->nome_fantasia ?? $empresa->razao_social }}</div>
                                    <div class="text-sm text-gray-500">{{ $empresa->tipo }}</div>
                                </div>
                                @if($currentCompany && $currentCompany->id == $empresa->id)
                                    <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-building text-4xl mb-4"></i>
                            <p>Nenhuma empresa vinculada</p>
                        </div>
                    @endif
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end mt-6">
                    <button onclick="closeCompanyModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Simple dropdown toggle
        document.addEventListener('DOMContentLoaded', function() {
            const dropdown = document.querySelector('.relative button');
            const menu = document.querySelector('.absolute');

            if (dropdown && menu) {
                dropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                    menu.classList.toggle('hidden');
                });

                document.addEventListener('click', function() {
                    menu.classList.add('hidden');
                });
            }
        });

        // Submenu toggle function
        function toggleSubmenu(menuId) {
            const submenu = document.getElementById(menuId + '-submenu');
            const arrow = document.getElementById(menuId + '-arrow');

            if (submenu && arrow) {
                const isHidden = submenu.classList.contains('hidden');

                if (isHidden) {
                    submenu.classList.remove('hidden');
                    arrow.classList.add('rotate-180');
                    // Save state to localStorage
                    localStorage.setItem('menu_' + menuId + '_open', 'true');
                } else {
                    submenu.classList.add('hidden');
                    arrow.classList.remove('rotate-180');
                    // Save state to localStorage
                    localStorage.setItem('menu_' + menuId + '_open', 'false');
                }
            }
        }

        // Initialize menu state - always start with menus closed
        function initializeMenuState() {
            // All available menu sections
            const allMenus = ['cadastro', 'financeiro', 'opcoes-auxiliares', 'configuracoes'];

            // Initialize all menus - always start closed
            allMenus.forEach(menuId => {
                const submenu = document.getElementById(menuId + '-submenu');
                const arrow = document.getElementById(menuId + '-arrow');

                if (submenu && arrow) {
                    // Always start with menus closed
                    submenu.classList.add('hidden');
                    arrow.classList.remove('rotate-180');

                    // Clear any saved state to ensure clean start
                    localStorage.removeItem('menu_' + menuId + '_open');
                }
            });
        }

        // Initialize menu state when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeMenuState();
        });

        // Company selector modal
        document.addEventListener('DOMContentLoaded', function() {
            const companySelector = document.getElementById('company-selector');

            if (companySelector) {
                companySelector.addEventListener('click', function(e) {
                    e.stopPropagation();
                    openCompanyModal();
                });
            }
        });

        // Modal functions
        function openCompanyModal() {
            const modal = document.getElementById('company-modal');
            if (modal) {
                modal.classList.remove('hidden');
            }
        }

        function closeCompanyModal() {
            const modal = document.getElementById('company-modal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        // Select company function
        window.selectCompany = function(companyId, companyName, companyType) {
            // Show loading state on selector
            const selector = document.getElementById('company-selector');
            const originalContent = selector.innerHTML;

            selector.innerHTML = `
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center animate-pulse">
                        <i class="fas fa-spinner fa-spin text-sm text-white"></i>
                    </div>
                    <div class="ml-3 text-left">
                        <p class="text-sm font-medium text-blue-600">Trocando empresa...</p>
                        <p class="text-xs text-gray-500">Aguarde um momento</p>
                    </div>
                </div>
            `;
            selector.disabled = true;

            // Show loading state on modal
            const modal = document.getElementById('company-modal');
            const modalContent = modal.querySelector('.mt-3');
            const originalModalContent = modalContent.innerHTML;

            modalContent.innerHTML = `
                <div class="flex flex-col items-center justify-center py-12">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mb-6 animate-pulse">
                        <i class="fas fa-spinner fa-spin text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Trocando empresa...</h3>
                    <p class="text-sm text-gray-500 text-center max-w-xs">Aguarde enquanto processamos sua solicitação</p>
                    <div class="mt-4 w-48 bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full animate-pulse" style="width: 0%; animation: loading 2s ease-in-out infinite;"></div>
                    </div>
                </div>
                <style>
                    @keyframes loading {
                        0% { width: 0%; }
                        50% { width: 70%; }
                        100% { width: 100%; }
                    }
                </style>
            `;

            // Disable modal interactions
            modal.style.pointerEvents = 'none';
            modal.classList.add('opacity-75');

            // Make AJAX request to switch company
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            console.log('CSRF Token:', csrfToken ? csrfToken.getAttribute('content') : 'Not found');

            if (!csrfToken) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de Segurança',
                    text: 'Token CSRF não encontrado. Recarregue a página e tente novamente.',
                    confirmButtonText: 'Recarregar Página',
                    confirmButtonColor: '#3b82f6'
                }).then(() => {
                    window.location.reload();
                });
                selector.innerHTML = originalContent;
                selector.disabled = false;
                return;
            }

            fetch('/switch-company', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                },
                body: JSON.stringify({
                    empresa_id: companyId
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);

                if (response.status === 419) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Sessão Expirada',
                        text: 'Sua sessão expirou. Recarregue a página e tente novamente.',
                        confirmButtonText: 'Recarregar Página',
                        confirmButtonColor: '#f59e0b',
                        showCancelButton: true,
                        cancelButtonText: 'Cancelar',
                        cancelButtonColor: '#6b7280'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                    selector.innerHTML = originalContent;
                    selector.disabled = false;
                    // Restore modal content
                    modalContent.innerHTML = originalModalContent;
                    modal.style.pointerEvents = 'auto';
                    modal.classList.remove('opacity-75');
                    return;
                }

                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Update the display
                    const nameElement = document.getElementById('current-company-name');
                    const typeElement = document.getElementById('current-company-type');

                    if (nameElement) {
                        nameElement.textContent = companyName;
                    }
                    if (typeElement) {
                        typeElement.textContent = companyType;
                    }

                    // Reload the page to update all data
                    window.location.reload();
                } else {
                    // Show error
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro ao Trocar Empresa',
                        text: data.message || 'Erro desconhecido ao trocar empresa.',
                        confirmButtonText: 'Tentar Novamente',
                        confirmButtonColor: '#ef4444',
                        showCancelButton: true,
                        cancelButtonText: 'Cancelar',
                        cancelButtonColor: '#6b7280'
                    });
                    selector.innerHTML = originalContent;
                    selector.disabled = false;
                    // Restore modal content
                    modalContent.innerHTML = originalModalContent;
                    modal.style.pointerEvents = 'auto';
                    modal.classList.remove('opacity-75');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de Conexão',
                    text: 'Não foi possível trocar a empresa. Verifique sua conexão e tente novamente.',
                    confirmButtonText: 'Tentar Novamente',
                    confirmButtonColor: '#ef4444',
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                    cancelButtonColor: '#6b7280'
                });
                selector.innerHTML = originalContent;
                selector.disabled = false;
                // Restore modal content
                modalContent.innerHTML = originalModalContent;
                modal.style.pointerEvents = 'auto';
                modal.classList.remove('opacity-75');
            });
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('company-modal');
            if (modal && !modal.classList.contains('hidden')) {
                if (e.target === modal) {
                    closeCompanyModal();
                }
            }
        });

    </script>

    <!-- Sidebar Toggle JavaScript -->
    <script>
        // Sidebar compacto (desktop): mostra apenas ícones
        function applyCompactSidebar(compact) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (!sidebar) return;

            if (compact) {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-20');
                document.body.classList.add('sidebar-compact');
                if (overlay) overlay.classList.add('hidden');
            } else {
                sidebar.classList.remove('w-20');
                sidebar.classList.add('w-64');
                document.body.classList.remove('sidebar-compact');
            }
        }

        function toggleSidebarCompact() {
            const isCompact = document.body.classList.contains('sidebar-compact');
            applyCompactSidebar(!isCompact);
            try { localStorage.setItem('sidebar_compact', !isCompact ? '1' : '0'); } catch(e) {}
        }

        // Sempre iniciar com menu expandido (com texto) ao entrar no sistema
        document.addEventListener('DOMContentLoaded', function() {
            try {
                applyCompactSidebar(false);
                localStorage.setItem('sidebar_compact', '0');
            } catch(e) {}
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (sidebar.classList.contains('-translate-x-full')) {
                // Abrir sidebar
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                // Fechar sidebar
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        // Fechar sidebar ao redimensionar para desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.add('hidden');
            }
        });

        // Fechar sidebar ao clicar em um link em mobile
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarLinks = document.querySelectorAll('#sidebar a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        toggleSidebar();
                    }
                });
            });
        });

        // Manter submenus abertos no modo compacto ao mover o mouse
        document.addEventListener('DOMContentLoaded', function() {
            function setupCompactHoverMenus() {
                if (!document.body.classList.contains('sidebar-compact')) return;

                const items = document.querySelectorAll('#sidebar li');
                items.forEach(li => {
                    const submenu = li.querySelector("[id$='-submenu']");
                    if (!submenu) return;

                    let closeTimer = null;

                    function open() {
                        if (closeTimer) { clearTimeout(closeTimer); closeTimer = null; }
                        li.classList.add('submenu-open');
                        submenu.style.display = 'block';
                    }

                    function scheduleClose() {
                        closeTimer = setTimeout(() => {
                            li.classList.remove('submenu-open');
                            submenu.style.display = 'none';
                        }, 120);
                    }

                    li.addEventListener('mouseenter', open);
                    li.addEventListener('mouseleave', scheduleClose);
                    submenu.addEventListener('mouseenter', open);
                    submenu.addEventListener('mouseleave', scheduleClose);
                });
            }

            setupCompactHoverMenus();

            // Reconfigurar ao alternar modo compacto
            window.addEventListener('storage', function(e) {
                if (e.key === 'sidebar_compact') {
                    setTimeout(setupCompactHoverMenus, 50);
                }
            });
        });
    </script>

    <!-- Estilos do modo compacto da sidebar -->
    <style>
        /* Tamanho da marca (logo JAMEES): 28px expandido, 14px no compacto */
        #brand-logo { font-size: 28px; }
        .sidebar-compact #brand-logo { font-size: 14px !important; }

        /* Reduz a sidebar e esconde textos mantendo ícones visíveis */
        .sidebar-compact #sidebar { width: 5rem; }
        .sidebar-compact #sidebar { overflow: visible; }
        .sidebar-compact #sidebar nav { overflow: visible !important; }
        .sidebar-compact #sidebar .px-6 { padding-left: 0.75rem; padding-right: 0.75rem; }
        .sidebar-compact #sidebar .ml-4 { margin-left: 0; }
        .sidebar-compact #sidebar nav ul li a,
        .sidebar-compact #sidebar div[onclick^="toggleSubmenu"] {
            justify-content: center;
        }
        .sidebar-compact #sidebar nav ul li a i,
        .sidebar-compact #sidebar div[onclick^="toggleSubmenu"] i {
            margin-right: 0 !important;
        }
        /* Truque para esconder texto sem alterar ícones */
        .sidebar-compact #sidebar nav ul li a { font-size: 0; }
        .sidebar-compact #sidebar nav ul li a i { font-size: 1rem; }
        .sidebar-compact #sidebar div[onclick^="toggleSubmenu"] { font-size: 0; }
        .sidebar-compact #sidebar div[onclick^="toggleSubmenu"] i { font-size: 1rem; }

        /* Esconder setas/chevrons no modo compacto */
        .sidebar-compact #sidebar [id$='-arrow'] { display: none; }

        /* Flyout de submenu no modo compacto (mostrar ao passar o mouse) */
        .sidebar-compact #sidebar li { position: relative; }
        .sidebar-compact #sidebar [id$='-submenu'] {
            display: none !important;
            position: absolute;
            top: 0;
            left: calc(5rem - 1px); /* encosta no menu, sem gap */
            background: #ffffff;
            border: 1px solid #e5e7eb; /* gray-200 */
            border-radius: 0; /* sem borda arredondada */
            min-width: 12rem;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
            z-index: 1000;
            padding: 0; /* sem padding extra para reduzir área morta */
        }
        .sidebar-compact #sidebar li:hover > [id$='-submenu'] {
            display: block !important;
        }
        /* Manter aberto quando o mouse entra no flyout (não perder foco) */
        .sidebar-compact #sidebar [id$='-submenu']:hover { display: block !important; }
        /* Submenu de segundo nível (abre ao lado do primeiro) */
        .sidebar-compact #sidebar [id$='-submenu'] li { position: relative; }
        .sidebar-compact #sidebar [id$='-submenu'] [id$='-submenu'] {
            left: calc(100% - 1px); /* encosta sem gap */
            top: 0;
        }

        /* Textos devem aparecer dentro dos flyouts mesmo em modo compacto */
        .sidebar-compact #sidebar [id$='-submenu'] a { font-size: 0.875rem; } /* 14px */
        .sidebar-compact #sidebar [id$='-submenu'] div[onclick^="toggleSubmenu"] { font-size: 0.875rem; }
        .sidebar-compact #sidebar [id$='-submenu'] i { margin-right: 0.75rem !important; }

        /* Compactar cabeçalho/logo da sidebar (reduzir, não ocultar) */
        .sidebar-compact #sidebar .text-xl { font-size: 0.875rem; line-height: 1.25rem; }

        /* Compactar seletor de empresa e botão de sair (mostrar só ícones) */
        .sidebar-compact #sidebar #company-selector { font-size: 0; padding-left: 0.5rem; padding-right: 0.5rem; }
        .sidebar-compact #sidebar #company-selector i { font-size: 1rem; }
        .sidebar-compact #sidebar .border-t .w-8.h-8 { margin: 0 auto; }
        .sidebar-compact #sidebar .border-t .ml-3 { display: none; }
    </style>

    <!-- User Dropdown JavaScript -->
    <script>
        function toggleUserDropdown() {
            const menu = document.getElementById('user-dropdown-menu');
            const arrow = document.getElementById('user-dropdown-arrow');

            console.log('Toggle clicked, menu display:', menu.style.display);

            if (menu.style.display === 'none' || menu.style.display === '') {
                menu.style.display = 'block';
                arrow.style.transform = 'rotate(180deg)';
                console.log('Menu opened');
            } else {
                menu.style.display = 'none';
                arrow.style.transform = 'rotate(0deg)';
                console.log('Menu closed');
            }
        }

        // Fechar dropdown quando clicar fora
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('user-dropdown-menu');
            const button = event.target.closest('[onclick="toggleUserDropdown()"]');

            if (!button && !dropdown.contains(event.target)) {
                dropdown.style.display = 'none';
                document.getElementById('user-dropdown-arrow').style.transform = 'rotate(0deg)';
            }
        });
    </script>

    @stack('scripts')
</body>
</html>

