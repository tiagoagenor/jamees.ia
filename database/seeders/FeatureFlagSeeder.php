<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FeatureFlag;
use App\Helpers\FeatureFlagHelper;

class FeatureFlagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $featureFlags = [
            [
                'codigo' => 'aplicativos',
                'nome' => 'Aplicativos',
                'descricao' => 'Habilita a exibição do menu e botão de Aplicativos',
                'escopo' => 'global',
                'ativo' => true,
            ],
            [
                'codigo' => 'boleto_bancario',
                'nome' => 'Boleto Bancário',
                'descricao' => 'Habilita a opção de pagamento via boleto bancário',
                'escopo' => 'global',
                'ativo' => true,
            ],
        ];

        foreach ($featureFlags as $flag) {
            FeatureFlagHelper::criarOuAtualizar(
                codigo: $flag['codigo'],
                nome: $flag['nome'],
                escopo: $flag['escopo'],
                ativo: $flag['ativo'],
                descricao: $flag['descricao'] ?? null
            );
        }

        $this->command->info('Feature flags criadas com sucesso!');
    }
}
