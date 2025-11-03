@extends('layouts.app')

@section('title', 'Detalhes do Log')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detalhes da Alteração</h1>
            <p class="text-gray-600">Informações sobre esta mudança no sistema</p>
        </div>
        <a href="{{ route('audit.index') }}"
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-arrow-left mr-2"></i>
            Voltar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informações Básicas -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Informações da Alteração</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Dados principais sobre esta mudança</p>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Data e Hora</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="flex items-center">
                                <i class="fas fa-calendar text-gray-400 mr-2"></i>
                                {{ $auditLog->getFormattedDate() }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">{{ $auditLog->getRelativeDate() }}</div>
                        </dd>
                    </div>

                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Usuário</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-gray-600 text-xs"></i>
                                </div>
                                <div>
                                    <div class="font-medium">{{ $auditLog->user_name ?? 'Sistema' }}</div>
                                    @if($auditLog->user_email)
                                        <div class="text-xs text-gray-500">{{ $auditLog->user_email }}</div>
                                    @endif
                                </div>
                            </div>
                        </dd>
                    </div>

                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Empresa</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="flex items-center">
                                <i class="fas fa-building text-gray-400 mr-2"></i>
                                {{ $auditLog->empresa_nome }}
                            </div>
                        </dd>
                    </div>

                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Ação Realizada</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $auditLog->getActionColor() }}-100 text-{{ $auditLog->getActionColor() }}-800">
                                <i class="fas fa-{{ $auditLog->getActionIcon() }} mr-1"></i>
                                {{ $auditLog->getActionLabel() }}
                            </span>
                        </dd>
                    </div>

                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Tipo de Registro</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $auditLog->getModelLabel() }}
                            </span>
                        </dd>
                    </div>

                    @if($auditLog->model_id)
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Registro Alterado</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="flex items-center">
                                <i class="fas fa-hashtag text-gray-400 mr-2"></i>
                                {{ $auditLog->model_name ?? 'Registro #' . substr($auditLog->model_id, 0, 8) }}
                            </div>
                        </dd>
                    </div>
                    @endif

                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Descrição</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $auditLog->description ?? 'Sem descrição disponível' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Informações Técnicas (Simplificadas) -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Informações Adicionais</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Dados técnicos da operação</p>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Localização</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                                {{ $auditLog->ip_address ?? 'Não disponível' }}
                            </div>
                        </dd>
                    </div>

                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Navegador</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="flex items-center">
                                <i class="fas fa-desktop text-gray-400 mr-2"></i>
                                @if($auditLog->user_agent)
                                    @php
                                        $browser = 'Navegador desconhecido';
                                        if (strpos($auditLog->user_agent, 'Chrome') !== false) {
                                            $browser = 'Google Chrome';
                                        } elseif (strpos($auditLog->user_agent, 'Firefox') !== false) {
                                            $browser = 'Mozilla Firefox';
                                        } elseif (strpos($auditLog->user_agent, 'Safari') !== false) {
                                            $browser = 'Safari';
                                        } elseif (strpos($auditLog->user_agent, 'Edge') !== false) {
                                            $browser = 'Microsoft Edge';
                                        }
                                    @endphp
                                    {{ $browser }}
                                @else
                                    Não disponível
                                @endif
                            </div>
                        </dd>
                    </div>

                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Página Acessada</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="flex items-center">
                                <i class="fas fa-link text-gray-400 mr-2"></i>
                                @if($auditLog->url)
                                    @php
                                        $urlParts = parse_url($auditLog->url);
                                        $path = $urlParts['path'] ?? '';
                                        $path = str_replace(['/clientes/', '/usuarios/', '/empresas/', '/movimentacao/'], ['Cliente ', 'Usuário ', 'Empresa ', 'Movimentação '], $path);
                                    @endphp
                                    {{ $path ?: 'Página principal' }}
                                @else
                                    Não disponível
                                @endif
                            </div>
                        </dd>
                    </div>

                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Horário Exato</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="flex items-center">
                                <i class="fas fa-clock text-gray-400 mr-2"></i>
                                {{ $auditLog->created_at->format('d/m/Y H:i:s') }}
                            </div>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Alterações Realizadas -->
    @if($auditLog->hasAuditChanges())
        <div class="mt-6">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Detalhes das Alterações</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">O que foi modificado nesta operação</p>
                </div>
                <div class="border-t border-gray-200">
                    <div class="p-6">
                        @if($auditLog->old_values && $auditLog->new_values)
                            <div class="space-y-4">
                                @php
                                    $oldValues = $auditLog->old_values;
                                    $newValues = $auditLog->new_values;
                                    $fieldLabels = [
                                        'nome' => 'Nome',
                                        'nome_fantasia' => 'Nome Fantasia',
                                        'razao_social' => 'Razão Social',
                                        'documento' => 'Documento',
                                        'email' => 'E-mail',
                                        'telefone_comercial' => 'Telefone Comercial',
                                        'celular' => 'Celular',
                                        'site' => 'Site',
                                        'observacao' => 'Observação',
                                        'status' => 'Status',
                                        'tipo_pessoa' => 'Tipo de Pessoa',
                                        'valor' => 'Valor',
                                        'descricao' => 'Descrição',
                                        'data_vencimento' => 'Data de Vencimento',
                                        'data_pagamento' => 'Data de Pagamento',
                                        'forma_pagamento_id' => 'Forma de Pagamento',
                                        'conta_empresa_id' => 'Conta Bancária',
                                        'centro_custo_id' => 'Centro de Custo',
                                        'plano_conta_id' => 'Plano de Conta',
                                        'situacao' => 'Situação',
                                        'tipo' => 'Tipo',
                                        'tipo_relacionamento' => 'Tipo de Relacionamento',
                                        'modalidade' => 'Modalidade',
                                        'periodo' => 'Período',
                                        'tipo_conta' => 'Tipo de Conta',
                                    ];

                                    // Função para normalizar valores (igual ao AuditService)
                                    function normalizeValue($value) {
                                        if ($value === null || $value === '' || $value === 'null') {
                                            return null;
                                        }
                                        // Converter strings numéricas para inteiros para comparação
                                        if (is_numeric($value)) {
                                            return (int) $value;
                                        }
                                        return $value;
                                    }

                                    // Função para verificar se houve mudança real
                                    function hasRealChange($oldValue, $newValue) {
                                        $oldNormalized = normalizeValue($oldValue);
                                        $newNormalized = normalizeValue($newValue);
                                        return $oldNormalized !== $newNormalized;
                                    }

                                    // Função para obter o label de um enum
                                    function getEnumLabel($field, $value) {
                                        if ($value === null || $value === '' || $value === 'null') {
                                            return null;
                                        }

                                        switch ($field) {
                                            case 'status':
                                                return $value ? 'Ativo (1)' : 'Inativo (0)';
                                            case 'tipo_pessoa':
                                                return $value == 1 ? 'Pessoa Física (1)' : 'Pessoa Jurídica (2)';
                                            case 'situacao':
                                                $labels = [
                                                    1 => 'Pendente (1)',
                                                    2 => 'Paga (2)',
                                                    3 => 'Vencida (3)',
                                                    4 => 'Cancelada (4)'
                                                ];
                                                return $labels[$value] ?? "Valor desconhecido ({$value})";
                                            case 'tipo':
                                                $labels = [
                                                    1 => 'Contas a Pagar (1)',
                                                    2 => 'Contas a Receber (2)'
                                                ];
                                                return $labels[$value] ?? "Valor desconhecido ({$value})";
                                            case 'tipo_relacionamento':
                                                $labels = [
                                                    1 => 'Cliente (1)',
                                                    2 => 'Fornecedor (2)',
                                                    3 => 'Funcionário (3)',
                                                    4 => 'Transportadora (4)'
                                                ];
                                                return $labels[$value] ?? "Valor desconhecido ({$value})";
                                            case 'modalidade':
                                                $labels = [
                                                    1 => 'À Vista (1)',
                                                    2 => 'À Prazo (2)',
                                                    3 => 'Parcelado (3)'
                                                ];
                                                return $labels[$value] ?? "Valor desconhecido ({$value})";
                                            case 'periodo':
                                                $labels = [
                                                    1 => 'Mensal (1)',
                                                    2 => 'Trimestral (2)',
                                                    3 => 'Semestral (3)',
                                                    4 => 'Anual (4)'
                                                ];
                                                return $labels[$value] ?? "Valor desconhecido ({$value})";
                                            case 'tipo_conta':
                                                $labels = [
                                                    1 => 'Conta Corrente (1)',
                                                    2 => 'Poupança (2)',
                                                    3 => 'Investimento (3)'
                                                ];
                                                return $labels[$value] ?? "Valor desconhecido ({$value})";
                                            default:
                                                return null;
                                        }
                                    }
                                @endphp

                                @foreach($newValues as $field => $newValue)
                                    @php
                                        $isCreate = $auditLog->action === 'CREATE';
                                        $oldValue = $oldValues[$field] ?? null;
                                        $shouldShow = $isCreate || hasRealChange($oldValue, $newValue);
                                    @endphp
                                    @if($shouldShow)
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <div class="flex items-center justify-between">
                                                <h4 class="text-sm font-medium text-gray-900">
                                                    {{ $fieldLabels[$field] ?? ucfirst(str_replace('_', ' ', $field)) }}
                                                </h4>
                                                <span class="text-xs text-gray-500">
                                                    {{ $isCreate ? 'Campo criado' : 'Campo alterado' }}
                                                </span>
                                            </div>
                                            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="text-xs font-medium text-red-600 uppercase tracking-wide">Valor Anterior</label>
                                                    <div class="mt-1 text-sm text-gray-900 bg-red-50 border border-red-200 rounded p-2">
                                                        @php
                                                            $isEmpty = normalizeValue($oldValue) === null;
                                                        @endphp
                                                        @if($isCreate)
                                                            <span class="text-gray-500 italic">Campo estava vazio</span>
                                                        @else
                                                            @php
                                                                $enumLabel = getEnumLabel($field, $oldValue);
                                                            @endphp
                                                            @if($enumLabel)
                                                                {{ $enumLabel }}
                                                            @elseif(in_array($field, ['valor', 'valor_pago']))
                                                                @if($isEmpty)
                                                                    <span class="text-gray-500 italic">Campo estava vazio</span>
                                                                @else
                                                                    R$ {{ number_format($oldValue, 2, ',', '.') }}
                                                                @endif
                                                            @elseif(in_array($field, ['data_vencimento', 'data_pagamento', 'created_at', 'updated_at']))
                                                                @if($isEmpty)
                                                                    <span class="text-gray-500 italic">Campo estava vazio</span>
                                                                @else
                                                                    {{ \Carbon\Carbon::parse($oldValue)->format('d/m/Y') }}
                                                                @endif
                                                            @else
                                                                @if($isEmpty)
                                                                    <span class="text-gray-500 italic">Campo estava vazio</span>
                                                                @else
                                                                    {{ $oldValue }}
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </div>
                                                    @if($isEmpty || $isCreate)
                                                        <p class="text-xs text-gray-500 mt-1">
                                                            <i class="fas fa-info-circle mr-1"></i>
                                                            {{ $isCreate ? 'Este campo foi criado com o valor atual' : 'Este campo não possuía nenhum valor anteriormente' }}
                                                        </p>
                                                    @endif
                                                </div>
                                                <div>
                                                    <label class="text-xs font-medium text-green-600 uppercase tracking-wide">Novo Valor</label>
                                                    <div class="mt-1 text-sm text-gray-900 bg-green-50 border border-green-200 rounded p-2">
                                                        @php
                                                            $newValueEmpty = normalizeValue($newValue) === null;
                                                        @endphp
                                                        @php
                                                            $enumLabel = getEnumLabel($field, $newValue);
                                                        @endphp
                                                        @if($enumLabel)
                                                            {{ $enumLabel }}
                                                        @elseif(in_array($field, ['valor', 'valor_pago']))
                                                            @if($newValueEmpty)
                                                                <span class="text-gray-500 italic">Campo foi esvaziado</span>
                                                            @else
                                                                R$ {{ number_format($newValue, 2, ',', '.') }}
                                                            @endif
                                                        @elseif(in_array($field, ['data_vencimento', 'data_pagamento', 'created_at', 'updated_at']))
                                                            @if($newValueEmpty)
                                                                <span class="text-gray-500 italic">Campo foi esvaziado</span>
                                                            @else
                                                                {{ \Carbon\Carbon::parse($newValue)->format('d/m/Y') }}
                                                            @endif
                                                        @else
                                                            @if($newValueEmpty)
                                                                <span class="text-gray-500 italic">Campo foi esvaziado</span>
                                                            @else
                                                                {{ $newValue }}
                                                            @endif
                                                        @endif
                                                    </div>
                                                    @if($newValueEmpty)
                                                        <p class="text-xs text-gray-500 mt-1">
                                                            <i class="fas fa-info-circle mr-1"></i>
                                                            Este campo foi esvaziado nesta alteração
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="mx-auto h-12 w-12 text-gray-400">
                                    <i class="fas fa-info-circle text-4xl"></i>
                                </div>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Sem detalhes de alteração</h3>
                                <p class="mt-1 text-sm text-gray-500">Esta operação não possui detalhes específicos de alteração.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Resumo das Alterações -->
    @if($auditLog->hasAuditChanges())
        <div class="mt-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Resumo da Operação</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>{{ $auditLog->getChangesSummary() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
