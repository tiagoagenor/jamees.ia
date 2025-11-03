@if(session('error') && str_contains(session('error'), 'plano'))
    <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-8 w-8 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-medium text-red-800">Acesso Restrito</h3>
                <p class="text-red-700 mt-1">{{ session('error') }}</p>
                <div class="mt-4">
                    <a href="{{ route('planos.index') }}"
                       class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Escolher Plano
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif
