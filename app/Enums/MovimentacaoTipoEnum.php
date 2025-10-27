<?php

namespace App\Enums;

enum MovimentacaoTipoEnum: int
{
    case PAGAR = 1;
    case RECEBER = 2;

    public function getLabel(): string
    {
        return match($this) {
            self::PAGAR => 'Contas a Pagar',
            self::RECEBER => 'Contas a Receber',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::PAGAR => 'red',
            self::RECEBER => 'green',
        };
    }

    public function getIcon(): string
    {
        return match($this) {
            self::PAGAR => 'fas fa-arrow-up',
            self::RECEBER => 'fas fa-arrow-down',
        };
    }

    public static function getOptions(): array
    {
        return [
            self::PAGAR->value => self::PAGAR->getLabel(),
            self::RECEBER->value => self::RECEBER->getLabel(),
        ];
    }
}
