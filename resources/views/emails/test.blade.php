@extends('layouts.app')

@section('title', 'Teste de Email SMTP')
@section('page-title', 'Teste de Email SMTP')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-2">📧 Teste de Email SMTP</h3>
        <p class="text-gray-600">Use esta tela para testar o envio de emails através do módulo genérico SMTP do sistema.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">Sucesso!</h3>
                    <div class="mt-2 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Erro!</h3>
                    <div class="mt-2 text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Tabs -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button onclick="showTab('test')" id="test-tab" class="tab-button active py-2 px-1 border-b-2 font-medium text-sm text-blue-600 border-blue-500">
                    <i class="fas fa-paper-plane mr-2"></i>Email de Teste
                </button>
                <button onclick="showTab('custom')" id="custom-tab" class="tab-button py-2 px-1 border-b-2 font-medium text-sm text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300">
                    <i class="fas fa-edit mr-2"></i>Email Personalizado
                </button>
                <button onclick="showTab('connection')" id="connection-tab" class="tab-button py-2 px-1 border-b-2 font-medium text-sm text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300">
                    <i class="fas fa-plug mr-2"></i>Teste de Conexão
                </button>
            </nav>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Email de Teste -->
        <div id="test-content" class="tab-panel">
            <form action="{{ route('emails.send-test') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="to" class="block text-sm font-medium text-gray-700 mb-2">
                            Email de Destino <span class="text-red-500">*</span>
                        </label>
                        <input type="email"
                               id="to"
                               name="to"
                               value="{{ old('to') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('to') border-red-500 @enderror"
                               placeholder="exemplo@email.com"
                               required>
                        @error('to')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                            Assunto
                        </label>
                        <input type="text"
                               id="subject"
                               name="subject"
                               value="{{ old('subject', 'Teste de Email - Sistema Jamees') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('subject') border-red-500 @enderror"
                               placeholder="Assunto do email">
                        @error('subject')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                        Mensagem Personalizada
                    </label>
                    <textarea id="message"
                              name="message"
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('message') border-red-500 @enderror"
                              placeholder="Digite uma mensagem personalizada (opcional)">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Configurações SMTP Personalizadas -->
                <div class="border-t pt-6">
                    <div class="flex items-center mb-4">
                        <input type="checkbox"
                               id="use_custom_smtp"
                               name="use_custom_smtp"
                               value="1"
                               {{ old('use_custom_smtp') ? 'checked' : '' }}
                               onchange="toggleCustomSmtp()"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="use_custom_smtp" class="ml-2 block text-sm font-medium text-gray-700">
                            Usar configurações SMTP personalizadas
                        </label>
                    </div>

                    <div id="custom-smtp-config" class="grid grid-cols-1 md:grid-cols-2 gap-4 {{ old('use_custom_smtp') ? '' : 'hidden' }}">
                        <div>
                            <label for="smtp_host" class="block text-sm font-medium text-gray-700 mb-1">Host SMTP</label>
                            <input type="text"
                                   id="smtp_host"
                                   name="smtp_host"
                                   value="{{ old('smtp_host', env('SMTP_MODULE_HOST', 'mail.jamees.com')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="smtp_port" class="block text-sm font-medium text-gray-700 mb-1">Porta</label>
                            <input type="number"
                                   id="smtp_port"
                                   name="smtp_port"
                                   value="{{ old('smtp_port', env('SMTP_MODULE_PORT', '465')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="smtp_username" class="block text-sm font-medium text-gray-700 mb-1">Usuário</label>
                            <input type="email"
                                   id="smtp_username"
                                   name="smtp_username"
                                   value="{{ old('smtp_username', env('SMTP_MODULE_USERNAME', 'contato@jamees.com')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="smtp_password" class="block text-sm font-medium text-gray-700 mb-1">Senha</label>
                            <input type="password"
                                   id="smtp_password"
                                   name="smtp_password"
                                   value="{{ old('smtp_password', env('SMTP_MODULE_PASSWORD', 'T9@genor104')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="smtp_encryption" class="block text-sm font-medium text-gray-700 mb-1">Criptografia</label>
                            <select id="smtp_encryption"
                                    name="smtp_encryption"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="tls" {{ old('smtp_encryption', 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ old('smtp_encryption') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                <option value="none" {{ old('smtp_encryption') == 'none' ? 'selected' : '' }}>Nenhuma</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Enviar Email de Teste
                    </button>
                </div>
            </form>
        </div>

        <!-- Email Personalizado -->
        <div id="custom-content" class="tab-panel hidden">
            <form action="{{ route('emails.send-custom') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="custom_to" class="block text-sm font-medium text-gray-700 mb-2">
                            Email de Destino <span class="text-red-500">*</span>
                        </label>
                        <input type="email"
                               id="custom_to"
                               name="to"
                               value="{{ old('to') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="exemplo@email.com"
                               required>
                    </div>

                    <div>
                        <label for="custom_subject" class="block text-sm font-medium text-gray-700 mb-2">
                            Assunto <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="custom_subject"
                               name="subject"
                               value="{{ old('subject') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Assunto do email"
                               required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="from_email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Remetente
                        </label>
                        <input type="email"
                               id="from_email"
                               name="from_email"
                               value="{{ old('from_email') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="remetente@email.com">
                    </div>

                    <div>
                        <label for="from_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome Remetente
                        </label>
                        <input type="text"
                               id="from_name"
                               name="from_name"
                               value="{{ old('from_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Nome do Remetente">
                    </div>
                </div>

                <div>
                    <label for="body" class="block text-sm font-medium text-gray-700 mb-2">
                        Conteúdo do Email <span class="text-red-500">*</span>
                    </label>
                    <textarea id="body"
                              name="body"
                              rows="8"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Digite o conteúdo do email..."
                              required>{{ old('body') }}</textarea>
                </div>

                <div class="flex items-center">
                    <input type="checkbox"
                           id="is_html"
                           name="is_html"
                           value="1"
                           {{ old('is_html', true) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_html" class="ml-2 block text-sm font-medium text-gray-700">
                        Conteúdo é HTML
                    </label>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-send mr-2"></i>
                        Enviar Email Personalizado
                    </button>
                </div>
            </form>
        </div>

        <!-- Teste de Conexão -->
        <div id="connection-content" class="tab-panel hidden">
            <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Teste de Conexão SMTP</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>Use esta ferramenta para testar se as configurações SMTP estão funcionando corretamente.</p>
                        </div>
                    </div>
                </div>
            </div>

            <form id="connection-form" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="conn_smtp_host" class="block text-sm font-medium text-gray-700 mb-2">
                            Host SMTP <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="conn_smtp_host"
                               value="{{ env('SMTP_MODULE_HOST', 'mail.jamees.com') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="mail.jamees.com"
                               required>
                    </div>

                    <div>
                        <label for="conn_smtp_port" class="block text-sm font-medium text-gray-700 mb-2">
                            Porta <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               id="conn_smtp_port"
                               value="{{ env('SMTP_MODULE_PORT', '465') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="465"
                               required>
                    </div>

                    <div>
                        <label for="conn_smtp_username" class="block text-sm font-medium text-gray-700 mb-2">
                            Usuário <span class="text-red-500">*</span>
                        </label>
                        <input type="email"
                               id="conn_smtp_username"
                               value="{{ env('SMTP_MODULE_USERNAME', 'contato@jamees.com') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="contato@jamees.com"
                               required>
                    </div>

                    <div>
                        <label for="conn_smtp_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Senha <span class="text-red-500">*</span>
                        </label>
                        <input type="password"
                               id="conn_smtp_password"
                               value="{{ env('SMTP_MODULE_PASSWORD', 'T9@genor104') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Sua senha"
                               required>
                    </div>

                    <div>
                        <label for="conn_smtp_encryption" class="block text-sm font-medium text-gray-700 mb-2">
                            Criptografia
                        </label>
                        <select id="conn_smtp_encryption"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="tls" selected>TLS</option>
                            <option value="ssl">SSL</option>
                            <option value="none">Nenhuma</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="button"
                            onclick="testConnection()"
                            id="test-connection-btn"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-plug mr-2"></i>
                        Testar Conexão
                    </button>
                </div>
            </form>

            <div id="connection-result" class="mt-6 hidden">
                <!-- Resultado será inserido aqui via JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
// Tab functionality
function showTab(tabName) {
    // Hide all tab panels
    document.querySelectorAll('.tab-panel').forEach(panel => {
        panel.classList.add('hidden');
    });

    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(tab => {
        tab.classList.remove('active', 'text-blue-600', 'border-blue-500');
        tab.classList.add('text-gray-500', 'border-transparent');
    });

    // Show selected tab panel
    document.getElementById(tabName + '-content').classList.remove('hidden');

    // Add active class to selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    activeTab.classList.add('active', 'text-blue-600', 'border-blue-500');
    activeTab.classList.remove('text-gray-500', 'border-transparent');
}

// Toggle custom SMTP configuration
function toggleCustomSmtp() {
    const checkbox = document.getElementById('use_custom_smtp');
    const configDiv = document.getElementById('custom-smtp-config');

    if (checkbox.checked) {
        configDiv.classList.remove('hidden');
    } else {
        configDiv.classList.add('hidden');
    }
}

// Test SMTP connection
function testConnection() {
    const btn = document.getElementById('test-connection-btn');
    const resultDiv = document.getElementById('connection-result');

    // Show loading state
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Testando...';

    // Get form data
    const data = {
        smtp_host: document.getElementById('conn_smtp_host').value,
        smtp_port: document.getElementById('conn_smtp_port').value,
        smtp_username: document.getElementById('conn_smtp_username').value,
        smtp_password: document.getElementById('conn_smtp_password').value,
        smtp_encryption: document.getElementById('conn_smtp_encryption').value,
    };

    // Make AJAX request
    fetch('{{ route("emails.test-connection") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        // Show result
        resultDiv.classList.remove('hidden');

        if (data.valid) {
            resultDiv.innerHTML = `
                <div class="bg-green-50 border border-green-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">Conexão SMTP Funcionando!</h3>
                            <div class="mt-2 text-sm text-green-700">
                                <p>${data.message}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        } else {
            resultDiv.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Erro na Conexão SMTP</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>${data.message}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
    })
    .catch(error => {
        resultDiv.classList.remove('hidden');
        resultDiv.innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Erro de Conexão</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>Erro ao testar conexão: ${error.message}</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
    })
    .finally(() => {
        // Restore button state
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-plug mr-2"></i>Testar Conexão';
    });
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Set default tab
    showTab('test');
});
</script>

<style>
.tab-button.active {
    color: #2563eb;
    border-color: #3b82f6;
}

.tab-button:hover {
    color: #374151;
    border-color: #d1d5db;
}
</style>
@endsection
