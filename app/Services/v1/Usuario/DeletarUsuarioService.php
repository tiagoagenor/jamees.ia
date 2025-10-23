<?php

namespace App\Services\v1\Usuario;

use App\Models\Usuario;

class DeletarUsuarioService
{
    public function execute(Usuario $usuario)
    {
        try {
            $usuario->delete();

            return [
                'success' => true,
                'message' => 'Usuário excluído com sucesso!'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao excluir usuário: ' . $e->getMessage()
            ];
        }
    }
}
