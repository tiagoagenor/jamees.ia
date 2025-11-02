@extends('layouts.app')

@section('title', 'Mapa - ' . $empreendimento->nome)

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Sobrescrever estilos do layout para esta página específica */
    main {
        padding: 0 !important;
        overflow: hidden !important;
        height: calc(100vh - 73px) !important; /* Altura total menos o header */
    }

    main .container {
        padding: 0 !important;
        margin: 0 !important;
        max-width: 100% !important;
        width: 100% !important;
        height: 100% !important;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: #f8fafc;
        overflow: hidden;
        height: 100vh;
    }

    .app-container {
        display: flex;
        width: 100%;
        height: 100%;
        max-height: 100%;
        overflow: hidden;
        position: relative;
    }

    /* SIDEBAR ESQUERDA */
    .sidebar {
        width: 350px;
        background: #ffffff;
        color: #1e293b;
        padding: 24px;
        overflow-y: auto;
        box-shadow: 2px 0 8px rgba(0,0,0,0.05);
        border-right: 1px solid #e2e8f0;
        position: relative;
    }

    .sidebar-header {
        margin-bottom: 32px;
        padding-bottom: 24px;
        border-bottom: 1px solid #e2e8f0;
    }

    .sidebar-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 6px;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .sidebar-title i {
        color: #3b82f6;
        font-size: 22px;
    }

    .sidebar-subtitle {
        font-size: 13px;
        color: #64748b;
        font-weight: 400;
    }

    .section {
        background: #f8fafc;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #e2e8f0;
    }

    .section-no-bg {
        background: transparent;
        border: none;
        padding: 20px 0;
    }

    .section-title {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .section-title i,
    .section-title svg {
        color: #3b82f6;
    }

    .add-pin-btn {
        width: 100%;
        padding: 12px 16px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        position: relative;
    }

    .add-pin-btn.inactive {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .add-pin-btn.inactive:hover {
        background: #e2e8f0;
        border-color: #cbd5e1;
        color: #334155;
    }

    .add-pin-btn.active {
        background: #3b82f6;
        color: white;
        border: 1px solid #2563eb;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
    }

    .add-pin-btn.active:hover {
        background: #2563eb;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .active-notice {
        background: #dbeafe;
        border: 1px solid #93c5fd;
        border-radius: 8px;
        padding: 12px 16px;
        margin-top: 12px;
        text-align: center;
        font-size: 13px;
        font-weight: 500;
        color: #1e40af;
    }

    .stats-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 16px;
    }

    .stats-table tbody tr {
        border-bottom: 1px solid #e2e8f0;
    }

    .stats-table tbody tr:last-child {
        border-bottom: none;
    }

    .stats-table td {
        padding: 12px 0;
    }

    .stats-table .stat-label {
        font-size: 13px;
        color: #475569;
        font-weight: 500;
        text-align: left;
    }

    .stats-table .stat-number {
        font-size: 18px;
        font-weight: 700;
        text-align: right;
        color: #1e293b;
    }

    .controls-section {
        margin-bottom: 16px;
    }

    .zoom-controls {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
    }

    .zoom-btn {
        width: 36px;
        height: 36px;
        background: white;
        color: #475569;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .zoom-btn:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        color: #334155;
    }

    .zoom-btn:active {
        transform: scale(0.95);
    }

    .zoom-display {
        flex: 1;
        text-align: center;
        background: white;
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        font-size: 12px;
    }

    .zoom-display > div:first-child {
        color: #64748b;
        font-size: 10px;
        margin-bottom: 2px;
    }

    .zoom-value {
        color: #1e293b;
        font-weight: 600;
        font-size: 14px;
    }

    .save-btn {
        width: 100%;
        padding: 12px 16px;
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .save-btn:hover {
        background: #2563eb;
    }

    .save-btn:active {
        transform: scale(0.98);
    }

    .reset-btn {
        width: 100%;
        padding: 12px 16px;
        background: white;
        color: #475569;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .reset-btn:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        color: #334155;
    }

    .reset-btn:active {
        transform: scale(0.98);
    }

    .instructions {
        background: white;
        border-radius: 8px;
        padding: 16px;
        font-size: 12px;
        line-height: 1.6;
        border: 1px solid #e2e8f0;
    }

    .instructions h4 {
        margin-bottom: 12px;
        font-size: 13px;
        color: #1e293b;
        font-weight: 600;
    }

    .instructions ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .instructions li {
        margin-bottom: 8px;
        padding-left: 20px;
        position: relative;
        color: #475569;
    }

    .instructions li::before {
        content: '•';
        position: absolute;
        left: 0;
        color: #3b82f6;
        font-weight: bold;
        font-size: 16px;
    }

    .instructions > div {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 12px;
        margin-top: 12px;
        font-size: 11px;
    }

    .instructions > div strong {
        color: #1e293b;
        display: block;
        margin-bottom: 6px;
    }

    .instructions > div span {
        color: #3b82f6;
        font-weight: 600;
    }

    .instructions > div em {
        color: #64748b;
        font-size: 10px;
        display: block;
        margin-top: 6px;
    }

    /* ÁREA DA IMAGEM */
    .map-area {
        flex: 1;
        background: #1a1a1a;
        position: relative;
        overflow: hidden;
        width: 100%;
        height: 100%;
        max-height: 100%;
    }

    .map-container {
        width: 100%;
        height: 100%;
        max-width: 100%;
        max-height: 100%;
        position: relative;
        cursor: grab;
        overflow: hidden;
    }

    .legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    .legend-dot.available {
        background: #10b981;
    }

    .legend-dot.sold {
        background: #ef4444;
    }

    .map-container.adding-pin {
        cursor: crosshair;
    }

    .map-container.dragging {
        cursor: grabbing;
    }

    .map-wrapper {
        position: relative;
        transform-origin: 0 0;
        transition: transform 0.1s ease-out;
    }

    .map-wrapper.no-transition {
        transition: none;
    }

    .map-image {
        width: 1200px !important; /* Largura fixa da imagem - !important para sobrescrever Tailwind */
        height: 800px !important; /* Altura fixa da imagem - !important para sobrescrever Tailwind */
        min-width: 1200px !important;
        min-height: 800px !important;
        max-width: 1200px !important;
        max-height: 800px !important;
        object-fit: contain; /* Mantém proporção sem cortar */
        user-select: none;
        display: block;
        flex-shrink: 0; /* Previne que flexbox encolha a imagem */
        flex-grow: 0; /* Previne que flexbox expanda a imagem */
    }

    .pin {
        position: absolute;
        transform: translate(-50%, -100%);
        cursor: pointer;
        z-index: 10;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));
    }

    .pin:hover {
        transform: translate(-50%, -100%) scale(var(--pin-hover-scale, 1.15));
        filter: drop-shadow(0 6px 12px rgba(0,0,0,0.4));
    }

    .pin-icon {
        width: var(--pin-size, 24px);
        height: var(--pin-size, 24px);
        position: relative;
    }

    .pin.available .pin-icon {
        color: #10b981;
        fill: #10b981;
    }

    .pin.sold .pin-icon {
        color: #ef4444;
        fill: #ef4444;
    }

    .pin.pending .pin-icon {
        color: #3b82f6;
        fill: #3b82f6;
        animation: bounce 1s infinite;
    }

    /* Pino com efeito de brilho */
    .pin-icon::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 120%;
        height: 120%;
        background: radial-gradient(circle, currentColor 0%, transparent 70%);
        opacity: 0.2;
        border-radius: 50%;
        z-index: -1;
    }

    /* Efeito de pulso para pinos disponíveis */
    .pin.available .pin-icon::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 150%;
        height: 150%;
        background: #10b981;
        opacity: 0;
        border-radius: 50%;
        z-index: -2;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            opacity: 0.6;
            transform: translate(-50%, -50%) scale(0.8);
        }
        70% {
            opacity: 0;
            transform: translate(-50%, -50%) scale(1.4);
        }
        100% {
            opacity: 0;
            transform: translate(-50%, -50%) scale(1.4);
        }
    }

    .pin-tooltip {
        display: none; /* Tooltip removido */
    }

    /* MODALS */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 50;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s;
        backdrop-filter: blur(5px);
    }

    .modal-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    .modal {
        background: white;
        border-radius: 16px;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        max-width: 450px;
        width: 90%;
        max-height: 90vh;
        overflow: hidden;
        transform: scale(0.9);
        transition: transform 0.3s;
    }

    .modal-overlay.show .modal {
        transform: scale(1);
    }

    .modal-header {
        padding: 24px 30px 20px;
        position: relative;
    }

    .modal-header.available {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .modal-header.sold {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .modal-header.select {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }

    .modal-title {
        font-size: 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .close-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255,255,255,0.2);
        border: none;
        color: currentColor;
        cursor: pointer;
        padding: 8px;
        border-radius: 8px;
        transition: background 0.2s;
    }

    .close-btn:hover {
        background: rgba(255,255,255,0.3);
    }

    .modal-content {
        padding: 30px;
    }

    .modal-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .modal-field {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .modal-field-icon {
        width: 22px;
        height: 22px;
    }

    .modal-field-content p:first-child {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 4px;
        font-weight: 500;
    }

    .modal-field-content p:last-child {
        font-weight: 600;
        font-size: 16px;
    }

    .description {
        margin-top: 20px;
    }

    .description p:first-child {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 10px;
        font-weight: 500;
    }

    .modal-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .btn {
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-primary {
        background: #3b82f6;
        color: white;
        flex: 1;
    }

    .btn-primary:hover:not(:disabled) {
        background: #2563eb;
        transform: translateY(-1px);
    }

    .btn-primary:disabled {
        background: #cbd5e1;
        color: #94a3b8;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .btn-primary.active {
        background: #3b82f6;
    }

    .btn-danger {
        background: #fee2e2;
        color: #dc2626;
    }

    .btn-danger:hover {
        background: #fecaca;
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #6b7280;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    .type-select-buttons {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-bottom: 20px;
    }

    .type-btn {
        width: 100%;
        padding: 16px 20px;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }

    .type-btn.available {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .type-btn.available:hover {
        background: linear-gradient(135deg, #059669, #047857);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
    }

    .type-btn.sold {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .type-btn.sold:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
    }

    .type-dot {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: rgba(255,255,255,0.3);
    }

    @keyframes bounce {
        0%, 20%, 53%, 80%, 100% {
            transform: translate(-50%, -100%) translateY(0);
        }
        40%, 43% {
            transform: translate(-50%, -100%) translateY(-10px);
        }
        70% {
            transform: translate(-50%, -100%) translateY(-5px);
        }
        90% {
            transform: translate(-50%, -100%) translateY(-2px);
        }
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .sidebar {
            width: 300px;
        }

    }
</style>

<div class="app-container">
    <!-- SIDEBAR ESQUERDA -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h1 class="sidebar-title">
                <i class="fas fa-map-marker-alt"></i>
                Adicionar Pino
            </h1>
            <p class="sidebar-subtitle">Marque os lotes no mapa interativo</p>
        </div>

        <!-- Seção Ativar Modo -->
        <div class="section section-no-bg">
            <h3 class="section-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                Modo de Adição
            </h3>

            <button id="addPinBtn" class="add-pin-btn inactive">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                <span>Ativar modo de adição</span>
            </button>

            <div id="activeNotice" class="active-notice" style="display: none;">
                <i class="fas fa-info-circle mr-2"></i>
                Modo ativo! Clique na imagem onde deseja colocar o pino
            </div>
        </div>

        <!-- Controles -->
        <div class="section section-no-bg">
            <h3 class="section-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 -1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                </svg>
                Controles de Visualização
            </h3>

            <div class="controls-section">
                <button id="saveBtn" class="save-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17,21 17,13 7,13 7,21"></polyline>
                        <polyline points="7,3 7,8 15,8"></polyline>
                    </svg>
                    Salvar Pinos
                </button>

                <div class="zoom-controls">
                    <button id="zoomOutBtn" class="zoom-btn" title="Diminuir Zoom">−</button>
                    <div class="zoom-display">
                        <div style="font-size: 12px; opacity: 0.8; margin-bottom: 2px;">Zoom</div>
                        <div class="zoom-value" id="zoomLevel">100%</div>
                    </div>
                    <button id="zoomInBtn" class="zoom-btn" title="Aumentar Zoom">+</button>
                </div>
                <button id="resetBtn" class="reset-btn">
                    <i class="fas fa-redo mr-2"></i>
                    Resetar Vista
                </button>
            </div>
        </div>

    </div>

    <!-- ÁREA DA IMAGEM -->
    <div class="map-area">
        <div id="mapContainer" class="map-container">
            <div id="mapWrapper" class="map-wrapper">
                <img id="mapImage" class="map-image"
                    src="{{ $empreendimento->imagem_mapa ? asset('storage/' . $empreendimento->imagem_mapa) : asset('map/mapa-dos-lotes-1637671897.webp') }}"
                    alt="Mapa do Loteamento"
                     draggable="false">
            </div>

        </div>
    </div>

    <!-- Modal de Seleção de Tipo -->
    <div id="typeModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header select">
                <h2 class="modal-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    Selecione o tipo de lote
                </h2>
                <button class="close-btn" onclick="cancelPin()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="modal-content">
                <p style="text-align: center; color: #6b7280; margin-bottom: 16px;">
                    Selecione o lote e o tipo:
                </p>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">
                        Selecionar Lote *
                    </label>
                    <select id="selectLote" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; cursor: pointer;">
                        <option value="">Selecione um lote...</option>
                    </select>
                    <p style="font-size: 11px; color: #64748b; margin-top: 4px;">Apenas lotes sem pino serão exibidos</p>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">
                        Tipo do Lote
                    </label>
                    <div class="type-select-buttons">
                        <button type="button" class="type-btn available" onclick="selectPinType('available')">
                            <div class="type-dot"></div>
                            <span>À Venda</span>
                        </button>

                        <button type="button" class="type-btn sold" onclick="selectPinType('sold')">
                            <div class="type-dot"></div>
                            <span>Vendido</span>
                        </button>
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button class="btn btn-primary" style="flex: 1;" onclick="confirmPinWithLote()" id="confirmPinBtn" disabled>
                        Confirmar
                    </button>
                    <button class="btn btn-secondary" style="flex: 1;" onclick="cancelPin()">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Detalhes -->
    <div id="detailModal" class="modal-overlay">
        <div class="modal">
            <div id="detailHeader" class="modal-header">
                <h2 class="modal-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9,22 9,12 15,12 15,22"></polyline>
                    </svg>
                    <span id="detailTitle">Lote Disponível</span>
                </h2>
                <button class="close-btn" onclick="closeDetailModal()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="modal-content">
                <div class="modal-grid">
                    <div class="modal-field">
                        <svg class="modal-field-icon" style="color: #10b981;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                        <div class="modal-field-content">
                            <p>Preço</p>
                            <p id="detailPrice">R$ 120.000</p>
                        </div>
                    </div>

                    <div class="modal-field">
                        <svg class="modal-field-icon" style="color: #3b82f6;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <div class="modal-field-content">
                            <p>Área</p>
                            <p id="detailArea">450m²</p>
                        </div>
                    </div>
                </div>

                <div class="modal-field" style="margin-bottom: 16px;">
                    <svg class="modal-field-icon" style="color: #8b5cf6;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <div class="modal-field-content">
                        <p>Status</p>
                        <p id="detailStatus">Disponível</p>
                    </div>
                </div>

                <div class="description">
                    <p>Descrição</p>
                    <p id="detailDescription">Excelente lote em localização privilegiada com vista para área verde.</p>
                </div>

                <div class="modal-actions">
                    <button id="interestBtn" class="btn btn-primary" style="display: none;">
                        Tenho Interesse
                    </button>
                    <button class="btn btn-danger" onclick="removeCurrentPin()">
                        Remover Pino
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // ========================================
    // CONFIGURAÇÕES PRINCIPAIS
    // ========================================
    const PIN_SIZE = 22; // Tamanho base dos pinos (em pixels)
    const PIN_HOVER_SCALE = 1.15; // Escala no hover
    // Zoom inicial vem do banco de dados, com fallback para 180
    const INITIAL_ZOOM = {{ !empty($empreendimento->zoom_default) && $empreendimento->zoom_default > 0 ? $empreendimento->zoom_default : 180 }};
    const PIN_OFFSET_X = 0; // Offset horizontal para novos pinos (em pixels)
    const PIN_OFFSET_Y = 6; // Offset vertical para novos pinos (em pixels)

    // Lotes carregados do banco de dados
    const lotesFromDatabase = @json($lotes ?? []);

    // Tamanho fixo da imagem (independente do monitor)
    const FIXED_IMAGE_WIDTH = 1200; // Largura fixa em pixels
    const FIXED_IMAGE_HEIGHT = 800; // Altura fixa em pixels

    // ========================================
    // VARIÁVEIS GLOBAIS
    // ========================================
    // let pins = [
    //     // Pinos iniciais simulando dados do banco - agora com coordenadas percentuais
    //     {
    //         id: 1,
    //         x: 15, // Porcentagem da largura da imagem
    //         y: 20, // Porcentagem da altura da imagem
    //         type: 'available',
    //         data: {
    //             title: "Lote 001 - Quadra A",
    //             price: "R$ 125.000",
    //             area: "450m²",
    //             status: "Disponível",
    //             description: "Lote de esquina com excelente localização, próximo à área de lazer central."
    //         }
    //     },
    //     {
    //         id: 2,
    //         x: 25,
    //         y: 25,
    //         type: 'sold',
    //         data: {
    //             title: "Lote 002 - Quadra A",
    //             price: "R$ 118.000",
    //             area: "420m²",
    //             status: "Vendido",
    //             description: "Lote vendido em março/2024. Vista privilegiada para área verde."
    //         }
    //     },
    //     {
    //         id: 3,
    //         x: 35,
    //         y: 30,
    //         type: 'available',
    //         data: {
    //             title: "Lote 003 - Quadra B",
    //             price: "R$ 135.000",
    //             area: "480m²",
    //             status: "Disponível",
    //             description: "Lote premium com maior área útil, ideal para projetos maiores."
    //         }
    //     },
    //     {
    //         id: 4,
    //         x: 20,
    //         y: 45,
    //         type: 'sold',
    //         data: {
    //             title: "Lote 004 - Quadra B",
    //             price: "R$ 110.000",
    //             area: "400m²",
    //             status: "Vendido",
    //             description: "Lote vendido em janeiro/2024. Localização estratégica."
    //         }
    //     },
    //     {
    //         id: 5,
    //         x: 42,
    //         y: 22,
    //         type: 'available',
    //         data: {
    //             title: "Lote 005 - Quadra C",
    //             price: "R$ 140.000",
    //             area: "500m²",
    //             status: "Disponível",
    //             description: "O maior lote disponível, perfeito para construção de casa grande."
    //         }
    //     }
    // ];

    let pins = [];

    // Carregar pinos dos lotes do banco de dados
    if (lotesFromDatabase && lotesFromDatabase.length > 0) {
        lotesFromDatabase.forEach((lote, index) => {
            if (lote.posicao_pino && lote.posicao_pino.x !== null && lote.posicao_pino.y !== null) {
                // Determinar tipo baseado no status
                let pinType = 'available';
                if (lote.status && lote.status.tipo == 2) { // Vendido
                    pinType = 'sold';
                }

                pins.push({
                    id: `lote_${lote.id}`,
                    lote_id: lote.id,
                    x: parseFloat(lote.posicao_pino.x),
                    y: parseFloat(lote.posicao_pino.y),
                    type: pinType,
                    data: {
                        title: lote.nome || `Lote ${index + 1}`,
                        price: lote.valor ? `R$ ${parseFloat(lote.valor).toLocaleString('pt-BR', {minimumFractionDigits: 2})}` : 'Não informado',
                        area: lote.m2 ? `${lote.m2}m²` : 'Não informado',
                        status: lote.status ? lote.status.nome : 'Não definido',
                        description: lote.observacao || 'Lote cadastrado no sistema.'
                    }
                });
            }
        });
    }

    // Variáveis de controle
    let scale = INITIAL_ZOOM / 100; // Converte porcentagem para decimal
    let position = { x: 0, y: 0 };
    let isDragging = false;
    let dragStart = { x: 0, y: 0 };
    let isAddingPin = false;
    let pendingPin = null;
    let currentPinForModal = null;
    let nextPinId = 6; // Próximo ID para novos pinos
    let selectedPinType = null; // Tipo selecionado no modal
    let selectedLoteId = null; // Lote selecionado no modal

    // Dados dos lotes
    const lotData = {
        available: {
            title: "Lote Disponível",
            price: "R$ 120.000",
            area: "450m²",
            status: "Disponível",
            description: "Excelente lote em localização privilegiada com vista para área verde."
        },
        sold: {
            title: "Lote Vendido",
            price: "R$ 95.000",
            area: "420m²",
            status: "Vendido",
            description: "Lote vendido recentemente. Ótima localização próximo às áreas de lazer."
        }
    };

    // Elementos do DOM
    const mapContainer = document.getElementById('mapContainer');
    const mapWrapper = document.getElementById('mapWrapper');
    const mapImage = document.getElementById('mapImage');
    const addPinBtn = document.getElementById('addPinBtn');
    const activeNotice = document.getElementById('activeNotice');
    const saveBtn = document.getElementById('saveBtn');
    const resetBtn = document.getElementById('resetBtn');
    const zoomInBtn = document.getElementById('zoomInBtn');
    const zoomOutBtn = document.getElementById('zoomOutBtn');
    const zoomLevel = document.getElementById('zoomLevel');
    const typeModal = document.getElementById('typeModal');
    const detailModal = document.getElementById('detailModal');

    // ========================================
    // FUNÇÕES PARA GERENCIAR DADOS DOS PINOS
    // ========================================
    function getAllPins() {
        return pins;
    }

    function savePinsToStorage() {
        // Simula salvamento - você pode integrar com seu backend aqui
        localStorage.setItem('loteamentoPins', JSON.stringify(pins));
        console.log('Pinos salvos:', pins);
    }

    function loadPinsFromStorage() {
        // Simula carregamento - você pode integrar com seu backend aqui
        const savedPins = localStorage.getItem('loteamentoPins');
        if (savedPins) {
            pins = JSON.parse(savedPins);
            // Atualizar nextPinId baseado nos IDs existentes
            nextPinId = Math.max(...pins.map(pin => pin.id)) + 1;
        }
    }

    // Função para salvar dados dos pinos
    async function savePinsData() {
        // Preparar dados para envio ao backend
        const pinsToSave = pins
            .filter(pin => pin.lote_id) // Apenas pinos que têm lote_id (vinculados a lotes)
            .map(pin => ({
                lote_id: pin.lote_id,
                x: pin.x,
                y: pin.y
            }));

        if (pinsToSave.length === 0) {
            showSaveNotification('Nenhum pino vinculado a lote para salvar.', 'warning');
            return;
        }

        try {
            const response = await fetch('{{ route("empreendimentos.salvar-posicoes-pinos", $empreendimento->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    pinos: pinsToSave
                })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                showSaveNotification('Posições dos pinos salvas com sucesso!', 'success');
                console.log('✅ Posições salvas:', pinsToSave);
            } else {
                showSaveNotification(data.error || 'Erro ao salvar posições dos pinos.', 'error');
                console.error('❌ Erro:', data);
            }
        } catch (error) {
            showSaveNotification('Erro ao conectar com o servidor.', 'error');
            console.error('❌ Erro de rede:', error);
        }

        // Log detalhado para debug
        const timestamp = new Date().toISOString();
        console.log('='.repeat(60));
        console.log('🎯 DADOS DOS PINOS');
        console.log('='.repeat(60));
        console.log('📅 Data/Hora:', timestamp);
        console.log('📊 Total de Pinos:', pins.length);
        console.log('💾 Pinos para salvar:', pinsToSave.length);
        console.log('🔧 Zoom Inicial:', INITIAL_ZOOM + '%');
        console.log('🔍 Zoom Atual:', Math.round(scale * 100) + '%');
        console.log('='.repeat(60));

        return { pins: pinsToSave };
    }

    function showSaveNotification(message = null, type = 'success') {
        const messages = {
            success: message || `✅ Dados salvos com sucesso! Zoom: ${Math.round(scale * 100)}%`,
            warning: message || '⚠️ Atenção: Verifique os dados.',
            error: message || '❌ Erro ao salvar dados.'
        };

        const colors = {
            success: 'linear-gradient(135deg, #10b981, #059669)',
            warning: 'linear-gradient(135deg, #f59e0b, #d97706)',
            error: 'linear-gradient(135deg, #ef4444, #dc2626)'
        };

        // Criar notificação temporária
        const notification = document.createElement('div');
        notification.innerHTML = `
            <div style="
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${colors[type]};
                color: white;
                padding: 16px 20px;
                border-radius: 10px;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
                z-index: 1000;
                animation: slideIn 0.3s ease-out;
                font-weight: 500;
            ">
                ${messages[type]}
            </div>
        `;

        // Adicionar animação CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
        `;
        document.head.appendChild(style);

        document.body.appendChild(notification);

        // Remover após 3 segundos
        setTimeout(() => {
            notification.remove();
            style.remove();
        }, 3000);
    }

    // ========================================
    // FUNÇÕES DE TRANSFORMAÇÃO E ZOOM
    // ========================================
    function clampPosition() {
        const containerRect = mapContainer.getBoundingClientRect();

        // Calcular dimensões escaladas da imagem com tamanho FIXO
        const scaledWidth = FIXED_IMAGE_WIDTH * scale;
        const scaledHeight = FIXED_IMAGE_HEIGHT * scale;

        // Limites para não deixar espaços vazios
        const maxX = 0;
        const minX = containerRect.width - scaledWidth;
        const maxY = 0;
        const minY = containerRect.height - scaledHeight;

        // Se a imagem for menor que o container, centralizar
        if (scaledWidth <= containerRect.width) {
            position.x = (containerRect.width - scaledWidth) / 2;
        } else {
            position.x = Math.max(minX, Math.min(maxX, position.x));
        }

        if (scaledHeight <= containerRect.height) {
            position.y = (containerRect.height - scaledHeight) / 2;
        } else {
            position.y = Math.max(minY, Math.min(maxY, position.y));
        }
    }

    function updateTransform() {
        clampPosition();
        mapWrapper.style.transform = `translate(${position.x}px, ${position.y}px) scale(${scale})`;
    }

    function updateZoomDisplay() {
        const zoomPercentage = Math.round(scale * 100);
        zoomLevel.textContent = zoomPercentage + '%';
    }


    // ========================================
    // FUNÇÕES DE CRIAÇÃO DE PINOS
    // ========================================
    function createPinElement(pin) {
        const pinElement = document.createElement('div');
        pinElement.className = `pin ${pin.type}`;

        // Converter coordenadas percentuais para pixels baseado no tamanho FIXO da imagem
        const pixelX = (pin.x / 100) * FIXED_IMAGE_WIDTH;
        const pixelY = (pin.y / 100) * FIXED_IMAGE_HEIGHT;

        pinElement.style.left = `${pixelX}px`;
        pinElement.style.top = `${pixelY}px`;
        pinElement.style.setProperty('--pin-size', `${PIN_SIZE}px`);
        pinElement.style.setProperty('--pin-hover-scale', PIN_HOVER_SCALE);
        pinElement.dataset.pinId = pin.id;

        pinElement.innerHTML = `
            <svg class="pin-icon" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
            </svg>
        `;

        pinElement.addEventListener('click', (e) => {
            e.stopPropagation();
            openDetailModal(pin);
        });

        return pinElement;
    }

    function createPendingPinElement(percentX, percentY) {
        const pinElement = document.createElement('div');
        pinElement.className = 'pin pending';

        // Converter coordenadas percentuais para pixels baseado no tamanho FIXO
        const pixelX = (percentX / 100) * FIXED_IMAGE_WIDTH;
        const pixelY = (percentY / 100) * FIXED_IMAGE_HEIGHT;

        pinElement.style.left = `${pixelX}px`;
        pinElement.style.top = `${pixelY}px`;
        pinElement.style.setProperty('--pin-size', `${PIN_SIZE}px`);
        pinElement.style.setProperty('--pin-hover-scale', PIN_HOVER_SCALE);
        pinElement.id = 'pendingPin';

        pinElement.innerHTML = `
            <svg class="pin-icon" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
            </svg>
        `;

        return pinElement;
    }

    // Função para reposicionar todos os pinos quando a tela muda
    function repositionAllPins() {
        pins.forEach(pin => {
            const pinElement = document.querySelector(`[data-pin-id="${pin.id}"]`);
            if (pinElement) {
                const pixelX = (pin.x / 100) * FIXED_IMAGE_WIDTH;
                const pixelY = (pin.y / 100) * FIXED_IMAGE_HEIGHT;
                pinElement.style.left = `${pixelX}px`;
                pinElement.style.top = `${pixelY}px`;
            }
        });
    }

    // ========================================
    // EVENT LISTENERS
    // ========================================
    addPinBtn.addEventListener('click', () => {
        isAddingPin = !isAddingPin;

        if (isAddingPin) {
            addPinBtn.className = 'add-pin-btn active';
            addPinBtn.innerHTML = `
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                <span>Clique na imagem para adicionar</span>
            `;
            activeNotice.style.display = 'block';
            mapContainer.classList.add('adding-pin');
        } else {
            addPinBtn.className = 'add-pin-btn inactive';
            addPinBtn.innerHTML = `
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                <span>Ativar modo de adição</span>
            `;
            activeNotice.style.display = 'none';
            mapContainer.classList.remove('adding-pin');
        }
    });

    resetBtn.addEventListener('click', () => {
        scale = INITIAL_ZOOM / 100; // Reseta para o zoom inicial configurado
        position = { x: 0, y: 0 };
        updateTransform();
        updateZoomDisplay();
    });

    // Botão de salvar
    saveBtn.addEventListener('click', () => {
        savePinsData();
    });

    // Botões de zoom com incrementos em porcentagem
    zoomInBtn.addEventListener('click', () => {
        const currentZoomPercent = Math.round(scale * 100);
        const newZoomPercent = Math.min(300, currentZoomPercent + 20); // Incrementa 20%
        scale = newZoomPercent / 100;
        updateTransform();
        updateZoomDisplay();
    });

    zoomOutBtn.addEventListener('click', () => {
        const currentZoomPercent = Math.round(scale * 100);
        const newZoomPercent = Math.max(50, currentZoomPercent - 20); // Decrementa 20%
        scale = newZoomPercent / 100;
        updateTransform();
        updateZoomDisplay();
    });

    // Eventos de mouse para arrastar
    mapContainer.addEventListener('mousedown', (e) => {
        if (e.target.closest('.pin') || isAddingPin) return;

        isDragging = true;
        mapContainer.classList.add('dragging');
        dragStart = {
            x: e.clientX - position.x,
            y: e.clientY - position.y
        };
    });

    mapContainer.addEventListener('mousemove', (e) => {
        if (!isDragging) return;

        mapWrapper.classList.add('no-transition');
        position = {
            x: e.clientX - dragStart.x,
            y: e.clientY - dragStart.y
        };
        updateTransform();
    });

    mapContainer.addEventListener('mouseup', () => {
        if (isDragging) {
            isDragging = false;
            mapContainer.classList.remove('dragging');
            mapWrapper.classList.remove('no-transition');
        }
    });

    mapContainer.addEventListener('mouseleave', () => {
        if (isDragging) {
            isDragging = false;
            mapContainer.classList.remove('dragging');
            mapWrapper.classList.remove('no-transition');
        }
    });

    // Clique na imagem para adicionar pino
    mapContainer.addEventListener('click', (e) => {
        if (isDragging || !isAddingPin) return;
        if (e.target.closest('.pin')) return;

        const rect = mapContainer.getBoundingClientRect();
        // Calcular posição em pixels considerando transformações
        const pixelX = (e.clientX - rect.left - position.x) / scale + PIN_OFFSET_X;
        const pixelY = (e.clientY - rect.top - position.y) / scale + PIN_OFFSET_Y;

        // Converter para coordenadas percentuais baseadas no tamanho FIXO da imagem
        const percentX = (pixelX / FIXED_IMAGE_WIDTH) * 100;
        const percentY = (pixelY / FIXED_IMAGE_HEIGHT) * 100;

        // Armazenar posição percentual para o pino pendente
        pendingPin = { x: percentX, y: percentY };

        // Log da posição para debug
        console.log('🎯 Novo pino - Posição do clique:', {
            clickX: e.clientX - rect.left,
            clickY: e.clientY - rect.top,
            pixelX: pixelX,
            pixelY: pixelY,
            percentX: percentX.toFixed(2) + '%',
            percentY: percentY.toFixed(2) + '%',
            imageSize: FIXED_IMAGE_WIDTH + 'x' + FIXED_IMAGE_HEIGHT,
            offsetX: PIN_OFFSET_X,
            offsetY: PIN_OFFSET_Y
        });

        // Criar pino temporário
        const pendingPinElement = createPendingPinElement(percentX, percentY);
        mapWrapper.appendChild(pendingPinElement);

        // Popular select de lotes
        populateLotesSelect();

        // Resetar seleções
        selectedPinType = null;
        selectedLoteId = null;
        document.getElementById('selectLote').value = '';
        document.querySelectorAll('.type-btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById('confirmPinBtn').disabled = true;

        // Mostrar modal de seleção
        typeModal.classList.add('show');
    });

    // ========================================
    // FUNÇÕES DOS MODAIS
    // ========================================
    function selectPinType(pinType) {
        selectedPinType = pinType;
        // Remover seleção anterior
        document.querySelectorAll('.type-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        // Adicionar seleção atual
        event.target.closest('.type-btn').classList.add('active');
        checkConfirmButton();
    }

    function checkConfirmButton() {
        const selectLote = document.getElementById('selectLote');
        const confirmBtn = document.getElementById('confirmPinBtn');

        if (selectLote && selectLote.value && selectedPinType) {
            confirmBtn.disabled = false;
            confirmBtn.classList.add('active');
        } else {
            confirmBtn.disabled = true;
            confirmBtn.classList.remove('active');
        }
    }

    function confirmPinWithLote() {
        if (!pendingPin) return;

        const selectLote = document.getElementById('selectLote');
        const loteId = selectLote.value;

        if (!loteId || !selectedPinType) {
            alert('Por favor, selecione um lote e um tipo.');
            return;
        }

        // Buscar dados do lote selecionado
        const loteSelecionado = lotesFromDatabase.find(l => l.id === loteId);

        if (!loteSelecionado) {
            alert('Lote não encontrado.');
            return;
        }

        // Verificar se o lote já tem um pino
        const loteJaTemPino = pins.find(p => p.lote_id === loteId);
        if (loteJaTemPino) {
            if (!confirm('Este lote já possui um pino no mapa. Deseja atualizar a posição?')) {
                return;
            }
            // Remover pino existente
            pins = pins.filter(p => p.lote_id !== loteId);
            const existingPinElement = document.querySelector(`[data-pin-id="lote_${loteId}"]`);
            if (existingPinElement) {
                existingPinElement.remove();
            }
        }

        // Determinar tipo baseado no status do lote, mas usar o selecionado se disponível
        let pinType = selectedPinType;
        if (loteSelecionado.status && loteSelecionado.status.tipo == 2) {
            pinType = 'sold'; // Forçar sold se o status for vendido
        }

        // Criar novo pino vinculado ao lote
        const newPin = {
            id: `lote_${loteId}`,
            lote_id: loteId,
            x: pendingPin.x,
            y: pendingPin.y,
            type: pinType,
            data: {
                title: loteSelecionado.nome || `Lote ${loteId.substring(0, 8)}`,
                price: loteSelecionado.valor ? `R$ ${parseFloat(loteSelecionado.valor).toLocaleString('pt-BR', {minimumFractionDigits: 2})}` : 'Não informado',
                area: loteSelecionado.m2 ? `${loteSelecionado.m2}m²` : 'Não informado',
                status: loteSelecionado.status ? loteSelecionado.status.nome : 'Não definido',
                description: loteSelecionado.observacao || 'Lote cadastrado no sistema.'
            }
        };

        pins.push(newPin);

        // Remover pino temporário
        const pendingPinElement = document.getElementById('pendingPin');
        if (pendingPinElement) {
            pendingPinElement.remove();
        }

        // Criar pino definitivo
        const pinElement = createPinElement(newPin);
        mapWrapper.appendChild(pinElement);

        // Limpar estado do modal
        pendingPin = null;
        selectedPinType = null;
        selectedLoteId = null;
        typeModal.classList.remove('show');
        document.getElementById('selectLote').value = '';
        document.querySelectorAll('.type-btn').forEach(btn => btn.classList.remove('active'));

        console.log('✅ Novo pino adicionado e vinculado ao lote:', newPin);
    }

    function populateLotesSelect() {
        const selectLote = document.getElementById('selectLote');

        // Remover listeners antigos (se houver)
        const newSelectLote = selectLote.cloneNode(true);
        selectLote.parentNode.replaceChild(newSelectLote, selectLote);

        const updatedSelectLote = document.getElementById('selectLote');
        updatedSelectLote.innerHTML = '<option value="">Selecione um lote...</option>';

        // Filtrar lotes que ainda não têm pino
        const lotesSemPino = lotesFromDatabase.filter(lote => {
            return !pins.find(pin => pin.lote_id === lote.id);
        });

        if (lotesSemPino.length === 0) {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'Todos os lotes já possuem pino';
            option.disabled = true;
            updatedSelectLote.appendChild(option);
        } else {
            lotesSemPino.forEach(lote => {
                const option = document.createElement('option');
                option.value = lote.id;
                option.textContent = `${lote.nome || 'Lote sem nome'}${lote.quadra ? ' - ' + lote.quadra.nome : ''}`;
                updatedSelectLote.appendChild(option);
            });
        }

        // Adicionar listener para habilitar botão de confirmar
        updatedSelectLote.addEventListener('change', function() {
            selectedLoteId = this.value;
            checkConfirmButton();
        });
    }

    function cancelPin() {
        // Remover pino temporário
        const pendingPinElement = document.getElementById('pendingPin');
        if (pendingPinElement) {
            pendingPinElement.remove();
        }

        pendingPin = null;
        selectedPinType = null;
        selectedLoteId = null;
        typeModal.classList.remove('show');
    }

    function openDetailModal(pin) {
        currentPinForModal = pin;

        // Atualizar conteúdo do modal
        const detailHeader = document.getElementById('detailHeader');
        const detailTitle = document.getElementById('detailTitle');
        const detailPrice = document.getElementById('detailPrice');
        const detailArea = document.getElementById('detailArea');
        const detailStatus = document.getElementById('detailStatus');
        const detailDescription = document.getElementById('detailDescription');
        const interestBtn = document.getElementById('interestBtn');

        detailHeader.className = `modal-header ${pin.type}`;
        detailTitle.textContent = pin.data.title;
        detailPrice.textContent = pin.data.price;
        detailArea.textContent = pin.data.area;
        detailStatus.textContent = pin.data.status;
        detailDescription.textContent = pin.data.description;

        if (pin.type === 'available') {
            interestBtn.style.display = 'block';
            detailStatus.style.color = '#10b981';
        } else {
            interestBtn.style.display = 'none';
            detailStatus.style.color = '#ef4444';
        }

        detailModal.classList.add('show');
    }

    function closeDetailModal() {
        detailModal.classList.remove('show');
        currentPinForModal = null;
    }

    function removeCurrentPin() {
        if (!currentPinForModal) return;

        // Remover do array
        pins = pins.filter(pin => pin.id !== currentPinForModal.id);

        // Remover elemento do DOM
        const pinElement = document.querySelector(`[data-pin-id="${currentPinForModal.id}"]`);
        if (pinElement) {
            pinElement.remove();
        }

        // Fechar modal
        closeDetailModal();

        // Salvar no storage
        savePinsToStorage();

        console.log('Pino removido, ID:', currentPinForModal.id);
    }

    // Fechar modais clicando fora
    typeModal.addEventListener('click', (e) => {
        if (e.target === typeModal) {
            cancelPin();
        }
    });

    detailModal.addEventListener('click', (e) => {
        if (e.target === detailModal) {
            closeDetailModal();
        }
    });

    // Tecla ESC para fechar modais
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (typeModal.classList.contains('show')) {
                cancelPin();
            }
            if (detailModal.classList.contains('show')) {
                closeDetailModal();
            }
        }
    });

    // ========================================
    // FUNÇÃO PARA CARREGAR PINOS INICIAIS
    // ========================================
    function loadInitialPins() {
        pins.forEach(pin => {
            const pinElement = createPinElement(pin);
            mapWrapper.appendChild(pinElement);
        });
    }

    // ========================================
    // ATUALIZAR DISPLAYS DE CONFIGURAÇÃO
    // ========================================
    function updateConfigDisplays() {
        const pinSizeDisplay = document.getElementById('pinSizeDisplay');
        const initialZoomDisplay = document.getElementById('initialZoomDisplay');
        const offsetXDisplay = document.getElementById('offsetXDisplay');
        const offsetYDisplay = document.getElementById('offsetYDisplay');

        if (pinSizeDisplay) {
            pinSizeDisplay.textContent = PIN_SIZE + 'px';
        }
        if (initialZoomDisplay) {
            initialZoomDisplay.textContent = INITIAL_ZOOM + '%';
        }
        if (offsetXDisplay) {
            offsetXDisplay.textContent = PIN_OFFSET_X + 'px';
        }
        if (offsetYDisplay) {
            offsetYDisplay.textContent = PIN_OFFSET_Y + 'px';
        }
    }

    // ========================================
    // INICIALIZAÇÃO
    // ========================================
    mapImage.addEventListener('load', () => {
        updateTransform();
        loadInitialPins(); // Carregar pinos iniciais
        updateConfigDisplays(); // Atualizar displays de configuração
    });

    window.addEventListener('resize', () => {
        updateTransform();
        repositionAllPins(); // Reposicionar pinos quando a tela mudar
    });

    // Carregar dados salvos (se existirem)
    // loadPinsFromStorage();

    // Inicializar displays
    updateZoomDisplay();
    updateConfigDisplays();

    // Função global para debug (acesso via console)
    window.getPinsData = getAllPins;
    window.savePinsData = savePinsData;

    // Log das configurações iniciais
    console.log('🎯 CONFIGURAÇÕES INICIAIS DO MAPA');
    console.log('📐 Tamanho dos pinos:', PIN_SIZE + 'px');
    console.log('🔍 Zoom inicial:', INITIAL_ZOOM + '%');
    console.log('📊 Escala hover:', PIN_HOVER_SCALE);
    console.log('🖼️ Tamanho FIXO da imagem:', FIXED_IMAGE_WIDTH + 'x' + FIXED_IMAGE_HEIGHT + 'px');
    console.log('📐 Offset X para novos pinos:', PIN_OFFSET_X + 'px');
    console.log('📐 Offset Y para novos pinos:', PIN_OFFSET_Y + 'px');
    console.log('📍 Total de pinos carregados:', pins.length);
    console.log('🎯 SISTEMA: Imagem com tamanho fixo + coordenadas percentuais!');
</script>
@endsection

