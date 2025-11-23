# JAMEES - Sistema de Gestão Empresarial

## 📋 Descrição do Sistema

O **JAMEES** é um sistema completo de gestão empresarial desenvolvido em Laravel 12, projetado para pequenas e médias empresas que buscam automatizar processos e ter controle total sobre suas operações financeiras, vendas, estoque e relacionamento com clientes.

### 🎯 Propósito Principal

O sistema foi criado para ajudar empresas a **vender mais e se preocupar menos**, oferecendo uma plataforma única que centraliza todas as operações empresariais, desde o controle financeiro até a gestão de relacionamento com clientes e fornecedores.

### 🏗️ Arquitetura e Tecnologias

**Backend:**
- **Laravel 12** (PHP 8.2+)
- **MySQL/MariaDB** (Banco de dados)
- **Eloquent ORM** (Mapeamento objeto-relacional)
- **Laravel Sanctum** (Autenticação API)
- **Inertia.js** (Integração frontend-backend)
- **PHPMailer** (Envio de e-mails)
- **Firebase JWT** (Autenticação JWT)

**Frontend:**
- **Vue.js 3** (Framework JavaScript)
- **Tailwind CSS 3** (Framework CSS)
- **Inertia.js** (SPA sem API)
- **ApexCharts** (Gráficos e visualizações)
- **Font Awesome 6** (Ícones)
- **Vite** (Build tool)

**Infraestrutura:**
- Sistema multi-empresa (multi-tenant)
- Whitelabel (personalização por empresa)
- Sistema de permissões granular
- Auditoria de ações (Audit Log)
- Sistema de planos e assinaturas
- Aplicativos modulares (plugins)

### 🎨 Design e UX

- **Design System:** Interface moderna e responsiva
- **Cores Principais:**
  - Azul primário: `#2563eb` (JAMEES Blue)
  - Verde: `#10b981` (JAMEES Green)
  - Roxo: `#8b5cf6` (JAMEES Purple)
  - Logo: Azul `#1e40af` (primary-800)
- **Tipografia:** Roboto (Google Fonts)
- **Layout:** Sidebar lateral com menu expansível, header fixo, conteúdo centralizado
- **Responsividade:** Totalmente responsivo (mobile-first)

### 🎯 Logo e Identidade Visual

- **Nome da Logo:** JAMEES
- **Fonte da Logo:**
  - Família: **Roboto** (Google Fonts)
  - Peso: **Bold** (700)
  - Estilo: **Italic** (itálico)
  - Tamanho padrão: 48px (variável conforme contexto)
- **Cor da Logo:**
  - Cor principal: `#1e40af` (Azul primary-800 do Tailwind)
  - Cor alternativa (em backgrounds escuros): `#ffffff` (Branco)
  - Text-shadow (em backgrounds escuros): `0 2px 4px rgba(0,0,0,0.3)`
- **Características:**
  - Texto em maiúsculas: JAMEES
  - Estilo itálico para dar movimento e modernidade
  - Fonte Roboto para consistência com o sistema
  - Uso de sombra de texto em contextos com background escuro para melhor legibilidade

### 📦 Módulos Principais

#### 1. **Financeiro**
- **Dashboard Financeiro:** Visão geral de contas a pagar/receber, fluxo de caixa, saldos
- **Contas a Pagar:** Gestão completa de despesas e obrigações
- **Contas a Receber:** Controle de recebimentos e cobranças
- **DRE (Demonstração do Resultado do Exercício):** Geração automática de DRE
- **Plano de Contas:** Estrutura hierárquica de contas contábeis
- **Centro de Custos:** Divisão de custos por departamento/projeto
- **Contas Bancárias:** Gestão de múltiplas contas bancárias
- **Formas de Pagamento:** Configuração de métodos de pagamento

#### 2. **Cadastros**
- **Clientes:** Cadastro completo com histórico de transações
- **Fornecedores:** Gestão de fornecedores e compras
- **Funcionários:** Controle de equipe e colaboradores
- **Transportadoras:** Gestão de entregas e logística
- **Entidades:** Sistema unificado para diferentes tipos de entidades

#### 3. **Aplicativos Modulares**
- **Loteamento:** Sistema completo para gestão de loteamentos, vendas e reservas de lotes
  - Gestão de empreendimentos
  - Controle de quadras e lotes
  - Status de lotes (disponível, reservado, vendido)
  - Integração com movimentações financeiras

#### 4. **Gestão de Usuários e Permissões**
- **Usuários:** Cadastro e gestão de usuários
- **Grupos:** Criação de grupos com permissões específicas
- **Permissões:** Sistema granular de permissões por módulo e ação
- **Horários de Acesso:** Controle de horários permitidos para login
- **Auditoria:** Log completo de ações dos usuários

