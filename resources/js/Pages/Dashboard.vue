<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Informações do Usuário -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informações do Usuário</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm font-medium text-gray-500">Nome</p>
                                    <p class="text-lg text-gray-900">{{ user.nome }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm font-medium text-gray-500">Email</p>
                                    <p class="text-lg text-gray-900">{{ user.email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Informações da Empresa Atual -->
                        <div class="mb-8" v-if="empresaAtual">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Empresa Atual</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <p class="text-sm font-medium text-blue-500">Nome Fantasia</p>
                                    <p class="text-lg text-blue-900">{{ empresaAtual.nome_fantasia }}</p>
                                </div>
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <p class="text-sm font-medium text-blue-500">Razão Social</p>
                                    <p class="text-lg text-blue-900">{{ empresaAtual.razao_social }}</p>
                                </div>
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <p class="text-sm font-medium text-blue-500">CNPJ</p>
                                    <p class="text-lg text-blue-900">{{ empresaAtual.cnpj }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Informações do White Label -->
                        <div class="mb-8" v-if="whitelabelAtual">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">White Label</h3>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <p class="text-sm font-medium text-green-500">Nome do White Label</p>
                                <p class="text-lg text-green-900">{{ whitelabelAtual.nome }}</p>
                            </div>
                        </div>

                        <!-- Último Acesso -->
                        <div class="mb-8" v-if="ultimoAcesso">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Último Acesso</h3>
                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <p class="text-sm font-medium text-yellow-500">Informações do último acesso</p>
                                <p class="text-lg text-yellow-900">
                                    Empresa: {{ ultimoAcesso.empresa?.nome_fantasia || 'N/A' }} |
                                    White Label: {{ ultimoAcesso.whitelabel?.nome || 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <!-- Cards de Resumo -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">Usuário</p>
                                        <p class="text-2xl font-semibold text-gray-900">{{ user.nome }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm" v-if="empresaAtual">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">Empresa</p>
                                        <p class="text-2xl font-semibold text-gray-900">{{ empresaAtual.nome_fantasia }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm" v-if="whitelabelAtual">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">White Label</p>
                                        <p class="text-2xl font-semibold text-gray-900">{{ whitelabelAtual.nome }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    user: Object,
    empresaAtual: Object,
    whitelabelAtual: Object,
    ultimoAcesso: Object,
});
</script>
