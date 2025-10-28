<?php

namespace App\Services\v1\Usuario;

use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use App\Services\AuditService;

class DeletarUsuarioService
{
    public function execute(Usuario $usuario)
    {
        try {
            $usuarioAtual = Auth::user();

            // Verificar se o usuário atual pode deletar o usuário alvo
            if (!$usuarioAtual->canDeleteUser($usuario)) {
                $mensagem = 'Você não tem permissão para deletar este usuário.';

                if ($usuario->isPrincipal()) {
                    $mensagem = 'Não é possível deletar o usuário principal do sistema.';
                } elseif ($usuarioAtual->isAdmin() && $usuario->isAdmin()) {
                    $mensagem = 'Apenas o usuário principal pode deletar outros administradores.';
                } elseif (!$usuarioAtual->isAdmin() && $usuario->isAdmin()) {
                    $mensagem = 'Apenas administradores podem deletar outros administradores.';
                }

                return redirect()->back()
                    ->with('error', $mensagem);
            }

            // Registrar no audit log antes da exclusão
            AuditService::logDelete($usuario, "Excluiu usuário: {$usuario->nome}");

            $usuario->delete();

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuário excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao excluir usuário: ' . $e->getMessage());
        }
    }
}