#### 5. **Planos e Assinaturas**
- **Planos:** Sistema de planos (Teste, Básico, Intermediário, Avançado)
- **Períodos:** Mensal, Trimestral, Semestral, Anual
- **Aplicativos Adicionais:** Contratação de módulos extras
- **Histórico:** Acompanhamento de assinaturas e pagamentos
- **Teste Grátis:** Período de teste de 10 dias

#### 6. **Dashboard e Relatórios**
- **Dashboard Principal:** Visão geral do negócio
- **Dashboard Financeiro:** Métricas financeiras em tempo real
- **Relatórios:** Geração de relatórios personalizados
- **Gráficos:** Visualizações com ApexCharts

#### 7. **Configurações**
- **Configurações Gerais:** Ajustes do sistema
- **Meus Dados:** Perfil do usuário
- **Troca de Empresa:** Multi-empresa com troca de contexto
- **Aplicativos:** Gestão de aplicativos contratados

### 🔐 Segurança e Autenticação

- Autenticação via Laravel Breeze
- Sistema de permissões baseado em grupos
- Horários de acesso configuráveis
- Rate limiting para login
- Auditoria de ações críticas
- Sessões gerenciadas por empresa

### 🏢 Multi-Empresa (Multi-Tenant)

- Usuários podem ter acesso a múltiplas empresas
- Empresa principal definida por usuário
- Troca de contexto entre empresas
- Whitelabel por empresa (personalização visual)
- Isolamento de dados por empresa

### 💳 Sistema de Pagamentos

- Integração com gateways de pagamento
- Suporte a cartão de crédito e PIX
- Controle de períodos de assinatura
- Cancelamento de planos
- Histórico de transações

### 📱 Responsividade

- Design mobile-first
- Interface adaptável para tablets e desktops
- Menu lateral colapsável em mobile
- Formulários otimizados para touch
- Tabelas responsivas com scroll horizontal

### 🚀 Funcionalidades Especiais

- **Sistema de Aplicativos:** Módulos adicionais podem ser contratados separadamente
- **Filtros Avançados:** Sistema de filtros por categoria e tipo
- **Busca Inteligente:** Pesquisa em múltiplos campos
- **Exportação de Dados:** Exportação de relatórios e listagens
- **Notificações:** Sistema de alertas e notificações
- **Backup Automático:** Proteção de dados

### 📊 Estrutura de Dados

**Principais Entidades:**
- Usuários (usuarios)
- Empresas (empresas)
- Planos (planos)
- Aplicativos (aplicativos)
- Movimentações (movimentacoes)
- Clientes (clientes)
- Fornecedores (fornecedores)
- Funcionários (funcionarios)
- Lotes (lotes)
- Empreendimentos (empreendimentos)
- Quadras (quadras)

**Relacionamentos:**
- Usuário ↔ Empresa (many-to-many com pivot)
- Empresa ↔ Plano (many-to-many com histórico)
- Empresa ↔ Aplicativo (many-to-many)
- Empresa ↔ Cliente/Fornecedor (one-to-many)
- Movimentação ↔ Entidade (polymorphic)

### 🎯 Público-Alvo

- Pequenas e médias empresas
- Empreendedores individuais
- Empresas de loteamento
- Empresas que precisam de controle financeiro completo
- Negócios que buscam automação de processos

### ✨ Diferenciais

1. **Completo e Gratuito (no plano básico)**
2. **Sem fidelidade**
3. **Teste grátis de 10 dias**
4. **Sistema modular (aplicativos adicionais)**
5. **Multi-empresa nativo**
6. **Interface moderna e intuitiva**
7. **Suporte especializado**
8. **Sistema de permissões avançado**
9. **Auditoria completa**
10. **API RESTful para integrações**

---

## 🤖 Prompt: O que vi do Sistema JAMEES

