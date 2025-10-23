<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Jamees')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/menu.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white flex flex-col">
            <!-- Logo -->
            <div class="p-4 border-b border-gray-700 text-center">
                <span style="font-family: 'Roboto'; font-size: 28px; font-weight: bold; font-style: italic; color: white;">JAMEES</span>
            </div>

            <!-- Menu -->
            <nav class="flex-1 p-4">
                <ul class="space-y-1">
                    <!-- Início -->
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <i class="fas fa-home mr-3"></i>
                            Início
                        </a>
                    </li>

                    <!-- Cadastro -->
                    <li>
                        <div class="flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white cursor-pointer" onclick="toggleSubmenu('cadastro')">
                            <div class="flex items-center">
                                <i class="fas fa-user-plus mr-3"></i>
                                Cadastro
                            </div>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200" id="cadastro-arrow"></i>
                        </div>
                        <ul id="cadastro-submenu" class="ml-6 mt-1 space-y-1 hidden">
                            <li>
                                <a href="#" class="flex items-center px-3 py-2 rounded-md text-sm text-gray-400 hover:bg-gray-700 hover:text-white">
                                    <i class="fas fa-user mr-3"></i>
                                    Cliente
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center px-3 py-2 rounded-md text-sm text-gray-400 hover:bg-gray-700 hover:text-white">
                                    <i class="fas fa-truck mr-3"></i>
                                    Fornecedor
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('usuarios.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm {{ request()->routeIs('usuarios.*') ? 'text-white bg-gray-600' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                                    <i class="fas fa-id-badge mr-3"></i>
                                    Funcionário
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center px-3 py-2 rounded-md text-sm text-gray-400 hover:bg-gray-700 hover:text-white">
                                    <i class="fas fa-shipping-fast mr-3"></i>
                                    Transportadora
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Financeiro -->
                    <li>
                        <div class="flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white cursor-pointer" onclick="toggleSubmenu('financeiro')">
                            <div class="flex items-center">
                                <i class="fas fa-dollar-sign mr-3"></i>
                                Financeiro
                            </div>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200" id="financeiro-arrow"></i>
                        </div>
                        <ul id="financeiro-submenu" class="ml-6 mt-1 space-y-1 hidden">
                            <li>
                                <a href="#" class="flex items-center px-3 py-2 rounded-md text-sm text-gray-400 hover:bg-gray-700 hover:text-white">
                                    <i class="fas fa-chart-line mr-3"></i>
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center px-3 py-2 rounded-md text-sm text-gray-400 hover:bg-gray-700 hover:text-white">
                                    <i class="fas fa-chart-pie mr-3"></i>
                                    DRE Gerencial
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center px-3 py-2 rounded-md text-sm text-gray-400 hover:bg-gray-700 hover:text-white">
                                    <i class="fas fa-credit-card mr-3"></i>
                                    Contas a Pagar
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center px-3 py-2 rounded-md text-sm text-gray-400 hover:bg-gray-700 hover:text-white">
                                    <i class="fas fa-money-bill-wave mr-3"></i>
                                    Contas a Receber
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Configurações -->
                    <li>
                        <div class="flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white cursor-pointer" onclick="toggleSubmenu('configuracoes')">
                            <div class="flex items-center">
                                <i class="fas fa-cog mr-3"></i>
                                Configurações
                            </div>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200" id="configuracoes-arrow"></i>
                        </div>
                        <ul id="configuracoes-submenu" class="ml-6 mt-1 space-y-1 hidden">
                            <li>
                                <a href="#" class="flex items-center px-3 py-2 rounded-md text-sm text-gray-400 hover:bg-gray-700 hover:text-white">
                                    <i class="fas fa-sliders-h mr-3"></i>
                                    Geral
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center px-3 py-2 rounded-md text-sm text-gray-400 hover:bg-gray-700 hover:text-white">
                                    <i class="fas fa-crown mr-3"></i>
                                    Meu Plano
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('usuarios.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm {{ request()->routeIs('usuarios.*') ? 'text-white bg-gray-600' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                                    <i class="fas fa-user mr-3"></i>
                                    Usuário
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center px-3 py-2 rounded-md text-sm text-gray-400 hover:bg-gray-700 hover:text-white">
                                    <i class="fas fa-users-cog mr-3"></i>
                                    Grupo de Usuário
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('empresas.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm {{ request()->routeIs('empresas.*') ? 'text-white bg-gray-600' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                                    <i class="fas fa-building mr-3"></i>
                                    Empresa/Loja
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center px-3 py-2 rounded-md text-sm text-gray-400 hover:bg-gray-700 hover:text-white">
                                    <i class="fas fa-envelope mr-3"></i>
                                    Modelos de Emails
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>

            <!-- Company Selector -->
            <div class="p-4 border-t border-gray-700">
                <div class="relative">
                    <button id="company-selector" class="w-full flex items-center justify-between px-3 py-2 bg-gray-700 rounded-md text-sm font-medium text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-building text-sm"></i>
                            </div>
                            <div class="ml-3 text-left">
                                <p class="text-sm font-medium" id="current-company-name">
                                    @if(session('current_company'))
                                        {{ session('current_company')->nome_fantasia ?? session('current_company')->razao_social }}
                                    @else
                                        Nenhuma empresa selecionada
                                    @endif
                                </p>
                                <p class="text-xs text-gray-400" id="current-company-type">
                                    @if(session('current_company'))
                                        {{ session('current_company')->tipo }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>

                    <!-- Company Dropdown -->
                    <div id="company-dropdown" class="absolute bottom-full left-0 right-0 mb-2 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                        <div class="px-4 py-2 text-xs text-gray-500 border-b">
                            Trocar Empresa
                        </div>
                        @if(Auth::user()->empresas->count() > 0)
                            @foreach(Auth::user()->empresas as $empresa)
                                <a href="#"
                                   onclick="switchCompany('{{ $empresa->id }}', '{{ $empresa->nome_fantasia ?? $empresa->razao_social }}', '{{ $empresa->tipo }}')"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ session('current_company') && session('current_company')->id == $empresa->id ? 'bg-blue-50 text-blue-700' : '' }}">
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-building text-xs"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium">{{ $empresa->nome_fantasia ?? $empresa->razao_social }}</div>
                                            <div class="text-xs text-gray-500">{{ $empresa->tipo }}</div>
                                        </div>
                                        @if(session('current_company') && session('current_company')->id == $empresa->id)
                                            <i class="fas fa-check text-blue-600 ml-auto"></i>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        @else
                            <div class="px-4 py-2 text-sm text-gray-500">
                                Nenhuma empresa vinculada
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Logout Button -->
            <div class="p-4 border-t border-gray-700">
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="w-full flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200">
                    <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-sign-out-alt text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">Sair do Sistema</p>
                        <p class="text-xs text-gray-400">{{ Auth::user()->nome }}</p>
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
                        <h2 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="text-gray-500 hover:text-gray-700 focus:outline-none">
                            <i class="fas fa-bell text-xl"></i>
                        </button>

                        <!-- User Dropdown -->
                        <div class="relative">
                            <button class="flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
                                <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-sm"></i>
                                </div>
                                <i class="fas fa-chevron-down ml-2 text-sm"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Perfil</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Configurações</a>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
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
                    @yield('content')
                </div>
            </main>
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
                submenu.classList.toggle('hidden');
                arrow.classList.toggle('rotate-180');
            }
        }

        // Company selector dropdown
        document.addEventListener('DOMContentLoaded', function() {
            const companySelector = document.getElementById('company-selector');
            const companyDropdown = document.getElementById('company-dropdown');

            if (companySelector && companyDropdown) {
                companySelector.addEventListener('click', function(e) {
                    e.stopPropagation();
                    companyDropdown.classList.toggle('hidden');
                });

                document.addEventListener('click', function() {
                    companyDropdown.classList.add('hidden');
                });
            }
        });

        // Switch company function
        function switchCompany(companyId, companyName, companyType) {
            // Show loading state
            const selector = document.getElementById('company-selector');
            const originalContent = selector.innerHTML;

            selector.innerHTML = `
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-spinner fa-spin text-sm"></i>
                    </div>
                    <div class="ml-3 text-left">
                        <p class="text-sm font-medium">Trocando empresa...</p>
                    </div>
                </div>
            `;
            selector.disabled = true;

            // Make AJAX request to switch company
            fetch('/switch-company', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    company_id: companyId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the display
                    document.getElementById('current-company-name').textContent = companyName;
                    document.getElementById('current-company-type').textContent = companyType;

                    // Reload the page to update all data
                    window.location.reload();
                } else {
                    // Show error
                    alert('Erro ao trocar empresa: ' + (data.message || 'Erro desconhecido'));
                    selector.innerHTML = originalContent;
                    selector.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erro ao trocar empresa');
                selector.innerHTML = originalContent;
                selector.disabled = false;
            });
        }
    </script>
</body>
</html>
