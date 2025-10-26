@extends('layouts.app')

@section('title', 'Detalhes do ' . ucfirst(strtolower($entidade->tipo_relacionamento->name)))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                @php
                    $tipoString = $entidade->tipo_relacionamento->name;
                    $routeName = match($tipoString) {
                        'CLIENTE' => 'clientes',
                        'FORNECEDOR' => 'fornecedores',
                        'FUNCIONARIO' => 'funcionarios',
                        'TRANSPORTADORA' => 'transportadoras',
                        default => strtolower($tipoString) . 's'
                    };
                @endphp
                <a href="{{ route($routeName . '.index') }}"
                   class="text-blue-600 hover:text-blue-800 mr-4">
                    ← Voltar para {{ ucfirst(strtolower($tipoString)) }}s
                </a>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route($routeName . '.edit', $entidade) }}"
                   class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                    <i class="fas fa-edit mr-2"></i>
                    Editar
                </a>
                <button type="button"
                        onclick="confirmarToggleStatus('{{ $entidade->id }}', '{{ $entidade->nome_completo }}', '{{ $routeName }}', {{ $entidade->isAtivo() ? 'true' : 'false' }})"
                        class="bg-{{ $entidade->isAtivo() ? 'red' : 'green' }}-600 hover:bg-{{ $entidade->isAtivo() ? 'red' : 'green' }}-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                    <i class="fas fa-{{ $entidade->isAtivo() ? 'ban' : 'check' }} mr-2"></i>
                    {{ $entidade->isAtivo() ? 'Desativar' : 'Ativar' }}
                </button>
            </div>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mt-4">
            <i class="{{ $entidade->getTipoRelacionamentoIcon() }} text-{{ $entidade->getTipoRelacionamentoColor() }}-600 mr-3"></i>
            {{ $entidade->nome_completo }}
        </h1>
        <p class="mt-2 text-gray-600">
            {{ $entidade->getTipoRelacionamentoLabel() }} -
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $entidade->isAtivo() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $entidade->isAtivo() ? 'Ativo' : 'Inativo' }}
            </span>
        </p>
    </div>

    <!-- Informações Principais -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Informações Básicas -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <i class="fas fa-info-circle text-indigo-600 mr-2"></i>
                Informações Básicas
            </h2>
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Nome Completo</dt>
                    <dd class="text-sm text-gray-900">{{ $entidade->nome_completo }}</dd>
                </div>
                @if($entidade->nome_fantasia && $entidade->nome_fantasia !== $entidade->nome_completo)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Nome Fantasia</dt>
                    <dd class="text-sm text-gray-900">{{ $entidade->nome_fantasia }}</dd>
                </div>
                @endif
                <div>
                    <dt class="text-sm font-medium text-gray-500">Documento</dt>
                    <dd class="text-sm text-gray-900">{{ $entidade->documento_formatado }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Tipo de Pessoa</dt>
                    <dd class="text-sm text-gray-900">{{ $entidade->isPessoaFisica() ? 'Pessoa Física' : 'Pessoa Jurídica' }}</dd>
                </div>
                @if($entidade->email)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="text-sm text-gray-900">
                        <a href="mailto:{{ $entidade->email }}" class="text-blue-600 hover:text-blue-800">
                            {{ $entidade->email }}
                        </a>
                    </dd>
                </div>
                @endif
                @if($entidade->site)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Site</dt>
                    <dd class="text-sm text-gray-900">
                        <a href="{{ $entidade->site }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                            {{ $entidade->site }}
                        </a>
                    </dd>
                </div>
                @endif
            </dl>
        </div>

        <!-- Contatos -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <i class="fas fa-phone text-green-600 mr-2"></i>
                Contatos
            </h2>
            <dl class="space-y-3">
                @if($entidade->telefone_comercial)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Telefone Comercial</dt>
                    <dd class="text-sm text-gray-900">
                        <a href="tel:{{ $entidade->telefone_comercial }}" class="text-blue-600 hover:text-blue-800">
                            {{ $entidade->telefone_comercial_formatado }}
                        </a>
                    </dd>
                </div>
                @endif
                @if($entidade->celular)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Celular</dt>
                    <dd class="text-sm text-gray-900">
                        <a href="tel:{{ $entidade->celular }}" class="text-blue-600 hover:text-blue-800">
                            {{ $entidade->celular_formatado }}
                        </a>
                    </dd>
                </div>
                @endif
                @if($entidade->contatos->count() > 0)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Contatos Adicionais</dt>
                    <dd class="text-sm text-gray-900">
                        @foreach($entidade->contatos as $contato)
                        <div class="mb-2">
                            <strong>{{ $contato->nome }}</strong>
                            @if($contato->cargo)
                                <span class="text-gray-500">({{ $contato->cargo }})</span>
                            @endif
                            <br>
                            <span class="text-blue-600">{{ $contato->contato_formatado }}</span>
                            @if($contato->observacao)
                                <br>
                                <span class="text-gray-500 text-xs">{{ $contato->observacao }}</span>
                            @endif
                        </div>
                        @endforeach
                    </dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Endereços -->
    @if($entidade->enderecos->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
            <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
            Endereços
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($entidade->enderecos as $endereco)
            <div class="border rounded-lg p-4">
                <h3 class="font-medium text-gray-900 mb-2">{{ $endereco->endereco_resumido }}</h3>
                <p class="text-sm text-gray-600">{{ $endereco->endereco_completo }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Observações -->
    @if($entidade->observacao)
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
            <i class="fas fa-sticky-note text-yellow-600 mr-2"></i>
            Observações
        </h2>
        <p class="text-gray-700 whitespace-pre-line">{{ $entidade->observacao }}</p>
    </div>
    @endif

    <!-- Informações do Sistema -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
            <i class="fas fa-cog text-gray-600 mr-2"></i>
            Informações do Sistema
        </h2>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">ID</dt>
                <dd class="text-sm text-gray-900 font-mono">{{ $entidade->id }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Empresa</dt>
                <dd class="text-sm text-gray-900">{{ $entidade->empresa->nome_fantasia }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                <dd class="text-sm text-gray-900">{{ $entidade->created_at->format('d/m/Y H:i') }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Atualizado em</dt>
                <dd class="text-sm text-gray-900">{{ $entidade->updated_at->format('d/m/Y H:i') }}</dd>
            </div>
        </dl>
    </div>

    <!-- Seção de Contatos -->
    <div class="bg-white shadow rounded-lg p-6 mt-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-address-book text-blue-600 mr-2"></i>
                Contatos
            </h3>
            <button type="button" onclick="abrirModalContato()" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-plus mr-1"></i>
                Adicionar Contato
            </button>
        </div>

        @if($entidade->contatos->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($entidade->contatos as $contato)
                    <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-medium text-gray-900">{{ $contato->nome }}</h4>
                            <div class="flex space-x-1">
                                <button onclick="editarContato('{{ $contato->id }}')" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="excluirContato('{{ $contato->id }}', '{{ $contato->nome }}')" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-{{ $contato->tipo_contato === 'email' ? 'envelope' : 'phone' }} mr-2"></i>
                                {{ $contato->contato_formatado }}
                            </div>
                            @if($contato->cargo)
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-briefcase mr-2"></i>
                                    {{ $contato->cargo }}
                                </div>
                            @endif
                            @if($contato->observacao)
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-sticky-note mr-2"></i>
                                    {{ $contato->observacao }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-address-book text-4xl mb-4"></i>
                <p>Nenhum contato cadastrado</p>
                <p class="text-sm">Clique em "Adicionar Contato" para começar</p>
            </div>
        @endif
    </div>

    <!-- Seção de Endereços -->
    <div class="bg-white shadow rounded-lg p-6 mt-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-map-marker-alt text-green-600 mr-2"></i>
                Endereços
            </h3>
            <button type="button" onclick="abrirModalEndereco()" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-plus mr-1"></i>
                Adicionar Endereço
            </button>
        </div>

        @if($entidade->enderecos->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($entidade->enderecos as $endereco)
                    <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-medium text-gray-900">Endereço {{ $loop->iteration }}</h4>
                            <div class="flex space-x-1">
                                <button onclick="editarEndereco('{{ $endereco->id }}')" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="excluirEndereco('{{ $endereco->id }}')" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="space-y-1 text-sm text-gray-600">
                            <div>
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                {{ $endereco->endereco_resumido }}
                            </div>
                            @if($endereco->cep)
                                <div>
                                    <i class="fas fa-mail-bulk mr-2"></i>
                                    {{ $endereco->cep_formatado }}
                                </div>
                            @endif
                            @if($endereco->complemento)
                                <div>
                                    <i class="fas fa-info-circle mr-2"></i>
                                    {{ $endereco->complemento }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-map-marker-alt text-4xl mb-4"></i>
                <p>Nenhum endereço cadastrado</p>
                <p class="text-sm">Clique em "Adicionar Endereço" para começar</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal para Contatos -->
<div id="modalContato" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="tituloModalContato">
                    Adicionar Contato
                </h3>
                <button onclick="fecharModalContato()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="formContato" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="contato_nome" class="block text-sm font-medium text-gray-700">Nome</label>
                        <input type="text" id="contato_nome" name="nome" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="contato_contato" class="block text-sm font-medium text-gray-700">Contato</label>
                        <input type="text" id="contato_contato" name="contato" required
                               placeholder="Email ou telefone"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="contato_cargo" class="block text-sm font-medium text-gray-700">Cargo</label>
                        <input type="text" id="contato_cargo" name="cargo"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="contato_observacao" class="block text-sm font-medium text-gray-700">Observação</label>
                        <textarea id="contato_observacao" name="observacao" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="fecharModalContato()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Endereços -->
<div id="modalEndereco" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="tituloModalEndereco">
                    Adicionar Endereço
                </h3>
                <button onclick="fecharModalEndereco()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="formEndereco" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="endereco_cep" class="block text-sm font-medium text-gray-700">CEP</label>
                        <input type="text" id="endereco_cep" name="cep"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div>
                        <label for="endereco_logradouro" class="block text-sm font-medium text-gray-700">Logradouro</label>
                        <input type="text" id="endereco_logradouro" name="logradouro" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="endereco_numero" class="block text-sm font-medium text-gray-700">Número</label>
                            <input type="text" id="endereco_numero" name="numero"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label for="endereco_complemento" class="block text-sm font-medium text-gray-700">Complemento</label>
                            <input type="text" id="endereco_complemento" name="complemento"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        </div>
                    </div>

                    <div>
                        <label for="endereco_bairro" class="block text-sm font-medium text-gray-700">Bairro</label>
                        <input type="text" id="endereco_bairro" name="bairro" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="endereco_cidade" class="block text-sm font-medium text-gray-700">Cidade</label>
                            <input type="text" id="endereco_cidade" name="cidade" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label for="endereco_estado" class="block text-sm font-medium text-gray-700">Estado</label>
                            <input type="text" id="endereco_estado" name="estado" required maxlength="2"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="fecharModalEndereco()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Função para mostrar mensagens de sucesso/erro
@if(session('success'))
    Swal.fire({
        title: 'Sucesso!',
        text: '{{ session('success') }}',
        icon: 'success',
        confirmButtonText: 'OK'
    });
@endif

@if(session('error'))
    Swal.fire({
        title: 'Erro!',
        text: '{{ session('error') }}',
        icon: 'error',
        confirmButtonText: 'OK'
    });
@endif

// Função para confirmar toggle de status com SweetAlert2
function confirmarToggleStatus(entidadeId, nomeEntidade, routeName, isAtivo) {
    const acao = isAtivo ? 'desativar' : 'ativar';
    const icone = isAtivo ? 'warning' : 'success';

    Swal.fire({
        title: `Tem certeza?`,
        text: `Deseja realmente ${acao} "${nomeEntidade}"?`,
        icon: icone,
        showCancelButton: true,
        confirmButtonColor: isAtivo ? '#d33' : '#28a745',
        cancelButtonColor: '#3085d6',
        confirmButtonText: `Sim, ${acao}!`,
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Criar formulário dinâmico para toggle status
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/${routeName}/${entidadeId}/toggle-status`;

            // Adicionar token CSRF
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Adicionar método PATCH
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'PATCH';
            form.appendChild(methodField);

            // Adicionar ao DOM e submeter
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Funções para gerenciar modais de contatos
function abrirModalContato() {
    document.getElementById('modalContato').classList.remove('hidden');
    document.getElementById('tituloModalContato').textContent = 'Adicionar Contato';
    document.getElementById('formContato').action = '{{ route("entidades.contatos.store", $entidade) }}';
    document.getElementById('formContato').reset();
}

function fecharModalContato() {
    document.getElementById('modalContato').classList.add('hidden');
}

function editarContato(contatoId) {
    // Implementar busca dos dados do contato via AJAX
    Swal.fire({
        title: 'Editar Contato',
        text: 'Funcionalidade será implementada em breve',
        icon: 'info',
        confirmButtonText: 'OK'
    });
}

function excluirContato(contatoId, nomeContato) {
    Swal.fire({
        title: 'Tem certeza?',
        text: `Deseja realmente excluir o contato "${nomeContato}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Criar formulário dinâmico para exclusão
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ route("entidades.contatos.destroy", $entidade) }}/${contatoId}`;

            // Adicionar token CSRF
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Adicionar método DELETE
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);

            // Adicionar ao DOM e submeter
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Funções para gerenciar modais de endereços
function abrirModalEndereco() {
    document.getElementById('modalEndereco').classList.remove('hidden');
    document.getElementById('tituloModalEndereco').textContent = 'Adicionar Endereço';
    document.getElementById('formEndereco').action = '{{ route("entidades.enderecos.store", $entidade) }}';
    document.getElementById('formEndereco').reset();
}

function fecharModalEndereco() {
    document.getElementById('modalEndereco').classList.add('hidden');
}

function editarEndereco(enderecoId) {
    // Implementar busca dos dados do endereço via AJAX
    Swal.fire({
        title: 'Editar Endereço',
        text: 'Funcionalidade será implementada em breve',
        icon: 'info',
        confirmButtonText: 'OK'
    });
}

function excluirEndereco(enderecoId) {
    Swal.fire({
        title: 'Tem certeza?',
        text: 'Deseja realmente excluir este endereço?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Criar formulário dinâmico para exclusão
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ route("entidades.enderecos.destroy", $entidade) }}/${enderecoId}`;

            // Adicionar token CSRF
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Adicionar método DELETE
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);

            // Adicionar ao DOM e submeter
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Máscara para CEP
document.addEventListener('DOMContentLoaded', function() {
    const cepInput = document.getElementById('endereco_cep');
    if (cepInput) {
        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{5})(\d{3})/, '$1-$2');
            e.target.value = value;
        });
    }
});
</script>
@endsection
