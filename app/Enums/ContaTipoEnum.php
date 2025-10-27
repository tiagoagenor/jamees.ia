<?php

namespace App\Enums;

enum ContaTipoEnum: int
{
    case CORRENTE = 1;
    case POUPANCA = 2;
    case INVESTIMENTO = 3;
    case CARTAO_CREDITO = 4;
    case CARTAO_DEBITO = 5;

    public function getLabel(): string
    {
        return match($this) {
            self::CORRENTE => 'Conta Corrente',
            self::POUPANCA => 'Conta Poupança',
            self::INVESTIMENTO => 'Conta Investimento',
            self::CARTAO_CREDITO => 'Cartão de Crédito',
            self::CARTAO_DEBITO => 'Cartão de Débito',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::CORRENTE => 'blue',
            self::POUPANCA => 'green',
            self::INVESTIMENTO => 'purple',
            self::CARTAO_CREDITO => 'red',
            self::CARTAO_DEBITO => 'orange',
        };
    }

    public function getIcon(): string
    {
        return match($this) {
            self::CORRENTE => 'fas fa-university',
            self::POUPANCA => 'fas fa-piggy-bank',
            self::INVESTIMENTO => 'fas fa-chart-line',
            self::CARTAO_CREDITO => 'fas fa-credit-card',
            self::CARTAO_DEBITO => 'fas fa-credit-card',
        };
    }
}
