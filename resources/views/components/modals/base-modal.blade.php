@props([
    'id' => 'base-modal',
    'title' => 'Modal',
    'size' => 'md', // sm, md, lg, xl, 2xl, full
    'showCloseButton' => true,
])

@php
    $sizeClasses = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        '3xl' => 'max-w-3xl',
        '4xl' => 'max-w-4xl',
        '5xl' => 'max-w-5xl',
        '6xl' => 'max-w-6xl',
        '7xl' => 'max-w-7xl',
        'full' => 'max-w-full mx-4',
    ];
    $modalSize = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<!-- Modal Overlay -->
<div id="{{ $id }}" class="fixed inset-0 z-50 hidden overflow-y-auto flex items-center justify-center p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal('{{ $id }}')"></div>

    <!-- Modal container -->
    <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all w-full {{ $modalSize }}">
        <!-- Modal header -->
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                    {{ $title }}
                </h3>
                @if($showCloseButton)
                    <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none" onclick="closeModal('{{ $id }}')">
                        <span class="sr-only">Fechar</span>
                        <i class="fas fa-times text-xl"></i>
                    </button>
                @endif
            </div>
        </div>

        <!-- Modal body -->
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 max-h-[70vh] overflow-y-auto">
            {{ $slot }}
        </div>

        <!-- Modal footer (opcional) -->
        @if(isset($footer))
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Funções globais para controlar modais
if (typeof window.openModal === 'undefined') {
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevenir scroll do body
        }
    };
}

if (typeof window.closeModal === 'undefined') {
    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Restaurar scroll do body
        }
    };
}

// Fechar modal ao pressionar ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const openModals = document.querySelectorAll('[id$="-modal"]:not(.hidden)');
        openModals.forEach(modal => {
            if (modal.id.includes('-modal')) {
                closeModal(modal.id);
            }
        });
    }
});
</script>
@endpush

