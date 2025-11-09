@extends('layouts.app')

@section('title', 'Mapa - ' . $empreendimento->nome)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <div class="mb-6">
                <div class="mx-auto w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-map text-yellow-600 text-5xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    Imagem do Mapa Não Cadastrada
                </h1>
                <p class="text-gray-600">
                    Para visualizar o mapa do empreendimento <strong>{{ $empreendimento->nome }}</strong>, é necessário cadastrar a imagem do mapa.
                </p>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 mr-3 mt-1"></i>
                    <div class="text-left">
                        <p class="text-sm text-blue-900 font-medium mb-1">Como cadastrar a imagem do mapa:</p>
                        <ol class="text-sm text-blue-800 list-decimal list-inside space-y-1">
                            <li>Acesse a página de edição do empreendimento</li>
                            <li>Localize o campo "Imagem do Mapa"</li>
                            <li>Faça o upload da imagem do mapa (máximo 10MB)</li>
                            <li>Salve as alterações</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('empreendimentos.edit', $empreendimento) }}"
                    class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Editar Empreendimento
                </a>
                <a href="{{ route('empreendimentos.index') }}"
                    class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar para Empreendimentos
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

