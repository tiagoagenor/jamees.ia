@extends('layouts.app')

@section('title', 'Detalhes do Usuário - Jamees')
@section('page-title', 'Detalhes do Usuário')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $usuario->nome }}</h1>
            <p class="text-gray-600">Detalhes completos do usuário</p>
        </div>
        <div class="flex items-center space-x-4">
            <a href="{{ route('usuarios.edit', $usuario) }}"
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                <i class="fas fa-edit mr-2"></i>
                Editar
            </a>
            <a href="{{ route('usuarios.index') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações Básicas -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Informações Básicas</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Dados principais do usuário</p>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Nome</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $usuario->nome }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $usuario->email }}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                @if($usuario->status)
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
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $usuario->criado_em ? $usuario->criado_em->format('d/m/Y H:i') : 'N/A' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Informações Pessoais -->
            @if($usuario->geral->count() > 0)
                <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Informações Pessoais</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Dados pessoais adicionais</p>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            @foreach($usuario->geral as $geral)
                                @if($geral->cpf)
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">CPF</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $geral->cpf }}</dd>
                                    </div>
                                @endif
                                @if($geral->rg)
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">RG</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $geral->rg }}</dd>
                                    </div>
                                @endif
                                @if($geral->data_nascimento)
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Data de Nascimento</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $geral->data_nascimento }}</dd>
                                    </div>
                                @endif
                                @if($geral->sexo)
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Sexo</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            @switch($geral->sexo)
                                                @case('M') Masculino @break
                                                @case('F') Feminino @break
                                                @case('O') Outro @break
                                                @default {{ $geral->sexo }}
                                            @endswitch
                                        </dd>
                                    </div>
                                @endif
                                @if($geral->comissao)
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Comissão</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $geral->comissao }}%</dd>
                                    </div>
                                @endif
                                @if($geral->desconto_maximo)
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Desconto Máximo</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $geral->desconto_maximo }}%</dd>
                                    </div>
                                @endif
                                @if($geral->obs)
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Observações</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $geral->obs }}</dd>
                                    </div>
                                @endif
                            @endforeach
                        </dl>
                    </div>
                </div>
            @endif

            <!-- Endereço -->
            @if($usuario->enderecos->count() > 0)
                <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Endereço</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Informações de endereço</p>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            @foreach($usuario->enderecos as $endereco)
                                @if($endereco->cep)
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">CEP</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $endereco->cep }}</dd>
                                    </div>
                                @endif
                                @if($endereco->logradouro)
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Logradouro</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $endereco->logradouro }}</dd>
                                    </div>
                                @endif
                                @if($endereco->numero)
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Número</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $endereco->numero }}</dd>
                                    </div>
                                @endif
                                @if($endereco->complemento)
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Complemento</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $endereco->complemento }}</dd>
                                    </div>
                                @endif
                                @if($endereco->bairro)
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Bairro</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $endereco->bairro }}</dd>
                                    </div>
                                @endif
                                @if($endereco->uf)
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">UF</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $endereco->uf }}</dd>
                                    </div>
                                @endif
                            @endforeach
                        </dl>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Empresas -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Empresas</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Empresas vinculadas</p>
                </div>
                <div class="border-t border-gray-200">
                    <div class="px-4 py-5">
                        @if($usuario->empresas->count() > 0)
                            @foreach($usuario->empresas as $empresa)
                                <div class="mb-4 p-3 bg-gray-50 rounded-md">
                                    <h4 class="text-sm font-medium text-gray-900">
                                        {{ $empresa->nome_fantasia ?: $empresa->razao_social ?: $empresa->nome_referencia }}
                                    </h4>
                                    @if($empresa->pivot->principal)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Principal
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p class="text-sm text-gray-500">Nenhuma empresa vinculada</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Telefones -->
            <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Contatos</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Telefones cadastrados</p>
                </div>
                <div class="border-t border-gray-200">
                    <div class="px-4 py-5">
                        @if($usuario->telefones->count() > 0)
                            @foreach($usuario->telefones as $telefone)
                                <div class="mb-4 p-3 bg-gray-50 rounded-md">
                                    <div class="flex items-center">
                                        <i class="fas fa-phone text-gray-400 mr-2"></i>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                ({{ $telefone->ddd }}) {{ $telefone->numero }}
                                            </p>
                                            <p class="text-xs text-gray-500 capitalize">{{ $telefone->tipo->label() }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-sm text-gray-500">Nenhum telefone cadastrado</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
