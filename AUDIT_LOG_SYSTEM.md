# Sistema de Histórico de Alterações (Audit Log)

## Visão Geral

O sistema de histórico de alterações foi criado para rastrear todas as mudanças realizadas no sistema, permitindo auditoria completa e controle de responsabilidades.

## Funcionalidades

### ✅ Implementado

1. **Tabela de Logs** - `audit_logs` com campos completos para auditoria
2. **Modelo AuditLog** - Com métodos helper e relacionamentos
3. **Serviço AuditService** - Para facilitar o registro de logs
4. **Controller AuditLogController** - Para exibir e gerenciar logs
5. **Views** - Interface para visualizar histórico
6. **Rotas** - Endpoints para acessar funcionalidades
7. **Menu** - Integrado no menu de configurações
8. **Exemplo de Integração** - MovimentacaoController com logs

### 📊 Dados Capturados

- **Usuário**: ID, nome, email
- **Empresa**: ID e nome da empresa atual
- **Ação**: CREATE, UPDATE, DELETE, VIEW, LOGIN, LOGOUT, etc.
- **Modelo**: Tipo do modelo alterado
- **Registro**: ID e nome do registro
- **Alterações**: Valores antes e depois
- **Contexto**: IP, User Agent, URL
- **Metadados**: Informações adicionais

## Como Usar

### 1. Registrar Criação
```php
use App\Services\AuditService;

// No controller, após criar um registro
AuditService::logCreate($model, "Descrição da ação");
```

### 2. Registrar Atualização
```php
// Salvar valores antigos antes da atualização
$oldValues = $model->getAttributes();

// Após a atualização
AuditService::logUpdate($model, $oldValues, "Descrição da ação");
```

### 3. Registrar Exclusão
```php
// Antes de deletar
AuditService::logDelete($model, "Descrição da ação");
$model->delete();
```

### 4. Registrar Visualização
```php
AuditService::logView($model, "Descrição da ação");
```

### 5. Registrar Login/Logout
```php
// No processo de login
AuditService::logLogin($user);

// No processo de logout
AuditService::logLogout($user);
```

### 6. Registrar Troca de Empresa
```php
AuditService::logCompanySwitch($empresa);
```

### 7. Registrar Ação Customizada
```php
AuditService::logCustom(
    'EXPORT',
    'Exportou relatório de vendas',
    'Relatorio',
    null,
    'Relatório de Vendas',
    ['total_registros' => 150]
);
```

## Acessando o Histórico

### Menu do Sistema
- **Configurações** → **Histórico de Alterações**

### URLs Disponíveis
- `/historico-alteracoes` - Lista todos os logs
- `/historico-alteracoes/{id}` - Detalhes de um log
- `/historico-alteracoes/export/csv` - Exportar para CSV
- `/historico-alteracoes/stats` - Estatísticas
- `/historico-alteracoes/usuario/{userId}` - Logs por usuário
- `/historico-alteracoes/modelo/{modelType}/{modelId?}` - Logs por modelo

## Filtros Disponíveis

- **Usuário**: Filtrar por usuário específico
- **Ação**: Filtrar por tipo de ação
- **Modelo**: Filtrar por tipo de modelo
- **Período**: Filtrar por data início/fim
- **Descrição**: Buscar por texto na descrição

## Permissões Necessárias

Para acessar o histórico, o usuário precisa ter permissão:
- `audit.listar` - Para visualizar lista
- `audit.visualizar` - Para ver detalhes
- `audit.exportar` - Para exportar dados

## Integração em Outros Controllers

Para integrar o audit log em outros controllers:

1. **Importar o AuditService**:
```php
use App\Services\AuditService;
```

2. **Adicionar logs nos métodos**:
```php
// CREATE
AuditService::logCreate($model, "Descrição");

// UPDATE
$oldValues = $model->getAttributes();
$model->update($data);
AuditService::logUpdate($model, $oldValues, "Descrição");

// DELETE
AuditService::logDelete($model, "Descrição");
$model->delete();

// VIEW
AuditService::logView($model, "Descrição");
```

## Exemplo Completo

```php
<?php

namespace App\Http\Controllers;

use App\Services\AuditService;
use App\Models\MinhaModel;

class MeuController extends Controller
{
    public function store(Request $request)
    {
        $model = MinhaModel::create($request->all());
        
        // Registrar criação
        AuditService::logCreate($model, "Criado registro: {$model->nome}");
        
        return redirect()->back()->with('success', 'Criado com sucesso!');
    }

    public function update(Request $request, MinhaModel $model)
    {
        // Salvar valores antigos
        $oldValues = $model->getAttributes();
        
        // Atualizar
        $model->update($request->all());
        
        // Registrar atualização
        AuditService::logUpdate($model, $oldValues, "Atualizado registro: {$model->nome}");
        
        return redirect()->back()->with('success', 'Atualizado com sucesso!');
    }

    public function destroy(MinhaModel $model)
    {
        // Registrar exclusão
        AuditService::logDelete($model, "Excluído registro: {$model->nome}");
        
        $model->delete();
        
        return redirect()->back()->with('success', 'Excluído com sucesso!');
    }

    public function show(MinhaModel $model)
    {
        // Registrar visualização
        AuditService::logView($model, "Visualizou registro: {$model->nome}");
        
        return view('minha-view', compact('model'));
    }
}
```

## Benefícios

1. **Auditoria Completa**: Rastreamento de todas as alterações
2. **Responsabilidade**: Saber quem fez cada alteração
3. **Segurança**: Monitoramento de acessos e mudanças
4. **Compliance**: Atendimento a requisitos de auditoria
5. **Debugging**: Facilita identificação de problemas
6. **Relatórios**: Dados para análises e relatórios

## Próximos Passos

Para expandir o sistema:

1. **Middleware Automático**: Capturar alterações automaticamente
2. **Notificações**: Alertas para ações críticas
3. **Relatórios**: Dashboards com estatísticas avançadas
4. **Backup**: Sistema de backup dos logs
5. **Retenção**: Política de retenção de dados
6. **API**: Endpoints para integração externa

## Manutenção

- **Limpeza**: Implementar limpeza automática de logs antigos
- **Performance**: Monitorar performance das consultas
- **Storage**: Considerar arquivamento de logs antigos
- **Indexação**: Otimizar índices da tabela conforme uso
