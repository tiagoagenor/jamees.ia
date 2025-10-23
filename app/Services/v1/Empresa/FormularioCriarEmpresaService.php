<?php

namespace App\Services\v1\Empresa;

use App\Models\Whitelabel;

class FormularioCriarEmpresaService
{
    public function execute()
    {
        $whitelabels = Whitelabel::all();

        return [
            'whitelabels' => $whitelabels
        ];
    }
}
