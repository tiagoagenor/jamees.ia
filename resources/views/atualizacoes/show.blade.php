@extends('layouts.app')

@section('title', $atualizacao->titulo . ' - Atualizações')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
            <li><i class="fas fa-chevron-right text-gray-400"></i></li>
            <li><a href="{{ route('atualizacoes.index') }}" class="hover:text-blue-600">Atualizações</a></li>
            <li><i class="fas fa-chevron-right text-gray-400"></i></li>
            <li class="text-gray-900 font-medium">{{ $atualizacao->titulo }}</li>
        </ol>
    </nav>

    <!-- Botão Voltar -->
    <div class="mb-6">
        <a href="{{ route('atualizacoes.index') }}" 
           class="inline-flex items-center text-blue-600 hover:text-blue-700">
            <i class="fas fa-arrow-left mr-2"></i>
            Voltar para Atualizações
        </a>
    </div>

    <!-- Card de Detalhes -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
        <!-- Header -->
        <div class="mb-6 pb-6 border-b border-gray-200">
            <div class="flex items-start gap-4">
                <!-- Ícone do Tipo -->
                <div class="flex-shrink-0">
                    <div class="w-16 h-16 rounded-lg flex items-center justify-center {{ $atualizacao->tipo->value === 1 ? 'bg-blue-100 text-blue-600' : 'bg-green-100 text-green-600' }}">
                        <i class="fas {{ $atualizacao->tipo_icon }} text-2xl"></i>
                    </div>
                </div>

                <!-- Título e Tipo -->
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 text-sm font-semibold rounded {{ $atualizacao->tipo->value === 1 ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                            {{ $atualizacao->tipo_label }}
                        </span>
                        <span class="text-sm text-gray-500">
                            <i class="far fa-calendar mr-1"></i>
                            {{ $atualizacao->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $atualizacao->titulo }}</h1>
                    <p class="text-lg text-gray-600">{{ $atualizacao->minitexto }}</p>
                </div>
            </div>
        </div>

        <!-- Conteúdo HTML -->
        <div class="prose max-w-none">
            {!! $atualizacao->texto !!}
        </div>
    </div>
</div>

<style>
    .prose {
        color: #374151;
        line-height: 1.75;
    }
    .prose h1, .prose h2, .prose h3, .prose h4 {
        font-weight: 700;
        margin-top: 1.5em;
        margin-bottom: 0.75em;
        color: #1f2937;
    }
    .prose h1 { font-size: 2em; }
    .prose h2 { font-size: 1.5em; }
    .prose h3 { font-size: 1.25em; }
    .prose p {
        margin-bottom: 1em;
    }
    .prose ul, .prose ol {
        margin: 1em 0;
        padding-left: 2em;
    }
    .prose li {
        margin: 0.5em 0;
    }
    .prose a {
        color: #2563eb;
        text-decoration: underline;
    }
    .prose a:hover {
        color: #1d4ed8;
    }
    .prose img {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
        margin: 1.5em 0;
    }
    .prose code {
        background: #f3f4f6;
        padding: 0.2em 0.4em;
        border-radius: 0.25rem;
        font-size: 0.9em;
    }
    .prose pre {
        background: #1f2937;
        color: #f9fafb;
        padding: 1em;
        border-radius: 0.5rem;
        overflow-x: auto;
        margin: 1.5em 0;
    }
    .prose blockquote {
        border-left: 4px solid #3b82f6;
        padding-left: 1em;
        margin: 1.5em 0;
        font-style: italic;
        color: #6b7280;
    }
</style>
@endsection

