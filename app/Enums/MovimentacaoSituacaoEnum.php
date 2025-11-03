<?php

namespace App\Enums;

enum MovimentacaoSituacaoEnum: int
{
    case PENDENTE = 1;
    case PAGA = 2;
    case VENCIDA = 3;
    case CANCELADA = 4;

    public function getLabel(): string
    {
        return match($this) {
            self::PENDENTE => 'Pendente',
            self::PAGA => 'Paga',
            self::VENCIDA => 'Vencida',
            self::CANCELADA => 'Cancelada',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::PENDENTE => 'yellow',
            self::PAGA => 'green',
            self::VENCIDA => 'red',
            self::CANCELADA => 'gray',
        };
    }

    public function getIcon(): string
    {
        return match($this) {
            self::PENDENTE => 'fas fa-clock',
            self::PAGA => 'fas fa-check-circle',
            self::VENCIDA => 'fas fa-exclamation-triangle',
            self::CANCELADA => 'fas fa-times-circle',
        };
    }

    public static function getOptions(): array
    {
        return [
            self::PENDENTE->value => self::PENDENTE->getLabel(),
            self::PAGA->value => self::PAGA->getLabel(),
            self::VENCIDA->value => self::VENCIDA->getLabel(),
            self::CANCELADA->value => self::CANCELADA->getLabel(),
        ];
    }
}
