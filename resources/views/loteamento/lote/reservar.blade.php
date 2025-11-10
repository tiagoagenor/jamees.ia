@extends('layouts.app')

@section('title', 'Reservar Lote - ' . $lote->nome)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Reservar Lote</h1>
            <p class="text-gray-600">Empreendimento: <strong>{{ $empreendimento->nome }}</strong></p>
            <p class="text-gray-600">Lote: <strong>{{ $lote->nome }}</strong></p>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p class="text-blue-800">
                <strong>Informações do Lote:</strong>
            </p>
            <ul class="mt-2 space-y-1 text-blue-700">
                @if($lote->valor)
                    <li>Valor: <strong>R$ {{ number_format($lote->valor, 2, ',', '.') }}</strong></li>
                @endif
                @if($lote->m2)
                    <li>Área: <strong>{{ number_format($lote->m2, 2, ',', '.') }} m²</strong></li>
                @endif
                @if($lote->status)
                    <li>Status: <strong>{{ $lote->status->nome }}</strong></li>
                @endif
            </ul>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <p class="text-yellow-800">
                <strong>⚠️ Tela em desenvolvimento</strong>
            </p>
            <p class="text-yellow-700 mt-2">
                Esta tela será implementada em breve.
            </p>
        </div>

        <div class="mt-6">
            <a href="{{ route('loteamentos.mapa.view', $empreendimento->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar ao Mapa
            </a>
        </div>
    </div>
</div>
@endsection

