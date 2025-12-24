# JAMEES - Documentação Completa do Sistema para Criação de Site

## 📋 Índice

1. [Visão Geral](#visão-geral)
2. [Identidade Visual e Branding](#identidade-visual-e-branding)
3. [Arquitetura e Tecnologias](#arquitetura-e-tecnologias)
4. [Módulos e Funcionalidades](#módulos-e-funcionalidades)
5. [Sistema de Planos](#sistema-de-planos)
6. [Aplicativos Modulares](#aplicativos-modulares)
7. [Recursos e Diferenciais](#recursos-e-diferenciais)
8. [Público-Alvo](#público-alvo)
9. [Fluxos Principais](#fluxos-principais)
10. [Estrutura de Dados](#estrutura-de-dados)
11. [Segurança e Compliance](#segurança-e-compliance)
12. [Design e UX](#design-e-ux)
13. [Conteúdo para Site](#conteúdo-para-site)

---

## 🎯 Visão Geral

### O que é o JAMEES?

O **JAMEES** é um sistema completo de gestão empresarial desenvolvido em Laravel 12, projetado para pequenas e médias empresas que buscam automatizar processos e ter controle total sobre suas operações financeiras, vendas, estoque e relacionamento com clientes.

### Propósito Principal

O sistema foi criado para ajudar empresas a **vender mais e se preocupar menos**, oferecendo uma plataforma única que centraliza todas as operações empresariais, desde o controle financeiro até a gestão de relacionamento com clientes e fornecedores.

### Missão

Fornecer uma solução completa, acessível e intuitiva de gestão empresarial que permita aos empreendedores focarem no que realmente importa: fazer seu negócio crescer.

### Valores

- **Simplicidade**: Interface intuitiva e fácil de usar
- **Confiabilidade**: Sistema robusto e seguro
- **Flexibilidade**: Adaptável às necessidades de cada empresa
- **Inovação**: Sempre evoluindo com novas funcionalidades
- **Transparência**: Sem fidelidade, sem pegadinhas

---

## 🎨 Identidade Visual e Branding

### Logo

#### Descrição da Logo

A logo do JAMEES é composta pelo texto "JAMEES" em estilo tipográfico moderno e dinâmico.

**Nome da Logo:**
- **Texto**: JAMEES (sempre em maiúsculas)
- **Formato**: Apenas texto, sem símbolo ou ícone adicional

#### Fonte da Logo

- **Família da Fonte**: Roboto (Google Fonts)
- **Peso**: Bold (700)
- **Estilo**: Italic (itálico)
- **Nome Completo**: Roboto Bold Italic
- **Fonte Alternativa**: Caso Roboto não esteja disponível, usar fonte sans-serif genérica com as mesmas características (bold + italic)

#### Características Tipográficas

- **Tamanho Padrão**: 48px (variável conforme contexto de uso)
- **Estilo Visual**: Texto em maiúsculas com estilo itálico para dar movimento e modernidade
- **Espaçamento**: Espaçamento padrão da fonte (letter-spacing normal)
- **Características**:
  - Texto sempre em maiúsculas: JAMEES
  - Estilo itálico para transmitir movimento e modernidade
  - Fonte Roboto para manter consistência com o sistema
  - Peso bold para garantir legibilidade e destaque

#### Cores da Logo

**Cor Principal (Backgrounds Claros):**
- **Cor**: `#1e40af` (Azul primary-800 do Tailwind CSS)
- **RGB**: rgb(30, 64, 175)
- **Uso**: Cor padrão da logo em backgrounds claros ou brancos
- **Aplicação**: Texto da logo em cor sólida

**Cor Alternativa (Backgrounds Escuros):**
- **Cor**: `#ffffff` (Branco)
- **RGB**: rgb(255, 255, 255)
- **Uso**: Cor da logo em backgrounds escuros ou coloridos
- **Text-shadow**: `0 2px 4px rgba(0,0,0,0.3)` (sombra de texto para melhor legibilidade em backgrounds escuros)

#### Especificações Técnicas

**CSS para Logo (Exemplo):**
```css
.logo {
    font-family: 'Roboto', sans-serif;
    font-weight: 700; /* Bold */
    font-style: italic;
    font-size: 48px;
    text-transform: uppercase;
    color: #1e40af; /* Cor principal */
    letter-spacing: normal;
}

.logo.dark-bg {
    color: #ffffff;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}
```

**HTML para Logo:**
```html
<!-- Background claro -->
<span class="logo">JAMEES</span>

<!-- Background escuro -->
<span class="logo dark-bg">JAMEES</span>
```

#### Diretrizes de Uso

- **Tamanho Mínimo**: 24px (para manter legibilidade)
- **Tamanho Recomendado**: 48px (uso padrão)
- **Tamanho Máximo**: Sem limite, mas manter proporções
- **Espaçamento**: Manter espaçamento adequado ao redor da logo
- **Área de Proteção**: Mínimo de 20% do tamanho da logo ao redor
- **Não Fazer**:
  - Não usar em minúsculas
  - Não remover o estilo itálico
  - Não alterar a cor principal sem necessidade
  - Não adicionar efeitos que comprometam a legibilidade
  - Não distorcer ou esticar a logo

### Paleta de Cores

#### Cores Principais
- **Azul Primário (JAMEES Blue)**: `#2563eb`
  - Uso: Botões primários, links, destaques principais
- **Verde (JAMEES Green)**: `#10b981`
  - Uso: Sucesso, confirmações, valores positivos
- **Roxo (JAMEES Purple)**: `#8b5cf6`
  - Uso: Elementos especiais, badges, destaques secundários

#### Cores da Logo
> **Nota**: Para informações detalhadas sobre a logo, consulte a seção "Logo" acima.

- **Cor Principal**: `#1e40af` (Azul primary-800 do Tailwind) - Usada em backgrounds claros
- **Cor Alternativa**: `#ffffff` (Branco) - Usada em backgrounds escuros
- **Text-shadow** (em backgrounds escuros): `0 2px 4px rgba(0,0,0,0.3)`

#### Cores Neutras
- **Background claro**: `#f9fafb`
- **Texto secundário**: `#6b7280`
- **Texto principal**: `#1f2937`
- **Bordas**: `#e5e7eb`

#### Cores de Destaque
- **Amarelo (CTAs)**: `#fbbf24`
  - Uso: Botões de ação principal, destaques importantes

### Tipografia

- **Família Principal**: Roboto (Google Fonts)
- **Títulos**: Roboto Bold
- **Corpo**: Roboto Regular
- **Logo**: Roboto Bold Italic
- **Hierarquia de Tamanhos**:
  - H1: 3rem (48px)
  - H2: 2.25rem (36px)
  - H3: 1.875rem (30px)
  - H4: 1.5rem (24px)
  - Corpo: 1rem (16px)
  - Pequeno: 0.875rem (14px)

---

## 🏗️ Arquitetura e Tecnologias

### Backend

- **Framework**: Laravel 12 (PHP 8.2+)
- **Banco de Dados**: MySQL/MariaDB (com suporte a SQLite para desenvolvimento)
- **ORM**: Eloquent ORM (Mapeamento objeto-relacional)
- **Autenticação API**: Laravel Sanctum
- **Integração Frontend**: Inertia.js (SPA sem necessidade de API REST)
- **Envio de E-mails**: PHPMailer
- **Autenticação JWT**: Firebase JWT (para APIs externas)
- **UUID**: Ramsey UUID Doctrine (chaves primárias UUID)

### Frontend

- **Framework JavaScript**: Vue.js 3 (Composition API)
- **Framework CSS**: Tailwind CSS 3
- **SPA**: Inertia.js (integração seamless entre Laravel e Vue)
- **Gráficos**: ApexCharts (visualizações e dashboards)
- **Ícones**: Font Awesome 6
- **Build Tool**: Vite 7
- **Formulários**: Tailwind Forms Plugin
- **Tipografia**: Tailwind Typography Plugin

### Infraestrutura

- **Multi-tenant**: Sistema multi-empresa nativo
- **Whitelabel**: Personalização visual por empresa
- **Permissões**: Sistema granular baseado em grupos
- **Auditoria**: Log completo de ações (Audit Log)
- **Planos**: Sistema de assinaturas e planos
- **Aplicativos**: Arquitetura modular (plugins)
- **Sessões**: Gerenciamento por empresa
- **Cache**: Sistema de cache integrado

### Ferramentas de Desenvolvimento

- **Testes**: PHPUnit 11
- **Code Style**: Laravel Pint
- **Logs**: Laravel Pail
- **Docker**: Laravel Sail (opcional)

---

## 📦 Módulos e Funcionalidades

### 1. Módulo Financeiro

#### Dashboard Financeiro
- Visão geral de contas a pagar/receber
- Fluxo de caixa em tempo real
- Saldos bancários consolidados
- Gráficos interativos (ApexCharts)
- Métricas financeiras principais
- Alertas de vencimentos próximos

#### Contas a Pagar
- Cadastro completo de despesas
- Geração automática de parcelas
- Controle de vencimentos
- Histórico de pagamentos
- Confirmação de pagamento via JWT
- Filtros avançados (data, status, fornecedor, etc.)
- Exportação de relatórios

#### Contas a Receber
- Cadastro de recebimentos
- Controle de cobranças
- Geração de parcelas
- Histórico de recebimentos
- Confirmação de recebimento
- Filtros avançados
- Exportação de relatórios

#### DRE (Demonstração do Resultado do Exercício)
- Geração automática de DRE
- Categorização por tipo (Receita, Despesa, etc.)
- Períodos configuráveis
- Exportação em PDF
- Visualização hierárquica

#### Plano de Contas
- Estrutura hierárquica de contas contábeis
- Categorização por DRE
- Códigos de ordenação (pai/filho)
- Busca inteligente
- Validação de estrutura

#### Centro de Custos
- Divisão de custos por departamento/projeto
- Controle de status (ativo/inativo)
- Relacionamento com movimentações
- Relatórios por centro de custo

#### Contas Bancárias
- Cadastro de múltiplas contas
- Integração com banco de dados de bancos (143 bancos cadastrados)
- Tipos de conta (Corrente, Poupança, etc.)
- Controle de saldos
- Histórico de transações

#### Formas de Pagamento
- Cadastro de métodos de pagamento
- Modalidades (Dinheiro, Cartão, PIX, etc.)
- Controle de disponibilidade
- Integração com movimentações

#### Conciliação Bancária
- Importação de arquivos OFX
- Vinculação automática de transações
- Criação de movimentações a partir de transações
- Controle de conciliação

### 2. Módulo de Cadastros

#### Clientes
- Cadastro completo (nome, documento, email, telefone)
- Múltiplos contatos por cliente
- Múltiplos endereços por cliente
- Histórico de transações
- Status (ativo/inativo)
- Busca avançada
- Exportação de dados

#### Fornecedores
- Cadastro completo
- Múltiplos contatos
- Múltiplos endereços
- Histórico de compras
- Status (ativo/inativo)
- Busca avançada

#### Funcionários
- Cadastro completo
- Múltiplos contatos
- Múltiplos endereços
- Histórico de relacionamento
- Status (ativo/inativo)

#### Transportadoras
- Cadastro completo
- Múltiplos contatos
- Múltiplos endereços
- Integração com entregas
- Status (ativo/inativo)

#### Entidades (Sistema Unificado)
- Sistema polimórfico para diferentes tipos de entidades
- Reutilização de código
- Contatos e endereços unificados
- Histórico centralizado

### 3. Aplicativo de Loteamento

#### Empreendimentos
- Cadastro de empreendimentos
- Informações completas (nome, descrição, localização)
- Upload de imagens
- Mapa interativo com posicionamento de pinos
- Gestão de quadras e lotes

#### Quadras
- Cadastro de quadras dentro de empreendimentos
- Organização hierárquica
- Visualização no mapa

#### Lotes
- Cadastro individual ou em massa (importação CSV)
- Status de lotes (disponível, reservado, vendido)
- Informações detalhadas (área, preço, localização)
- Histórico de reservas
- Comentários por lote
- Integração com movimentações financeiras

#### Vendas de Lotes
- Processo completo de venda
- Geração automática de parcelas
- Vinculação com clientes
- Integração financeira
- Histórico de vendas

#### Reservas de Lotes
- Sistema de reserva
- Controle de prazo de reserva
- Histórico de reservas
- Comentários por cliente

#### Mapa Interativo
- Visualização de empreendimentos
- Posicionamento de pinos
- Navegação entre quadras e lotes
- Filtros por status

### 4. Gestão de Usuários e Permissões

#### Usuários
- Cadastro completo
- Múltiplos endereços
- Múltiplos telefones
- Vinculação a múltiplas empresas
- Empresa principal configurável
- Status (ativo/inativo)
- Horários de acesso configuráveis

#### Grupos
- Criação de grupos de usuários
- Permissões por grupo
- Grupos administrativos (acesso total)
- Status (ativo/inativo)

#### Permissões
- Sistema granular de permissões
- Permissões por módulo e ação (listar, criar, editar, deletar, visualizar)
- Controle fino de acesso
- Herança de permissões

#### Horários de Acesso
- Configuração de horários permitidos
- Controle por usuário
- Bloqueio automático fora do horário

### 5. Dashboard e Relatórios

#### Dashboard Principal
- Visão geral do negócio
- Configurações personalizáveis
- Vídeo institucional (YouTube)
- Logo da empresa
- Texto de boas-vindas
- Últimas atualizações do sistema
- Ideias em desenvolvimento

#### Dashboard Financeiro
- Métricas financeiras em tempo real
- Gráficos interativos
- Contas a pagar/receber
- Fluxo de caixa
- Saldos bancários

#### Relatórios
- Relatórios de cadastros (Clientes, Fornecedores, etc.)
- Relatórios financeiros
- Exportação em CSV
- Filtros avançados
- Histórico de alterações (Audit Log)

### 6. Configurações

#### Configurações Gerais
- Limite de registros por página
- Logo da empresa
- Texto de boas-vindas
- Frase da empresa
- Vídeo institucional
- Título do vídeo

#### Meus Dados
- Edição de perfil
- Alteração de senha
- Dados pessoais
- Endereços e telefones

#### Troca de Empresa
- Multi-empresa com troca de contexto
- Seleção de empresa atual
- Sessão por empresa
- Whitelabel por empresa

#### Aplicativos
- Listagem de aplicativos disponíveis
- Contratação de aplicativos
- Gestão de aplicativos contratados
- Cancelamento de aplicativos

### 7. Portal de Ideias

#### Ideias
- Sistema colaborativo de ideias
- Criação de ideias pelos usuários
- Comentários em ideias
- Sistema de votação
- Status (Em análise, Em desenvolvimento, Implementada, etc.)
- Acompanhamento de ideias

### 8. Atualizações do Sistema

#### Atualizações
- Listagem de atualizações
- Filtro por tipo (Novos Recursos / Melhorias)
- Visualização detalhada
- Conteúdo HTML rico
- Data de publicação

---

## 💳 Sistema de Planos

### Tipos de Planos

#### 1. Plano de Teste
- **Duração**: 10 dias grátis
- **Preço**: R$ 0,00
- **Limite de usuários**: 999 (praticamente ilimitado)
- **Limite de empresas**: 999 (praticamente ilimitado)
- **Funcionalidades**: Acesso total ao sistema
- **Suporte**: Completo durante o período de teste

#### 2. Plano Base
- **Preço Mensal**: R$ 29,90
- **Preço Trimestral**: R$ 79,90 (5% desconto)
- **Preço Semestral**: R$ 149,90 (10% desconto)
- **Preço Anual**: R$ 269,90 (20% desconto)
- **Limite de usuários**: 5
- **Limite de empresas**: 1
- **Funcionalidades**:
  - Gestão básica de usuários
  - Gestão básica de empresas
  - Relatórios simples
  - Suporte por email

#### 3. Plano Premium
- **Preço Mensal**: R$ 59,90
- **Preço Trimestral**: R$ 159,90 (5% desconto)
- **Preço Semestral**: R$ 299,90 (10% desconto)
- **Preço Anual**: R$ 539,90 (20% desconto)
- **Limite de usuários**: 15
- **Limite de empresas**: 3
- **Funcionalidades**:
  - Todas as funcionalidades do Plano Base
  - Gestão avançada de usuários
  - Gestão avançada de empresas
  - Relatórios avançados
  - Integração com APIs
  - Suporte prioritário

#### 4. Plano Master
- **Preço Mensal**: R$ 99,90
- **Preço Trimestral**: R$ 269,90 (5% desconto)
- **Preço Semestral**: R$ 509,90 (10% desconto)
- **Preço Anual**: R$ 919,90 (20% desconto)
- **Limite de usuários**: 50
- **Limite de empresas**: 10
- **Funcionalidades**:
  - Todas as funcionalidades do Plano Premium
  - Gestão ilimitada de usuários
  - Gestão ilimitada de empresas
  - Relatórios personalizados
  - Integração completa com APIs
  - Suporte 24/7
  - Backup automático
  - SLA garantido

#### 5. Plano Personalizado
- **Preço**: Sob consulta
- **Limite de usuários**: Ilimitado
- **Limite de empresas**: Ilimitado
- **Funcionalidades**:
  - Funcionalidades personalizadas
  - Desenvolvimento sob demanda
  - Integração customizada
  - Suporte dedicado
  - Consultoria especializada

### Períodos de Assinatura

- **Mensal**: Pagamento mensal
- **Trimestral**: Pagamento trimestral (5% desconto)
- **Semestral**: Pagamento semestral (10% desconto)
- **Anual**: Pagamento anual (20% desconto)

### Características dos Planos

- **Sem fidelidade**: Cancele quando quiser
- **Teste grátis**: 10 dias para testar todas as funcionalidades
- **Upgrade/Downgrade**: Possibilidade de alterar plano a qualquer momento
- **Cancelamento**: Cancelamento a qualquer momento
- **Histórico**: Acompanhamento completo de assinaturas

---

## 📱 Aplicativos Modulares

### Sistema de Aplicativos

Os aplicativos são módulos adicionais que podem ser contratados separadamente, permitindo que cada empresa personalize o sistema conforme suas necessidades.

### Aplicativo de Loteamento

#### Descrição
Sistema completo para gestão de loteamentos, vendas e reservas de lotes.

#### Funcionalidades
- Gestão de empreendimentos
- Controle de quadras e lotes
- Status de lotes (disponível, reservado, vendido)
- Mapa interativo
- Vendas com geração de parcelas
- Sistema de reservas
- Comentários por lote
- Integração com movimentações financeiras
- Importação em massa de lotes (CSV)

#### Preços
- **Mensal**: Conforme configuração
- **Trimestral**: Conforme configuração
- **Semestral**: Conforme configuração
- **Anual**: Conforme configuração

#### Período
O período do aplicativo deve corresponder ao período do plano principal.

### Outros Aplicativos

O sistema está preparado para receber novos aplicativos no futuro, mantendo a arquitetura modular.

---

## ✨ Recursos e Diferenciais

### Diferenciais Principais

1. **Completo e Acessível**
   - Todas as funcionalidades essenciais no plano básico
   - Preços competitivos
   - Sem custos ocultos

2. **Sem Fidelidade**
   - Cancele quando quiser
   - Sem multas ou taxas de cancelamento
   - Flexibilidade total

3. **Teste Grátis**
   - 10 dias para testar todas as funcionalidades
   - Acesso completo durante o período de teste
   - Sem necessidade de cartão de crédito

4. **Sistema Modular**
   - Aplicativos adicionais contratáveis
   - Personalização conforme necessidade
   - Expansão gradual

5. **Multi-Empresa Nativo**
   - Gestão de múltiplas empresas em uma conta
   - Troca de contexto fácil
   - Isolamento de dados por empresa

6. **Interface Moderna e Intuitiva**
   - Design limpo e profissional
   - Fácil de usar
   - Totalmente responsivo

7. **Suporte Especializado**
   - Suporte por email (todos os planos)
   - Suporte prioritário (planos Premium e Master)
   - Suporte 24/7 (plano Master)

8. **Sistema de Permissões Avançado**
   - Controle granular de acesso
   - Grupos de usuários
   - Permissões por módulo e ação

9. **Auditoria Completa**
   - Log de todas as ações
   - Histórico de alterações
   - Rastreabilidade total

10. **API RESTful**
    - Integração com outros sistemas
    - Autenticação JWT
    - Documentação completa

### Recursos Técnicos

- **Performance**: Sistema otimizado para alta performance
- **Segurança**: Criptografia, autenticação robusta, isolamento de dados
- **Backup**: Backup automático (plano Master)
- **Escalabilidade**: Arquitetura preparada para crescimento
- **Atualizações**: Atualizações regulares com novas funcionalidades
- **Portal de Ideias**: Sistema colaborativo para sugestões

---

## 🎯 Público-Alvo

### Segmentos Principais

1. **Pequenas e Médias Empresas**
   - Empresas que precisam de controle financeiro completo
   - Negócios em crescimento
   - Empresas que buscam automação

2. **Empreendedores Individuais**
   - Profissionais autônomos
   - Microempresas
   - MEIs

3. **Empresas de Loteamento**
   - Construtoras
   - Incorporadoras
   - Empresas imobiliárias

4. **Empresas com Múltiplas Unidades**
   - Franquias
   - Redes de lojas
   - Grupos empresariais

5. **Empresas que Precisam de Controle Financeiro**
   - Controle de contas a pagar/receber
   - Gestão de fluxo de caixa
   - Relatórios financeiros

6. **Negócios que Buscam Automação**
   - Redução de processos manuais
   - Integração de sistemas
   - Automação de rotinas

---

## 🔄 Fluxos Principais

### 1. Fluxo de Registro e Ativação

1. **Registro**
   - Usuário acessa o site
   - Preenche formulário de registro
   - Confirma email (opcional)

2. **Seleção de Plano**
   - Visualiza planos disponíveis
   - Escolhe plano (ou inicia teste grátis)
   - Configura período de assinatura

3. **Ativação**
   - Plano é ativado automaticamente
   - Teste grátis: 10 dias de acesso total
   - Plano pago: ativação após pagamento

4. **Dashboard**
   - Acesso ao dashboard principal
   - Configurações iniciais
   - Primeiros passos

### 2. Fluxo de Uso Diário

1. **Login**
   - Autenticação
   - Seleção de empresa (se múltiplas)
   - Dashboard principal

2. **Navegação**
   - Acesso aos módulos (Financeiro, Cadastros, etc.)
   - Menu lateral expansível
   - Busca rápida

3. **Operações**
   - Criação de registros
   - Edição de dados
   - Visualização de relatórios
   - Exportação de dados

4. **Logout**
   - Encerramento de sessão
   - Segurança de dados

### 3. Fluxo de Contratação de Aplicativos

1. **Visualização**
   - Acesso à página de aplicativos
   - Visualização de aplicativos disponíveis
   - Detalhes de cada aplicativo

2. **Seleção**
   - Escolha do aplicativo
   - Visualização de preços
   - Seleção de período

3. **Pagamento**
   - Processamento de pagamento
   - Validação de período (deve corresponder ao plano)

4. **Ativação**
   - Aplicativo é ativado automaticamente
   - Aparece no menu
   - Pronto para uso

### 4. Fluxo de Gestão de Permissões

1. **Criação de Grupo**
   - Administrador cria grupo
   - Define permissões por módulo/ação
   - Salva configurações

2. **Vinculação de Usuários**
   - Usuários são adicionados ao grupo
   - Herdam permissões do grupo
   - Permissões individuais (opcional)

3. **Controle de Acesso**
   - Sistema verifica permissões
   - Bloqueia ações não permitidas
   - Registra tentativas de acesso

---

## 📊 Estrutura de Dados

### Principais Entidades

#### Usuários e Autenticação
- **usuarios**: Usuários do sistema
- **usuario_empresa**: Relacionamento usuário-empresa (many-to-many)
- **usuario_grupo**: Relacionamento usuário-grupo (many-to-many)
- **usuario_endereco**: Endereços dos usuários
- **usuario_telefone**: Telefones dos usuários
- **usuario_horario_acesso**: Horários de acesso configuráveis

#### Empresas e Multi-tenant
- **empresa**: Empresas cadastradas
- **whitelabel**: Configurações de whitelabel por empresa
- **empresa_contato**: Contatos das empresas
- **empresa_endereco**: Endereços das empresas

#### Planos e Assinaturas
- **planos**: Planos disponíveis
- **empresa_plano**: Histórico de assinaturas (many-to-many com histórico)
- **aplicativos**: Aplicativos modulares
- **empresa_aplicativo**: Aplicativos contratados por empresa (many-to-many)

#### Financeiro
- **movimentacoes**: Contas a pagar/receber
- **plano_conta**: Plano de contas contábeis
- **dre**: Categorias DRE
- **centro_custo**: Centros de custo
- **conta_empresa**: Contas bancárias
- **forma_pagamento**: Formas de pagamento
- **banco**: Banco de dados de bancos (143 bancos)
- **conciliacao_bancaria**: Conciliações bancárias
- **transacao_ofx**: Transações importadas de arquivos OFX

#### Cadastros
- **clientes**: Clientes
- **cliente_contato**: Contatos dos clientes
- **cliente_endereco**: Endereços dos clientes
- **fornecedores**: Fornecedores
- **fornecedor_contato**: Contatos dos fornecedores
- **fornecedor_endereco**: Endereços dos fornecedores
- **funcionarios**: Funcionários
- **funcionario_contato**: Contatos dos funcionários
- **funcionario_endereco**: Endereços dos funcionários
- **transportadoras**: Transportadoras
- **transportadora_contato**: Contatos das transportadoras
- **transportadora_endereco**: Endereços das transportadoras
- **entidades**: Sistema unificado de entidades (polymorphic)

#### Loteamento
- **empreendimentos**: Empreendimentos
- **quadras**: Quadras dentro de empreendimentos
- **lotes**: Lotes dentro de quadras
- **lote_status**: Status de lotes
- **lote_comentario**: Comentários em lotes
- **lote_reserva_historico**: Histórico de reservas
- **lote_venda_parcela**: Parcelas de vendas de lotes

#### Gestão e Configurações
- **grupos**: Grupos de usuários
- **permissoes**: Permissões do sistema
- **grupo_permissao**: Relacionamento grupo-permissão (many-to-many)
- **configuracoes**: Configurações por empresa
- **audit_log**: Log de auditoria
- **atualizacoes_sistema**: Atualizações do sistema
- **ideias**: Portal de ideias
- **ideia_comentario**: Comentários em ideias

### Relacionamentos Principais

- **Usuário ↔ Empresa**: Many-to-many (com pivot: principal, status)
- **Empresa ↔ Plano**: Many-to-many (com histórico: data_inicio, data_fim, status)
- **Empresa ↔ Aplicativo**: Many-to-many
- **Empresa ↔ Cliente/Fornecedor/Funcionário/Transportadora**: One-to-many
- **Movimentação ↔ Entidade**: Polymorphic (cliente, fornecedor, etc.)
- **Lote ↔ Movimentação**: One-to-many (vendas de lotes)
- **Usuário ↔ Grupo**: Many-to-many
- **Grupo ↔ Permissão**: Many-to-many (com pivot: concedida)

### Características Técnicas

- **Chaves Primárias**: UUID (string)
- **Timestamps**: created_at, updated_at em todas as tabelas
- **Soft Deletes**: Em várias tabelas (usuarios, empresas, etc.)
- **Casts**: Enums, arrays, dates, decimals
- **Índices**: Otimizados para performance

---

## 🔐 Segurança e Compliance

### Autenticação

- **Laravel Breeze**: Sistema de autenticação robusto
- **Laravel Sanctum**: Autenticação para APIs
- **JWT**: Tokens JWT para integrações externas
- **Rate Limiting**: Proteção contra ataques de força bruta
- **Senhas**: Hash bcrypt
- **Sessões**: Gerenciamento seguro de sessões

### Permissões

- **Sistema Granular**: Permissões por módulo e ação
- **Grupos**: Organização de permissões por grupos
- **Herança**: Permissões administrativas
- **Middleware**: Verificação em todas as rotas protegidas

### Isolamento de Dados

- **Multi-tenant**: Isolamento completo por empresa
- **Scopes**: Filtros automáticos por empresa
- **Validação**: Verificação de acesso em todas as operações

### Auditoria

- **Audit Log**: Registro de todas as ações críticas
- **Rastreabilidade**: Histórico completo de alterações
- **Usuário**: Identificação do usuário em cada ação
- **Timestamp**: Data e hora de cada ação
- **Dados Antigos/Novos**: Comparação de alterações

### Horários de Acesso

- **Configurável**: Horários permitidos por usuário
- **Bloqueio Automático**: Acesso negado fora do horário
- **Flexibilidade**: Múltiplos horários por usuário

### Proteção de Dados

- **Criptografia**: Dados sensíveis criptografados
- **Backup**: Backup automático (plano Master)
- **Integridade**: Validação de integridade de dados
- **CSRF**: Proteção contra CSRF em formulários
- **XSS**: Proteção contra XSS em inputs

---

## 🎨 Design e UX

### Layout

- **Sidebar Lateral**: Menu expansível/colapsável
- **Header Fixo**: Cabeçalho sempre visível
- **Conteúdo Centralizado**: Área principal de conteúdo
- **Responsivo**: Adaptação para mobile, tablet e desktop

### Componentes

- **Cards**: Informações em cards
- **Tabelas**: Tabelas responsivas com scroll horizontal
- **Modais**: Ações em modais
- **Formulários**: Formulários com validação visual
- **Botões**: Estados hover/active/focus
- **Badges**: Tags e badges informativos
- **Alerts**: Alertas e notificações
- **Toasts**: Feedback visual de ações

### Estados Visuais

- **Loading**: Estados de carregamento
- **Empty States**: Estados vazios com mensagens
- **Error States**: Tratamento de erros
- **Success States**: Confirmações de sucesso

### Acessibilidade

- **WCAG 2.1**: Conformidade com padrões de acessibilidade
- **Navegação por Teclado**: Suporte completo
- **Screen Readers**: Compatibilidade com leitores de tela
- **Contraste**: Contraste adequado de cores
- **Foco Visual**: Indicadores de foco claros

### Responsividade

- **Mobile-First**: Design mobile-first
- **Breakpoints**: 
  - Mobile: < 640px
  - Tablet: 640px - 1024px
  - Desktop: > 1024px
- **Menu Mobile**: Menu hamburger em mobile
- **Tabelas Mobile**: Scroll horizontal em tabelas
- **Formulários Mobile**: Otimizados para touch

### Performance

- **Lazy Loading**: Carregamento sob demanda
- **Otimização de Imagens**: Compressão e formatos otimizados
- **Cache**: Sistema de cache integrado
- **Minificação**: CSS e JS minificados em produção
- **CDN**: Suporte a CDN para assets estáticos

---

## 📝 Conteúdo para Site

### Hero Section

**Título Principal:**
"Sistema de gestão empresarial para vender mais e se preocupar menos"

**Subtítulo:**
"Controle financeiro, vendas, estoque e muito mais em uma única plataforma"

**CTAs:**
- Primário: "Comece Grátis Agora" (amarelo #fbbf24)
- Secundário: "Ver Planos" (outline azul)

**Badge:**
"✓ Teste grátis por 10 dias ✓ Sem fidelidade ✓ Suporte especializado"

**Imagem/Ilustração:**
Dashboard do sistema ou animação mostrando as principais funcionalidades

### Seção de Recursos/Features

#### 1. Gestão Financeira Completa
- **Ícone**: fa-dollar-sign
- **Título**: "Controle Financeiro Total"
- **Descrição**: "Gerencie contas a pagar e receber, fluxo de caixa, DRE automática e muito mais"
- **Benefícios**:
  - Contas a pagar/receber organizadas
  - DRE automática
  - Fluxo de caixa em tempo real
  - Múltiplas contas bancárias
  - Conciliação bancária automática

#### 2. Cadastros Inteligentes
- **Ícone**: fa-address-book
- **Título**: "Cadastros Organizados"
- **Descrição**: "Clientes, fornecedores, funcionários e transportadoras em um só lugar"
- **Benefícios**:
  - Cadastro completo e organizado
  - Múltiplos contatos e endereços
  - Histórico de relacionamento
  - Busca inteligente
  - Exportação de dados

#### 3. Dashboard Inteligente
- **Ícone**: fa-chart-line
- **Título**: "Visão Geral do Negócio"
- **Descrição**: "Métricas e indicadores em tempo real para tomar decisões assertivas"
- **Benefícios**:
  - Métricas financeiras em tempo real
  - Gráficos interativos
  - Alertas de vencimentos
  - Personalização completa
  - Exportação de relatórios

#### 4. Sistema Modular
- **Ícone**: fa-puzzle-piece
- **Título**: "Aplicativos Adicionais"
- **Descrição**: "Contrate apenas os módulos que sua empresa precisa"
- **Benefícios**:
  - Loteamento completo
  - Novos aplicativos em desenvolvimento
  - Preços acessíveis
  - Ativação instantânea

#### 5. Multi-Empresa
- **Ícone**: fa-building
- **Título**: "Gestão de Múltiplas Empresas"
- **Descrição**: "Gerencie várias empresas em uma única conta"
- **Benefícios**:
  - Troca de contexto fácil
  - Isolamento de dados
  - Whitelabel por empresa
  - Economia de tempo

#### 6. Segurança e Permissões
- **Ícone**: fa-shield-alt
- **Título**: "Controle Total de Acesso"
- **Descrição**: "Sistema granular de permissões para sua equipe"
- **Benefícios**:
  - Permissões por módulo e ação
  - Grupos de usuários
  - Horários de acesso
  - Auditoria completa

### Seção "Como Funciona"

#### Passo 1: Registro
- **Ícone**: fa-user-plus
- **Título**: "Crie sua Conta"
- **Descrição**: "Registre-se gratuitamente em poucos minutos"

#### Passo 2: Escolha seu Plano
- **Ícone**: fa-crown
- **Título**: "Escolha seu Plano"
- **Descrição**: "Teste grátis por 10 dias ou escolha um plano que se adapte ao seu negócio"

#### Passo 3: Configure
- **Ícone**: fa-cog
- **Título**: "Configure seu Sistema"
- **Descrição**: "Personalize seu dashboard e comece a usar"

#### Passo 4: Use e Cresça
- **Ícone**: fa-rocket
- **Título**: "Use e Veja Resultados"
- **Descrição**: "Gerencie seu negócio de forma eficiente e veja os resultados"

### Seção de Planos e Preços

**Título**: "Planos que se adaptam ao seu negócio"

**Descrição**: "Escolha o plano ideal para sua empresa. Sem fidelidade, cancele quando quiser."

**Toggle de Período**: Mensal / Trimestral / Semestral / Anual

**Cards de Planos**: (Ver detalhes na seção "Sistema de Planos")

### Seção de Aplicativos

**Título**: "Aplicativos que expandem suas possibilidades"

**Descrição**: "Contrate módulos adicionais conforme sua necessidade"

**Card do Aplicativo de Loteamento**:
- **Nome**: Loteamento
- **Ícone**: fa-map
- **Descrição**: "Sistema completo para gestão de loteamentos, vendas e reservas de lotes"
- **Funcionalidades**:
  - Gestão de empreendimentos
  - Controle de quadras e lotes
  - Mapa interativo
  - Vendas com parcelas
  - Sistema de reservas
- **CTA**: "Saiba Mais"

### Seção de Depoimentos

**Título**: "O que nossos clientes dizem"

**Depoimentos Sugeridos** (exemplos - substituir por reais):

1. **Nome**: João Silva
   **Empresa**: Construtora ABC
   **Avaliação**: ⭐⭐⭐⭐⭐
   **Texto**: "O JAMEES transformou nossa gestão financeira. Agora temos controle total e podemos focar no que realmente importa."

2. **Nome**: Maria Santos
   **Empresa**: Loteamentos XYZ
   **Avaliação**: ⭐⭐⭐⭐⭐
   **Texto**: "O aplicativo de loteamento é incrível! Facilita muito nosso trabalho e nossos clientes adoram o mapa interativo."

3. **Nome**: Pedro Oliveira
   **Empresa**: Comércio 123
   **Avaliação**: ⭐⭐⭐⭐⭐
   **Texto**: "Interface intuitiva, suporte excelente e preço justo. Recomendo para qualquer empresa que busca organização."

### Seção Sobre/Quem Somos

**Título**: "Sobre o JAMEES"

**História**:
"O JAMEES nasceu da necessidade de oferecer uma solução completa de gestão empresarial acessível para pequenas e médias empresas. Desenvolvido com as mais modernas tecnologias, nosso sistema combina simplicidade, poder e flexibilidade."

**Missão**:
"Fornecer uma solução completa, acessível e intuitiva de gestão empresarial que permita aos empreendedores focarem no que realmente importa: fazer seu negócio crescer."

**Visão**:
"Ser a plataforma de gestão empresarial mais completa e acessível do mercado brasileiro."

**Valores**:
- **Simplicidade**: Interface intuitiva e fácil de usar
- **Confiabilidade**: Sistema robusto e seguro
- **Flexibilidade**: Adaptável às necessidades de cada empresa
- **Inovação**: Sempre evoluindo com novas funcionalidades
- **Transparência**: Sem fidelidade, sem pegadinhas

**Estatísticas** (exemplos - atualizar com dados reais):
- X empresas atendidas
- X usuários ativos
- X transações processadas
- X% de satisfação

### Seção FAQ

**Título**: "Perguntas Frequentes"

#### Categoria: Preços

**P: Quais são os planos disponíveis?**
R: Oferecemos 4 planos principais: Base (R$ 29,90/mês), Premium (R$ 59,90/mês), Master (R$ 99,90/mês) e Personalizado (sob consulta). Todos os planos incluem teste grátis de 10 dias.

**P: Há fidelidade?**
R: Não! Você pode cancelar a qualquer momento, sem multas ou taxas.

**P: Posso mudar de plano?**
R: Sim! Você pode fazer upgrade ou downgrade a qualquer momento.

**P: Há desconto para pagamento anual?**
R: Sim! Oferecemos 20% de desconto no pagamento anual em todos os planos.

#### Categoria: Funcionalidades

**P: O que está incluído no plano básico?**
R: O plano básico inclui gestão básica de usuários e empresas, relatórios simples e suporte por email.

**P: Posso contratar aplicativos adicionais?**
R: Sim! Oferecemos aplicativos modulares que podem ser contratados separadamente, como o aplicativo de Loteamento.

**P: O sistema funciona offline?**
R: Não, o JAMEES é uma aplicação web que requer conexão com a internet.

**P: Posso exportar meus dados?**
R: Sim! Você pode exportar relatórios e dados em formato CSV.

#### Categoria: Suporte

**P: Como funciona o suporte?**
R: Oferecemos suporte por email para todos os planos. Planos Premium e Master incluem suporte prioritário, e o plano Master inclui suporte 24/7.

**P: Há treinamento disponível?**
R: Sim! Oferecemos documentação completa e estamos sempre disponíveis para ajudar.

**P: Com que frequência o sistema é atualizado?**
R: Lançamos atualizações regularmente com novas funcionalidades e melhorias.

#### Categoria: Técnico

**P: Meus dados estão seguros?**
R: Sim! Utilizamos criptografia, backups automáticos e seguimos as melhores práticas de segurança.

**P: O sistema é compatível com quais navegadores?**
R: O JAMEES é compatível com os principais navegadores modernos: Chrome, Firefox, Safari e Edge.

**P: Há API disponível?**
R: Sim! Oferecemos API RESTful com autenticação JWT para integrações.

**P: Posso integrar com outros sistemas?**
R: Sim! Nossa API permite integração com outros sistemas e ferramentas.

### Seção de Contato

**Título**: "Fale Conosco"

**Formulário de Contato**:
- Nome (obrigatório)
- Email (obrigatório)
- Telefone (opcional)
- Assunto (obrigatório)
- Mensagem (obrigatório)
- CTA: "Enviar Mensagem"

**Informações de Contato**:
- **Email**: contato@jamees.com.br (exemplo)
- **Telefone**: (00) 0000-0000 (exemplo)
- **Endereço**: Rua Exemplo, 123 - Cidade/UF (exemplo)

**Redes Sociais** (exemplos):
- Facebook
- Instagram
- LinkedIn
- YouTube

### Footer

**Coluna 1: Sobre**
- Logo JAMEES
- Descrição breve
- Redes sociais

**Coluna 2: Produto**
- Recursos
- Planos
- Aplicativos
- Preços

**Coluna 3: Empresa**
- Sobre
- Blog (se houver)
- Carreiras (se houver)
- Contato

**Coluna 4: Legal**
- Termos de Uso
- Política de Privacidade
- LGPD
- Cookies

**Copyright**:
"© 2024 JAMEES. Todos os direitos reservados."

---

## 📞 Informações de Contato (Exemplos - Atualizar)

- **Email**: contato@jamees.com.br
- **Telefone**: (00) 0000-0000
- **WhatsApp**: (00) 00000-0000
- **Endereço**: Rua Exemplo, 123 - Cidade/UF - CEP 00000-000
- **Horário de Atendimento**: Segunda a Sexta, 9h às 18h

---

## 📅 Última Atualização

Este documento foi criado em dezembro de 2024 com base na análise completa do código-fonte do sistema JAMEES.

---

## 📝 Notas Finais

Este documento serve como referência completa para entender o sistema JAMEES e criar materiais de marketing, documentação técnica ou sites institucionais baseados nas características e funcionalidades identificadas no código-fonte.

**Importante**: 
- Atualizar informações de contato com dados reais
- Substituir depoimentos de exemplo por depoimentos reais
- Atualizar estatísticas com dados reais
- Ajustar preços e funcionalidades conforme necessário
- Incluir screenshots reais do sistema
- Adicionar vídeos demonstrativos se disponíveis

---

**Fim do Documento**

