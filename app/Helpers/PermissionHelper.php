<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class PermissionHelper
{
    /**
     * Verifica se o usuário tem permissão para uma ação específica
     */
    public static function can($modulo, $acao): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();

        if (!$user instanceof Usuario) {
            return false;
        }

        /** @var Usuario $user */
        // Se o usuário é admin, permite tudo
        if ($user->isAdmin()) {
            return true;
        }

        return $user->temPermissao($modulo, $acao);
    }

    /**
     * Verifica se o usuário pode acessar uma rota específica
     */
    public static function canAccessRoute($routeName): bool
    {
        $routePermissions = [
            'usuarios.index' => ['usuarios', 'listar'],
            'usuarios.create' => ['usuarios', 'criar'],
            'usuarios.show' => ['usuarios', 'visualizar'],
            'usuarios.edit' => ['usuarios', 'editar'],
            'usuarios.destroy' => ['usuarios', 'deletar'],

            'empresas.index' => ['empresas', 'listar'],
            'empresas.create' => ['empresas', 'criar'],
            'empresas.show' => ['empresas', 'visualizar'],
            'empresas.edit' => ['empresas', 'editar'],
            'empresas.destroy' => ['empresas', 'deletar'],

            'grupos.index' => ['grupos', 'listar'],
            'grupos.create' => ['grupos', 'criar'],
            'grupos.show' => ['grupos', 'visualizar'],
            'grupos.edit' => ['grupos', 'editar'],
            'grupos.destroy' => ['grupos', 'deletar'],

            'permissoes.index' => ['permissoes', 'listar'],
            'permissoes.create' => ['permissoes', 'criar'],
            'permissoes.show' => ['permissoes', 'visualizar'],
            'permissoes.edit' => ['permissoes', 'editar'],
            'permissoes.destroy' => ['permissoes', 'deletar'],
        ];

        if (!isset($routePermissions[$routeName])) {
            return true; // Se não há permissão definida, permite acesso
        }

        [$modulo, $acao] = $routePermissions[$routeName];
        return self::can($modulo, $acao);
    }

    /**
     * Retorna as permissões do usuário atual
     */
    public static function getUserPermissions(): array
    {
        if (!Auth::check()) {
            return [];
        }

        $user = Auth::user();

        if (!$user instanceof Usuario) {
            return [];
        }

        /** @var Usuario $user */
        if ($user->isAdmin()) {
            return ['*']; // Admin tem todas as permissões
        }

        $grupo = $user->grupos()->first();
        if (!$grupo) {
            return [];
        }

        return $grupo->permissoes()
            ->wherePivot('concedida', true)
            ->get()
            ->map(function ($permissao) {
                return $permissao->modulo . '.' . $permissao->acao;
            })
            ->toArray();
    }

    /**
     * Retorna a empresa principal do usuário logado
     */
    public static function getEmpresaPrincipal()
    {
        if (!Auth::check()) {
            return null;
        }

        $user = Auth::user();

        if (!$user instanceof Usuario) {
            return null;
        }

        return $user->empresas()->wherePivot('principal', 1)->first();
    }

    /**
     * Retorna o ID da empresa principal do usuário logado
     */
    public static function getEmpresaPrincipalId()
    {
        $empresaPrincipal = self::getEmpresaPrincipal();
        return $empresaPrincipal ? $empresaPrincipal->id : null;
    }
}

