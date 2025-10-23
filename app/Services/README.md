# Services Structure

Esta pasta contém a lógica de negócio separada dos controllers, organizando cada funcionalidade em services específicos.

## Estrutura

```
app/Services/
├── v1/                    # Versão 1 dos services
│   ├── Usuario/          # Services relacionados a usuários
│   │   ├── ListarUsuariosService.php
│   │   ├── FormularioCriarUsuarioService.php
│   │   ├── CriarUsuarioService.php
│   │   ├── MostrarUsuarioService.php
│   │   ├── FormularioEditarUsuarioService.php
│   │   ├── AtualizarUsuarioService.php
│   │   └── DeletarUsuarioService.php
│   └── Empresa/          # Services relacionados a empresas
│       ├── ListarEmpresasService.php
│       ├── FormularioCriarEmpresaService.php
│       ├── CriarEmpresaService.php
│       ├── MostrarEmpresaService.php
│       ├── FormularioEditarEmpresaService.php
│       ├── AtualizarEmpresaService.php
│       └── DeletarEmpresaService.php
└── README.md
```

## Padrão de Nomenclatura

- **Listar[Entidade]Service**: Para listagem com filtros e paginação
- **FormularioCriar[Entidade]Service**: Para dados necessários no formulário de criação
- **Criar[Entidade]Service**: Para lógica de criação
- **Mostrar[Entidade]Service**: Para exibição de detalhes
- **FormularioEditar[Entidade]Service**: Para dados necessários no formulário de edição
- **Atualizar[Entidade]Service**: Para lógica de atualização
- **Deletar[Entidade]Service**: Para lógica de exclusão

## Versões

- **v1**: Versão atual dos services
- **v2, v3, etc.**: Futuras versões para evolução da API

## Benefícios

1. **Separação de Responsabilidades**: Controllers apenas orquestram, services contêm a lógica
2. **Reutilização**: Services podem ser usados em diferentes contextos (web, API, jobs)
3. **Testabilidade**: Cada service pode ser testado independentemente
4. **Manutenibilidade**: Lógica centralizada e organizada
5. **Versionamento**: Possibilidade de criar novas versões sem quebrar compatibilidade

## Uso nos Controllers

### Injeção de Dependência

```php
class UsuarioController extends Controller
{
    public function __construct(
        private ListarUsuariosService $listarUsuariosService,
        private CriarUsuarioService $criarUsuarioService,
        // ... outros services
    ) {}

    public function index(Request $request)
    {
        $result = $this->listarUsuariosService->execute($request);
        
        return view('usuarios.index', $result);
    }
}
```

### Benefícios da Injeção de Dependência

- **Testabilidade**: Fácil mock de services nos testes
- **Flexibilidade**: Laravel resolve automaticamente as dependências
- **Manutenibilidade**: Mudanças nos services não afetam o controller
- **Performance**: Services são reutilizados entre requests

## Retorno Padrão

Todos os services retornam um array com:
- `success`: boolean indicando sucesso
- `message`: string com mensagem de retorno
- `data`: array com dados específicos (quando aplicável)
