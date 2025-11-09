<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demonstração - JAMEES</title>
    @include('components.favicon')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .demo-container {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        .demo-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .demo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .screenshot {
            border-radius: 8px;
            overflow: hidden;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .feature-highlight {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
        }
    </style>
</head>
<body class="demo-container min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-blue-600">JAMEES</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/" class="text-gray-700 hover:text-blue-600 transition-colors">Voltar ao Site</a>
                    <a href="/register" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Teste Grátis
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-6">
                Veja o JAMEES em <span class="text-blue-600">ação</span>
            </h1>
            <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                Explore as principais funcionalidades do nosso sistema de gestão empresarial
            </p>
        </div>
    </section>

    <!-- Demo Sections -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
                <!-- Dashboard Demo -->
                <div class="demo-card p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Dashboard Executivo</h3>
                            <p class="text-gray-600">Visão geral do seu negócio</p>
                        </div>
                    </div>
                    <div class="screenshot p-6 mb-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-3 gap-4 mb-4">
                                <div class="bg-green-100 p-3 rounded-lg text-center">
                                    <div class="text-2xl font-bold text-green-600">R$ 45.230</div>
                                    <div class="text-sm text-gray-600">Vendas Hoje</div>
                                </div>
                                <div class="bg-blue-100 p-3 rounded-lg text-center">
                                    <div class="text-2xl font-bold text-blue-600">127</div>
                                    <div class="text-sm text-gray-600">Pedidos</div>
                                </div>
                                <div class="bg-purple-100 p-3 rounded-lg text-center">
                                    <div class="text-2xl font-bold text-purple-600">89%</div>
                                    <div class="text-sm text-gray-600">Conversão</div>
                                </div>
                            </div>
                            <div class="bg-white rounded-lg p-4">
                                <div class="h-32 bg-gradient-to-r from-blue-100 to-green-100 rounded flex items-center justify-center">
                                    <i class="fas fa-chart-bar text-4xl text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Métricas em tempo real</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Gráficos interativos</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> KPIs personalizáveis</li>
                    </ul>
                </div>

                <!-- Financial Management Demo -->
                <div class="demo-card p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-chart-pie text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Gestão Financeira</h3>
                            <p class="text-gray-600">Controle total das finanças</p>
                        </div>
                    </div>
                    <div class="screenshot p-6 mb-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="space-y-3">
                                <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                                    <span class="text-gray-700">Contas a Pagar</span>
                                    <span class="font-bold text-red-600">R$ 12.450</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                                    <span class="text-gray-700">Contas a Receber</span>
                                    <span class="font-bold text-green-600">R$ 28.900</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                                    <span class="text-gray-700">Saldo Atual</span>
                                    <span class="font-bold text-blue-600">R$ 16.450</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Fluxo de caixa automático</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> DRE em tempo real</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Relatórios detalhados</li>
                    </ul>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
                <!-- Inventory Demo -->
                <div class="demo-card p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-boxes text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Controle de Estoque</h3>
                            <p class="text-gray-600">Gerencie produtos e fornecedores</p>
                        </div>
                    </div>
                    <div class="screenshot p-6 mb-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="space-y-2">
                                <div class="flex justify-between items-center p-2 bg-white rounded">
                                    <span class="text-sm">Produto A</span>
                                    <span class="text-sm font-bold text-green-600">150 unidades</span>
                                </div>
                                <div class="flex justify-between items-center p-2 bg-white rounded">
                                    <span class="text-sm">Produto B</span>
                                    <span class="text-sm font-bold text-yellow-600">5 unidades</span>
                                </div>
                                <div class="flex justify-between items-center p-2 bg-white rounded">
                                    <span class="text-sm">Produto C</span>
                                    <span class="text-sm font-bold text-red-600">0 unidades</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Alertas de estoque baixo</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Controle de fornecedores</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Histórico de movimentações</li>
                    </ul>
                </div>

                <!-- Sales Demo -->
                <div class="demo-card p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-shopping-cart text-orange-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Gestão de Vendas</h3>
                            <p class="text-gray-600">Acompanhe pedidos e clientes</p>
                        </div>
                    </div>
                    <div class="screenshot p-6 mb-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="space-y-2">
                                <div class="flex justify-between items-center p-2 bg-white rounded">
                                    <span class="text-sm">Pedido #001</span>
                                    <span class="text-sm font-bold text-green-600">R$ 450</span>
                                </div>
                                <div class="flex justify-between items-center p-2 bg-white rounded">
                                    <span class="text-sm">Pedido #002</span>
                                    <span class="text-sm font-bold text-blue-600">R$ 320</span>
                                </div>
                                <div class="flex justify-between items-center p-2 bg-white rounded">
                                    <span class="text-sm">Pedido #003</span>
                                    <span class="text-sm font-bold text-yellow-600">R$ 180</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Cadastro de clientes</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Acompanhamento de pedidos</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i> Relatórios de vendas</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-6">
                Pronto para experimentar o JAMEES?
            </h2>
            <p class="text-xl mb-8 text-blue-100">
                Comece seu teste grátis de 30 dias agora mesmo
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register" class="bg-yellow-400 text-gray-900 px-8 py-4 rounded-lg font-semibold hover:bg-yellow-300 transition-colors">
                    <i class="fas fa-rocket mr-2"></i>
                    Começar Teste Grátis
                </a>
                <a href="/" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar ao Site
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h3 class="text-2xl font-bold mb-4">JAMEES</h3>
                <p class="text-gray-400 mb-6">
                    Sistema de gestão empresarial completo
                </p>
                <div class="flex justify-center space-x-6">
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
                <div class="mt-8 pt-8 border-t border-gray-800 text-gray-400">
                    <p>&copy; 2025 JAMEES. Todos os direitos reservados.</p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
