<?php

namespace App\Enums;

enum IdeiaStatusEnum: int
{
    case EM_ABERTO = 1;
    case EM_ANALISE = 2;
    case EM_DESENVOLVIMENTO = 3;
    case CONCLUIDO = 4;
    case SEM_PREVISAO = 5;

    public function label(): string
    {
        return match($this) {
            self::EM_ABERTO => 'Em Aberto',
            self::EM_ANALISE => 'Em Análise',
            self::EM_DESENVOLVIMENTO => 'Em Desenvolvimento',
            self::CONCLUIDO => 'Concluído',
            self::SEM_PREVISAO => 'Sem Previsão',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::EM_ABERTO => 'blue',
            self::EM_ANALISE => 'yellow',
            self::EM_DESENVOLVIMENTO => 'purple',
            self::CONCLUIDO => 'green',
            self::SEM_PREVISAO => 'red',
        };
    }

    public static function options(): array
    {
        return [
            self::EM_ABERTO->value => self::EM_ABERTO->label(),
            self::EM_ANALISE->value => self::EM_ANALISE->label(),
            self::EM_DESENVOLVIMENTO->value => self::EM_DESENVOLVIMENTO->label(),
            self::CONCLUIDO->value => self::CONCLUIDO->label(),
            self::SEM_PREVISAO->value => self::SEM_PREVISAO->label(),
        ];
    }

    public static function fromValue(int $value): ?self
    {
        return match($value) {
            1 => self::EM_ABERTO,
            2 => self::EM_ANALISE,
            3 => self::EM_DESENVOLVIMENTO,
            4 => self::CONCLUIDO,
            5 => self::SEM_PREVISAO,
            default => null,
        };
    }
}
