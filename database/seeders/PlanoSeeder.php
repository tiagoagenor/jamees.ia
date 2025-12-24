<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plano;
use App\Enums\PlanoTipoEnum;
use Illuminate\Support\Str;

class PlanoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $planos = [
            [
                'nome' => 'Plano Base',
                'descricao' => 'Plano básico com funcionalidades essenciais para pequenas empresas',
                'tipo' => PlanoTipoEnum::BASE,
                'preco_mensal' => 49.90,
                'preco_trimestral' => 142.21,
                'preco_semestral' => 269.46,
                'preco_anual' => 479.04,
                'limite_usuarios' => 1,
                'limite_empresas' => 1,
                'funcionalidades' => [
                    'Gestão básica de usuários',
                    'Gestão básica de empresas',
                    'Relatórios simples',
                    'Suporte por email',
                ],
            ],
            [
                'nome' => 'Plano Premium',
                'descricao' => 'Plano premium com funcionalidades avançadas para empresas em crescimento',
                'tipo' => PlanoTipoEnum::PREMIUM,
                'preco_mensal' => 99.90,
                'preco_trimestral' => 284.71,
                'preco_semestral' => 539.46,
                'preco_anual' => 959.04,
                'limite_usuarios' => 3,
                'limite_empresas' => 1,
                'funcionalidades' => [
                    'Todas as funcionalidades do Plano Base',
                    'Gestão avançada de usuários',
                    'Gestão avançada de empresas',
                    'Relatórios avançados',
                    'Integração com APIs',
                    'Suporte prioritário',
                ],
            ],
            [
                'nome' => 'Plano Master',
                'descricao' => 'Plano master com todas as funcionalidades para grandes empresas',
                'tipo' => PlanoTipoEnum::MASTER,
                'preco_mensal' => 129.90,
                'preco_trimestral' => 370.21,
                'preco_semestral' => 701.46,
                'preco_anual' => 1247.04,
                'limite_usuarios' => 5,
                'limite_empresas' => 2,
                'funcionalidades' => [
                    'Todas as funcionalidades do Plano Premium',
                    'Gestão ilimitada de usuários',
                    'Gestão ilimitada de empresas',
                    'Relatórios personalizados',
                    'Integração completa com APIs',
                    'Suporte 24/7',
                    'Backup automático',
                    'SLA garantido',
                ],
            ],
            [
                'nome' => 'Plano Personalizado',
                'descricao' => 'Plano personalizado conforme necessidade específica da empresa',
                'tipo' => PlanoTipoEnum::PERSONALIZADO,
                'preco_mensal' => 0.00, // Preço sob consulta
                'preco_trimestral' => 0.00,
                'preco_semestral' => 0.00,
                'preco_anual' => 0.00,
                'limite_usuarios' => null, // Ilimitado
                'limite_empresas' => null, // Ilimitado
                'funcionalidades' => [
                    'Funcionalidades personalizadas',
                    'Desenvolvimento sob demanda',
                    'Integração customizada',
                    'Suporte dedicado',
                    'Consultoria especializada',
                ],
                'ativo' => false, // Inativo no banco
            ],
            [
                'nome' => 'Plano de Teste',
                'descricao' => 'Plano de teste gratuito para novos usuários - 15 dias',
                'tipo' => PlanoTipoEnum::TESTE,
                'dias_teste' => 15,
                'preco_mensal' => 0.00,
                'preco_trimestral' => 0.00,
                'preco_semestral' => 0.00,
                'preco_anual' => 0.00,
                'limite_usuarios' => 999, // Praticamente ilimitado
                'limite_empresas' => 999, // Praticamente ilimitado
                'funcionalidades' => [
                    'Acesso total ao sistema',
                    'Todas as funcionalidades disponíveis',
                    'Suporte completo',
                    'Período de teste de 15 dias',
                ],
            ],
        ];

        foreach ($planos as $planoData) {
            // Usar updateOrCreate para atualizar planos existentes ou criar novos
            // Busca pelo tipo para manter o mesmo plano se já existir
            Plano::updateOrCreate(
                [
                    'tipo' => $planoData['tipo'],
                ],
                [
                    'nome' => $planoData['nome'],
                    'descricao' => $planoData['descricao'],
                    'preco_mensal' => $planoData['preco_mensal'],
                    'preco_trimestral' => $planoData['preco_trimestral'],
                    'preco_semestral' => $planoData['preco_semestral'],
                    'preco_anual' => $planoData['preco_anual'],
                    'limite_usuarios' => $planoData['limite_usuarios'] ?? null,
                    'limite_empresas' => $planoData['limite_empresas'] ?? null,
                    'dias_teste' => $planoData['dias_teste'] ?? null,
                    'funcionalidades' => $planoData['funcionalidades'] ?? [],
                    'ativo' => $planoData['ativo'] ?? true,
                ]
            );
        }

        $this->command->info('Planos criados com sucesso!');
    }
}
