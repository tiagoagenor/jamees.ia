@extends('layouts.app')

@section('title', 'Mapa - Loteamentos')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-map-marked-alt text-emerald-600 mr-3"></i>
                    Mapa - Loteamentos
                </h1>
                <p class="text-gray-600 mt-2">Visualize os mapas dos empreendimentos</p>
            </div>
        </div>
    </div>

    <!-- Lista de Empreendimentos -->
    @if($empreendimentos->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($empreendimentos as $empreendimento)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    @if($empreendimento->imagem)
                        <img src="{{ asset($empreendimento->imagem) }}" alt="{{ $empreendimento->nome }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-building text-gray-400 text-4xl"></i>
                        </div>
                    @endif
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-xl font-semibold text-gray-900">{{ $empreendimento->nome }}</h3>
                            @if($empreendimento->status == 1)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Ativo</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inativo</span>
                            @endif
                        </div>
                        <div class="space-y-2 mb-4">
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-map-marked-alt text-gray-400 mr-2"></i>
                                Lotes: <span class="font-medium">{{ $empreendimento->lotes()->count() }}</span>
                            </p>
                        </div>
                        <!-- Botão Ver Mapa -->
                        <div class="mt-4">
                            <a href="{{ route('loteamentos.mapa.view', $empreendimento->id) }}" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-center px-4 py-2 rounded-md text-sm font-medium block">
                                <i class="fas fa-map mr-2"></i>
                                Ver Mapa
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-map-marked-alt text-gray-400 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Nenhum empreendimento encontrado</h3>
            <p class="text-gray-600">Não há empreendimentos cadastrados para visualizar no mapa.</p>
        </div>
    @endif
</div>
@endsection

