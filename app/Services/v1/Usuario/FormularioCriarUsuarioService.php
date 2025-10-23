<?php

namespace App\Services\v1\Usuario;

use App\Models\Empresa;

class FormularioCriarUsuarioService
{
    public function execute()
    {
        $empresas = Empresa::all();

        return [
            'empresas' => $empresas
        ];
    }
}
