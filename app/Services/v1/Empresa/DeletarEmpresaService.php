<?php

namespace App\Services\v1\Empresa;

use App\Models\Empresa;

class DeletarEmpresaService
{
    public function execute(Empresa $empresa)
    {
        try {
            $empresa->delete();

            return [
                'success' => true,
                'message' => 'Empresa excluída com sucesso!'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao excluir empresa: ' . $e->getMessage()
            ];
        }
    }
}
