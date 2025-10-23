<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\Whitelabel;
use App\Models\Usuario;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $whitelabel = Whitelabel::first();
        $usuario = Usuario::first();

        $empresa = Empresa::create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'whitelabel_id' => $whitelabel->id,
            'nome_referencia' => 'Empresa Principal',
            'tipo' => 'PJ',
            'nome_fantasia' => 'Jamees Sistemas',
            'razao_social' => 'Jamees Sistemas LTDA',
            'cnpj' => '12.345.678/0001-90',
            'status' => 1,
            'principal' => 1,
            'criado_em' => now(),
            'atualizado_em' => now(),
        ]);

        // Vincular usuário à empresa
        $usuario->empresas()->attach($empresa->id, [
            'principal' => 1,
            'status' => 1,
            'criado_em' => now(),
            'atualizado_em' => now(),
        ]);
    }
}
