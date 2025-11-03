<?php

namespace App\Enums;

enum PlanoContaMovimentacaoEnum: int
{
    case DEBITO = 1;
    case CREDITO = 2;

    public function getLabel(): string
    {
        return match($this) {
            self::DEBITO => 'Débito',
            self::CREDITO => 'Crédito',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::DEBITO => 'red',
            self::CREDITO => 'green',
        };
    }

    public function getIcon(): string
    {
        return match($this) {
            self::DEBITO => 'fas fa-minus-circle',
            self::CREDITO => 'fas fa-plus-circle',
        };
    }
}
