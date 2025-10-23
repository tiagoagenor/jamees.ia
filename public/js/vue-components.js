// Vue.js Components for Jamees System

// Empresas Index Component
window.EmpresasIndex = {
    methods: {
        deleteEmpresa(id, nome) {
            Swal.fire({
                title: 'Tem certeza?',
                text: `Você está prestes a excluir a empresa "${nome}". Esta ação não pode ser desfeita!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Excluindo...',
                        text: 'Aguarde enquanto excluímos a empresa.',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }
    }
};

// Usuários Index Component
window.UsuariosIndex = {
    methods: {
        deleteUsuario(id, nome) {
            Swal.fire({
                title: 'Tem certeza?',
                text: `Você está prestes a excluir o usuário "${nome}". Esta ação não pode ser desfeita!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Excluindo...',
                        text: 'Aguarde enquanto excluímos o usuário.',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }
    }
};

// Usuários Create Component
window.UsuariosCreate = {
    data() {
        return {
            telefoneIndex: 1,
            cepTimeout: null
        }
    },
    methods: {
        addTelefone() {
            const telefonesContainer = document.getElementById('telefones-container');
            const telefoneItem = document.createElement('div');
            telefoneItem.className = 'telefone-item flex items-end space-x-2 mb-2';
            telefoneItem.innerHTML = `
                <div class="flex-1">
                    <input type="text"
                           name="telefones[${this.telefoneIndex}][numero]"
                           class="telefone-input w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="(31) 99999-9999">
                </div>
                <div class="w-32">
                    <select name="telefones[${this.telefoneIndex}][tipo]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="celular">Celular</option>
                        <option value="residencial">Residencial</option>
                        <option value="comercial">Comercial</option>
                        <option value="whatsapp">WhatsApp</option>
                    </select>
                </div>
                <button type="button" class="remove-telefone px-3 py-2 text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            `;

            telefonesContainer.appendChild(telefoneItem);
            this.telefoneIndex++;

            const telefoneInput = telefoneItem.querySelector('.telefone-input');
            this.addTelefoneMask(telefoneInput);

            telefoneItem.querySelector('.remove-telefone').addEventListener('click', function() {
                telefoneItem.remove();
            });
        },
        addTelefoneMask(input) {
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length <= 2) {
                    e.target.value = value;
                } else if (value.length <= 6) {
                    e.target.value = `(${value.substring(0, 2)}) ${value.substring(2)}`;
                } else if (value.length <= 10) {
                    e.target.value = `(${value.substring(0, 2)}) ${value.substring(2, 6)}-${value.substring(6)}`;
                } else if (value.length <= 11) {
                    e.target.value = `(${value.substring(0, 2)}) ${value.substring(2, 7)}-${value.substring(7)}`;
                }
            });
        },
        aplicarMascaraCEP(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 5) {
                e.target.value = value.substring(0, 5) + '-' + value.substring(5, 8);
            } else {
                e.target.value = value;
            }
        },
        buscarCEP(e) {
            const cep = e.target.value.replace(/\D/g, '');
            const cepLoading = document.getElementById('cep-loading');
            const logradouroInput = document.getElementById('logradouro');
            const bairroInput = document.getElementById('bairro');
            const ufSelect = document.getElementById('uf');

            clearTimeout(this.cepTimeout);

            if (cep.length === 8) {
                this.cepTimeout = setTimeout(() => {
                    this.executarBuscaCEP(cep, cepLoading, logradouroInput, bairroInput, ufSelect);
                }, 500);
            }
        },
        executarBuscaCEP(cep, cepLoading, logradouroInput, bairroInput, ufSelect) {
            cepLoading.classList.remove('hidden');

            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    cepLoading.classList.add('hidden');

                    if (data.erro) {
                        this.mostrarErroCEP('CEP não encontrado');
                        return;
                    }

                    logradouroInput.value = data.logradouro || '';
                    bairroInput.value = data.bairro || '';
                    ufSelect.value = data.uf || '';

                    this.mostrarSucessoCEP('Endereço encontrado!');
                })
                .catch(error => {
                    cepLoading.classList.add('hidden');
                    this.mostrarErroCEP('Erro ao buscar CEP');
                    console.error('Erro:', error);
                });
        },
        mostrarErroCEP(mensagem) {
            const cepInput = document.getElementById('cep');
            const mensagemAnterior = document.getElementById('cep-mensagem');
            if (mensagemAnterior) {
                mensagemAnterior.remove();
            }

            const div = document.createElement('div');
            div.id = 'cep-mensagem';
            div.className = 'mt-1 text-sm text-red-600';
            div.textContent = mensagem;

            cepInput.parentNode.appendChild(div);
        },
        mostrarSucessoCEP(mensagem) {
            const cepInput = document.getElementById('cep');
            const mensagemAnterior = document.getElementById('cep-mensagem');
            if (mensagemAnterior) {
                mensagemAnterior.remove();
            }

            const div = document.createElement('div');
            div.id = 'cep-mensagem';
            div.className = 'mt-1 text-sm text-green-600';
            div.textContent = mensagem;

            cepInput.parentNode.appendChild(div);

            setTimeout(() => {
                if (div.parentNode) {
                    div.remove();
                }
            }, 3000);
        }
    },
    mounted() {
        document.querySelectorAll('.telefone-input').forEach(input => {
            this.addTelefoneMask(input);

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

        const cepInput = document.getElementById('cep');
        cepInput.addEventListener('input', this.aplicarMascaraCEP);
        cepInput.addEventListener('keyup', this.buscarCEP);
    }
};

// Usuários Edit Component
window.UsuariosEdit = {
    data() {
        return {
            telefoneIndex: 0,
            cepTimeout: null
        }
    },
    methods: {
        addTelefone() {
            const telefonesContainer = document.getElementById('telefones-container');
            const telefoneItem = document.createElement('div');
            telefoneItem.className = 'telefone-item flex items-end space-x-2 mb-2';
            telefoneItem.innerHTML = `
                <div class="flex-1">
                    <input type="text"
                           name="telefones[${this.telefoneIndex}][numero]"
                           class="telefone-input w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="(31) 99999-9999">
                </div>
                <div class="w-32">
                    <select name="telefones[${this.telefoneIndex}][tipo]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="celular">Celular</option>
                        <option value="residencial">Residencial</option>
                        <option value="comercial">Comercial</option>
                        <option value="whatsapp">WhatsApp</option>
                    </select>
                </div>
                <button type="button" class="remove-telefone px-3 py-2 text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            `;

            telefonesContainer.appendChild(telefoneItem);
            this.telefoneIndex++;

            const telefoneInput = telefoneItem.querySelector('.telefone-input');
            this.addTelefoneMask(telefoneInput);

            telefoneItem.querySelector('.remove-telefone').addEventListener('click', function() {
                telefoneItem.remove();
            });
        },
        addTelefoneMask(input) {
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length <= 2) {
                    e.target.value = value;
                } else if (value.length <= 6) {
                    e.target.value = `(${value.substring(0, 2)}) ${value.substring(2)}`;
                } else if (value.length <= 10) {
                    e.target.value = `(${value.substring(0, 2)}) ${value.substring(2, 6)}-${value.substring(6)}`;
                } else if (value.length <= 11) {
                    e.target.value = `(${value.substring(0, 2)}) ${value.substring(2, 7)}-${value.substring(7)}`;
                }
            });
        },
        aplicarMascaraCEP(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 5) {
                e.target.value = value.substring(0, 5) + '-' + value.substring(5, 8);
            } else {
                e.target.value = value;
            }
        },
        buscarCEP(e) {
            const cep = e.target.value.replace(/\D/g, '');
            const cepLoading = document.getElementById('cep-loading');
            const logradouroInput = document.getElementById('logradouro');
            const bairroInput = document.getElementById('bairro');
            const ufSelect = document.getElementById('uf');

            clearTimeout(this.cepTimeout);

            if (cep.length === 8) {
                this.cepTimeout = setTimeout(() => {
                    this.executarBuscaCEP(cep, cepLoading, logradouroInput, bairroInput, ufSelect);
                }, 500);
            }
        },
        executarBuscaCEP(cep, cepLoading, logradouroInput, bairroInput, ufSelect) {
            cepLoading.classList.remove('hidden');

            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    cepLoading.classList.add('hidden');

                    if (data.erro) {
                        this.mostrarErroCEP('CEP não encontrado');
                        return;
                    }

                    logradouroInput.value = data.logradouro || '';
                    bairroInput.value = data.bairro || '';
                    ufSelect.value = data.uf || '';

                    this.mostrarSucessoCEP('Endereço encontrado!');
                })
                .catch(error => {
                    cepLoading.classList.add('hidden');
                    this.mostrarErroCEP('Erro ao buscar CEP');
                    console.error('Erro:', error);
                });
        },
        mostrarErroCEP(mensagem) {
            const cepInput = document.getElementById('cep');
            const mensagemAnterior = document.getElementById('cep-mensagem');
            if (mensagemAnterior) {
                mensagemAnterior.remove();
            }

            const div = document.createElement('div');
            div.id = 'cep-mensagem';
            div.className = 'mt-1 text-sm text-red-600';
            div.textContent = mensagem;

            cepInput.parentNode.appendChild(div);
        },
        mostrarSucessoCEP(mensagem) {
            const cepInput = document.getElementById('cep');
            const mensagemAnterior = document.getElementById('cep-mensagem');
            if (mensagemAnterior) {
                mensagemAnterior.remove();
            }

            const div = document.createElement('div');
            div.id = 'cep-mensagem';
            div.className = 'mt-1 text-sm text-green-600';
            div.textContent = mensagem;

            cepInput.parentNode.appendChild(div);

            setTimeout(() => {
                if (div.parentNode) {
                    div.remove();
                }
            }, 3000);
        }
    },
    mounted() {
        document.querySelectorAll('.telefone-input').forEach(input => {
            this.addTelefoneMask(input);

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

        const cepInput = document.getElementById('cep');
        if (cepInput) {
            cepInput.addEventListener('input', this.aplicarMascaraCEP);
            cepInput.addEventListener('keyup', this.buscarCEP);
        }
    }
};

// Empresas Create Component
window.EmpresasCreate = {
    data() {
        return {
            contatoIndex: 0,
            enderecoIndex: 0,
            showPessoaFisica: false
        }
    },
    methods: {
        togglePessoaFisica() {
            this.showPessoaFisica = document.getElementById('tipo').value === 'PF';
        },
        addContato() {
            const contatosContainer = document.getElementById('contatos-container');
            const contatoDiv = document.createElement('div');
            contatoDiv.className = 'border border-gray-200 rounded-lg p-4 mb-4';
            contatoDiv.innerHTML = `
                <div class="flex items-center justify-between mb-4">
                    <h5 class="text-sm font-medium text-gray-900">Contato ${this.contatoIndex + 1}</h5>
                    <button type="button" class="remove-contato text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                        <select name="contatos[${this.contatoIndex}][tipo]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="telefone">Telefone</option>
                            <option value="email">Email</option>
                            <option value="site">Site</option>
                            <option value="whatsapp">WhatsApp</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dado</label>
                        <input type="text" name="contatos[${this.contatoIndex}][dado]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Digite o contato">
                    </div>
                </div>
            `;

            contatosContainer.appendChild(contatoDiv);
            this.contatoIndex++;

            contatoDiv.querySelector('.remove-contato').addEventListener('click', function() {
                contatoDiv.remove();
            });
        },
        addEndereco() {
            const enderecosContainer = document.getElementById('enderecos-container');
            const enderecoDiv = document.createElement('div');
            enderecoDiv.className = 'border border-gray-200 rounded-lg p-4 mb-4';
            enderecoDiv.innerHTML = `
                <div class="flex items-center justify-between mb-4">
                    <h5 class="text-sm font-medium text-gray-900">Endereço ${this.enderecoIndex + 1}</h5>
                    <button type="button" class="remove-endereco text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">CEP</label>
                        <input type="text" name="enderecos[${this.enderecoIndex}][cep]" class="cep-input w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="00000-000">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Logradouro</label>
                        <input type="text" name="enderecos[${this.enderecoIndex}][logradouro]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Rua, Avenida, etc.">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Número</label>
                        <input type="text" name="enderecos[${this.enderecoIndex}][numero]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="123">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Complemento</label>
                        <input type="text" name="enderecos[${this.enderecoIndex}][complemento]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Apto, Sala, etc.">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bairro</label>
                        <input type="text" name="enderecos[${this.enderecoIndex}][bairro]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Nome do bairro">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">UF</label>
                        <input type="text" name="enderecos[${this.enderecoIndex}][uf]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="MG" maxlength="2">
                    </div>
                </div>
            `;

            enderecosContainer.appendChild(enderecoDiv);
            this.enderecoIndex++;

            enderecoDiv.querySelector('.remove-endereco').addEventListener('click', function() {
                enderecoDiv.remove();
            });

            const cepInput = enderecoDiv.querySelector('.cep-input');
            cepInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 5) {
                    e.target.value = value.substring(0, 5) + '-' + value.substring(5, 8);
                } else {
                    e.target.value = value;
                }
            });
        }
    },
    mounted() {
        this.addContato();
        this.addEndereco();

        document.getElementById('tipo').addEventListener('change', this.togglePessoaFisica);
        this.togglePessoaFisica();
    }
};

