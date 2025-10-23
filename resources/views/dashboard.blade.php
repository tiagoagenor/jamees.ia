@extends('layouts.app')

@section('title', 'Dashboard - Jamees')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Welcome Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-user text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900">Bem-vindo!</h3>
                <p class="text-gray-600">{{ Auth::user()->nome }}</p>
            </div>
        </div>
    </div>

    <!-- Company Info -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-building text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900">Empresa Atual</h3>
                <p class="text-gray-600">Jamees Sistemas</p>
            </div>
        </div>
    </div>

    <!-- Last Access -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <i class="fas fa-clock text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900">Último Acesso</h3>
                <p class="text-gray-600">{{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-users text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Usuários</p>
                <p class="text-2xl font-semibold text-gray-900">1</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-building text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Empresas</p>
                <p class="text-2xl font-semibold text-gray-900">1</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-purple-100 text-purple-600">
                <i class="fas fa-chart-line text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Relatórios</p>
                <p class="text-2xl font-semibold text-gray-900">0</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-red-100 text-red-600">
                <i class="fas fa-cog text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Configurações</p>
                <p class="text-2xl font-semibold text-gray-900">0</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="mt-8">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Atividade Recente</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-center">
                    <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-900">Login realizado com sucesso</p>
                        <p class="text-xs text-gray-500">{{ now()->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="w-2 h-2 bg-blue-400 rounded-full"></div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-900">Sistema inicializado</p>
                        <p class="text-xs text-gray-500">{{ now()->subMinutes(5)->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
