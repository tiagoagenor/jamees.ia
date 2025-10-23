@extends('layouts.app')

@section('title', 'Detalhes da Empresa - Jamees')
@section('page-title', 'Detalhes da Empresa')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $empresa->nome_fantasia }}</h1>
            <p class="text-gray-600">{{ $empresa->razao_social }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('empresas.edit', $empresa) }}"
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                <i class="fas fa-edit mr-2"></i>
                Editar
            </a>
            <a href="{{ route('empresas.index') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações Básicas -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Dados Principais -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informações Básicas</h3>
                </div>
                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nome Fantasia</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $empresa->nome_fantasia }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Razão Social</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $empresa->razao_social }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">CNPJ/CPF</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $empresa->cnpj }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tipo</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $empresa->tipo == 'PJ' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $empresa->tipo == 'PJ' ? 'Pessoa Jurídica' : 'Pessoa Física' }}
                                </span>
                            </dd>
                        </div>
                        @if($empresa->nome_referencia)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nome Referência</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $empresa->nome_referencia }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                @if($empresa->status)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Ativo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Inativo
                                    </span>
                                @endif
                            </dd>
                        </div>
                        @if($empresa->principal)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Empresa Principal</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Sim
                                </span>
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Informações Tributárias -->
            @if($empresa->inscricao_estadual || $empresa->inscricao_municipal || $empresa->cnae || $empresa->regime_tributario)
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informações Tributárias</h3>
                </div>
                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($empresa->inscricao_estadual)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Inscrição Estadual</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $empresa->inscricao_estadual }}</dd>
                        </div>
                        @endif
                        @if($empresa->inscricao_estadual_isenta)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Inscrição Estadual Isenta</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $empresa->inscricao_estadual_isenta }}</dd>
                        </div>
                        @endif
                        @if($empresa->inscricao_municipal)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Inscrição Municipal</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $empresa->inscricao_municipal }}</dd>
                        </div>
                        @endif
                        @if($empresa->cnae)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">CNAE</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $empresa->cnae }}</dd>
                        </div>
                        @endif
                        @if($empresa->regime_tributario)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Regime Tributário</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $empresa->regime_tributario }}</dd>
                        </div>
                        @endif
                        @if($empresa->regime_especial)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Regime Especial</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $empresa->regime_especial }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
            @endif

            <!-- Informações Pessoais (para PF) -->
            @if($empresa->tipo == 'PF' && ($empresa->nome || $empresa->cpf || $empresa->rg))
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informações Pessoais</h3>
                </div>
                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($empresa->nome)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nome</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $empresa->nome }}</dd>
                        </div>
                        @endif
                        @if($empresa->cpf)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">CPF</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $empresa->cpf }}</dd>
                        </div>
                        @endif
                        @if($empresa->rg)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">RG</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $empresa->rg }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">

            <!-- Contatos -->
            @if($empresa->contatos->count() > 0)
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Contatos</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="space-y-3">
                        @foreach($empresa->contatos as $contato)
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                @switch($contato->tipo)
                                    @case('telefone')
                                        <i class="fas fa-phone text-gray-600 text-sm"></i>
                                        @break
                                    @case('email')
                                        <i class="fas fa-envelope text-gray-600 text-sm"></i>
                                        @break
                                    @case('site')
                                        <i class="fas fa-globe text-gray-600 text-sm"></i>
                                        @break
                                    @case('whatsapp')
                                        <i class="fab fa-whatsapp text-green-600 text-sm"></i>
                                        @break
                                @endswitch
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ ucfirst($contato->tipo) }}</p>
                                <p class="text-sm text-gray-500">{{ $contato->dado }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Endereços -->
            @if($empresa->enderecos->count() > 0)
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Endereços</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="space-y-4">
                        @foreach($empresa->enderecos as $endereco)
                        <div class="border-l-4 border-indigo-200 pl-4">
                            @if($endereco->logradouro)
                            <p class="text-sm font-medium text-gray-900">
                                {{ $endereco->logradouro }}
                                @if($endereco->numero), {{ $endereco->numero }}@endif
                                @if($endereco->complemento) - {{ $endereco->complemento }}@endif
                            </p>
                            @endif
                            @if($endereco->bairro)
                            <p class="text-sm text-gray-600">{{ $endereco->bairro }}</p>
                            @endif
                            @if($endereco->cep)
                            <p class="text-sm text-gray-500">CEP: {{ $endereco->cep }}</p>
                            @endif
                            @if($endereco->uf)
                            <p class="text-sm text-gray-500">{{ $endereco->uf }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Informações do Sistema -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informações do Sistema</h3>
                </div>
                <div class="px-6 py-4">
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $empresa->criado_em ? $empresa->criado_em->format('d/m/Y H:i') : 'N/A' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Atualizado em</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $empresa->atualizado_em ? $empresa->atualizado_em->format('d/m/Y H:i') : 'N/A' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
