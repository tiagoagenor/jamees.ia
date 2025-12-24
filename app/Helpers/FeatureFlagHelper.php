<?php

namespace App\Helpers;

use App\Models\FeatureFlag;
use Illuminate\Support\Facades\Auth;

class FeatureFlagHelper
{
    /**
     * Carrega todas as feature flags ativas na sessão do usuário
     *
     * @param string|null $empresaId ID da empresa (opcional, usa empresa atual se não informado)
     * @param string|null $usuarioId ID do usuário (opcional, usa usuário logado se não informado)
     * @return void
     */
    public static function carregarNaSessao(?string $empresaId = null, ?string $usuarioId = null): void
    {
        // Se não informou empresa, tentar pegar da empresa atual
        if (!$empresaId && Auth::check()) {
            $user = Auth::user();
            if ($user instanceof \App\Models\Usuario) {
                $empresaAtual = $user->empresaAtual();
                if ($empresaAtual) {
                    $empresaId = $empresaAtual->id;
                }
            }
        }

        // Se não informou usuário, usar o usuário logado
        if (!$usuarioId && Auth::check()) {
            $usuarioId = Auth::id();
        }

        // Obter todas as feature flags ativas
        $flagsAtivas = self::obterAtivas($empresaId, $usuarioId);

        // Salvar na sessão como array associativo [codigo => true]
        $flagsArray = $flagsAtivas->mapWithKeys(function ($flag) {
            return [$flag->codigo => true];
        })->toArray();

        session(['feature_flags' => $flagsArray]);
        session()->save();
    }

    /**
     * Verifica se uma feature flag está ativa
     * Primeiro verifica na sessão, depois no banco se necessário
     *
     * @param string $codigo Código da feature flag
     * @param string|null $empresaId ID da empresa (opcional, usa empresa atual se não informado)
     * @param string|null $usuarioId ID do usuário (opcional, usa usuário logado se não informado)
     * @return bool
     */
    public static function estaAtiva(string $codigo, ?string $empresaId = null, ?string $usuarioId = null): bool
    {
        // Primeiro verificar na sessão (mais rápido)
        $flagsSessao = session('feature_flags', []);
        if (isset($flagsSessao[$codigo])) {
            return $flagsSessao[$codigo] === true;
        }

        // Se não está na sessão, verificar no banco
        // Se não informou empresa, tentar pegar da empresa atual
        if (!$empresaId && Auth::check()) {
            $user = Auth::user();
            if ($user instanceof \App\Models\Usuario) {
                $empresaAtual = $user->empresaAtual();
                if ($empresaAtual) {
                    $empresaId = $empresaAtual->id;
                }
            }
        }

        // Se não informou usuário, usar o usuário logado
        if (!$usuarioId && Auth::check()) {
            $usuarioId = Auth::id();
        }

        $estaAtiva = FeatureFlag::estaAtiva($codigo, $empresaId, $usuarioId);

        // Atualizar a sessão com o resultado
        if ($estaAtiva) {
            $flagsSessao[$codigo] = true;
            session(['feature_flags' => $flagsSessao]);
        }

        return $estaAtiva;
    }

    /**
     * Ativar uma feature flag
     *
     * @param string $codigo Código da feature flag
     * @param string $escopo Escopo: 'global', 'empresa' ou 'usuario'
     * @param string|null $empresaId ID da empresa (se escopo for empresa)
     * @param string|null $usuarioId ID do usuário (se escopo for usuario)
     * @param bool $recarregarSessao Se deve recarregar a sessão após ativar (padrão: true)
     * @return FeatureFlag
     */
    public static function ativar(string $codigo, string $escopo = 'global', ?string $empresaId = null, ?string $usuarioId = null, bool $recarregarSessao = true): FeatureFlag
    {
        $flag = FeatureFlag::ativar($codigo, $escopo, $empresaId, $usuarioId);

        // Recarregar sessão se solicitado e usuário estiver logado
        if ($recarregarSessao && Auth::check()) {
            self::carregarNaSessao($empresaId, $usuarioId);
        }

        return $flag;
    }

    /**
     * Desativar uma feature flag
     *
     * @param string $codigo Código da feature flag
     * @param string $escopo Escopo: 'global', 'empresa' ou 'usuario'
     * @param string|null $empresaId ID da empresa (se escopo for empresa)
     * @param string|null $usuarioId ID do usuário (se escopo for usuario)
     * @param bool $recarregarSessao Se deve recarregar a sessão após desativar (padrão: true)
     * @return bool
     */
    public static function desativar(string $codigo, string $escopo = 'global', ?string $empresaId = null, ?string $usuarioId = null, bool $recarregarSessao = true): bool
    {
        $resultado = FeatureFlag::desativar($codigo, $escopo, $empresaId, $usuarioId);

        // Recarregar sessão se solicitado e usuário estiver logado
        if ($recarregarSessao && Auth::check()) {
            self::carregarNaSessao($empresaId, $usuarioId);
        }

        return $resultado;
    }

    /**
     * Criar ou atualizar uma feature flag
     *
     * @param string $codigo Código único da feature flag
     * @param string $nome Nome descritivo
     * @param string $escopo Escopo: 'global', 'empresa' ou 'usuario'
     * @param bool $ativo Se está ativa
     * @param string|null $descricao Descrição (opcional)
     * @param string|null $empresaId ID da empresa (se escopo for empresa)
     * @param string|null $usuarioId ID do usuário (se escopo for usuario)
     * @param array|null $configuracao Configurações adicionais (opcional)
     * @return FeatureFlag
     */
    public static function criarOuAtualizar(
        string $codigo,
        string $nome,
        string $escopo = 'global',
        bool $ativo = false,
        ?string $descricao = null,
        ?string $empresaId = null,
        ?string $usuarioId = null,
        ?array $configuracao = null
    ): FeatureFlag {
        return FeatureFlag::updateOrCreate(
            [
                'codigo' => $codigo,
                'escopo' => $escopo,
                'empresa_id' => $empresaId,
                'usuario_id' => $usuarioId,
            ],
            [
                'nome' => $nome,
                'descricao' => $descricao,
                'ativo' => $ativo,
                'configuracao' => $configuracao,
            ]
        );
    }

    /**
     * Obter todas as feature flags ativas para o contexto atual
     *
     * @param string|null $empresaId ID da empresa
     * @param string|null $usuarioId ID do usuário
     * @return \Illuminate\Support\Collection
     */
    public static function obterAtivas(?string $empresaId = null, ?string $usuarioId = null)
    {
        $flags = collect();

        // Feature flags globais ativas
        $flagsGlobais = FeatureFlag::globais()->ativas()->get();
        $flags = $flags->merge($flagsGlobais);

        // Feature flags por empresa
        if ($empresaId) {
            $flagsEmpresa = FeatureFlag::porEmpresa($empresaId)->ativas()->get();
            $flags = $flags->merge($flagsEmpresa);
        }

        // Feature flags por usuário
        if ($usuarioId) {
            $flagsUsuario = FeatureFlag::porUsuario($usuarioId)->ativas()->get();
            $flags = $flags->merge($flagsUsuario);
        }

        return $flags->unique('codigo')->keyBy('codigo');
    }
}

