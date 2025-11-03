<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Grupo;

class UsuarioGrupoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar o usuário admin (primeiro usuário criado)
        $usuarioAdmin = Usuario::first();

        if ($usuarioAdmin) {
            // Buscar o grupo Administrativo
            $grupoAdmin = Grupo::where('administrativo', true)->first();

            if ($grupoAdmin) {
                // Vincular usuário ao grupo administrativo
                $usuarioAdmin->grupos()->sync([$grupoAdmin->id]);
            }
        }
    }
}
