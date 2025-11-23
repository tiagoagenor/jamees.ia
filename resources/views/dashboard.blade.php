@extends('layouts.app')

@section('title', 'Dashboard - Jamees')
@section('page-title', 'Dashboard')

@section('content')
<style>

    .dashboard-card {
        background: white;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        height: 450px;
    }

    .dashboard-card.welcome-card {
        max-height: 450px;
        overflow-y: auto;
        padding-right: 0.5rem;
    }

    .dashboard-card.welcome-card::-webkit-scrollbar {
        width: 6px;
    }

    .dashboard-card.welcome-card::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .dashboard-card.welcome-card::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .dashboard-card.welcome-card::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .welcome-section {
        text-align: left;
    }

    .company-logo {
        max-width: 220px;
        max-height: 110px;
        object-fit: contain;
        margin: 0 auto 1.5rem auto;
        display: block;
    }

    .welcome-section h5 {
        font-size: 0.9375rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .welcome-section .welcome-text {
        font-size: 0.8125rem;
        color: #6b7280;
        margin-bottom: 1.5rem;
    }

    .company-message-title {
        font-size: 0.875rem;
        font-weight: 700;
        color: #1f2937;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
    }

    .company-quote {
        font-style: italic;
        color: #6b7280;
        font-size: 0.8125rem;
        margin-top: 0.5rem;
    }

    .video-container {
        position: relative;
        min-height: 200px;
        flex: 1;
        overflow: hidden;
        border-radius: 0.5rem;
    }

    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 0.5rem;
    }

    .updates-list {
        list-style: none;
        padding: 0;
        margin: 0;
        max-height: 500px;
        overflow-y: auto;
        padding-right: 0.5rem;
    }

    .updates-list::-webkit-scrollbar {
        width: 6px;
    }

    .updates-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .updates-list::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .updates-list::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .update-item {
        padding: 0;
        background: #f9fafb;
        margin-bottom: 0.75rem;
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .update-item a {
        padding: 1rem;
        display: flex;
        gap: 1rem;
        text-decoration: none;
        color: inherit;
    }

    .update-icon {
        flex-shrink: 0;
        width: 40px;
        height: 40px;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .update-icon.melhorias {
        background: #d1fae5;
        color: #10b981;
    }

    .update-icon.novos-recursos {
        background: #dbeafe;
        color: #3b82f6;
    }

    .update-content {
        flex: 1;
    }

    .update-item h4 {
        font-size: 0.875rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }

    .update-item p {
        font-size: 0.8125rem;
        color: #6b7280;
        margin: 0 0 0.5rem 0;
        line-height: 1.5;
    }

    .update-date {
        font-size: 0.6875rem;
        color: #9ca3af;
        margin-top: 0.25rem;
    }

    .btn-portal-ideias {
        width: 100%;
        padding: 0.625rem 1rem;
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: background 0.2s;
        margin-top: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-portal-ideias:hover {
        background: #2563eb;
    }

    .development-list {
        max-height: 500px;
        overflow-y: auto;
        padding-right: 0.5rem;
    }

    .development-list::-webkit-scrollbar {
        width: 6px;
    }

    .development-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .development-list::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .development-list::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .development-item {
        padding: 1rem;
        background: #f9fafb;
        border-radius: 0.5rem;
        margin-bottom: 0.75rem;
    }

    .development-item h4 {
        font-size: 0.875rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .progress-bar {
        width: 100%;
        height: 8px;
        background: #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
        margin-top: 0.5rem;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #3b82f6, #2563eb);
        transition: width 0.3s ease;
    }

    .progress-text {
        font-size: 0.6875rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }


    .section-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .video-section-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e5e7eb;
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #9ca3af;
        font-size: 0.875rem;
    }

    @media (max-width: 1024px) {
        .welcome-section h5 {
            font-size: 0.875rem;
        }
        
        .section-title,
        .video-section-title {
            font-size: 1rem;
        }
    }
</style>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
    <!-- Coluna 1 -->
    <div class="flex flex-col gap-6">
        <!-- Parte 1: Logo e Boas-vindas -->
        <div class="dashboard-card welcome-card">
            <div class="welcome-section">
                @if(isset($configuracoes['logo_empresa']) && $configuracoes['logo_empresa'])
                    <img src="{{ asset('storage/' . $configuracoes['logo_empresa']) }}" alt="Logo" class="company-logo">
                @else
                    <div class="company-logo" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color: white; font-weight: 700; font-size: 2rem; font-family: 'Roboto', sans-serif; font-style: italic;">
                        JAMEES
                    </div>
                @endif
                
                <h5>Olá, empresa!</h5>
                <p class="welcome-text">{{ $configuracoes['texto_boas_vindas'] ?? 'Tenha um excelente dia!' }}</p>
                
                <h5 class="company-message-title">Mensagem da Empresa</h5>
                <div class="company-quote">
                    "{{ $configuracoes['frase_empresa'] ?? 'Transformando desafios em oportunidades' }}"
                </div>
            </div>
        </div>

        <!-- Parte 2: Vídeo do YouTube -->
        @if(isset($configuracoes['video_institucional']) && $configuracoes['video_institucional'])
            @php
                $videoUrl = $configuracoes['video_institucional'];
                // Se for URL completa, extrair o ID, senão usar o valor direto
                if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/', $videoUrl, $matches)) {
                    $videoId = $matches[1];
                } else {
                    $videoId = $videoUrl;
                }
            @endphp
            <div class="dashboard-card">
                <h3 class="video-section-title">Vídeo Institucional</h3>
                <div class="video-container" style="flex: 1;">
                    <iframe 
                        src="https://www.youtube.com/embed/{{ $videoId }}" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        @endif
    </div>

    <!-- Coluna 2 -->
    <div class="flex flex-col gap-6">
        <!-- Parte 1: Atualizações -->
        <div class="dashboard-card">
            <h3 class="section-title">Atualizações</h3>
            <ul class="updates-list" id="updatesList" style="flex: 1;">
                @forelse($atualizacoes as $atualizacao)
                    <li class="update-item">
                        <a href="{{ route('atualizacoes.show', $atualizacao) }}" class="flex gap-4 w-full hover:opacity-80 transition-opacity">
                            <div class="update-icon {{ $atualizacao->tipo->value === 1 ? 'novos-recursos' : 'melhorias' }}">
                                <i class="fas {{ $atualizacao->tipo_icon }}"></i>
                            </div>
                            <div class="update-content">
                                <h4>{{ $atualizacao->titulo }}</h4>
                                <p>{{ $atualizacao->minitexto }}</p>
                                <div class="update-date">{{ $atualizacao->created_at->format('d/m/Y') }}</div>
                            </div>
                        </a>
                    </li>
                @empty
                    <li class="update-item">
                        <div class="update-content">
                            <p class="text-gray-500 text-center">Nenhuma atualização disponível no momento.</p>
                        </div>
                    </li>
                @endforelse
            </ul>
            <a href="{{ route('atualizacoes.index') }}" class="btn-portal-ideias" style="text-decoration: none;">
                <i class="fas fa-list"></i>
                Ver Atualizações
            </a>
        </div>

        <!-- Parte 2: Desenvolvimento -->
        <div class="dashboard-card">
            <h3 class="section-title">Desenvolvimento</h3>
            <div id="developmentList" class="development-list" style="flex: 1;">
                <div class="development-item">
                    <h4>Integração com APIs de Pagamento</h4>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 75%"></div>
                    </div>
                    <div class="progress-text">75% concluído</div>
                </div>
                <div class="development-item">
                    <h4>App Mobile</h4>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 45%"></div>
                    </div>
                    <div class="progress-text">45% concluído</div>
                </div>
                <div class="development-item">
                    <h4>Dashboard Analytics Avançado</h4>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 30%"></div>
                    </div>
                    <div class="progress-text">30% concluído</div>
                </div>
                <div class="development-item">
                    <h4>Sistema de Backup Automático</h4>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 60%"></div>
                    </div>
                    <div class="progress-text">60% concluído</div>
                </div>
                <div class="development-item">
                    <h4>Integração com WhatsApp Business</h4>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 40%"></div>
                    </div>
                    <div class="progress-text">40% concluído</div>
                </div>
                <div class="development-item">
                    <h4>API RESTful Completa</h4>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 55%"></div>
                    </div>
                    <div class="progress-text">55% concluído</div>
                </div>
                <div class="development-item">
                    <h4>Modulo de E-commerce</h4>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 25%"></div>
                    </div>
                    <div class="progress-text">25% concluído</div>
                </div>
                <div class="development-item">
                    <h4>Sistema de Assinaturas Recorrentes</h4>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 70%"></div>
                    </div>
                    <div class="progress-text">70% concluído</div>
                </div>
                <div class="development-item">
                    <h4>Integração com Google Analytics</h4>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 50%"></div>
                    </div>
                    <div class="progress-text">50% concluído</div>
                </div>
                <div class="development-item">
                    <h4>Chat em Tempo Real</h4>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 35%"></div>
                    </div>
                    <div class="progress-text">35% concluído</div>
                </div>
            </div>
            <button class="btn-portal-ideias" onclick="window.location.href='#portal-ideias'">
                <i class="fas fa-lightbulb"></i>
                Portal de Ideias
            </button>
        </div>
    </div>

</div>
@endsection
