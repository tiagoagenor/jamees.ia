<?php

namespace App\Enums;

enum EmpresaStatusEnum: int
{
    case INATIVA = 0;
    case ATIVA = 1;

    public function label(): string
    {
        return match($this) {
            self::ATIVA => 'Ativa',
            self::INATIVA => 'Inativa',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ATIVA => 'green',
            self::INATIVA => 'red',
        };
    }

    public static function options(): array
    {
        return [
            self::ATIVA->value => self::ATIVA->label(),
            self::INATIVA->value => self::INATIVA->label(),
        ];
    }

    public static function fromValue(int $value): ?self
    {
        return match($value) {
            0 => self::INATIVA,
            1 => self::ATIVA,
            default => null,
        };
    }
}
