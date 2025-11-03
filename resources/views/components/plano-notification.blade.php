@if(session('plano_atual'))
    @php
        $plano = session('plano_atual');
    @endphp

    @if($plano['is_teste'] && $plano['tipo'] === 'teste')
        <!-- Tarja amarela para período de teste -->
        <div class="bg-yellow-400 border-b-4 border-yellow-600 text-yellow-900 py-3 px-4 w-full">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-yellow-800 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-sm">
                            🎉 Você está no período de teste gratuito!
                        </p>
                        <p class="text-xs">
                            Restam {{ $plano['dias_restantes'] }} dias para escolher seu plano definitivo.
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('planos.index') }}"
                       class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        Contratar Plano
                    </a>
                    <button onclick="this.parentElement.parentElement.parentElement.style.display='none'"
                            class="text-yellow-800 hover:text-yellow-900 text-sm">
                        ✕
                    </button>
                </div>
            </div>
        </div>
    @elseif($plano['is_proximo_vencimento'])
        <!-- Notificação de vencimento próximo -->
        <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4 mb-4 w-full">
            <div class="max-w-7xl mx-auto flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">
                        Seu plano {{ $plano['nome'] }} expira em {{ $plano['dias_restantes'] }} dias
                        ({{ $plano['data_fim'] }}).
                    </p>
                    <div class="mt-2">
                        <a href="{{ route('planos.index') }}"
                           class="text-sm font-medium underline hover:text-orange-600">
                            Renovar plano
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif
