<?php

namespace App\Enums;

enum FormaPagamentoModalidadeEnum: int
{
    case DINHEIRO = 1;
    case CARTAO_CREDITO = 2;
    case CARTAO_DEBITO = 3;
    case CHEQUE = 4;
    case BOLETO = 5;
    case TRANSFERENCIA = 6;
    case DEPOSITO = 8;
    case DEVOLUCAO_MERCADORIAS = 10;
    case DUPLICATA_MERCANTIL = 11;
    case CARNE = 12;
    case BOLETO_BANCARIO = 13;
    case PIX = 15;
    case TRANSFERENCIA_BANCARIA = 16;
    case A_COMBINAR = 18;

    public function getLabel(): string
    {
        return match($this) {
            self::DINHEIRO => 'Dinheiro',
            self::CARTAO_CREDITO => 'Cartão de Crédito',
            self::CARTAO_DEBITO => 'Cartão de Débito',
            self::CHEQUE => 'Cheque',
            self::BOLETO => 'Boleto',
            self::TRANSFERENCIA => 'Transferência',
            self::DEPOSITO => 'Depósito',
            self::DEVOLUCAO_MERCADORIAS => 'Devolução de Mercadorias',
            self::DUPLICATA_MERCANTIL => 'Duplicata Mercantil',
            self::CARNE => 'Carnê',
            self::BOLETO_BANCARIO => 'Boleto Bancário',
            self::PIX => 'PIX',
            self::TRANSFERENCIA_BANCARIA => 'Transferência Bancária',
            self::A_COMBINAR => 'A Combinar',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::DINHEIRO => 'green',
            self::CARTAO_CREDITO => 'blue',
            self::CARTAO_DEBITO => 'purple',
            self::CHEQUE => 'pink',
            self::BOLETO => 'orange',
            self::TRANSFERENCIA => 'indigo',
            self::DEPOSITO => 'gray',
            self::DEVOLUCAO_MERCADORIAS => 'red',
            self::DUPLICATA_MERCANTIL => 'indigo',
            self::CARNE => 'yellow',
            self::BOLETO_BANCARIO => 'orange',
            self::PIX => 'yellow',
            self::TRANSFERENCIA_BANCARIA => 'indigo',
            self::A_COMBINAR => 'gray',
        };
    }

    public function getIcon(): string
    {
        return match($this) {
            self::DINHEIRO => 'fas fa-money-bill-wave',
            self::CARTAO_CREDITO => 'fas fa-credit-card',
            self::CARTAO_DEBITO => 'fas fa-credit-card',
            self::CHEQUE => 'fas fa-file-signature',
            self::BOLETO => 'fas fa-file-invoice',
            self::TRANSFERENCIA => 'fas fa-exchange-alt',
            self::DEPOSITO => 'fas fa-university',
            self::DEVOLUCAO_MERCADORIAS => 'fas fa-undo',
            self::DUPLICATA_MERCANTIL => 'fas fa-file-contract',
            self::CARNE => 'fas fa-book',
            self::BOLETO_BANCARIO => 'fas fa-file-invoice',
            self::PIX => 'fas fa-qrcode',
            self::TRANSFERENCIA_BANCARIA => 'fas fa-exchange-alt',
            self::A_COMBINAR => 'fas fa-handshake',
        };
    }
}
