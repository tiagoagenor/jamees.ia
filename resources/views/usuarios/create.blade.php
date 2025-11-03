@extends('layouts.app')

@section('title', 'Criar Usuário - Jamees')
@section('page-title', 'Criar Usuário')

@section('content')
<div id="usuarios-create-app" class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Novo Usuário</h3>
            <p class="text-sm text-gray-600">Preencha os dados para criar um novo usuário</p>
        </div>

        <form method="POST" action="{{ route('usuarios.store') }}" class="p-6 space-y-6">
            @csrf

            <!-- Informações Básicas -->
            <div class="space-y-6">
                <h4 class="text-md font-medium text-gray-900 border-b border-gray-200 pb-2">Informações Básicas</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nome -->
                    <div>
                        <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome Completo *</label>
                        <input type="text"
                               id="nome"
                               name="nome"
                               value="{{ old('nome') }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('nome') border-red-500 @enderror">
                        @error('nome')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Senha -->
                    <div>
                        <label for="senha" class="block text-sm font-medium text-gray-700 mb-1">Senha *</label>
                        <input type="password"
                               id="senha"
                               name="senha"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('senha') border-red-500 @enderror">
                        @error('senha')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmar Senha -->
                    <div>
                        <label for="senha_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Senha *</label>
                        <input type="password"
                               id="senha_confirmation"
                               name="senha_confirmation"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <!-- Empresas -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Empresas *</label>
                        <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-300 rounded-md p-3">
                            @foreach($empresas as $empresa)
                                <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                    <input type="checkbox"
                                           name="empresas[]"
                                           value="{{ $empresa->id }}"
                                           {{ in_array($empresa->id, old('empresas', [])) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <div class="flex items-center space-x-2">
                                        @php
                                            $user = Auth::user();
                                            $empresaPrincipal = $user->empresaPrincipal();
                                        @endphp
                                        @if($empresaPrincipal && $empresa->id == $empresaPrincipal->id)
                                            <span class="text-yellow-600">👑</span>
                                            <span class="text-sm font-medium text-gray-900">{{ $empresa->nome_fantasia }}</span>
                                            <span class="text-xs text-yellow-600 font-medium">(Principal)</span>
                                        @elseif($empresaPrincipal && $empresa->empresa_id == $empresaPrincipal->id)
                                            <span class="text-blue-600">🏢</span>
                                            <span class="text-sm font-medium text-gray-900">{{ $empresa->nome_fantasia }}</span>
                                            <span class="text-xs text-blue-600 font-medium">(Filial)</span>
                                        @else
                                            <span class="text-gray-600">🏢</span>
                                            <span class="text-sm font-medium text-gray-900">{{ $empresa->nome_fantasia }}</span>
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Grupos *</label>
                        <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-300 rounded-md p-3">
                            @foreach($grupos as $grupo)
                                <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                    <input type="checkbox"
                                           name="grupos[]"
                                           value="{{ $grupo->id }}"
                                           {{ in_array($grupo->id, old('grupos', [])) ? 'checked' : '' }}
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
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status"
                                name="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach(\App\Enums\UsuarioStatusEnum::options() as $value => $label)
                                <option value="{{ $value }}" {{ old('status', '1') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Telefones -->
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h4 class="text-md font-medium text-gray-900 border-b border-gray-200 pb-2">Telefones</h4>
                </div>

                <div id="telefones-container">
                    <div class="telefone-item flex items-end space-x-2 mb-2">
                        <div class="flex-1">
                            <input type="text"
                                   name="telefones[0][numero]"
                                   placeholder="Ex: (31) 99624-1675"
                                   class="telefone-input w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   required>
                        </div>
                        <div class="w-32">
                            <select name="telefones[0][tipo]"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach(\App\Enums\UsuarioTelefoneTipoEnum::options() as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button"
                                class="remove-telefone px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 hidden">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>

                <button type="button"
                        @click="addTelefone"
                        class="mt-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-plus mr-2"></i>
                    Adicionar Telefone
                </button>
                @error('telefones')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Informações Pessoais -->
            <div class="space-y-6">
                <h4 class="text-md font-medium text-gray-900 border-b border-gray-200 pb-2">Informações Pessoais</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- CPF -->
                    <div>
                        <label for="cpf" class="block text-sm font-medium text-gray-700 mb-1">CPF</label>
                        <input type="text"
                               id="cpf"
                               name="cpf"
                               value="{{ old('cpf') }}"
                               placeholder="000.000.000-00"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('cpf') border-red-500 @enderror">
                        @error('cpf')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- RG -->
                    <div>
                        <label for="rg" class="block text-sm font-medium text-gray-700 mb-1">RG</label>
                        <input type="text"
                               id="rg"
                               name="rg"
                               value="{{ old('rg') }}"
                               placeholder="00.000.000-0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('rg') border-red-500 @enderror">
                        @error('rg')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Data de Nascimento -->
                    <div>
                        <label for="data_nascimento" class="block text-sm font-medium text-gray-700 mb-1">Data de Nascimento</label>
                        <input type="date"
                               id="data_nascimento"
                               name="data_nascimento"
                               value="{{ old('data_nascimento') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('data_nascimento') border-red-500 @enderror">
                        @error('data_nascimento')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sexo -->
                    <div>
                        <label for="sexo" class="block text-sm font-medium text-gray-700 mb-1">Sexo</label>
                        <select id="sexo"
                                name="sexo"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('sexo') border-red-500 @enderror">
                            <option value="">Selecione</option>
                            <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                            <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Feminino</option>
                            <option value="O" {{ old('sexo') == 'O' ? 'selected' : '' }}>Outro</option>
                        </select>
                        @error('sexo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Comissão -->
                    <div>
                        <label for="comissao" class="block text-sm font-medium text-gray-700 mb-1">Comissão (%)</label>
                        <input type="number"
                               id="comissao"
                               name="comissao"
                               value="{{ old('comissao') }}"
                               min="0"
                               max="100"
                               step="0.01"
                               placeholder="0.00"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('comissao') border-red-500 @enderror">
                        @error('comissao')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Desconto Máximo -->
                    <div>
                        <label for="desconto_maximo" class="block text-sm font-medium text-gray-700 mb-1">Desconto Máximo (%)</label>
                        <input type="number"
                               id="desconto_maximo"
                               name="desconto_maximo"
                               value="{{ old('desconto_maximo') }}"
                               min="0"
                               max="100"
                               step="0.01"
                               placeholder="0.00"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('desconto_maximo') border-red-500 @enderror">
                        @error('desconto_maximo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Observações -->
                <div>
                    <label for="obs" class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                    <textarea id="obs"
                              name="obs"
                              rows="3"
                              placeholder="Observações sobre o usuário..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('obs') border-red-500 @enderror">{{ old('obs') }}</textarea>
                    @error('obs')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Endereço -->
            <div class="space-y-6">
                <h4 class="text-md font-medium text-gray-900 border-b border-gray-200 pb-2">Endereço</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- CEP -->
                    <div>
                        <label for="cep" class="block text-sm font-medium text-gray-700 mb-1">CEP</label>
                        <div class="relative">
                            <input type="text"
                                   id="cep"
                                   name="cep"
                                   value="{{ old('cep') }}"
                                   placeholder="00000-000"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('cep') border-red-500 @enderror">
                            <div id="cep-loading" class="absolute right-3 top-3 hidden">
                                <i class="fas fa-spinner fa-spin text-gray-400"></i>
                            </div>
                        </div>
                        @error('cep')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Logradouro -->
                    <div>
                        <label for="logradouro" class="block text-sm font-medium text-gray-700 mb-1">Logradouro</label>
                        <input type="text"
                               id="logradouro"
                               name="logradouro"
                               value="{{ old('logradouro') }}"
                               placeholder="Rua, Avenida, etc."
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('logradouro') border-red-500 @enderror">
                        @error('logradouro')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Número -->
                    <div>
                        <label for="numero" class="block text-sm font-medium text-gray-700 mb-1">Número</label>
                        <input type="text"
                               id="numero"
                               name="numero"
                               value="{{ old('numero') }}"
                               placeholder="123"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('numero') border-red-500 @enderror">
                        @error('numero')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Complemento -->
                    <div>
                        <label for="complemento" class="block text-sm font-medium text-gray-700 mb-1">Complemento</label>
                        <input type="text"
                               id="complemento"
                               name="complemento"
                               value="{{ old('complemento') }}"
                               placeholder="Apto, Sala, etc."
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('complemento') border-red-500 @enderror">
                        @error('complemento')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bairro -->
                    <div>
                        <label for="bairro" class="block text-sm font-medium text-gray-700 mb-1">Bairro</label>
                        <input type="text"
                               id="bairro"
                               name="bairro"
                               value="{{ old('bairro') }}"
                               placeholder="Nome do bairro"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('bairro') border-red-500 @enderror">
                        @error('bairro')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- UF -->
                    <div>
                        <label for="uf" class="block text-sm font-medium text-gray-700 mb-1">UF</label>
                        <select id="uf"
                                name="uf"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('uf') border-red-500 @enderror">
                            <option value="">Selecione</option>
                            <option value="AC" {{ old('uf') == 'AC' ? 'selected' : '' }}>AC</option>
                            <option value="AL" {{ old('uf') == 'AL' ? 'selected' : '' }}>AL</option>
                            <option value="AP" {{ old('uf') == 'AP' ? 'selected' : '' }}>AP</option>
                            <option value="AM" {{ old('uf') == 'AM' ? 'selected' : '' }}>AM</option>
                            <option value="BA" {{ old('uf') == 'BA' ? 'selected' : '' }}>BA</option>
                            <option value="CE" {{ old('uf') == 'CE' ? 'selected' : '' }}>CE</option>
                            <option value="DF" {{ old('uf') == 'DF' ? 'selected' : '' }}>DF</option>
                            <option value="ES" {{ old('uf') == 'ES' ? 'selected' : '' }}>ES</option>
                            <option value="GO" {{ old('uf') == 'GO' ? 'selected' : '' }}>GO</option>
                            <option value="MA" {{ old('uf') == 'MA' ? 'selected' : '' }}>MA</option>
                            <option value="MT" {{ old('uf') == 'MT' ? 'selected' : '' }}>MT</option>
                            <option value="MS" {{ old('uf') == 'MS' ? 'selected' : '' }}>MS</option>
                            <option value="MG" {{ old('uf') == 'MG' ? 'selected' : '' }}>MG</option>
                            <option value="PA" {{ old('uf') == 'PA' ? 'selected' : '' }}>PA</option>
                            <option value="PB" {{ old('uf') == 'PB' ? 'selected' : '' }}>PB</option>
                            <option value="PR" {{ old('uf') == 'PR' ? 'selected' : '' }}>PR</option>
                            <option value="PE" {{ old('uf') == 'PE' ? 'selected' : '' }}>PE</option>
                            <option value="PI" {{ old('uf') == 'PI' ? 'selected' : '' }}>PI</option>
                            <option value="RJ" {{ old('uf') == 'RJ' ? 'selected' : '' }}>RJ</option>
                            <option value="RN" {{ old('uf') == 'RN' ? 'selected' : '' }}>RN</option>
                            <option value="RS" {{ old('uf') == 'RS' ? 'selected' : '' }}>RS</option>
                            <option value="RO" {{ old('uf') == 'RO' ? 'selected' : '' }}>RO</option>
                            <option value="RR" {{ old('uf') == 'RR' ? 'selected' : '' }}>RR</option>
                            <option value="SC" {{ old('uf') == 'SC' ? 'selected' : '' }}>SC</option>
                            <option value="SP" {{ old('uf') == 'SP' ? 'selected' : '' }}>SP</option>
                            <option value="SE" {{ old('uf') == 'SE' ? 'selected' : '' }}>SE</option>
                            <option value="TO" {{ old('uf') == 'TO' ? 'selected' : '' }}>TO</option>
                        </select>
                        @error('uf')
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
                    Criar Usuário
                </button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/vue-components.js') }}"></script>
<script>
const { createApp } = Vue;
createApp(window.UsuariosCreate).mount('#usuarios-create-app');
</script>
@endsection
