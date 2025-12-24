<?php

namespace App\Enums;

enum EmpresaTipoEnum: int
{
    case PJ = 1;
    case PF = 2;

    public function getLabel(): string
    {
        return match($this) {
            self::PJ => 'Pessoa Jurídica',
            self::PF => 'Pessoa Física',
        };
    }

    public function getValue(): string
    {
        return match($this) {
            self::PJ => 'PJ',
            self::PF => 'PF',
        };
    }

    public static function fromString(string $value): ?self
    {
        return match(strtoupper($value)) {
            'PJ' => self::PJ,
            'PF' => self::PF,
            default => null,
        };
    }

    public static function options(): array
    {
        return [
            self::PJ->value => self::PJ->getLabel(),
            self::PF->value => self::PF->getLabel(),
        ];
    }
}



