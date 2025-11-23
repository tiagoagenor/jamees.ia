<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ideia;
use App\Models\Usuario;
use App\Enums\IdeiaStatusEnum;
use Carbon\Carbon;

class IdeiaDesenvolvimentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar um usuário para criar as ideias (ou criar um se não existir)
        $usuario = Usuario::first();
        
        if (!$usuario) {
            // Se não houver usuário, não podemos criar ideias
            $this->command->warn('Nenhum usuário encontrado. Por favor, execute os seeders de usuário primeiro.');
            return;
        }

        $ideias = [
            [
                'titulo' => 'Integração com APIs de Pagamento',
                'descricao' => 'Implementar integração com principais gateways de pagamento (Stripe, PayPal, Mercado Pago) para facilitar transações e cobranças automáticas. Incluir suporte a múltiplas moedas e métodos de pagamento.',
                'categoria' => 'Financeiro',
                'status' => IdeiaStatusEnum::EM_DESENVOLVIMENTO,
                'porcentagem' => 75,
                'votos' => 12,
                'created_at' => Carbon::now()->subDays(45),
            ],
            [
                'titulo' => 'App Mobile Nativo',
                'descricao' => 'Desenvolvimento de aplicativo mobile nativo para iOS e Android, permitindo acesso completo ao sistema através de dispositivos móveis. Incluir notificações push e sincronização offline.',
                'categoria' => 'Gestão',
                'status' => IdeiaStatusEnum::EM_DESENVOLVIMENTO,
                'porcentagem' => 45,
                'votos' => 8,
                'created_at' => Carbon::now()->subDays(30),
            ],
            [
                'titulo' => 'Dashboard Analytics Avançado',
                'descricao' => 'Criar dashboard com gráficos interativos, métricas em tempo real e relatórios personalizáveis. Incluir exportação de dados e filtros avançados para análise de performance.',
                'categoria' => 'Dashboard',
                'status' => IdeiaStatusEnum::EM_DESENVOLVIMENTO,
                'porcentagem' => 30,
                'votos' => 15,
                'created_at' => Carbon::now()->subDays(20),
            ],
            [
                'titulo' => 'Sistema de Backup Automático',
                'descricao' => 'Implementar sistema de backup automático com agendamento personalizado, versionamento de dados e restauração rápida. Incluir notificações de status de backup.',
                'categoria' => 'Configurações',
                'status' => IdeiaStatusEnum::EM_DESENVOLVIMENTO,
                'porcentagem' => 60,
                'votos' => 10,
                'created_at' => Carbon::now()->subDays(35),
            ],
            [
                'titulo' => 'Integração com WhatsApp Business',
                'descricao' => 'Integrar sistema com WhatsApp Business API para envio de mensagens automáticas, notificações e atendimento ao cliente. Incluir chatbot básico e templates de mensagens.',
                'categoria' => 'Atendimentos',
                'status' => IdeiaStatusEnum::EM_DESENVOLVIMENTO,
                'porcentagem' => 40,
                'votos' => 18,
                'created_at' => Carbon::now()->subDays(25),
            ],
            [
                'titulo' => 'API RESTful Completa',
                'descricao' => 'Desenvolver API RESTful completa com documentação Swagger, autenticação OAuth2, rate limiting e versionamento. Permitir integração com sistemas externos.',
                'categoria' => 'Configurações',
                'status' => IdeiaStatusEnum::EM_DESENVOLVIMENTO,
                'porcentagem' => 55,
                'votos' => 7,
                'created_at' => Carbon::now()->subDays(40),
            ],
            [
                'titulo' => 'Módulo de E-commerce',
                'descricao' => 'Criar módulo completo de e-commerce com catálogo de produtos, carrinho de compras, checkout e gestão de pedidos. Incluir integração com gateways de pagamento.',
                'categoria' => 'E-commerce',
                'status' => IdeiaStatusEnum::EM_DESENVOLVIMENTO,
                'porcentagem' => 25,
                'votos' => 22,
                'created_at' => Carbon::now()->subDays(15),
            ],
            [
                'titulo' => 'Sistema de Assinaturas Recorrentes',
                'descricao' => 'Implementar sistema de assinaturas recorrentes com diferentes planos, cobrança automática e gestão de renovações. Incluir prorrogação e cancelamento de assinaturas.',
                'categoria' => 'Financeiro',
                'status' => IdeiaStatusEnum::EM_DESENVOLVIMENTO,
                'porcentagem' => 70,
                'votos' => 14,
                'created_at' => Carbon::now()->subDays(50),
            ],
            [
                'titulo' => 'Integração com Google Analytics',
                'descricao' => 'Integrar sistema com Google Analytics para rastreamento de eventos, conversões e análise de comportamento do usuário. Incluir dashboard com métricas principais.',
                'categoria' => 'Marketing',
                'status' => IdeiaStatusEnum::EM_DESENVOLVIMENTO,
                'porcentagem' => 50,
                'votos' => 9,
                'created_at' => Carbon::now()->subDays(28),
            ],
            [
                'titulo' => 'Chat em Tempo Real',
                'descricao' => 'Desenvolver sistema de chat em tempo real para comunicação interna e atendimento ao cliente. Incluir salas de chat, compartilhamento de arquivos e histórico de conversas.',
                'categoria' => 'Atendimentos',
                'status' => IdeiaStatusEnum::EM_DESENVOLVIMENTO,
                'porcentagem' => 35,
                'votos' => 11,
                'created_at' => Carbon::now()->subDays(18),
            ],
            [
                'titulo' => 'Sistema de Relatórios Personalizados',
                'descricao' => 'Criar sistema para geração de relatórios personalizados com campos customizáveis, filtros avançados e exportação em múltiplos formatos (PDF, Excel, CSV).',
                'categoria' => 'Relatórios',
                'status' => IdeiaStatusEnum::EM_DESENVOLVIMENTO,
                'porcentagem' => 65,
                'votos' => 13,
                'created_at' => Carbon::now()->subDays(42),
            ],
            [
                'titulo' => 'Autenticação de Dois Fatores (2FA)',
                'descricao' => 'Implementar autenticação de dois fatores para aumentar a segurança das contas. Suportar aplicativos autenticadores e SMS.',
                'categoria' => 'Usuários',
                'status' => IdeiaStatusEnum::EM_DESENVOLVIMENTO,
                'porcentagem' => 80,
                'votos' => 16,
                'created_at' => Carbon::now()->subDays(55),
            ],
            [
                'titulo' => 'Integração com CRM',
                'descricao' => 'Desenvolver integração com principais CRMs do mercado (Salesforce, HubSpot, Pipedrive) para sincronização de dados de clientes e oportunidades.',
                'categoria' => 'Vendas',
                'status' => IdeiaStatusEnum::EM_DESENVOLVIMENTO,
                'porcentagem' => 38,
                'votos' => 9,
                'created_at' => Carbon::now()->subDays(22),
            ],
            [
                'titulo' => 'Sistema de Notificações Push',
                'descricao' => 'Implementar sistema de notificações push no navegador para alertas importantes, atualizações e lembretes. Incluir preferências de notificação por usuário.',
                'categoria' => 'Configurações',
                'status' => IdeiaStatusEnum::EM_DESENVOLVIMENTO,
                'porcentagem' => 58,
                'votos' => 12,
                'created_at' => Carbon::now()->subDays(33),
            ],
            [
                'titulo' => 'Exportação de Dados em Lote',
                'descricao' => 'Criar funcionalidade para exportação de grandes volumes de dados em lote, com processamento assíncrono e notificação por email quando concluído.',
                'categoria' => 'Relatórios',
                'status' => IdeiaStatusEnum::EM_DESENVOLVIMENTO,
                'porcentagem' => 42,
                'votos' => 6,
                'created_at' => Carbon::now()->subDays(27),
            ],
            [
                'titulo' => 'Integração com Redes Sociais',
                'descricao' => 'Desenvolver integração com principais redes sociais (Facebook, Instagram, LinkedIn) para publicação automática e monitoramento de menções.',
                'categoria' => 'Marketing',
                'status' => IdeiaStatusEnum::EM_DESENVOLVIMENTO,
                'porcentagem' => 28,
                'votos' => 19,
                'created_at' => Carbon::now()->subDays(12),
            ],
            [
                'titulo' => 'Sistema de Workflow Automatizado',
                'descricao' => 'Criar sistema de workflow com automação de processos, aprovações em múltiplas etapas e notificações automáticas para participantes.',
                'categoria' => 'Gestão',
                'status' => IdeiaStatusEnum::EM_DESENVOLVIMENTO,
                'porcentagem' => 48,
                'votos' => 10,
                'created_at' => Carbon::now()->subDays(38),
            ],
            [
                'titulo' => 'Painel de Métricas em Tempo Real',
                'descricao' => 'Desenvolver painel com métricas atualizadas em tempo real, gráficos interativos e alertas configuráveis para monitoramento de KPIs.',
                'categoria' => 'Dashboard',
                'status' => IdeiaStatusEnum::EM_DESENVOLVIMENTO,
                'porcentagem' => 52,
                'votos' => 14,
                'created_at' => Carbon::now()->subDays(32),
            ],
            [
                'titulo' => 'Sistema de Tickets de Suporte',
                'descricao' => 'Implementar sistema completo de tickets para suporte ao cliente, com categorização, priorização, SLA e histórico de atendimentos.',
                'categoria' => 'Atendimentos',
                'status' => IdeiaStatusEnum::EM_DESENVOLVIMENTO,
                'porcentagem' => 68,
                'votos' => 17,
                'created_at' => Carbon::now()->subDays(48),
            ],
            [
                'titulo' => 'Integração com Email Marketing',
                'descricao' => 'Integrar sistema com plataformas de email marketing (Mailchimp, SendGrid, RD Station) para campanhas automatizadas e segmentação de clientes.',
                'categoria' => 'Marketing',
                'status' => IdeiaStatusEnum::EM_DESENVOLVIMENTO,
                'porcentagem' => 33,
                'votos' => 8,
                'created_at' => Carbon::now()->subDays(16),
            ],
        ];

        foreach ($ideias as $ideiaData) {
            $ideia = Ideia::create([
                'usuario_id' => $usuario->id,
                'titulo' => $ideiaData['titulo'],
                'descricao' => $ideiaData['descricao'],
                'categoria' => $ideiaData['categoria'],
                'status' => $ideiaData['status']->value,
                'porcentagem' => $ideiaData['porcentagem'],
                'votos' => $ideiaData['votos'],
                'created_at' => $ideiaData['created_at'],
                'updated_at' => Carbon::now(),
            ]);

            // Criar alguns votos para a ideia (simular votos de outros usuários)
            // Buscar outros usuários para criar votos realistas
            $outrosUsuarios = Usuario::where('id', '!=', $usuario->id)->limit(min($ideiaData['votos'], 10))->get();
            
            // Se houver outros usuários, criar votos reais
            if ($outrosUsuarios->count() > 0) {
                foreach ($outrosUsuarios as $outroUsuario) {
                    try {
                        $ideia->votosUsuarios()->attach($outroUsuario->id);
                    } catch (\Exception $e) {
                        // Ignorar se já houver voto (unique constraint)
                    }
                }
                // Atualizar o contador de votos
                $ideia->atualizarVotos();
            }
        }

        $this->command->info('Ideias em desenvolvimento criadas com sucesso!');
    }
}
