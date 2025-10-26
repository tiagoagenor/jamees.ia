<?php

namespace App\Enums;

enum PlanoStatusEnum: string
{
    case ATIVO = 'ativo';
    case INATIVO = 'inativo';
    case TESTE = 'teste';
    case EXPIRADO = 'expirado';
    case CANCELADO = 'cancelado';

    public function getLabel(): string
    {
        return match($this) {
            self::ATIVO => 'Ativo',
            self::INATIVO => 'Inativo',
            self::TESTE => 'Período de Teste',
            self::EXPIRADO => 'Expirado',
            self::CANCELADO => 'Cancelado',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::ATIVO => 'green',
            self::INATIVO => 'gray',
            self::TESTE => 'blue',
            self::EXPIRADO => 'red',
            self::CANCELADO => 'orange',
        };
    }
}
