<?php

namespace App\Enums;

enum AtualizacaoTipoEnum: int
{
    case NOVOS_RECURSOS = 1;
    case MELHORIAS = 2;

    public function label(): string
    {
        return match($this) {
            self::NOVOS_RECURSOS => 'Novos Recursos',
            self::MELHORIAS => 'Melhorias',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::NOVOS_RECURSOS => 'fa-star',
            self::MELHORIAS => 'fa-wrench',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::NOVOS_RECURSOS => 'blue',
            self::MELHORIAS => 'orange',
        };
    }

    public static function options(): array
    {
        return [
            self::NOVOS_RECURSOS->value => self::NOVOS_RECURSOS->label(),
            self::MELHORIAS->value => self::MELHORIAS->label(),
        ];
    }

    public static function fromValue(int $value): ?self
    {
        return match($value) {
            1 => self::NOVOS_RECURSOS,
            2 => self::MELHORIAS,
            default => null,
        };
    }
}

