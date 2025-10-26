<?php

namespace App\Enums;

enum DreTipoEnum: int
{
    case RECEITA = 1;
    case DESPESA = 2;
    case TOTALIZADOR = 3;

    public function getLabel(): string
    {
        return match($this) {
            self::RECEITA => 'Receita',
            self::DESPESA => 'Despesa',
            self::TOTALIZADOR => 'Totalizador',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::RECEITA => 'green',
            self::DESPESA => 'red',
            self::TOTALIZADOR => 'blue',
        };
    }

    public function getIcon(): string
    {
        return match($this) {
            self::RECEITA => 'fas fa-arrow-up',
            self::DESPESA => 'fas fa-arrow-down',
            self::TOTALIZADOR => 'fas fa-equals',
        };
    }
}
