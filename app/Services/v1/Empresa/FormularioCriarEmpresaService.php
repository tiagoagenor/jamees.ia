<?php

namespace App\Services\v1\Empresa;

class FormularioCriarEmpresaService
{
    public function execute()
    {
        return view('empresas.create');
    }
}
