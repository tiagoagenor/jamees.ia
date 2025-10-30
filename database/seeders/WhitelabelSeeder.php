<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Whitelabel;

class WhitelabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Whitelabel::create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'nome' => 'jamees',
            'dominio' => null,
            'criado_em' => now(),
            'atualizado_em' => now(),
        ]);

        // Novo whitelabel: lotees
        Whitelabel::create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'nome' => 'lotees',
            'dominio' => 'lotees.com',
            'criado_em' => now(),
            'atualizado_em' => now(),
        ]);
    }
}
