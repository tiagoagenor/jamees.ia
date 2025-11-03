<?php

namespace App\Enums;

enum PlanoPeriodoEnum: string
{
    case MENSAL = 'mensal';
    case TRIMESTRAL = 'trimestral';
    case SEMESTRAL = 'semestral';
    case ANUAL = 'anual';

    public function getLabel(): string
    {
        return match($this) {
            self::MENSAL => 'Mensal',
            self::TRIMESTRAL => 'Trimestral',
            self::SEMESTRAL => 'Semestral',
            self::ANUAL => 'Anual',
        };
    }

    public function getDays(): int
    {
        return match($this) {
            self::MENSAL => 30,
            self::TRIMESTRAL => 90,
            self::SEMESTRAL => 180,
            self::ANUAL => 365,
        };
    }

    public function getDiscount(): float
    {
        return match($this) {
            self::MENSAL => 0.0,
            self::TRIMESTRAL => 0.05, // 5% desconto
            self::SEMESTRAL => 0.10, // 10% desconto
            self::ANUAL => 0.20, // 20% desconto
        };
    }
}
