<?php

namespace App\Services\v1\Empresa;

use App\Models\Empresa;
use App\Models\Whitelabel;

class FormularioEditarEmpresaService
{
    public function execute(Empresa $empresa)
    {
        $empresa->load('whitelabel', 'contatos', 'enderecos');
        $whitelabels = Whitelabel::all();

        return view('empresas.edit', [
            'empresa' => $empresa,
            'whitelabels' => $whitelabels
        ]);
    }
}