// Empresas Edit Component
window.EmpresasEdit = {
    data() {
        return {
            contatoIndex: 0,
            enderecoIndex: 0,
            showPessoaFisica: false
        }
    },
    methods: {
        togglePessoaFisica() {
            this.showPessoaFisica = document.getElementById('tipo').value === 'PF';
        },
        addContato() {
            const contatosContainer = document.getElementById('contatos-container');
            const contatoDiv = document.createElement('div');
            contatoDiv.className = 'border border-gray-200 rounded-lg p-4 mb-4';
            contatoDiv.innerHTML = `
                <div class="flex items-center justify-between mb-4">
                    <h5 class="text-sm font-medium text-gray-900">Contato ${this.contatoIndex + 1}</h5>
                    <button type="button" class="remove-contato text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                        <select name="contatos[${this.contatoIndex}][tipo]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="telefone">Telefone</option>
                            <option value="email">Email</option>
                            <option value="site">Site</option>
                            <option value="whatsapp">WhatsApp</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dado</label>
                        <input type="text" name="contatos[${this.contatoIndex}][dado]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Digite o contato">
                    </div>
                </div>
            `;

            contatosContainer.appendChild(contatoDiv);
            this.contatoIndex++;

            contatoDiv.querySelector('.remove-contato').addEventListener('click', function() {
                contatoDiv.remove();
            });
        },
        addEndereco() {
            const enderecosContainer = document.getElementById('enderecos-container');
            const enderecoDiv = document.createElement('div');
            enderecoDiv.className = 'border border-gray-200 rounded-lg p-4 mb-4';
            enderecoDiv.innerHTML = `
                <div class="flex items-center justify-between mb-4">
                    <h5 class="text-sm font-medium text-gray-900">Endereço ${this.enderecoIndex + 1}</h5>
                    <button type="button" class="remove-endereco text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">CEP</label>
                        <input type="text" name="enderecos[${this.enderecoIndex}][cep]" class="cep-input w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="00000-000">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Logradouro</label>
                        <input type="text" name="enderecos[${this.enderecoIndex}][logradouro]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Rua, Avenida, etc.">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Número</label>
                        <input type="text" name="enderecos[${this.enderecoIndex}][numero]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="123">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Complemento</label>
                        <input type="text" name="enderecos[${this.enderecoIndex}][complemento]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Apto, Sala, etc.">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bairro</label>
                        <input type="text" name="enderecos[${this.enderecoIndex}][bairro]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Nome do bairro">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">UF</label>
                        <input type="text" name="enderecos[${this.enderecoIndex}][uf]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="MG" maxlength="2">
                    </div>
                </div>
            `;

            enderecosContainer.appendChild(enderecoDiv);
            this.enderecoIndex++;

            enderecoDiv.querySelector('.remove-endereco').addEventListener('click', function() {
                enderecoDiv.remove();
            });

            const cepInput = enderecoDiv.querySelector('.cep-input');
            cepInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 5) {
                    e.target.value = value.substring(0, 5) + '-' + value.substring(5, 8);
                } else {
                    e.target.value = value;
                }
            });
        }
    },
    mounted() {
        document.getElementById('tipo').addEventListener('change', this.togglePessoaFisica);
        this.togglePessoaFisica();

        document.querySelectorAll('.remove-contato').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.border').remove();
            });
        });

        document.querySelectorAll('.remove-endereco').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.border').remove();
            });
        });

        document.querySelectorAll('.cep-input').forEach(input => {
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 5) {
                    e.target.value = value.substring(0, 5) + '-' + value.substring(5, 8);
                } else {
                    e.target.value = value;
                }
            });
        });
    }
};
