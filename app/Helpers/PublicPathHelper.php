<?php

namespace App\Helpers;

class PublicPathHelper
{
    /**
     * Retorna o caminho base da pasta public
     * Se PUBLIC_PATH estiver definido no .env, usa esse valor
     * Caso contrário, usa o public_path() padrão do Laravel
     */
    public static function getBasePath(): string
    {
        $customPath = env('PUBLIC_PATH', null);

        if ($customPath !== null) {
            // Remove barra final se existir
            $customPath = rtrim($customPath, '/');
            // Se for relativo (começa com .), converte para absoluto
            if (substr($customPath, 0, 2) === './') {
                $customPath = base_path($customPath);
            }
            return $customPath;
        }

        return public_path();
    }

    /**
     * Retorna o caminho completo para um arquivo/pasta dentro do public
     * Equivalente a public_path($path), mas usando a configuração do .env
     */
    public static function path(string $path = ''): string
    {
        $basePath = self::getBasePath();

        if (empty($path)) {
            return $basePath;
        }

        // Remove barra inicial do path se existir
        $path = ltrim($path, '/');

        return $basePath . '/' . $path;
    }
}

