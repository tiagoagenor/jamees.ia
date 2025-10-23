<?php

namespace App\Services\v1\Empresa;

use App\Models\Empresa;

class MostrarEmpresaService
{
    public function execute(Empresa $empresa)
    {
        $empresa->load('contatos', 'enderecos');

        return view('empresas.show', [
            'empresa' => $empresa
        ]);
    }
}
