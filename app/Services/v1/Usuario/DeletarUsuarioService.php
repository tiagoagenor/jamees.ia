<?php

namespace App\Services\v1\Usuario;

use App\Models\Usuario;

class DeletarUsuarioService
{
    public function execute(Usuario $usuario)
    {
        try {
            $usuario->delete();

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuário excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao excluir usuário: ' . $e->getMessage());
        }
    }
}
