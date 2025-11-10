<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JAMEES - Sistema de Gestão Empresarial</title>
    <meta name="description" content="Sistema de gestão empresarial completo para pequenas e médias empresas. Controle financeiro, vendas, estoque e muito mais.">
    @include('components.favicon')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/landing.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'jamees-blue': '#2563eb',
                        'jamees-green': '#10b981',
                        'jamees-purple': '#8b5cf6',
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .hero-bg {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 50%, #1e40af 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-jamees-blue" style="font-family: 'Roboto'; font-style: italic;">JAMEES</h1>
                    </div>
                </div>
                <nav class="hidden md:flex space-x-8">
                    <a href="#recursos" class="text-gray-700 hover:text-jamees-blue transition-colors">Recursos</a>
                    <a href="#precos" class="text-gray-700 hover:text-jamees-blue transition-colors">Preços</a>
                    <a href="#sobre" class="text-gray-700 hover:text-jamees-blue transition-colors">Sobre</a>
                    <a href="#contato" class="text-gray-700 hover:text-jamees-blue transition-colors">Contato</a>
                </nav>
                <div class="flex items-center space-x-4">
                    <a href="/login" class="text-gray-700 hover:text-jamees-blue transition-colors">Login</a>
                    <a href="/register" class="bg-jamees-blue text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Teste Grátis
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-bg text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-5xl font-bold mb-6">
                        Sistema de gestão empresarial para <span class="text-yellow-300">vender mais</span> e se preocupar menos
                    </h1>
                    <p class="text-xl mb-8 text-blue-100">
                        Controle financeiro, vendas, estoque e muito mais em uma única plataforma.
                        Automatize processos e foque no crescimento do seu negócio.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="/register" class="bg-yellow-400 text-gray-900 px-8 py-4 rounded-lg font-semibold hover:bg-yellow-300 transition-colors text-center">
                            <i class="fas fa-rocket mr-2"></i>
                            Comece Grátis Agora
                        </a>
                        <a href="#precos" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors text-center">
                            <i class="fas fa-tags mr-2"></i>
                            Ver Planos
                        </a>
                    </div>
                    <p class="text-sm text-blue-200 mt-4">
                        ✓ Teste grátis por 10 dias ✓ Sem fidelidade ✓ Suporte especializado
                    </p>
                </div>
                <div class="relative">
                    <div class="bg-white rounded-2xl shadow-2xl p-8 animate-float">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-gradient-to-r from-jamees-blue to-jamees-purple rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-chart-line text-white text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">Dashboard Inteligente</h3>
                            <p class="text-gray-600 text-sm">Controle total do seu negócio em tempo real</p>
                        </div>

                        <!-- Interface Simulada -->
                        <div class="space-y-4">
                            <!-- Header do Dashboard -->
                            <div class="bg-gray-50 rounded-lg p-3 flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-jamees-blue rounded flex items-center justify-center">
                                        <i class="fas fa-chart-bar text-white text-sm"></i>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700">Visão Geral</span>
                                </div>
                                <div class="flex space-x-2">
                                    <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                    <div class="w-2 h-2 bg-yellow-400 rounded-full"></div>
                                    <div class="w-2 h-2 bg-red-400 rounded-full"></div>
                                </div>
                            </div>

                            <!-- Cards de Métricas -->
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-3 border border-green-200">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-trending-up text-green-600 text-sm mr-2"></i>
                                        <span class="text-xs font-medium text-green-700">Vendas</span>
                                    </div>
                                    <div class="h-8 bg-white rounded flex items-end justify-between px-1 py-1">
                                        <div class="w-1 bg-green-400 rounded-t" style="height: 60%"></div>
                                        <div class="w-1 bg-green-400 rounded-t" style="height: 80%"></div>
                                        <div class="w-1 bg-green-400 rounded-t" style="height: 45%"></div>
                                        <div class="w-1 bg-green-400 rounded-t" style="height: 70%"></div>
                                        <div class="w-1 bg-green-400 rounded-t" style="height: 90%"></div>
                                    </div>
                                </div>

                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-3 border border-blue-200">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-users text-blue-600 text-sm mr-2"></i>
                                        <span class="text-xs font-medium text-blue-700">Clientes</span>
                                    </div>
                                    <div class="h-8 bg-white rounded flex items-end justify-between px-1 py-1">
                                        <div class="w-1 bg-blue-400 rounded-t" style="height: 40%"></div>
                                        <div class="w-1 bg-blue-400 rounded-t" style="height: 65%"></div>
                                        <div class="w-1 bg-blue-400 rounded-t" style="height: 55%"></div>
                                        <div class="w-1 bg-blue-400 rounded-t" style="height: 75%"></div>
                                        <div class="w-1 bg-blue-400 rounded-t" style="height: 85%"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Gráfico Principal -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-semibold text-gray-700">Performance Mensal</h4>
                                    <div class="flex space-x-1">
                                        <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                        <div class="w-2 h-2 bg-blue-400 rounded-full"></div>
                                        <div class="w-2 h-2 bg-purple-400 rounded-full"></div>
                                    </div>
                                </div>
                                <div class="h-16 bg-white rounded border flex items-end justify-between px-2 py-2">
                                    <div class="w-3 bg-gradient-to-t from-green-400 to-green-300 rounded-t" style="height: 50%"></div>
                                    <div class="w-3 bg-gradient-to-t from-blue-400 to-blue-300 rounded-t" style="height: 70%"></div>
                                    <div class="w-3 bg-gradient-to-t from-purple-400 to-purple-300 rounded-t" style="height: 40%"></div>
                                    <div class="w-3 bg-gradient-to-t from-green-400 to-green-300 rounded-t" style="height: 60%"></div>
                                    <div class="w-3 bg-gradient-to-t from-blue-400 to-blue-300 rounded-t" style="height: 80%"></div>
                                    <div class="w-3 bg-gradient-to-t from-purple-400 to-purple-300 rounded-t" style="height: 55%"></div>
                                    <div class="w-3 bg-gradient-to-t from-green-400 to-green-300 rounded-t" style="height: 75%"></div>
                                    <div class="w-3 bg-gradient-to-t from-blue-400 to-blue-300 rounded-t" style="height: 65%"></div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500 mt-2">
                                    <span>Jan</span>
                                    <span>Fev</span>
                                    <span>Mar</span>
                                    <span>Abr</span>
                                    <span>Mai</span>
                                    <span>Jun</span>
                                    <span>Jul</span>
                                    <span>Ago</span>
                                </div>
                            </div>

                            <!-- Status Indicators -->
                            <div class="flex justify-between items-center">
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                    <span class="text-xs text-gray-600">Sistema Online</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-sync-alt text-gray-400 text-xs animate-spin"></i>
                                    <span class="text-xs text-gray-600">Atualizando</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Elementos decorativos -->
                    <div class="absolute -top-2 -right-2 w-4 h-4 bg-yellow-400 rounded-full animate-pulse"></div>
                    <div class="absolute -bottom-2 -left-2 w-3 h-3 bg-green-400 rounded-full animate-pulse" style="animation-delay: 0.5s;"></div>
                    <div class="absolute top-1/2 -right-4 w-2 h-2 bg-purple-400 rounded-full animate-pulse" style="animation-delay: 1s;"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="recursos" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Recursos essenciais para <span class="text-jamees-blue">impulsionar</span> seu negócio
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Automatize processos e ganhe tempo para focar no crescimento do seu negócio
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Gestão Financeira -->
                <div class="bg-white rounded-xl shadow-lg p-8 card-hover border border-gray-100">
                    <div class="w-12 h-12 bg-jamees-green rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-chart-pie text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Gestão Financeira</h3>
                    <p class="text-gray-600 mb-6">
                        Controle contas a pagar e receber, fluxo de caixa, DRE e relatórios financeiros completos.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Contas a pagar e receber</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Fluxo de caixa em tempo real</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Relatórios financeiros</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> DRE automática</li>
                    </ul>
                </div>

                <!-- Controle de Estoque -->
                <div class="bg-white rounded-xl shadow-lg p-8 card-hover border border-gray-100">
                    <div class="w-12 h-12 bg-jamees-blue rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-boxes text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Controle de Estoque</h3>
                    <p class="text-gray-600 mb-6">
                        Gerencie produtos, fornecedores e movimentações de estoque com total controle.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Cadastro de produtos</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Controle de fornecedores</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Alertas de estoque baixo</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Relatórios de movimentação</li>
                    </ul>
                </div>

                <!-- Gestão de Vendas -->
                <div class="bg-white rounded-xl shadow-lg p-8 card-hover border border-gray-100">
                    <div class="w-12 h-12 bg-jamees-purple rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-shopping-cart text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Gestão de Vendas</h3>
                    <p class="text-gray-600 mb-6">
                        Acompanhe pedidos, clientes e vendas com dashboards inteligentes e relatórios detalhados.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Cadastro de clientes</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Controle de pedidos</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Dashboard de vendas</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Relatórios de performance</li>
                    </ul>
                </div>

                <!-- Contas Bancárias -->
                <div class="bg-white rounded-xl shadow-lg p-8 card-hover border border-gray-100">
                    <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-university text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Contas Bancárias</h3>
                    <p class="text-gray-600 mb-6">
                        Gerencie múltiplas contas bancárias e acompanhe saldos em tempo real.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Múltiplas contas</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Saldo em tempo real</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Histórico de movimentações</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Relatórios bancários</li>
                    </ul>
                </div>

                <!-- Formas de Pagamento -->
                <div class="bg-white rounded-xl shadow-lg p-8 card-hover border border-gray-100">
                    <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-credit-card text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Formas de Pagamento</h3>
                    <p class="text-gray-600 mb-6">
                        Configure e gerencie diferentes formas de pagamento para suas vendas.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Dinheiro, cartão, PIX</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Boleto bancário</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Transferência</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Relatórios de recebimento</li>
                    </ul>
                </div>

                <!-- Relatórios Inteligentes -->
                <div class="bg-white rounded-xl shadow-lg p-8 card-hover border border-gray-100">
                    <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-chart-bar text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Relatórios Inteligentes</h3>
                    <p class="text-gray-600 mb-6">
                        Acesse dashboards e relatórios detalhados para tomar decisões estratégicas.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Dashboard executivo</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Relatórios personalizados</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Exportação de dados</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Análise de tendências</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="precos" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Planos que cabem no seu <span class="text-jamees-blue">orçamento</span>
                </h2>
                <p class="text-xl text-gray-600">
                    Escolha o plano ideal para o seu negócio
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Plano Base -->
                <div class="flex flex-col">
                    <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-200 hover:border-jamees-blue transition-colors duration-300 cursor-pointer flex-grow">
                        <div class="text-center mb-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Plano Base</h3>
                            <p class="text-gray-600 mb-4">Para pequenas empresas</p>
                            <div class="text-4xl font-bold text-jamees-blue mb-2">R$ 29,90</div>
                            <p class="text-gray-500">/mês</p>
                            <p class="text-sm text-green-600 mt-2">Até 20% desconto anual</p>
                        </div>
                        <ul class="space-y-3">
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> Até 5 usuários</li>
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> 1 empresa</li>
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> Gestão básica de usuários</li>
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> Gestão básica de empresas</li>
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> Relatórios simples</li>
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> Suporte por email</li>
                        </ul>
                    </div>
                    <a href="/register" class="w-full bg-gray-900 text-white py-4 rounded-lg font-semibold text-center block hover:bg-gray-800 transition-colors mt-4">
                        Teste Grátis
                    </a>
                </div>

                <!-- Plano Premium -->
                <div class="flex flex-col">
                    <div class="bg-white rounded-xl shadow-lg p-8 border-2 border-jamees-blue relative hover:border-jamees-green transition-colors duration-300 cursor-pointer flex-grow">
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                            <span class="bg-jamees-blue text-white px-4 py-1 rounded-full text-sm font-semibold">Mais Popular</span>
                        </div>
                        <div class="text-center mb-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Plano Premium</h3>
                            <p class="text-gray-600 mb-4">Para empresas em crescimento</p>
                            <div class="text-4xl font-bold text-jamees-blue mb-2">R$ 59,90</div>
                            <p class="text-gray-500">/mês</p>
                            <p class="text-sm text-green-600 mt-2">Até 20% desconto anual</p>
                        </div>
                        <ul class="space-y-3">
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> Até 15 usuários</li>
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> Até 3 empresas</li>
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> Todas funcionalidades do Base</li>
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> Gestão avançada de usuários</li>
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> Gestão avançada de empresas</li>
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> Relatórios avançados</li>
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> Integração com APIs</li>
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> Suporte prioritário</li>
                        </ul>
                    </div>
                    <a href="/register" class="w-full bg-jamees-blue text-white py-4 rounded-lg font-semibold text-center block hover:bg-blue-700 transition-colors mt-4">
                        Teste Grátis
                    </a>
                </div>

                <!-- Plano Master -->
                <div class="flex flex-col">
                    <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-200 hover:border-jamees-purple transition-colors duration-300 cursor-pointer flex-grow">
                        <div class="text-center mb-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Plano Master</h3>
                            <p class="text-gray-600 mb-4">Para grandes empresas</p>
                            <div class="text-4xl font-bold text-jamees-blue mb-2">R$ 99,90</div>
                            <p class="text-gray-500">/mês</p>
                            <p class="text-sm text-green-600 mt-2">Até 20% desconto anual</p>
                        </div>
                        <ul class="space-y-3">
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> Até 50 usuários</li>
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> Até 10 empresas</li>
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> Todas funcionalidades do Premium</li>
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> Relatórios personalizados</li>
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> Integração completa com APIs</li>
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> Suporte 24/7</li>
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> Backup automático</li>
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> SLA garantido</li>
                        </ul>
                    </div>
                    <a href="/register" class="w-full bg-gray-900 text-white py-4 rounded-lg font-semibold text-center block hover:bg-gray-800 transition-colors mt-4">
                        Teste Grátis
                    </a>
                </div>

                <!-- Plano Personalizado -->
                <div class="flex flex-col">
                    <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-200 hover:border-red-500 transition-colors duration-300 cursor-pointer flex-grow">
                        <div class="text-center mb-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Plano Personalizado</h3>
                            <p class="text-gray-600 mb-4">Sob medida para sua empresa</p>
                            <div class="text-4xl font-bold text-jamees-blue mb-2">Sob Consulta</div>
                            <p class="text-gray-500">Preço personalizado</p>
                        </div>
                        <ul class="space-y-3">
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> Usuários ilimitados</li>
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> Empresas ilimitadas</li>
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> Funcionalidades personalizadas</li>
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> Desenvolvimento sob demanda</li>
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> Integração customizada</li>
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> Suporte dedicado</li>
                            <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i> Consultoria especializada</li>
                        </ul>
                    </div>
                    <a href="/register" class="w-full bg-gray-900 text-white py-4 rounded-lg font-semibold text-center block hover:bg-gray-800 transition-colors mt-4">
                        Teste Grátis
                    </a>
                </div>
            </div>

            <div class="text-center mt-12">
                <p class="text-gray-600 mb-4">Todos os planos incluem teste grátis de 10 dias</p>
                <a href="#contato" class="text-jamees-blue hover:text-blue-700 font-semibold">
                    Precisa de um plano personalizado? Entre em contato
                </a>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Por que escolher o <span class="text-jamees-blue" style="font-family: 'Roboto'; font-style: italic;">JAMEES</span>
                </h2>
                <p class="text-xl text-gray-600">
                    Descubra os benefícios que fazem do JAMEES a melhor escolha para sua empresa
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-gray-50 rounded-xl p-8">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-jamees-blue rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-shield-alt text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Segurança Garantida</h3>
                    </div>
                    <p class="text-gray-700 mb-6">
                        Seus dados estão protegidos com criptografia de ponta a ponta e backup automático diário.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Criptografia SSL</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Backup automático</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Conformidade LGPD</li>
                    </ul>
                </div>

                <div class="bg-gray-50 rounded-xl p-8">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-jamees-green rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-rocket text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Implementação Rápida</h3>
                    </div>
                    <p class="text-gray-700 mb-6">
                        Comece a usar o <span style="font-family: 'Roboto'; font-style: italic;">JAMEES</span> em minutos. Interface intuitiva que não requer treinamento complexo.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Setup em 5 minutos</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Interface intuitiva</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Suporte especializado</li>
                    </ul>
                </div>

                <div class="bg-gray-50 rounded-xl p-8">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-jamees-purple rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-chart-line text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Crescimento Comprovado</h3>
                    </div>
                    <p class="text-gray-700 mb-6">
                        Relatórios inteligentes e dashboards em tempo real para tomar decisões estratégicas.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Dashboards em tempo real</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Relatórios personalizados</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Análise de tendências</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 gradient-bg text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold mb-6">
                Pronto para transformar seu negócio?
            </h2>
            <p class="text-xl mb-8 text-blue-100">
                Junte-se a milhares de empresas que já usam o <span style="font-family: 'Roboto'; font-style: italic;">JAMEES</span> para crescer
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register" class="bg-yellow-400 text-gray-900 px-8 py-4 rounded-lg font-semibold hover:bg-yellow-300 transition-colors">
                    <i class="fas fa-rocket mr-2"></i>
                    Começar Teste Grátis
                </a>
                <a href="#contato" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors">
                    <i class="fas fa-phone mr-2"></i>
                    Falar com Especialista
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contato" class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-2xl font-bold mb-4" style="font-family: 'Roboto'; font-style: italic;">JAMEES</h3>
                    <p class="text-gray-400 mb-4">
                        Sistema de gestão empresarial completo para pequenas e médias empresas.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Produto</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#recursos" class="hover:text-white transition-colors">Recursos</a></li>
                        <li><a href="#precos" class="hover:text-white transition-colors">Preços</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Integrações</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">API</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Suporte</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Central de Ajuda</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Documentação</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Treinamentos</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Status do Sistema</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Contato</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-envelope mr-2"></i> contato@jamees.com.br</li>
                        <li><i class="fas fa-phone mr-2"></i> (11) 99999-9999</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i> São Paulo, SP</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
                <p>&copy; 2025 <span style="font-family: 'Roboto'; font-style: italic;">JAMEES</span>. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add scroll effect to header
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            if (window.scrollY > 100) {
                header.classList.add('bg-white/95', 'backdrop-blur-sm');
            } else {
                header.classList.remove('bg-white/95', 'backdrop-blur-sm');
            }
        });
    </script>
</body>
</html>
