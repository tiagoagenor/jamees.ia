<?php

namespace App\Services\v1\Usuario;

use App\Models\Usuario;

class MostrarUsuarioService
{
    public function execute(Usuario $usuario)
    {
        $usuario->load('empresas', 'geral', 'enderecos', 'telefones');

        return view('usuarios.show', [
            'usuario' => $usuario
        ]);
    }
}
