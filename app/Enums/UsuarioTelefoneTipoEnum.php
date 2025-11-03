<?php

namespace App\Enums;

enum UsuarioTelefoneTipoEnum: int
{
    case CELULAR = 1;
    case RESIDENCIAL = 2;
    case COMERCIAL = 3;
    case WHATSAPP = 4;

    public function label(): string
    {
        return match($this) {
            self::CELULAR => 'Celular',
            self::RESIDENCIAL => 'Residencial',
            self::COMERCIAL => 'Comercial',
            self::WHATSAPP => 'WhatsApp',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::CELULAR => 'fas fa-mobile-alt',
            self::RESIDENCIAL => 'fas fa-phone',
            self::COMERCIAL => 'fas fa-building',
            self::WHATSAPP => 'fab fa-whatsapp',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::CELULAR => 'blue',
            self::RESIDENCIAL => 'gray',
            self::COMERCIAL => 'green',
            self::WHATSAPP => 'green',
        };
    }

    public static function options(): array
    {
        return [
            self::CELULAR->value => self::CELULAR->label(),
            self::RESIDENCIAL->value => self::RESIDENCIAL->label(),
            self::COMERCIAL->value => self::COMERCIAL->label(),
            self::WHATSAPP->value => self::WHATSAPP->label(),
        ];
    }

    public static function fromValue(int $value): ?self
    {
        return match($value) {
            1 => self::CELULAR,
            2 => self::RESIDENCIAL,
            3 => self::COMERCIAL,
            4 => self::WHATSAPP,
            default => null,
        };
    }

    public static function fromString(string $string): ?self
    {
        return match(strtolower($string)) {
            'celular' => self::CELULAR,
            'residencial' => self::RESIDENCIAL,
            'comercial' => self::COMERCIAL,
            'whatsapp' => self::WHATSAPP,
            default => null,
        };
    }
}
