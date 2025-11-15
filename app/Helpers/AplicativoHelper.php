<?php

namespace App\Helpers;

class AplicativoHelper
{
    /**
     * Verifica se o usuário tem um aplicativo específico
     * 
     * @param string $codigo Código do aplicativo (ex: 'loteamento')
     * @return bool
     */
    public static function temAplicativo(string $codigo): bool
    {
        $aplicativos = session('aplicativos_empresa', []);
        return in_array($codigo, $aplicativos);
    }

    /**
     * Obtém todos os códigos dos aplicativos da empresa na sessão
     * 
     * @return array
     */
    public static function getAplicativos(): array
    {
        return session('aplicativos_empresa', []);
    }
}