```
Analisei o sistema JAMEES (Sistema de Gestão Empresarial) e identifiquei as seguintes características:

**TECNOLOGIAS:**
- Backend: Laravel 12 (PHP 8.2+)
- Frontend: Vue.js 3 + Inertia.js + Tailwind CSS
- Banco de Dados: MySQL/MariaDB
- Autenticação: Laravel Breeze + Sanctum
- Build: Vite
- Gráficos: ApexCharts

**ARQUITETURA:**
- Multi-tenant (multi-empresa)
- Sistema de permissões granular baseado em grupos
- Whitelabel por empresa
- Aplicativos modulares (plugins)
- Sistema de planos e assinaturas
- Auditoria de ações

**MÓDULOS PRINCIPAIS:**
1. Financeiro:
   - Dashboard financeiro
   - Contas a pagar/receber
   - DRE automática
   - Plano de contas
   - Centro de custos
   - Contas bancárias

2. Cadastros:
   - Clientes
   - Fornecedores
   - Funcionários
   - Transportadoras
   - Entidades (sistema unificado)

3. Aplicativos:
   - Loteamento (gestão de empreendimentos, quadras, lotes)

4. Gestão:
   - Usuários e grupos
   - Permissões
   - Planos e assinaturas
   - Configurações

**DESIGN:**
- Cores: Azul #2563eb, Verde #10b981, Roxo #8b5cf6
- Fonte: Roboto
- Layout: Sidebar + Header + Conteúdo
- Totalmente responsivo
- Interface moderna e limpa

**FUNCIONALIDADES ESPECIAIS:**
- Sistema de planos (Teste, Básico, Intermediário, Avançado)
- Períodos: Mensal, Trimestral, Semestral, Anual
- Teste grátis de 10 dias
- Aplicativos adicionais contratáveis
- Multi-empresa com troca de contexto
- Horários de acesso configuráveis
- Sistema de auditoria

**FLUXOS PRINCIPAIS:**
1. Registro → Seleção de Plano → Ativação → Dashboard
2. Login → Dashboard → Módulos (Financeiro, Cadastros, etc.)
3. Contratação de Aplicativos → Pagamento → Ativação
4. Gestão de Permissões → Grupos → Usuários

**ESTRUTURA DE DADOS:**
- UUIDs como chaves primárias
- Relacionamentos many-to-many (usuários-empresas, empresas-planos, empresas-aplicativos)
- Polymorphic relationships (movimentações-entidades)
- Soft deletes em várias tabelas
- Timestamps em todas as tabelas

**SEGURANÇA:**
- Autenticação robusta
- Rate limiting
- Permissões por módulo/ação
- Auditoria de ações
- Isolamento de dados por empresa

**UX/UI:**
- Menu lateral expansível
- Filtros por categoria
- Cards informativos
- Modais para ações
- Formulários com validação
- Feedback visual (toasts, alerts)
- Loading states
- Empty states

**RESPONSIVIDADE:**
- Mobile-first
- Menu colapsável em mobile
- Tabelas com scroll horizontal
- Formulários adaptáveis
- Layout flexível
```

---

## 🌐 Prompt: Criar Site para o Sistema JAMEES

