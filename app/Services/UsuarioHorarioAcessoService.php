<?php

namespace App\Services;

use App\Models\Usuario;
use Carbon\Carbon;

class UsuarioHorarioAcessoService
{
    /**
     * Validar se o usuário pode acessar no momento atual
     */
    public function validarAcesso(Usuario $usuario): bool
    {
        $horarioAcesso = $usuario->horarioAcesso;

        // Se não há horário de acesso configurado ou está desativado, permitir acesso
        if (!$horarioAcesso || !$horarioAcesso->ativo) {
            return true;
        }

        $agora = Carbon::now();
        $diaSemanaAtual = $this->obterDiaSemana($agora->dayOfWeek);
        $horaAtual = $agora->format('H:i');

        // Verificar se o dia atual está permitido
        if (!in_array($diaSemanaAtual, $horarioAcesso->dias_permitidos ?? [])) {
            return false;
        }

        // Converter horários para formato H:i para comparação
        // Os campos time do banco retornam como string no formato H:i:s ou H:i
        $horaEntrada = $horarioAcesso->hora_entrada ? substr($horarioAcesso->hora_entrada, 0, 5) : null;
        $horaAlmocoInicio = $horarioAcesso->hora_almoco_inicio ? substr($horarioAcesso->hora_almoco_inicio, 0, 5) : null;
        $horaAlmocoFim = $horarioAcesso->hora_almoco_fim ? substr($horarioAcesso->hora_almoco_fim, 0, 5) : null;
        $horaSaida = $horarioAcesso->hora_saida ? substr($horarioAcesso->hora_saida, 0, 5) : null;

        // Verificar se está no horário permitido
        // Permitir acesso entre hora_entrada e hora_almoco_inicio
        // OU entre hora_almoco_fim e hora_saida
        if ($horaEntrada && $horaAlmocoInicio && $horaAtual >= $horaEntrada && $horaAtual < $horaAlmocoInicio) {
            return true;
        }

        if ($horaAlmocoFim && $horaSaida && $horaAtual >= $horaAlmocoFim && $horaAtual <= $horaSaida) {
            return true;
        }

        return false;
    }

    /**
     * Obter mensagem de erro personalizada
     */
    public function obterMensagemErro(Usuario $usuario): string
    {
        $horarioAcesso = $usuario->horarioAcesso;

        if (!$horarioAcesso || !$horarioAcesso->ativo) {
            return 'Acesso negado.';
        }

        $agora = Carbon::now();
        $diaSemanaAtual = $this->obterDiaSemana($agora->dayOfWeek);
        $horaAtual = $agora->format('H:i');

        // Verificar se o problema é o dia
        if (!in_array($diaSemanaAtual, $horarioAcesso->dias_permitidos ?? [])) {
            $diasPermitidos = implode(', ', array_map('ucfirst', $horarioAcesso->dias_permitidos ?? []));
            return "Acesso permitido apenas nos seguintes dias: {$diasPermitidos}.";
        }

        // Verificar se o problema é o horário
        $horaEntrada = $horarioAcesso->hora_entrada ? substr($horarioAcesso->hora_entrada, 0, 5) : null;
        $horaAlmocoInicio = $horarioAcesso->hora_almoco_inicio ? substr($horarioAcesso->hora_almoco_inicio, 0, 5) : null;
        $horaAlmocoFim = $horarioAcesso->hora_almoco_fim ? substr($horarioAcesso->hora_almoco_fim, 0, 5) : null;
        $horaSaida = $horarioAcesso->hora_saida ? substr($horarioAcesso->hora_saida, 0, 5) : null;

        if ($horaEntrada && $horaSaida) {
            if ($horaAlmocoInicio && $horaAlmocoFim) {
                return "Acesso permitido de {$horaEntrada} às {$horaAlmocoInicio} e de {$horaAlmocoFim} às {$horaSaida}.";
            } else {
                return "Acesso permitido de {$horaEntrada} às {$horaSaida}.";
            }
        }

        return 'Acesso negado fora do horário permitido.';
    }

    /**
     * Converter número do dia da semana (Carbon) para nome em português
     */
    private function obterDiaSemana(int $dayOfWeek): string
    {
        $dias = [
            0 => 'domingo',
            1 => 'segunda',
            2 => 'terça',
            3 => 'quarta',
            4 => 'quinta',
            5 => 'sexta',
            6 => 'sabado',
        ];

        return $dias[$dayOfWeek] ?? 'domingo';
    }
}

