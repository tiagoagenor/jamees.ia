@extends('layouts.app')

@section('title', 'Relatórios - Cadastros')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-user-plus text-blue-600 mr-3"></i>
                    Relatórios - Cadastros
                </h1>
                <p class="text-gray-600 mt-2">Selecione o tipo de relatório que deseja visualizar</p>
            </div>
        </div>
    </div>

    <!-- Cards de Relatórios -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Card Clientes -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 cursor-pointer" onclick="abrirRelatorio('clientes')">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-tie text-blue-600 text-2xl"></i>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Clientes</h3>
                <p class="text-gray-600 text-sm mb-4">Relatório de clientes. Filtro por tipo, situação, nome, telefone, e-mail e período.</p>
                <div class="flex items-center text-blue-600 text-sm font-medium">
                    <span>Ver relatório</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </div>
        </div>

        <!-- Card Aniversariantes -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 cursor-pointer" onclick="abrirRelatorio('aniversariantes')">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-birthday-cake text-pink-600 text-2xl"></i>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Aniversariantes</h3>
                <p class="text-gray-600 text-sm mb-4">Relatório de aniversariantes. Filtro por data, situação cadastral, cidade e estado.</p>
                <div class="flex items-center text-pink-600 text-sm font-medium">
                    <span>Ver relatório</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </div>
        </div>

        <!-- Card Funcionários -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 cursor-pointer" onclick="abrirRelatorio('funcionarios')">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-purple-600 text-2xl"></i>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Funcionários</h3>
                <p class="text-gray-600 text-sm mb-4">Relatório de funcionários. Filtro por nome, telefone/celular, email, cidade, estado, campos extras e período.</p>
                <div class="flex items-center text-purple-600 text-sm font-medium">
                    <span>Ver relatório</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </div>
        </div>

        <!-- Card Fornecedores -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 cursor-pointer" onclick="abrirRelatorio('fornecedores')">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-truck text-green-600 text-2xl"></i>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Fornecedores</h3>
                <p class="text-gray-600 text-sm mb-4">Relatório de fornecedores. Filtro por tipo, situação, nome, telefone, e-mail e período.</p>
                <div class="flex items-center text-green-600 text-sm font-medium">
                    <span>Ver relatório</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </div>
        </div>

        <!-- Card Transportadoras -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 cursor-pointer" onclick="abrirRelatorio('transportadoras')">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-shipping-fast text-orange-600 text-2xl"></i>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Transportadoras</h3>
                <p class="text-gray-600 text-sm mb-4">Relatório de transportadoras. Filtro por tipo, situação, nome, telefone, e-mail e período.</p>
                <div class="flex items-center text-orange-600 text-sm font-medium">
                    <span>Ver relatório</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function abrirRelatorio(tipo) {
    // Redirecionar para a página do relatório específico
    window.location.href = `/relatorios/cadastros/${tipo}`;
}
</script>
@endsection
