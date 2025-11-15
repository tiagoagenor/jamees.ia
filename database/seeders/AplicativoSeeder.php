<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Aplicativo;

class AplicativoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $aplicativos = [
            [
                'nome' => 'Loteamento',
                'descricao' => 'Sistema completo para gestão de loteamentos, vendas e reservas de lotes',
                'codigo' => 'loteamento',
                'categoria' => 'Gestão',
                'preco_mensal' => 50.00,
                'preco_trimestral' => 135.00, // 10% desconto
                'preco_semestral' => 270.00, // 10% desconto
                'preco_anual' => 480.00, // 20% desconto
                'ativo' => true,
            ],
        ];

        foreach ($aplicativos as $aplicativo) {
            Aplicativo::updateOrCreate(
                ['codigo' => $aplicativo['codigo']],
                $aplicativo
            );
        }
    }
}