```
Crie um site institucional moderno e profissional para o sistema JAMEES (Sistema de Gestão Empresarial).

**REQUISITOS GERAIS:**
- Design moderno, limpo e profissional
- Totalmente responsivo (mobile-first)
- Performance otimizada
- SEO friendly
- Acessibilidade (WCAG 2.1)

**PALETA DE CORES:**
- Primária: #2563eb (Azul JAMEES)
- Secundária: #10b981 (Verde)
- Terciária: #8b5cf6 (Roxo)
- Logo: #1e40af (Azul primary-800)
- Neutros: Tons de cinza (#f9fafb, #6b7280, #1f2937)
- Destaque: #fbbf24 (Amarelo para CTAs)

**TIPOGRAFIA:**
- Títulos: Roboto Bold (Google Fonts)
- Corpo: Roboto Regular (Google Fonts)
- Logo: Roboto Bold Italic (Google Fonts)
- Tamanhos: Hierarquia clara (h1: 3rem, h2: 2.25rem, h3: 1.875rem)

**LOGO:**
- Texto: "JAMEES" (maiúsculas)
- Fonte: Roboto Bold Italic
- Cor principal: #1e40af
- Cor alternativa (backgrounds escuros): #ffffff com text-shadow
- Estilo: Itálico para movimento e modernidade

**SEÇÕES OBRIGATÓRIAS:**

1. **HERO SECTION**
   - Título impactante: "Sistema de gestão empresarial para vender mais e se preocupar menos"
   - Subtítulo: "Controle financeiro, vendas, estoque e muito mais em uma única plataforma"
   - CTAs: "Comece Grátis Agora" (primário) + "Ver Planos" (secundário)
   - Badge: "✓ Teste grátis por 10 dias ✓ Sem fidelidade ✓ Suporte especializado"
   - Imagem/Ilustração: Dashboard do sistema ou animação

2. **RECURSOS/FEATURES**
   - Grid de cards (3 colunas desktop, 1 mobile)
   - Ícones Font Awesome
   - Título + Descrição + Lista de benefícios
   - Recursos principais:
     * Gestão Financeira (Contas a pagar/receber, DRE, Fluxo de caixa)
     * Controle de Cadastros (Clientes, Fornecedores, Funcionários)
     * Dashboard Inteligente (Métricas em tempo real)
     * Relatórios Completos (Análises e insights)
     * Sistema Modular (Aplicativos adicionais)
     * Multi-Empresa (Gestão de múltiplas empresas)

3. **COMO FUNCIONA**
   - Passo a passo visual (4-5 etapas)
   - Ícones ou ilustrações
   - Texto explicativo
   - Exemplo: Registro → Configuração → Uso → Resultados

4. **PLANOS E PREÇOS**
   - Cards de planos (Teste, Básico, Intermediário, Avançado)
   - Preços destacados
   - Lista de funcionalidades por plano
   - CTA "Começar Agora" em cada card
   - Badge "Mais Popular" no plano recomendado
   - Períodos: Mensal, Trimestral, Semestral, Anual (toggle)

5. **APLICATIVOS/MÓDULOS**
   - Grid de aplicativos disponíveis
   - Card por aplicativo com:
     * Nome e ícone
     * Descrição
     * Preço
     * CTA "Saiba Mais"
   - Exemplo: Loteamento (gestão de empreendimentos)

6. **DEPOIMENTOS**
   - Carousel de depoimentos
   - Foto, nome, empresa, avaliação (estrelas)
   - Texto do depoimento
   - 3-5 depoimentos

7. **SOBRE/QUEM SOMOS**
   - História da empresa
   - Missão, visão, valores
   - Equipe (opcional)
   - Números/Estatísticas (ex: X empresas atendidas)

8. **FAQ**
   - Accordion com perguntas frequentes
   - Categorias: Preços, Funcionalidades, Suporte, Técnico

9. **CONTATO**
   - Formulário de contato
   - Informações de contato (email, telefone, endereço)
   - Redes sociais
   - Mapa (opcional)

10. **FOOTER**
    - Logo
    - Links úteis (Sobre, Recursos, Planos, Contato)
    - Links legais (Termos, Privacidade)
    - Redes sociais
    - Copyright

**ELEMENTOS DE DESIGN:**
- Gradientes sutis nos backgrounds
- Sombras suaves nos cards
- Animações suaves (hover, scroll)
- Ícones Font Awesome 6
- Ilustrações ou screenshots do sistema
- Botões com estados hover/active
- Formulários com validação visual

**COMPONENTES REUTILIZÁVEIS:**
- Header fixo com menu hamburger (mobile)
- Cards de recursos
- Cards de planos
- Formulários
- Botões (primário, secundário, outline)
- Badges/Tags
- Accordion
- Carousel/Slider

**ANIMAÇÕES:**
- Fade in on scroll
- Hover effects nos cards
- Smooth scroll
- Loading states
- Transições suaves

**OTIMIZAÇÕES:**
- Lazy loading de imagens
- Minificação de CSS/JS
- Compressão de imagens
- CDN para assets estáticos
- Meta tags SEO
- Schema.org markup
- Sitemap.xml
- robots.txt

**INTEGRAÇÕES:**
- Google Analytics
- Facebook Pixel (opcional)
- Chat de suporte (opcional)
- Formulário de contato com envio de email

**CALLS TO ACTION (CTAs):**
- "Comece Grátis Agora" (principal, amarelo)
- "Ver Planos" (secundário, outline)
- "Saiba Mais" (terciário, link)
- "Fale Conosco" (contato)

**CONTEÚDO SUGERIDO:**
- Textos persuasivos focados em benefícios
- Linguagem clara e acessível
- Foco em resultados e ROI
- Social proof (depoimentos, números)
- Urgência sutil (teste grátis limitado)

**TECNOLOGIAS SUGERIDAS:**
- HTML5 semântico
- CSS3 (Tailwind CSS ou CSS custom)
- JavaScript vanilla ou framework leve
- Framework CSS: Tailwind CSS ou Bootstrap
- Animações: AOS (Animate On Scroll) ou Framer Motion
- Formulários: Validação HTML5 + JavaScript

**ENTREGÁVEIS:**
1. HTML estruturado e semântico
2. CSS organizado (ou classes Tailwind)
3. JavaScript para interatividade
4. Assets (imagens, ícones otimizados)
5. Documentação de componentes
6. Guia de estilo
7. Arquivos de configuração (package.json, etc.)

**NOTAS IMPORTANTES:**
- O site deve transmitir confiança e profissionalismo
- Foco em conversão (registros e assinaturas)
- Mobile-first é obrigatório
- Performance é crítica (Lighthouse score > 90)
- Acessibilidade é essencial
- SEO otimizado para "sistema de gestão empresarial", "ERP", "controle financeiro"
```

---

## 📝 Notas Finais

Este documento serve como referência completa para entender o sistema JAMEES e criar materiais de marketing, documentação técnica ou sites institucionais baseados nas características e funcionalidades identificadas no código-fonte.

**Última atualização:** Baseado na análise do código-fonte em dezembro de 2024.

