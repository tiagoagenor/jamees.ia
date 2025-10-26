# Sistema de Planos - Jamees

## 📋 Visão Geral

O sistema de planos permite que empresas gerenciem suas assinaturas com diferentes tipos de planos, períodos de pagamento e funcionalidades.

## 🎯 Funcionalidades Implementadas

### ✅ Planos Disponíveis
- **Plano Base**: R$ 29,90/mês (5 usuários, 1 empresa)
- **Plano Premium**: R$ 59,90/mês (15 usuários, 3 empresas)  
- **Plano Master**: R$ 99,90/mês (50 usuários, 10 empresas)
- **Plano Personalizado**: Preço sob consulta (ilimitado)

### ✅ Períodos e Descontos
- **Mensal**: Sem desconto
- **Trimestral**: 5% desconto
- **Semestral**: 10% desconto
- **Anual**: 20% desconto

### ✅ Período de Teste
- **15 dias gratuitos** para novos clientes
- **Conversão automática** do teste para plano pago
- **Barra de notificação** durante período de teste

### ✅ Sistema de Notificações
- **Barra no topo** para período de teste
- **Alerta de vencimento** quando faltam 5 dias
- **Middleware** para verificação automática de plano ativo

## 🚀 Como Usar

### 1. Acessar "Meu Plano"
- Faça login no sistema
- Clique em "Meu Plano" no menu lateral
- Ou acesse diretamente: `/planos`

### 2. Visualizar Plano Atual
- Veja informações do plano atual
- Status (Ativo, Teste, Expirado)
- Dias restantes
- Valor pago e desconto aplicado

### 3. Escolher Novo Plano
- Clique em "Escolher Plano" ou "Alterar Plano"
- Selecione o plano desejado
- Escolha o período (mensal, trimestral, semestral, anual)
- Clique em "Ativar Plano"

### 4. Ver Histórico
- Clique em "Ver Histórico"
- Visualize todos os planos anteriores
- Veja valores pagos e descontos aplicados

## 🔧 Funcionalidades Técnicas

### Middleware de Verificação
- Verifica automaticamente se o plano está ativo
- Redireciona para página de planos se expirado
- Adiciona informações do plano à sessão

### Comando de Verificação
```bash
php artisan planos:verificar-expirados
```
- Verifica planos expirados
- Atualiza status automaticamente
- Lista planos próximos do vencimento

### API de Informações
- `GET /api/plano-info` - Retorna informações do plano atual

## 📊 Estrutura do Banco

### Tabela `planos`
- Informações dos planos disponíveis
- Preços por período
- Limites de usuários e empresas
- Funcionalidades incluídas

### Tabela `empresa_plano`
- Histórico de planos por empresa
- Status e datas de vigência
- Valores pagos e descontos
- Controle de período de teste

## 🎨 Interface

### Views Criadas
- `planos/index.blade.php` - Lista de planos e plano atual
- `planos/show.blade.php` - Detalhes e ativação de plano
- `planos/historico.blade.php` - Histórico de planos
- `components/plano-notification.blade.php` - Barra de notificação

### Componentes
- Barra de notificação no topo das páginas
- Cards de planos com preços e funcionalidades
- Tabela de histórico com status coloridos
- Formulários de ativação de planos

## 🔐 Segurança

### Validações
- Apenas um plano ativo por empresa
- Verificação de empresa principal
- Validação de períodos disponíveis
- Controle de acesso por middleware

### Histórico
- Mantém histórico completo de planos
- Não permite exclusão de registros históricos
- Rastreamento de mudanças de status

## 📈 Monitoramento

### Verificações Automáticas
- Status de planos expirados
- Planos próximos do vencimento
- Validação no login do usuário

### Relatórios Disponíveis
- Histórico completo por empresa
- Valores pagos e descontos aplicados
- Estatísticas de uso por plano

## 🛠️ Manutenção

### Comandos Úteis
```bash
# Verificar planos expirados
php artisan planos:verificar-expirados

# Executar seeds dos planos
php artisan db:seed --class=PlanoSeeder

# Ver rotas de planos
php artisan route:list --name=planos
```

### Configuração de Cron
Para verificação automática, adicione ao crontab:
```bash
0 0 * * * cd /path/to/project && php artisan planos:verificar-expirados
```

## 🎯 Próximos Passos

1. **Sistema de Pagamento**: Integração com gateway de pagamento
2. **Relatórios Avançados**: Dashboard de uso e estatísticas
3. **Notificações por Email**: Alertas de vencimento
4. **API Externa**: Endpoints para integração com outros sistemas
5. **Planos Personalizados**: Interface para criação de planos customizados

---

**Sistema desenvolvido com Laravel 11, Tailwind CSS e componentes Blade.**
