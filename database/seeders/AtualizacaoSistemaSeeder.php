<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AtualizacaoSistema;
use App\Enums\AtualizacaoTipoEnum;
use Carbon\Carbon;

class AtualizacaoSistemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $atualizacoes = [
            // Novos Recursos
            [
                'tipo' => AtualizacaoTipoEnum::NOVOS_RECURSOS,
                'titulo' => 'Nova Interface do Dashboard',
                'minitexto' => 'Redesign completo do dashboard com layout em 2 colunas, seções de boas-vindas, vídeo institucional, atualizações e desenvolvimento.',
                'texto' => '<h2>Nova Interface do Dashboard</h2><p>Implementamos uma interface completamente redesenhada para o dashboard, proporcionando uma experiência mais moderna e intuitiva.</p><h3>Principais mudanças:</h3><ul><li>Layout em 2 colunas responsivo</li><li>Seção de boas-vindas personalizável</li><li>Integração com vídeo institucional do YouTube</li><li>Painel de atualizações do sistema</li><li>Visualização de projetos em desenvolvimento</li></ul><p>Essas melhorias tornam o dashboard mais informativo e fácil de navegar.</p>',
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
            ],
            [
                'tipo' => AtualizacaoTipoEnum::NOVOS_RECURSOS,
                'titulo' => 'Sistema de Atualizações do Sistema',
                'minitexto' => 'Nova página dedicada para exibir todas as atualizações, melhorias e novos recursos implementados no sistema.',
                'texto' => '<h2>Sistema de Atualizações do Sistema</h2><p>Criamos uma página completa para que você fique sempre informado sobre as novidades do sistema.</p><h3>Funcionalidades:</h3><ul><li>Listagem de todas as atualizações</li><li>Filtro por tipo (Novos Recursos / Melhorias)</li><li>Visualização detalhada de cada atualização</li><li>Conteúdo HTML rico para melhor apresentação</li></ul>',
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
            ],
            [
                'tipo' => AtualizacaoTipoEnum::NOVOS_RECURSOS,
                'titulo' => 'Integração com APIs de Pagamento',
                'minitexto' => 'Nova integração com principais gateways de pagamento para facilitar transações e cobranças automáticas.',
                'texto' => '<h2>Integração com APIs de Pagamento</h2><p>Implementamos integração com os principais gateways de pagamento do mercado.</p><h3>Gateways suportados:</h3><ul><li>Stripe</li><li>PayPal</li><li>Mercado Pago</li><li>PagSeguro</li></ul><p>Essa integração permite processar pagamentos de forma segura e automatizada.</p>',
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
            ],
            [
                'tipo' => AtualizacaoTipoEnum::NOVOS_RECURSOS,
                'titulo' => 'Notificações em Tempo Real',
                'minitexto' => 'Sistema de notificações push implementado para alertas importantes e atualizações do sistema.',
                'texto' => '<h2>Notificações em Tempo Real</h2><p>Sistema completo de notificações para manter você sempre informado.</p><h3>Recursos:</h3><ul><li>Notificações push no navegador</li><li>Alertas de novas atualizações</li><li>Notificações de tarefas pendentes</li><li>Histórico de notificações</li></ul>',
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
            ],
            [
                'tipo' => AtualizacaoTipoEnum::NOVOS_RECURSOS,
                'titulo' => 'Exportação de Relatórios em PDF',
                'minitexto' => 'Nova funcionalidade para exportar relatórios financeiros e gerenciais diretamente em formato PDF.',
                'texto' => '<h2>Exportação de Relatórios em PDF</h2><p>Agora você pode exportar todos os relatórios em formato PDF profissional.</p><h3>Benefícios:</h3><ul><li>Formatação profissional</li><li>Fácil compartilhamento</li><li>Arquivo leve e compatível</li><li>Personalização de cabeçalhos e rodapés</li></ul>',
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
            ],
            [
                'tipo' => AtualizacaoTipoEnum::NOVOS_RECURSOS,
                'titulo' => 'Dashboard Analytics Avançado',
                'minitexto' => 'Novo painel de analytics com métricas detalhadas, gráficos interativos e insights automatizados.',
                'texto' => '<h2>Dashboard Analytics Avançado</h2><p>Painel completo de analytics para análise de dados e tomada de decisões.</p><h3>Métricas disponíveis:</h3><ul><li>Gráficos interativos</li><li>Análise de tendências</li><li>Comparativos de períodos</li><li>Exportação de dados</li></ul>',
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
            ],
            [
                'tipo' => AtualizacaoTipoEnum::NOVOS_RECURSOS,
                'titulo' => 'Sistema de Configurações Genérico',
                'minitexto' => 'Nova estrutura de configurações flexível que permite adicionar novas configurações sem alterar o banco de dados.',
                'texto' => '<h2>Sistema de Configurações Genérico</h2><p>Implementamos uma estrutura genérica e flexível para configurações do sistema.</p><h3>Vantagens:</h3><ul><li>Adicionar configurações sem migrations</li><li>Organização por grupos</li><li>Suporte a valores serializados</li><li>Fácil manutenção</li></ul>',
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
            ],
            [
                'tipo' => AtualizacaoTipoEnum::NOVOS_RECURSOS,
                'titulo' => 'App Mobile Nativo',
                'minitexto' => 'Lançamento do aplicativo mobile nativo para iOS e Android com todas as funcionalidades principais.',
                'texto' => '<h2>App Mobile Nativo</h2><p>Disponibilizamos o aplicativo mobile nativo para iOS e Android.</p><h3>Funcionalidades:</h3><ul><li>Acesso completo ao sistema</li><li>Notificações push</li><li>Modo offline</li><li>Sincronização automática</li></ul>',
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
            ],
            [
                'tipo' => AtualizacaoTipoEnum::NOVOS_RECURSOS,
                'titulo' => 'API RESTful Completa',
                'minitexto' => 'Nova API RESTful completa para integração com sistemas externos e desenvolvimento de aplicações customizadas.',
                'texto' => '<h2>API RESTful Completa</h2><p>API completa para integrações e desenvolvimento de soluções customizadas.</p><h3>Recursos:</h3><ul><li>Documentação completa (Swagger)</li><li>Autenticação via tokens</li><li>Rate limiting</li><li>Webhooks</li></ul>',
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
            ],
            [
                'tipo' => AtualizacaoTipoEnum::NOVOS_RECURSOS,
                'titulo' => 'Integração com WhatsApp Business',
                'minitexto' => 'Nova integração com WhatsApp Business API para envio de mensagens automáticas e atendimento ao cliente.',
                'texto' => '<h2>Integração com WhatsApp Business</h2><p>Integração completa com WhatsApp Business para comunicação automatizada.</p><h3>Funcionalidades:</h3><ul><li>Envio de mensagens automáticas</li><li>Chat integrado</li><li>Templates de mensagens</li><li>Histórico de conversas</li></ul>',
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
            ],
            // Melhorias
            [
                'tipo' => AtualizacaoTipoEnum::MELHORIAS,
                'titulo' => 'Melhorias no Sistema Financeiro',
                'minitexto' => 'Novos relatórios e visualizações para análise financeira com gráficos interativos e exportação em múltiplos formatos.',
                'texto' => '<h2>Melhorias no Sistema Financeiro</h2><p>Aprimoramos significativamente o módulo financeiro com novas funcionalidades.</p><h3>Melhorias implementadas:</h3><ul><li>Novos relatórios financeiros</li><li>Gráficos interativos</li><li>Exportação em múltiplos formatos</li><li>Filtros avançados</li></ul>',
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
            ],
            [
                'tipo' => AtualizacaoTipoEnum::MELHORIAS,
                'titulo' => 'Otimização de Performance',
                'minitexto' => 'Melhorias significativas na velocidade de carregamento das páginas e otimização de consultas ao banco de dados.',
                'texto' => '<h2>Otimização de Performance</h2><p>Realizamos otimizações importantes para melhorar a performance do sistema.</p><h3>Otimizações:</h3><ul><li>Cache de consultas</li><li>Lazy loading de imagens</li><li>Minificação de assets</li><li>CDN para arquivos estáticos</li></ul>',
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
            ],
            [
                'tipo' => AtualizacaoTipoEnum::MELHORIAS,
                'titulo' => 'Melhorias na Gestão de Usuários',
                'minitexto' => 'Interface aprimorada para gerenciamento de usuários, grupos e permissões com maior flexibilidade.',
                'texto' => '<h2>Melhorias na Gestão de Usuários</h2><p>Aprimoramos o sistema de gestão de usuários e permissões.</p><h3>Melhorias:</h3><ul><li>Interface mais intuitiva</li><li>Gestão de grupos aprimorada</li><li>Permissões granulares</li><li>Auditoria de ações</li></ul>',
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
            ],
            [
                'tipo' => AtualizacaoTipoEnum::MELHORIAS,
                'titulo' => 'Correções no Módulo de Loteamento',
                'minitexto' => 'Correção de bugs e melhorias na interface do módulo de gestão de loteamentos e vendas de lotes.',
                'texto' => '<h2>Correções no Módulo de Loteamento</h2><p>Corrigimos diversos bugs e melhoramos a interface do módulo de loteamento.</p><h3>Correções:</h3><ul><li>Correção de cálculos de parcelas</li><li>Melhoria na visualização do mapa</li><li>Correção de relatórios</li><li>Interface mais responsiva</li></ul>',
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
            ],
            [
                'tipo' => AtualizacaoTipoEnum::MELHORIAS,
                'titulo' => 'Melhorias na Responsividade',
                'minitexto' => 'Otimização da interface para dispositivos móveis com melhor experiência de uso em tablets e smartphones.',
                'texto' => '<h2>Melhorias na Responsividade</h2><p>Aprimoramos a experiência em dispositivos móveis.</p><h3>Melhorias:</h3><ul><li>Layout adaptativo</li><li>Touch-friendly</li><li>Navegação otimizada</li><li>Performance em mobile</li></ul>',
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
            ],
        ];

        // Gerar mais atualizações para chegar a 100
        $tipos = [AtualizacaoTipoEnum::NOVOS_RECURSOS, AtualizacaoTipoEnum::MELHORIAS];
        
        $titulosNovosRecursos = [
            'Sistema de Backup Automático',
            'Chat em Tempo Real',
            'Integração com Google Analytics',
            'Sistema de Assinaturas Recorrentes',
            'Módulo de E-commerce',
            'Gestão de Estoque Avançada',
            'Sistema de Tickets de Suporte',
            'Relatórios Personalizados',
            'Integração com CRM',
            'Sistema de Comentários',
            'Upload de Arquivos em Lote',
            'Editor WYSIWYG Avançado',
            'Sistema de Tags',
            'Busca Avançada',
            'Filtros Dinâmicos',
            'Exportação para Excel',
            'Importação de Dados',
            'Sistema de Templates',
            'Workflow Automatizado',
            'Integração com Email Marketing',
            'Sistema de Pontos e Recompensas',
            'Gamificação',
            'Dashboard Personalizável',
            'Widgets Customizáveis',
            'Integração com Redes Sociais',
            'Sistema de Blog',
            'Galeria de Imagens',
            'Player de Vídeo',
            'Sistema de Comentários em Tempo Real',
            'Notificações por Email',
            'Sistema de Agendamento',
            'Calendário Interativo',
            'Gerenciador de Tarefas',
            'Sistema de Projetos',
            'Colaboração em Tempo Real',
            'Versionamento de Documentos',
            'Sistema de Aprovações',
            'Workflow de Aprovação',
            'Assinatura Digital',
            'Integração com Assinatura Eletrônica',
        ];

        $titulosMelhorias = [
            'Melhoria na Velocidade de Carregamento',
            'Otimização de Consultas SQL',
            'Melhoria na Interface de Login',
            'Correção de Bugs Críticos',
            'Melhoria na Segurança',
            'Atualização de Bibliotecas',
            'Melhoria na Validação de Formulários',
            'Otimização de Imagens',
            'Melhoria na Navegação',
            'Correção de Problemas de Compatibilidade',
            'Melhoria na Acessibilidade',
            'Otimização de Memória',
            'Melhoria na Experiência do Usuário',
            'Correção de Problemas de Sincronização',
            'Melhoria na Interface de Relatórios',
            'Otimização de Exportações',
            'Melhoria na Gestão de Permissões',
            'Correção de Problemas de Timezone',
            'Melhoria na Interface Mobile',
            'Otimização de Cache',
            'Melhoria na Interface de Configurações',
            'Correção de Problemas de Encoding',
            'Melhoria na Interface de Dashboard',
            'Otimização de Queries',
            'Melhoria na Interface de Filtros',
            'Correção de Problemas de Paginação',
            'Melhoria na Interface de Busca',
            'Otimização de Assets',
            'Melhoria na Interface de Upload',
            'Correção de Problemas de Sessão',
            'Melhoria na Interface de Notificações',
            'Otimização de API',
            'Melhoria na Interface de Perfil',
            'Correção de Problemas de Autenticação',
            'Melhoria na Interface de Relatórios',
            'Otimização de Banco de Dados',
            'Melhoria na Interface de Gráficos',
            'Correção de Problemas de Integração',
            'Melhoria na Interface de Mensagens',
            'Otimização de Processamento',
        ];

        // Adicionar atualizações base
        foreach ($atualizacoes as $atualizacao) {
            AtualizacaoSistema::create($atualizacao);
        }

        // Gerar mais atualizações aleatórias
        $count = count($atualizacoes);
        while ($count < 100) {
            $tipo = $tipos[array_rand($tipos)];
            $titulos = $tipo === AtualizacaoTipoEnum::NOVOS_RECURSOS ? $titulosNovosRecursos : $titulosMelhorias;
            $titulo = $titulos[array_rand($titulos)];
            
            // Remover título usado para evitar duplicatas
            $titulos = array_filter($titulos, fn($t) => $t !== $titulo);
            
            $minitextos = [
                'Nova funcionalidade implementada para melhorar a experiência do usuário.',
                'Recurso adicionado para facilitar o trabalho diário.',
                'Nova opção disponível para otimizar processos.',
                'Funcionalidade implementada com sucesso.',
                'Melhoria significativa na usabilidade do sistema.',
                'Nova ferramenta disponível para os usuários.',
                'Recurso adicionado para aumentar a produtividade.',
                'Funcionalidade implementada para melhorar a eficiência.',
            ];

            $textos = [
                '<h2>' . $titulo . '</h2><p>Implementamos esta funcionalidade para melhorar a experiência do usuário e aumentar a produtividade.</p><h3>Principais benefícios:</h3><ul><li>Melhor experiência do usuário</li><li>Aumento da produtividade</li><li>Processos mais eficientes</li><li>Interface mais intuitiva</li></ul>',
                '<h2>' . $titulo . '</h2><p>Esta atualização traz melhorias significativas para o sistema.</p><h3>Recursos adicionados:</h3><ul><li>Novas funcionalidades</li><li>Interface aprimorada</li><li>Melhor performance</li><li>Maior estabilidade</li></ul>',
                '<h2>' . $titulo . '</h2><p>Implementação completa desta funcionalidade com todas as melhorias necessárias.</p><h3>Destaques:</h3><ul><li>Interface moderna</li><li>Funcionalidades avançadas</li><li>Alta performance</li><li>Fácil de usar</li></ul>',
            ];

            AtualizacaoSistema::create([
                'tipo' => $tipo,
                'titulo' => $titulo,
                'minitexto' => $minitextos[array_rand($minitextos)],
                'texto' => $textos[array_rand($textos)],
                'created_at' => Carbon::now()->subDays(rand(0, 90)),
            ]);

            $count++;
        }
    }
}
