<?php

namespace App\Services\v1\Empresa;

use App\Models\Empresa;
use App\Services\AuditService;

class DeletarEmpresaService
{
    public function execute(Empresa $empresa)
    {
        try {
            // Registrar no audit log antes da exclusão
            AuditService::logDelete($empresa, "Excluiu empresa: {$empresa->nome_fantasia}");

            $empresa->delete();

            return redirect()->route('empresas.index')
                ->with('success', 'Empresa excluída com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao excluir empresa: ' . $e->getMessage());
        }
    }
}
