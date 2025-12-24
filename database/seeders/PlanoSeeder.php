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
                'preco_mensal' => 29.90,
                'preco_trimestral' => 79.90, // 5% desconto
                'preco_semestral' => 149.90, // 10% desconto
                'preco_anual' => 269.90, // 20% desconto
                'limite_usuarios' => 5,
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
                'preco_mensal' => 59.90,
                'preco_trimestral' => 159.90, // 5% desconto
                'preco_semestral' => 299.90, // 10% desconto
                'preco_anual' => 539.90, // 20% desconto
                'limite_usuarios' => 15,
                'limite_empresas' => 3,
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
                'preco_mensal' => 99.90,
                'preco_trimestral' => 269.90, // 5% desconto
                'preco_semestral' => 509.90, // 10% desconto
                'preco_anual' => 919.90, // 20% desconto
                'limite_usuarios' => 50,
                'limite_empresas' => 10,
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
            ],
            [
                'nome' => 'Plano de Teste',
                'descricao' => 'Plano de teste gratuito para novos usuários - 10 dias',
                'tipo' => PlanoTipoEnum::TESTE,
                'dias_teste' => 10,
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
                    'Período de teste de 10 dias',
                ],
            ],
        ];

        foreach ($planos as $planoData) {
            Plano::create([
                'id' => Str::uuid()->toString(),
                ...$planoData,
                'ativo' => true,
            ]);
        }

        $this->command->info('Planos criados com sucesso!');
    }
}
