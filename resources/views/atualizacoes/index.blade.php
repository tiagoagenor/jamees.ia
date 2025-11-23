@extends('layouts.app')

@section('title', 'Atualizações do Sistema')

@section('content')
<style>
    .timeline-container {
        position: relative;
        padding-left: 3rem;
    }

    .timeline-line {
        position: absolute;
        left: 1.5rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #3b82f6, #e5e7eb);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
        padding-left: 2rem;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-dot {
        position: absolute;
        left: -3.0rem;
        top: 50%;
        transform: translateY(-50%);
        width: 3rem;
        height: 3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        z-index: 10;
    }

    .timeline-dot.novos-recursos {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        border-radius: 50%;
    }

    .timeline-dot.melhorias {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border-radius: 50%;
    }

    .timeline-content {
        background: white;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        padding: 1.5rem;
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    .timeline-content:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transform: translateY(-2px);
    }

    .timeline-content.novos-recursos {
        border-left-color: #3b82f6;
    }

    .timeline-content.melhorias {
        border-left-color: #10b981;
    }

    @media (max-width: 768px) {
        .timeline-container {
            padding-left: 2rem;
        }

        .timeline-line {
            left: 1rem;
        }

        .timeline-dot {
            left: -1.5rem;
            width: 2.5rem;
            height: 2.5rem;
        }

        .timeline-item {
            padding-left: 1.5rem;
        }
    }
</style>

<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-sync-alt text-blue-600 mr-3"></i>
            Atualizações do Sistema
        </h1>
        <p class="text-gray-600 mt-2">Fique por dentro das últimas novidades e melhorias do sistema</p>
    </div>

    <!-- Timeline Vertical -->
    <div class="timeline-container">
        <!-- Linha Vertical -->
        <div class="timeline-line"></div>

        <!-- Itens da Timeline -->
        @forelse($atualizacoes as $atualizacao)
            <div class="timeline-item">
                <!-- Ponto na Timeline -->
                <div class="timeline-dot {{ $atualizacao->tipo->value === 1 ? 'novos-recursos' : 'melhorias' }}">
                    <i class="fas {{ $atualizacao->tipo_icon }}"></i>
                </div>

                <!-- Conteúdo -->
                <div class="timeline-content {{ $atualizacao->tipo->value === 1 ? 'novos-recursos' : 'melhorias' }}">
                    <div class="flex items-start justify-between gap-4 mb-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-3 py-1 text-xs font-semibold {{ $atualizacao->tipo->value === 1 ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                    {{ $atualizacao->tipo_label }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    <i class="far fa-calendar mr-1"></i>
                                    {{ $atualizacao->created_at->format('d/m/Y') }}
                                </span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $atualizacao->titulo }}</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $atualizacao->minitexto }}</p>
                        </div>
                    </div>
                    
                    <!-- Botão Saiba Mais -->
                    <div class="mt-4">
                        <a href="{{ route('atualizacoes.show', $atualizacao) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 transition-colors duration-200 text-sm font-medium">
                            <i class="fas fa-info-circle mr-2"></i>
                            Saiba Mais
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Nenhuma atualização encontrada</h3>
                <p class="text-gray-500">Ainda não há atualizações cadastradas no sistema.</p>
            </div>
        @endforelse
    </div>

    <!-- Paginação -->
    @if($atualizacoes->hasPages())
        <div class="mt-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-700">
                    Mostrando {{ $atualizacoes->firstItem() }} até {{ $atualizacoes->lastItem() }} de {{ $atualizacoes->total() }} resultados
                </div>
                <div class="flex items-center space-x-1 flex-wrap justify-center">
                    @php
                        $currentPage = $atualizacoes->currentPage();
                        $lastPage = $atualizacoes->lastPage();
                        $onEachSide = 2; // Número de páginas a mostrar de cada lado da página atual
                        
                        // Calcular o range de páginas a mostrar
                        $start = max(1, $currentPage - $onEachSide);
                        $end = min($lastPage, $currentPage + $onEachSide);
                        
                        // Ajustar se estiver muito perto do início ou fim
                        if ($start == 1) {
                            $end = min($lastPage, $start + ($onEachSide * 2) + 1);
                        }
                        if ($end == $lastPage) {
                            $start = max(1, $end - ($onEachSide * 2) - 1);
                        }
                    @endphp
                    
                    {{-- Botão Primeira Página --}}
                    @if ($atualizacoes->onFirstPage())
                        <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed" title="Primeira página">
                            <i class="fas fa-angle-double-left"></i>
                        </span>
                    @else
                        <a href="{{ $atualizacoes->url(1) }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900" title="Primeira página">
                            <i class="fas fa-angle-double-left"></i>
                        </a>
                    @endif

                    {{-- Botão Página Anterior --}}
                    @if ($atualizacoes->onFirstPage())
                        <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $atualizacoes->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    {{-- Primeira página --}}
                    @if ($start > 1)
                        <a href="{{ $atualizacoes->url(1) }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">1</a>
                        @if ($start > 2)
                            <span class="px-3 py-2 text-sm text-gray-500">...</span>
                        @endif
                    @endif

                    {{-- Páginas do range --}}
                    @for ($page = $start; $page <= $end; $page++)
                        @if ($page == $currentPage)
                            <span class="px-3 py-2 text-sm text-white bg-blue-600 border border-blue-600 rounded-md font-semibold">{{ $page }}</span>
                        @else
                            <a href="{{ $atualizacoes->url($page) }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">{{ $page }}</a>
                        @endif
                    @endfor

                    {{-- Última página --}}
                    @if ($end < $lastPage)
                        @if ($end < $lastPage - 1)
                            <span class="px-3 py-2 text-sm text-gray-500">...</span>
                        @endif
                        <a href="{{ $atualizacoes->url($lastPage) }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">{{ $lastPage }}</a>
                    @endif

                    {{-- Botão Próxima Página --}}
                    @if ($atualizacoes->hasMorePages())
                        <a href="{{ $atualizacoes->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    @endif

                    {{-- Botão Última Página --}}
                    @if ($atualizacoes->currentPage() == $lastPage)
                        <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed" title="Última página">
                            <i class="fas fa-angle-double-right"></i>
                        </span>
                    @else
                        <a href="{{ $atualizacoes->url($lastPage) }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-900" title="Última página">
                            <i class="fas fa-angle-double-right"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

