<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Usuario::create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'nome' => 'Administrador',
            'email' => 'admin@jamees.com',
            'senha' => Hash::make('123456'),
            'status' => 1,
            'criado_em' => now(),
            'atualizado_em' => now(),
        ]);
    }
}
