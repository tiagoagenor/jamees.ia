<?php

namespace App\Services\v1\Usuario;

use App\Models\Usuario;
use App\Models\Empresa;

class FormularioEditarUsuarioService
{
    public function execute(Usuario $usuario)
    {
        $usuario->load('empresas', 'geral', 'enderecos', 'telefones');
        $empresas = Empresa::all();

        return view('usuarios.edit', [
            'usuario' => $usuario,
            'empresas' => $empresas
        ]);
    }
}
