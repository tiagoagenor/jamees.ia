<?php

namespace App\Enums;

enum EntidadeTipoEnum: int
{
    case CLIENTE = 1;
    case FORNECEDOR = 2;
    case FUNCIONARIO = 3;
    case TRANSPORTADORA = 4;
    case LOTEAMENTO = 5;

    public function getLabel(): string
    {
        return match($this) {
            self::CLIENTE => 'Cliente',
            self::FORNECEDOR => 'Fornecedor',
            self::FUNCIONARIO => 'Funcionário',
            self::TRANSPORTADORA => 'Transportadora',
            self::LOTEAMENTO => 'Loteamento',
        };
    }

    public function getDescription(): string
    {
        return match($this) {
            self::CLIENTE => 'Entidade que compra produtos/serviços',
            self::FORNECEDOR => 'Entidade que fornece produtos/serviços',
            self::FUNCIONARIO => 'Funcionário da empresa',
            self::TRANSPORTADORA => 'Empresa responsável pelo transporte',
            self::LOTEAMENTO => 'Lote vinculado a uma venda',
        };
    }

    public function getIcon(): string
    {
        return match($this) {
            self::CLIENTE => 'fas fa-user-tie',
            self::FORNECEDOR => 'fas fa-truck',
            self::FUNCIONARIO => 'fas fa-user',
            self::TRANSPORTADORA => 'fas fa-shipping-fast',
            self::LOTEAMENTO => 'fas fa-map-marked-alt',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::CLIENTE => 'blue',
            self::FORNECEDOR => 'green',
            self::FUNCIONARIO => 'purple',
            self::TRANSPORTADORA => 'orange',
            self::LOTEAMENTO => 'indigo',
        };
    }
}
