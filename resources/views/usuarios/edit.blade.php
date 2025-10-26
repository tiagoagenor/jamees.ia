@extends('layouts.app')

@section('title', 'Editar Usuário - Jamees')
@section('page-title', 'Editar Usuário')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Editar Usuário</h3>
            <p class="text-sm text-gray-600">Atualize os dados do usuário {{ $usuario->nome }}</p>
        </div>

        <form method="POST" action="{{ route('usuarios.update', $usuario) }}" class="p-6">
            @csrf
            @method('PUT')

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Informações Básicas -->
            <div class="mb-8">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Informações Básicas</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nome -->
                    <div>
                        <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome Completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="nome"
                               name="nome"
                               value="{{ old('nome', $usuario->nome) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('nome') border-red-500 @enderror"
                               required>
                        @error('nome')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email', $usuario->email) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Senha -->
                    <div>
                        <label for="senha" class="block text-sm font-medium text-gray-700 mb-2">
                            Nova Senha (deixe em branco para manter a atual)
                        </label>
                        <input type="password"
                               id="senha"
                               name="senha"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('senha') border-red-500 @enderror">
                        @error('senha')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmar Senha -->
                    <div>
                        <label for="senha_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmar Nova Senha
                        </label>
                        <input type="password"
                               id="senha_confirmation"
                               name="senha_confirmation"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <!-- Telefones -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Telefones <span class="text-red-500">*</span>
                        </label>
                        <div id="telefones-container">
                            @if($usuario->telefones->count() > 0)
                                @foreach($usuario->telefones as $index => $telefone)
                                    <div class="telefone-item flex items-end space-x-2 mb-2">
                                        <div class="flex-1">
                                            <input type="text"
                                                   name="telefones[{{ $index }}][numero]"
                                                   value="{{ old('telefones.'.$index.'.numero', $telefone->ddd . $telefone->numero) }}"
                                                   placeholder="Ex: (31) 99624-1675"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 telefone-input"
                                                   required>
                                        </div>
                                        <div class="w-32">
                                            <select name="telefones[{{ $index }}][tipo]"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                                @foreach(\App\Enums\UsuarioTelefoneTipoEnum::options() as $value => $label)
                                                    <option value="{{ $value }}" {{ old('telefones.'.$index.'.tipo', $telefone->tipo->value) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="button"
                                                class="remove-telefone px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 {{ $usuario->telefones->count() > 1 ? '' : 'hidden' }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="telefone-item flex items-end space-x-2 mb-2">
                                    <div class="flex-1">
                                        <input type="text"
                                               name="telefones[0][numero]"
                                               value="{{ old('telefones.0.numero') }}"
                                               placeholder="Ex: (31) 99624-1675"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 telefone-input"
                                               required>
                                    </div>
                                    <div class="w-32">
                                        <select name="telefones[0][tipo]"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                            @foreach(\App\Enums\UsuarioTelefoneTipoEnum::options() as $value => $label)
                                                <option value="{{ $value }}" {{ old('telefones.0.tipo') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button"
                                            class="remove-telefone px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 hidden">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button"
                                id="add-telefone"
                                class="mt-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-plus mr-2"></i>
                            Adicionar Telefone
                        </button>
                        @error('telefones')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Empresas -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Empresas <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-300 rounded-md p-3">
                            @foreach($empresas as $empresa)
                                <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                    <input type="checkbox"
                                           name="empresas[]"
                                           value="{{ $empresa->id }}"
                                           {{ in_array($empresa->id, old('empresas', $usuario->empresas->pluck('id')->toArray())) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <div class="flex items-center space-x-2">
                                        @if($empresaPrincipal && $empresa->id == $empresaPrincipal->id)
                                            <span class="text-yellow-600">👑</span>
                                            <span class="text-sm font-medium text-gray-900">{{ $empresa->nome_fantasia ?: $empresa->razao_social ?: $empresa->nome_referencia }}</span>
                                            <span class="text-xs text-yellow-600 font-medium">(Principal)</span>
                                        @elseif($empresaPrincipal && $empresa->empresa_id == $empresaPrincipal->id)
                                            <span class="text-blue-600">🏢</span>
                                            <span class="text-sm font-medium text-gray-900">{{ $empresa->nome_fantasia ?: $empresa->razao_social ?: $empresa->nome_referencia }}</span>
                                            <span class="text-xs text-blue-600 font-medium">(Filial)</span>
                                        @else
                                            <span class="text-gray-600">🏢</span>
                                            <span class="text-sm font-medium text-gray-900">{{ $empresa->nome_fantasia ?: $empresa->razao_social ?: $empresa->nome_referencia }}</span>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('empresas')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('empresas.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Grupos -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Grupos <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-300 rounded-md p-3">
                            @foreach($grupos as $grupo)
                                <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                    <input type="checkbox"
                                           name="grupos[]"
                                           value="{{ $grupo->id }}"
                                           {{ in_array($grupo->id, old('grupos', $usuario->grupos->pluck('id')->toArray())) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <div class="flex items-center space-x-2">
                                        @if($grupo->administrativo)
                                            <span class="text-red-600">👑</span>
                                            <span class="text-sm font-medium text-gray-900">{{ $grupo->nome }}</span>
                                            <span class="text-xs text-red-600 font-medium">(Administrativo)</span>
                                        @else
                                            <span class="text-blue-600">👥</span>
                                            <span class="text-sm font-medium text-gray-900">{{ $grupo->nome }}</span>
                                        @endif
                                    </div>
                                    @if($grupo->descricao)
                                        <div class="text-xs text-gray-500 ml-6">{{ $grupo->descricao }}</div>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                        @error('grupos')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('grupos.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status
                        </label>
                        <select id="status"
                                name="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach(\App\Enums\UsuarioStatusEnum::options() as $value => $label)
                                <option value="{{ $value }}" {{ old('status', $usuario->status->value) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Informações Pessoais -->
            <div class="mb-8">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Informações Pessoais</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- CPF -->
                    <div>
                        <label for="cpf" class="block text-sm font-medium text-gray-700 mb-2">
                            CPF
                        </label>
                        <input type="text"
                               id="cpf"
                               name="cpf"
                               value="{{ old('cpf', $usuario->geral->first()->cpf ?? '') }}"
                               placeholder="000.000.000-00"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('cpf') border-red-500 @enderror">
                        @error('cpf')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- RG -->
                    <div>
                        <label for="rg" class="block text-sm font-medium text-gray-700 mb-2">
                            RG
                        </label>
                        <input type="text"
                               id="rg"
                               name="rg"
                               value="{{ old('rg', $usuario->geral->first()->rg ?? '') }}"
                               placeholder="00.000.000-0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('rg') border-red-500 @enderror">
                        @error('rg')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Data de Nascimento -->
                    <div>
                        <label for="data_nascimento" class="block text-sm font-medium text-gray-700 mb-2">
                            Data de Nascimento
                        </label>
                        <input type="date"
                               id="data_nascimento"
                               name="data_nascimento"
                               value="{{ old('data_nascimento', $usuario->geral->first()->data_nascimento ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('data_nascimento') border-red-500 @enderror">
                        @error('data_nascimento')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sexo -->
                    <div>
                        <label for="sexo" class="block text-sm font-medium text-gray-700 mb-2">
                            Sexo
                        </label>
                        <select id="sexo"
                                name="sexo"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Selecione</option>
                            <option value="M" {{ old('sexo', $usuario->geral->first()->sexo ?? '') == 'M' ? 'selected' : '' }}>Masculino</option>
                            <option value="F" {{ old('sexo', $usuario->geral->first()->sexo ?? '') == 'F' ? 'selected' : '' }}>Feminino</option>
                            <option value="O" {{ old('sexo', $usuario->geral->first()->sexo ?? '') == 'O' ? 'selected' : '' }}>Outro</option>
                        </select>
                    </div>

                    <!-- Comissão -->
                    <div>
                        <label for="comissao" class="block text-sm font-medium text-gray-700 mb-2">
                            Comissão (%)
                        </label>
                        <input type="number"
                               id="comissao"
                               name="comissao"
                               value="{{ old('comissao', $usuario->geral->first()->comissao ?? '') }}"
                               step="0.01"
                               min="0"
                               max="100"
                               placeholder="0.00"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('comissao') border-red-500 @enderror">
                        @error('comissao')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Desconto Máximo -->
                    <div>
                        <label for="desconto_maximo" class="block text-sm font-medium text-gray-700 mb-2">
                            Desconto Máximo (%)
                        </label>
                        <input type="number"
                               id="desconto_maximo"
                               name="desconto_maximo"
                               value="{{ old('desconto_maximo', $usuario->geral->first()->desconto_maximo ?? '') }}"
                               step="0.01"
                               min="0"
                               max="100"
                               placeholder="0.00"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('desconto_maximo') border-red-500 @enderror">
                        @error('desconto_maximo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Observações -->
                <div class="mt-6">
                    <label for="obs" class="block text-sm font-medium text-gray-700 mb-2">
                        Observações
                    </label>
                    <textarea id="obs"
                              name="obs"
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('obs') border-red-500 @enderror"
                              placeholder="Observações adicionais sobre o usuário...">{{ old('obs', $usuario->geral->first()->obs ?? '') }}</textarea>
                    @error('obs')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Endereço -->
            <div class="mb-8">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Endereço</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- CEP -->
                    <div>
                        <label for="cep" class="block text-sm font-medium text-gray-700 mb-2">
                            CEP
                        </label>
                        <div class="relative">
                            <input type="text"
                                   id="cep"
                                   name="cep"
                                   value="{{ old('cep', $usuario->enderecos->first()->cep ?? '') }}"
                                   placeholder="00000-000"
                                   maxlength="9"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('cep') border-red-500 @enderror">
                            <div id="cep-loading" class="absolute inset-y-0 right-0 pr-3 flex items-center hidden">
                                <i class="fas fa-spinner fa-spin text-gray-400"></i>
                            </div>
                        </div>
                        @error('cep')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- UF -->
                    <div>
                        <label for="uf" class="block text-sm font-medium text-gray-700 mb-2">
                            UF
                        </label>
                        <select id="uf"
                                name="uf"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Selecione</option>
                            <option value="AC" {{ old('uf', $usuario->enderecos->first()->uf ?? '') == 'AC' ? 'selected' : '' }}>Acre</option>
                            <option value="AL" {{ old('uf', $usuario->enderecos->first()->uf ?? '') == 'AL' ? 'selected' : '' }}>Alagoas</option>
                            <option value="AP" {{ old('uf', $usuario->enderecos->first()->uf ?? '') == 'AP' ? 'selected' : '' }}>Amapá</option>
                            <option value="AM" {{ old('uf', $usuario->enderecos->first()->uf ?? '') == 'AM' ? 'selected' : '' }}>Amazonas</option>
                            <option value="BA" {{ old('uf', $usuario->enderecos->first()->uf ?? '') == 'BA' ? 'selected' : '' }}>Bahia</option>
                            <option value="CE" {{ old('uf', $usuario->enderecos->first()->uf ?? '') == 'CE' ? 'selected' : '' }}>Ceará</option>
                            <option value="DF" {{ old('uf', $usuario->enderecos->first()->uf ?? '') == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                            <option value="ES" {{ old('uf', $usuario->enderecos->first()->uf ?? '') == 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                            <option value="GO" {{ old('uf', $usuario->enderecos->first()->uf ?? '') == 'GO' ? 'selected' : '' }}>Goiás</option>
                            <option value="MA" {{ old('uf', $usuario->enderecos->first()->uf ?? '') == 'MA' ? 'selected' : '' }}>Maranhão</option>
                            <option value="MT" {{ old('uf', $usuario->enderecos->first()->uf ?? '') == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                            <option value="MS" {{ old('uf', $usuario->enderecos->first()->uf ?? '') == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                            <option value="MG" {{ old('uf', $usuario->enderecos->first()->uf ?? '') == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                            <option value="PA" {{ old('uf', $usuario->enderecos->first()->uf ?? '') == 'PA' ? 'selected' : '' }}>Pará</option>
                            <option value="PB" {{ old('uf', $usuario->enderecos->first()->uf ?? '') == 'PB' ? 'selected' : '' }}>Paraíba</option>
                            <option value="PR" {{ old('uf', $usuario->enderecos->first()->uf ?? '') == 'PR' ? 'selected' : '' }}>Paraná</option>
                            <option value="PE" {{ old('uf', $usuario->enderecos->first()->uf ?? '') == 'PE' ? 'selected' : '' }}>Pernambuco</option>
                            <option value="PI" {{ old('uf', $usuario->enderecos->first()->uf ?? '') == 'PI' ? 'selected' : '' }}>Piauí</option>
                            <option value="RJ" {{ old('uf', $usuario->enderecos->first()->uf ?? '') == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                            <option value="RN" {{ old('uf', $usuario->enderecos->first()->uf ?? '') == 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                            <option value="RS" {{ old('uf', $usuario->enderecos->first()->uf ?? '') == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                            <option value="RO" {{ old('uf', $usuario->enderecos->first()->uf ?? '') == 'RO' ? 'selected' : '' }}>Rondônia</option>
                            <option value="RR" {{ old('uf', $usuario->enderecos->first()->uf ?? '') == 'RR' ? 'selected' : '' }}>Roraima</option>
                            <option value="SC" {{ old('uf', $usuario->enderecos->first()->uf ?? '') == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                            <option value="SP" {{ old('uf', $usuario->enderecos->first()->uf ?? '') == 'SP' ? 'selected' : '' }}>São Paulo</option>
                            <option value="SE" {{ old('uf', $usuario->enderecos->first()->uf ?? '') == 'SE' ? 'selected' : '' }}>Sergipe</option>
                            <option value="TO" {{ old('uf', $usuario->enderecos->first()->uf ?? '') == 'TO' ? 'selected' : '' }}>Tocantins</option>
                        </select>
                    </div>

                    <!-- Logradouro -->
                    <div>
                        <label for="logradouro" class="block text-sm font-medium text-gray-700 mb-2">
                            Logradouro
                        </label>
                        <input type="text"
                               id="logradouro"
                               name="logradouro"
                               value="{{ old('logradouro', $usuario->enderecos->first()->logradouro ?? '') }}"
                               placeholder="Rua, Avenida, etc."
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('logradouro') border-red-500 @enderror">
                        @error('logradouro')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Número -->
                    <div>
                        <label for="numero" class="block text-sm font-medium text-gray-700 mb-2">
                            Número
                        </label>
                        <input type="text"
                               id="numero"
                               name="numero"
                               value="{{ old('numero', $usuario->enderecos->first()->numero ?? '') }}"
                               placeholder="123"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('numero') border-red-500 @enderror">
                        @error('numero')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Complemento -->
                    <div>
                        <label for="complemento" class="block text-sm font-medium text-gray-700 mb-2">
                            Complemento
                        </label>
                        <input type="text"
                               id="complemento"
                               name="complemento"
                               value="{{ old('complemento', $usuario->enderecos->first()->complemento ?? '') }}"
                               placeholder="Apto, Bloco, etc."
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('complemento') border-red-500 @enderror">
                        @error('complemento')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bairro -->
                    <div>
                        <label for="bairro" class="block text-sm font-medium text-gray-700 mb-2">
                            Bairro
                        </label>
                        <input type="text"
                               id="bairro"
                               name="bairro"
                               value="{{ old('bairro', $usuario->enderecos->first()->bairro ?? '') }}"
                               placeholder="Nome do bairro"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('bairro') border-red-500 @enderror">
                        @error('bairro')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="mt-8 flex items-center justify-end space-x-4">
                <a href="{{ route('usuarios.index') }}"
                   class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Atualizar Usuário
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cepInput = document.getElementById('cep');
    const logradouroInput = document.getElementById('logradouro');
    const bairroInput = document.getElementById('bairro');
    const ufSelect = document.getElementById('uf');
    const cepLoading = document.getElementById('cep-loading');

    // Gerenciamento de telefones
    let telefoneIndex = {{ $usuario->telefones->count() }};
    const telefonesContainer = document.getElementById('telefones-container');
    const addTelefoneBtn = document.getElementById('add-telefone');

    // Adicionar telefone
    addTelefoneBtn.addEventListener('click', function() {
        const telefoneItem = document.createElement('div');
        telefoneItem.className = 'telefone-item flex items-end space-x-2 mb-2';
        telefoneItem.innerHTML = `
            <div class="flex-1">
                <input type="text"
                       name="telefones[${telefoneIndex}][numero]"
                       placeholder="Ex: (31) 99624-1675"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 telefone-input"
                       required>
            </div>
            <div class="w-32">
                <select name="telefones[${telefoneIndex}][tipo]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="celular">Celular</option>
                    <option value="residencial">Residencial</option>
                    <option value="comercial">Comercial</option>
                    <option value="whatsapp">WhatsApp</option>
                </select>
            </div>
            <button type="button"
                    class="remove-telefone px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                <i class="fas fa-trash"></i>
            </button>
        `;

        telefonesContainer.appendChild(telefoneItem);
        telefoneIndex++;

        // Mostrar botões de remover em todos os telefones
        updateRemoveButtons();

        // Adicionar máscara ao novo input
        addTelefoneMask(telefoneItem.querySelector('.telefone-input'));
    });

    // Remover telefone
    telefonesContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-telefone')) {
            const telefoneItem = e.target.closest('.telefone-item');
            telefoneItem.remove();
            updateRemoveButtons();
        }
    });

    // Atualizar botões de remover
    function updateRemoveButtons() {
        const telefoneItems = telefonesContainer.querySelectorAll('.telefone-item');
        telefoneItems.forEach((item, index) => {
            const removeBtn = item.querySelector('.remove-telefone');
            if (telefoneItems.length > 1) {
                removeBtn.classList.remove('hidden');
            } else {
                removeBtn.classList.add('hidden');
            }
        });
    }

    // Máscara para telefone brasileiro
    function addTelefoneMask(input) {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');

            // Aplicar máscara baseada no tamanho
            if (value.length <= 2) {
                e.target.value = value;
            } else if (value.length <= 6) {
                // (XX) XXXX
                e.target.value = `(${value.substring(0, 2)}) ${value.substring(2)}`;
            } else if (value.length <= 10) {
                // (XX) XXXX-XXXX
                e.target.value = `(${value.substring(0, 2)}) ${value.substring(2, 6)}-${value.substring(6)}`;
            } else if (value.length <= 11) {
                // (XX) XXXXX-XXXX
                e.target.value = `(${value.substring(0, 2)}) ${value.substring(2, 7)}-${value.substring(7)}`;
            } else {
                // Limitar a 11 dígitos
                value = value.substring(0, 11);
                e.target.value = `(${value.substring(0, 2)}) ${value.substring(2, 7)}-${value.substring(7)}`;
            }
        });
    }

    // Aplicar máscara aos telefones existentes
    document.querySelectorAll('.telefone-input').forEach(function(input) {
        addTelefoneMask(input);

        // Aplicar máscara ao valor existente se não estiver formatado
        if (input.value && !input.value.includes('(')) {
            let value = input.value.replace(/\D/g, '');
            if (value.length >= 2) {
                if (value.length <= 6) {
                    input.value = `(${value.substring(0, 2)}) ${value.substring(2)}`;
                } else if (value.length <= 10) {
                    input.value = `(${value.substring(0, 2)}) ${value.substring(2, 6)}-${value.substring(6)}`;
                } else if (value.length <= 11) {
                    input.value = `(${value.substring(0, 2)}) ${value.substring(2, 7)}-${value.substring(7)}`;
                }
            }
        }
    });

    // Máscara para CEP
    cepInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 5) {
            value = value.substring(0, 5) + '-' + value.substring(5, 8);
        }
        e.target.value = value;
    });

    // Variável para controlar se já fez a busca
    let cepJaBuscado = '';

    // Buscar CEP no ViaCEP automaticamente quando atingir 8 caracteres
    cepInput.addEventListener('keyup', function() {
        const cep = this.value.replace(/\D/g, '');

        // Só busca se tiver 8 dígitos e for diferente do último CEP buscado
        if (cep.length === 8 && cep !== cepJaBuscado) {
            cepJaBuscado = cep;
            buscarCEP(cep);
        }

        // Reset se o CEP for alterado
        if (cep.length < 8) {
            cepJaBuscado = '';
        }
    });

    function buscarCEP(cep) {
        // Mostrar loading
        cepLoading.classList.remove('hidden');

        // Fazer requisição para o ViaCEP
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                cepLoading.classList.add('hidden');

                if (data.erro) {
                    mostrarErroCEP('CEP não encontrado');
                    return;
                }

                // Preencher campos
                if (data.logradouro) {
                    logradouroInput.value = data.logradouro;
                }
                if (data.bairro) {
                    bairroInput.value = data.bairro;
                }
                if (data.uf) {
                    ufSelect.value = data.uf;
                }

                // Mostrar sucesso
                mostrarSucessoCEP('Endereço encontrado!');
            })
            .catch(error => {
                cepLoading.classList.add('hidden');
                mostrarErroCEP('Erro ao buscar CEP');
                console.error('Erro:', error);
            });
    }

    function mostrarErroCEP(mensagem) {
        // Remover mensagens anteriores
        const mensagemAnterior = document.getElementById('cep-mensagem');
        if (mensagemAnterior) {
            mensagemAnterior.remove();
        }

        // Criar nova mensagem de erro
        const div = document.createElement('div');
        div.id = 'cep-mensagem';
        div.className = 'mt-1 text-sm text-red-600';
        div.textContent = mensagem;

        cepInput.parentNode.appendChild(div);
    }

    function mostrarSucessoCEP(mensagem) {
        // Remover mensagens anteriores
        const mensagemAnterior = document.getElementById('cep-mensagem');
        if (mensagemAnterior) {
            mensagemAnterior.remove();
        }

        // Criar nova mensagem de sucesso
        const div = document.createElement('div');
        div.id = 'cep-mensagem';
        div.className = 'mt-1 text-sm text-green-600';
        div.textContent = mensagem;

        cepInput.parentNode.appendChild(div);

        // Remover mensagem após 3 segundos
        setTimeout(() => {
            if (div.parentNode) {
                div.remove();
            }
        }, 3000);
    }
});
</script>
@endsection
