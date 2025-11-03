<?php

namespace App\Enums;

enum PlanoTipoEnum: string
{
    case BASE = 'base';
    case PREMIUM = 'premium';
    case MASTER = 'master';
    case PERSONALIZADO = 'personalizado';
    case TESTE = 'teste';

    public function getLabel(): string
    {
        return match($this) {
            self::BASE => 'Plano Base',
            self::PREMIUM => 'Plano Premium',
            self::MASTER => 'Plano Master',
            self::PERSONALIZADO => 'Plano Personalizado',
            self::TESTE => 'Plano de Teste',
        };
    }

    public function getDescription(): string
    {
        return match($this) {
            self::BASE => 'Plano básico com funcionalidades essenciais',
            self::PREMIUM => 'Plano premium com funcionalidades avançadas',
            self::MASTER => 'Plano master com todas as funcionalidades',
            self::PERSONALIZADO => 'Plano personalizado conforme necessidade',
            self::TESTE => 'Plano de teste gratuito para novos usuários',
        };
    }
}
