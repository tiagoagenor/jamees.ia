<?php

namespace App\Enums;

enum UsuarioStatusEnum: int
{
    case INATIVO = 0;
    case ATIVO = 1;

    public function label(): string
    {
        return match($this) {
            self::ATIVO => 'Ativo',
            self::INATIVO => 'Inativo',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ATIVO => 'green',
            self::INATIVO => 'red',
        };
    }

    public static function options(): array
    {
        return [
            self::ATIVO->value => self::ATIVO->label(),
            self::INATIVO->value => self::INATIVO->label(),
        ];
    }

    public static function fromValue(int $value): ?self
    {
        return match($value) {
            0 => self::INATIVO,
            1 => self::ATIVO,
            default => null,
        };
    }
}
