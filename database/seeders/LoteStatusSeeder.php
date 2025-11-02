<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LoteStatus;
use App\Models\Empresa;

class LoteStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Status padrão para cada empresa
        // tipo: 1 = disponível/negociação/reserva, 2 = vendido
        $statusPadrao = [
            ['nome' => 'Disponível', 'tipo' => 1, 'cor' => '#10b981'], // Verde - disponível
            ['nome' => 'Negociação', 'tipo' => 1, 'cor' => '#3b82f6'], // Azul - negociação
            ['nome' => 'Reserva Cliente', 'tipo' => 1, 'cor' => '#f59e0b'], // Amarelo/Laranja - reserva cliente
            ['nome' => 'Reserva Técnica', 'tipo' => 1, 'cor' => '#8b5cf6'], // Roxo - reserva técnica
            ['nome' => 'Vendido', 'tipo' => 2, 'cor' => '#ef4444'], // Vermelho - vendido
        ];

        // Buscar todas as empresas
        $empresas = Empresa::all();

        foreach ($empresas as $empresa) {
            foreach ($statusPadrao as $status) {
                // Verificar se já existe
                $exists = LoteStatus::where('empresa_id', $empresa->id)
                    ->where('nome', $status['nome'])
                    ->first();

                if (!$exists) {
                    LoteStatus::create([
                        'empresa_id' => $empresa->id,
                        'nome' => $status['nome'],
                        'tipo' => $status['tipo'],
                        'cor' => $status['cor'],
                    ]);
                }
            }
        }
    }
}
