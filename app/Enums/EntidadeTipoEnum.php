<?php

namespace App\Enums;

enum EntidadeTipoEnum: int
{
    case CLIENTE = 1;
    case FORNECEDOR = 2;
    case FUNCIONARIO = 3;
    case TRANSPORTADORA = 4;

    public function getLabel(): string
    {
        return match($this) {
            self::CLIENTE => 'Cliente',
            self::FORNECEDOR => 'Fornecedor',
            self::FUNCIONARIO => 'Funcionário',
            self::TRANSPORTADORA => 'Transportadora',
        };
    }

    public function getDescription(): string
    {
        return match($this) {
            self::CLIENTE => 'Entidade que compra produtos/serviços',
            self::FORNECEDOR => 'Entidade que fornece produtos/serviços',
            self::FUNCIONARIO => 'Funcionário da empresa',
            self::TRANSPORTADORA => 'Empresa responsável pelo transporte',
        };
    }

    public function getIcon(): string
    {
        return match($this) {
            self::CLIENTE => 'fas fa-user-tie',
            self::FORNECEDOR => 'fas fa-truck',
            self::FUNCIONARIO => 'fas fa-user',
            self::TRANSPORTADORA => 'fas fa-shipping-fast',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::CLIENTE => 'blue',
            self::FORNECEDOR => 'green',
            self::FUNCIONARIO => 'purple',
            self::TRANSPORTADORA => 'orange',
        };
    }
}
