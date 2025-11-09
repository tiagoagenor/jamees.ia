@extends('layouts.app')

@section('title', 'Informações do PHP - Upload')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-6xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                Informações do PHP - Configurações de Upload
            </h1>
            <p class="text-gray-600 mt-1">Configurações relacionadas ao upload de arquivos</p>
        </div>

        <div class="space-y-6">
            <!-- Configurações Principais de Upload -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h2 class="text-lg font-semibold text-blue-900 mb-4">
                    <i class="fas fa-upload mr-2"></i>
                    Configurações Principais de Upload
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white rounded p-3">
                        <div class="text-sm text-gray-600">upload_max_filesize</div>
                        <div class="text-xl font-bold {{ ini_get('upload_max_filesize') == '2M' ? 'text-red-600' : 'text-green-600' }}">
                            {{ ini_get('upload_max_filesize') }}
                            @if(ini_get('upload_max_filesize') == '2M')
                                <span class="text-xs text-red-500">⚠️ Muito baixo! Recomendado: 10M</span>
                            @endif
                        </div>
                    </div>
                    <div class="bg-white rounded p-3">
                        <div class="text-sm text-gray-600">post_max_size</div>
                        <div class="text-xl font-bold {{ (int)str_replace('M', '', ini_get('post_max_size')) < 12 ? 'text-yellow-600' : 'text-green-600' }}">
                            {{ ini_get('post_max_size') }}
                            @if((int)str_replace('M', '', ini_get('post_max_size')) < 12)
                                <span class="text-xs text-yellow-600">⚠️ Deve ser maior que upload_max_filesize</span>
                            @endif
                        </div>
                    </div>
                    <div class="bg-white rounded p-3">
                        <div class="text-sm text-gray-600">max_file_uploads</div>
                        <div class="text-xl font-bold text-gray-800">
                            {{ ini_get('max_file_uploads') }}
                        </div>
                    </div>
                    <div class="bg-white rounded p-3">
                        <div class="text-sm text-gray-600">memory_limit</div>
                        <div class="text-xl font-bold text-gray-800">
                            {{ ini_get('memory_limit') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configurações de Tempo -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-clock mr-2"></i>
                    Configurações de Tempo
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white rounded p-3">
                        <div class="text-sm text-gray-600">max_execution_time</div>
                        <div class="text-xl font-bold text-gray-800">
                            {{ ini_get('max_execution_time') }} segundos
                        </div>
                    </div>
                    <div class="bg-white rounded p-3">
                        <div class="text-sm text-gray-600">max_input_time</div>
                        <div class="text-xl font-bold text-gray-800">
                            {{ ini_get('max_input_time') }} segundos
                        </div>
                    </div>
                    <div class="bg-white rounded p-3">
                        <div class="text-sm text-gray-600">default_socket_timeout</div>
                        <div class="text-xl font-bold text-gray-800">
                            {{ ini_get('default_socket_timeout') }} segundos
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações do Servidor -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h2 class="text-lg font-semibold text-green-900 mb-4">
                    <i class="fas fa-server mr-2"></i>
                    Informações do Servidor
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white rounded p-3">
                        <div class="text-sm text-gray-600">PHP Version</div>
                        <div class="text-xl font-bold text-gray-800">
                            {{ PHP_VERSION }}
                        </div>
                    </div>
                    <div class="bg-white rounded p-3">
                        <div class="text-sm text-gray-600">SAPI (Server API)</div>
                        <div class="text-xl font-bold text-gray-800">
                            {{ php_sapi_name() }}
                        </div>
                    </div>
                    <div class="bg-white rounded p-3">
                        <div class="text-sm text-gray-600">php.ini Location</div>
                        <div class="text-sm font-mono text-gray-800 break-all">
                            {{ php_ini_loaded_file() ?: 'Não encontrado' }}
                        </div>
                    </div>
                    <div class="bg-white rounded p-3">
                        <div class="text-sm text-gray-600">Additional .ini files</div>
                        <div class="text-sm font-mono text-gray-800 break-all">
                            {{ php_ini_scanned_files() ?: 'Nenhum' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Diagnóstico -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <h2 class="text-lg font-semibold text-yellow-900 mb-4">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Diagnóstico
                </h2>
                <div class="space-y-2">
                    @php
                        $uploadMax = (int)str_replace('M', '', ini_get('upload_max_filesize'));
                        $postMax = (int)str_replace('M', '', ini_get('post_max_size'));
                        $issues = [];

                        if ($uploadMax < 10) {
                            $issues[] = [
                                'type' => 'error',
                                'message' => 'upload_max_filesize está muito baixo (' . ini_get('upload_max_filesize') . '). Recomendado: 10M ou mais.'
                            ];
                        }

                        if ($postMax < 12) {
                            $issues[] = [
                                'type' => 'warning',
                                'message' => 'post_max_size (' . ini_get('post_max_size') . ') deve ser pelo menos 12M para suportar uploads de 10M.'
                            ];
                        }

                        if ($postMax <= $uploadMax) {
                            $issues[] = [
                                'type' => 'error',
                                'message' => 'post_max_size deve ser MAIOR que upload_max_filesize.'
                            ];
                        }
                    @endphp

                    @if(count($issues) > 0)
                        @foreach($issues as $issue)
                            <div class="bg-white rounded p-3 border-l-4 {{ $issue['type'] == 'error' ? 'border-red-500' : 'border-yellow-500' }}">
                                <div class="flex items-start">
                                    <i class="fas {{ $issue['type'] == 'error' ? 'fa-times-circle text-red-500' : 'fa-exclamation-circle text-yellow-500' }} mr-2 mt-1"></i>
                                    <div class="text-sm {{ $issue['type'] == 'error' ? 'text-red-800' : 'text-yellow-800' }}">
                                        {{ $issue['message'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="bg-white rounded p-3 border-l-4 border-green-500">
                            <div class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                                <div class="text-sm text-green-800">
                                    ✅ Todas as configurações estão adequadas para uploads de até 10MB!
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Instruções -->
            @if(count($issues) > 0)
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-wrench mr-2"></i>
                    Como Ajustar
                </h2>
                <div class="bg-white rounded p-4 space-y-3">
                    <div>
                        <p class="text-sm text-gray-700 mb-2"><strong>1. Encontre o arquivo php.ini:</strong></p>
                        <code class="block bg-gray-100 p-2 rounded text-sm">{{ php_ini_loaded_file() ?: '/opt/homebrew/etc/php/8.3/php.ini' }}</code>
                    </div>
                    <div>
                        <p class="text-sm text-gray-700 mb-2"><strong>2. Edite as seguintes linhas:</strong></p>
                        <pre class="bg-gray-100 p-3 rounded text-sm overflow-x-auto">upload_max_filesize = 10M
post_max_size = 12M
max_execution_time = 300
max_input_time = 300</pre>
                    </div>
                    <div>
                        <p class="text-sm text-gray-700 mb-2"><strong>3. Reinicie o servidor PHP:</strong></p>
                        <code class="block bg-gray-100 p-2 rounded text-sm"># Pare o php artisan serve (Ctrl+C) e reinicie</code>
                    </div>
                    <div>
                        <p class="text-sm text-gray-700 mb-2"><strong>4. Verifique se funcionou:</strong></p>
                        <code class="block bg-gray-100 p-2 rounded text-sm">php -r "echo ini_get('upload_max_filesize');"</code>
                        <p class="text-xs text-gray-500 mt-1">Deve mostrar: 10M</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Todas as Configurações (Colapsável) -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <details>
                    <summary class="cursor-pointer text-lg font-semibold text-gray-900 hover:text-blue-600">
                        <i class="fas fa-chevron-down mr-2"></i>
                        Todas as Configurações PHP (Expandir)
                    </summary>
                    <div class="mt-4 bg-white rounded p-4 overflow-auto max-h-96">
                        <pre class="text-xs">{{ print_r(ini_get_all(), true) }}</pre>
                    </div>
                </details>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar ao Dashboard
            </a>
        </div>
    </div>
</div>
@endsection

