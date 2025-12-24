<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CompanySwitchController extends Controller
{
    /**
     * Switch the current company for the authenticated user
     */
    public function switchCompany(Request $request)
    {
        try {
            $request->validate([
                'empresa_id' => 'required|string|exists:empresa,id'
            ]);

            $user = Auth::user();
            $companyId = $request->empresa_id;

            // Check if user has access to this company
            $userCompany = $user->empresas()->where('empresa.id', $companyId)->first();

            if (!$userCompany) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não tem acesso a esta empresa.'
                ], 403);
            }

            // Get the company details
            $company = Empresa::find($companyId);
            if (!$company) {
                return response()->json([
                    'success' => false,
                    'message' => 'Empresa não encontrada.'
                ], 404);
            }

            // Set the current company in session and force save
            session(['empresa_atual_id' => $company->id]);
            session(['whitelabel_atual_id' => $company->whitelabel_id]);
            session()->save(); // Force save to ensure persistence

            // Log the access
            $this->logAccess($user->id, $company->whitelabel_id, $company->id);

            Log::info('Empresa alterada na sessão', [
                'user_id' => $user->id,
                'empresa_id' => $company->id,
                'empresa_nome' => $company->nome_fantasia ?? $company->razao_social,
                'whitelabel_id' => $company->whitelabel_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Empresa alterada com sucesso.',
                'company' => [
                    'id' => $company->id,
                    'nome' => $company->nome_fantasia ?? $company->razao_social,
                    'tipo' => $company->tipo?->value
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao trocar empresa: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Log user access to company
     */
    private function logAccess($usuarioId, $whitelabelId, $empresaId)
    {
        try {
            \App\Models\UltimaAcesso::updateOrCreate(
                [
                    'usuario_id' => $usuarioId,
                    'whitelabel_id' => $whitelabelId,
                    'empresa_id' => $empresaId,
                ],
                [
                    'usuario_id' => $usuarioId,
                    'whitelabel_id' => $whitelabelId,
                    'empresa_id' => $empresaId,
                ]
            );
        } catch (\Exception $e) {
            // Log error but don't fail the request
            Log::error('Erro ao registrar acesso: ' . $e->getMessage());
        }
    }
}
