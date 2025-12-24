@extends('layouts.app')

@section('title', 'Aguardar Pagamento PIX')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-8">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Aguardar Pagamento PIX</h1>
                <p class="text-gray-600">Escaneie o QR Code ou copie o código PIX para realizar o pagamento</p>
            </div>

            @if(isset($pagamentoPix['qr_code_url']))
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 text-center">QR Code PIX</h2>
                <div class="flex justify-center mb-4">
                    <img src="{{ $pagamentoPix['qr_code_url'] }}" 
                         alt="QR Code PIX" 
                         class="max-w-xs border-2 border-gray-200 rounded-lg p-4 bg-white">
                </div>
            </div>
            @endif

            @if(isset($pagamentoPix['qr_code_text']))
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Código PIX (Copia e Cola)</h2>
                <div class="flex items-start space-x-2">
                    <textarea 
                        id="pix-code" 
                        readonly 
                        class="flex-1 border border-gray-300 rounded-md px-4 py-3 text-sm font-mono bg-white"
                        rows="4">{{ $pagamentoPix['qr_code_text'] }}</textarea>
                    <button 
                        onclick="copiarPix()" 
                        class="px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-medium transition-colors">
                        Copiar
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-2">Clique em "Copiar" e cole no aplicativo do seu banco</p>
            </div>
            @endif

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm text-blue-800">
                            <strong>Valor a pagar:</strong> R$ {{ number_format($pagamentoPix['valor_total'] ?? 0, 2, ',', '.') }}
                        </p>
                        <p class="text-xs text-blue-700 mt-1">
                            Após o pagamento, seu plano será ativado automaticamente. Isso pode levar alguns minutos.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('planos.index') }}" 
                   class="flex-1 text-center px-6 py-3 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                    Voltar para Planos
                </a>
                <button 
                    onclick="verificarPagamento()" 
                    class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors">
                    Verificar Pagamento
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function copiarPix() {
    const pixCode = document.getElementById('pix-code');
    pixCode.select();
    pixCode.setSelectionRange(0, 99999); // Para mobile
    
    try {
        document.execCommand('copy');
        alert('Código PIX copiado!');
    } catch (err) {
        // Fallback para navegadores modernos
        navigator.clipboard.writeText(pixCode.value).then(() => {
            alert('Código PIX copiado!');
        });
    }
}

function verificarPagamento() {
    // Recarregar a página para verificar se o pagamento foi processado
    window.location.reload();
}

// O pagamento será verificado automaticamente via webhook do PagBank
// Quando o pagamento for confirmado, o usuário será redirecionado automaticamente
</script>
@endsection

