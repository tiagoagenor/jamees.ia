<?php

namespace App\Services\v1\Empresa;

use App\Models\Empresa;

class DeletarEmpresaService
{
    public function execute(Empresa $empresa)
    {
        try {
            $empresa->delete();

            return redirect()->route('empresas.index')
                ->with('success', 'Empresa excluída com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao excluir empresa: ' . $e->getMessage());
        }
    }
}
