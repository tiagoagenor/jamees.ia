<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;
use App\Traits\HasUuid;
use App\Enums\EmpresaStatusEnum;

class Empresa extends Model
{
    use HasUuid;

    protected $table = 'empresa';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'whitelabel_id',
        'empresa_id',
        'nome_referencia',
        'tipo',
        'nome_fantasia',
        'razao_social',
        'cnpj',
        'inscricao_estadual',
        'inscricao_estadual_isenta',
        'inscricao_municipal',
        'cnae',
        'regime_tributario',
        'regime_especial',
        'nome',
        'cpf',
        'rg',
        'principal',
        'status',
    ];

    public $timestamps = false;

    protected $casts = [
        'criado_em' => 'datetime',
        'atualizado_em' => 'datetime',
        'status' => EmpresaStatusEnum::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->criado_em = now();
            $model->atualizado_em = now();

            // Se não tem empresa_id definida, buscar empresa principal do usuário logado
            if (!$model->empresa_id && Auth::check()) {
                $user = Auth::user();
                $empresaPrincipal = $user->empresas()->wherePivot('principal', 1)->first();

                if ($empresaPrincipal) {
                    $model->empresa_id = $empresaPrincipal->id;
                }
            }
        });

        static::updating(function ($model) {
            $model->atualizado_em = now();
        });
    }

    public function whitelabel(): BelongsTo
    {
        return $this->belongsTo(Whitelabel::class, 'whitelabel_id');
    }

    public function empresaPai(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function empresasFilhas(): HasMany
    {
        return $this->hasMany(Empresa::class, 'empresa_id');
    }

    public function grupos(): HasMany
    {
        return $this->hasMany(Grupo::class, 'empresa_id');
    }

    public function contatos(): HasMany
    {
        return $this->hasMany(EmpresaContato::class, 'empresa_id');
    }

    public function enderecos(): HasMany
    {
        return $this->hasMany(EmpresaEndereco::class, 'empresa_id');
    }

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(Usuario::class, 'usuario_empresa', 'empresa_id', 'usuario_id')
                    ->withPivot('principal', 'status', 'criado_em', 'atualizado_em')
                    ->withTimestamps();
    }

    public function ultimosAcessos(): HasMany
    {
        return $this->hasMany(UltimaAcesso::class, 'empresa_id');
    }

    public function planos(): HasMany
    {
        return $this->hasMany(EmpresaPlano::class, 'empresa_id');
    }

    public function planoAtual(): BelongsTo
    {
        return $this->belongsTo(EmpresaPlano::class, 'id', 'empresa_id')
                    ->whereIn('status', [\App\Enums\PlanoStatusEnum::ATIVO, \App\Enums\PlanoStatusEnum::TESTE])
                    ->where('data_fim', '>', now())
                    ->orderBy('data_fim', 'desc');
    }

    public function isPlanoAtivo(): bool
    {
        $planoAtual = $this->planos()
            ->whereIn('status', [\App\Enums\PlanoStatusEnum::ATIVO, \App\Enums\PlanoStatusEnum::TESTE])
            ->where('data_fim', '>', now())
            ->first();

        return $planoAtual !== null;
    }

    public function getPlanoAtual()
    {
        return $this->planos()
            ->whereIn('status', [\App\Enums\PlanoStatusEnum::ATIVO, \App\Enums\PlanoStatusEnum::TESTE])
            ->where('data_fim', '>', now())
            ->orderBy('data_fim', 'desc')
            ->first();
    }

    /**
     * Obtém o plano da empresa principal para verificação de plano
     * Se esta empresa é uma filial, busca o plano da empresa principal
     * Se esta empresa é a principal, retorna o plano dela mesma
     */
    public function getPlanoEmpresaPrincipal()
    {
        // Se esta empresa tem empresa_id (é uma filial), buscar a empresa principal
        if ($this->empresa_id) {
            $empresaPrincipal = Empresa::find($this->empresa_id);
            if ($empresaPrincipal) {
                return $empresaPrincipal->getPlanoAtual();
            }
        }

        // Se não tem empresa_id ou não encontrou a principal, retorna o plano desta empresa
        return $this->getPlanoAtual();
    }

    /**
     * Verifica se o plano da empresa principal está ativo
     */
    public function isPlanoEmpresaPrincipalAtivo(): bool
    {
        $plano = $this->getPlanoEmpresaPrincipal();
        return $plano !== null && $plano->isAtivo();
    }

    /**
     * Método estático para obter o plano da empresa principal do usuário logado
     * Considera a empresa atual da sessão e retorna o plano da empresa principal
     */
    public static function getPlanoEmpresaPrincipalUsuarioLogado()
    {
        if (!Auth::check()) {
            return null;
        }

        $user = Auth::user();

        if (!$user instanceof \App\Models\Usuario) {
            return null;
        }

        // Obter empresa atual (da sessão)
        $empresaAtual = $user->empresaAtual();

        if (!$empresaAtual) {
            return null;
        }

        // Obter empresa principal do usuário
        $empresaPrincipal = $user->empresas()->wherePivot('principal', 1)->first();

        if (!$empresaPrincipal) {
            return null;
        }

        // Retornar o plano da empresa principal
        return $empresaPrincipal->getPlanoAtual();
    }

    /**
     * Método estático para verificar se o plano da empresa principal está ativo
     */
    public static function isPlanoEmpresaPrincipalUsuarioLogadoAtivo(): bool
    {
        $plano = self::getPlanoEmpresaPrincipalUsuarioLogado();
        return $plano !== null && $plano->isAtivo();
    }

    /**
     * Scope para filtrar empresas por empresa principal
     */
    public function scopeByEmpresaPrincipal($query, $empresaPrincipalId = null)
    {
        if (!$empresaPrincipalId && Auth::check()) {
            $user = Auth::user();
            $empresaPrincipal = $user->empresas()->wherePivot('principal', 1)->first();
            $empresaPrincipalId = $empresaPrincipal ? $empresaPrincipal->id : null;
        }

        if ($empresaPrincipalId) {
            return $query->where('empresa_id', $empresaPrincipalId);
        }

        return $query;
    }

    /**
     * Scope para incluir apenas empresas da empresa principal atual
     */
    public function scopeDaEmpresaPrincipal($query)
    {
        return $this->scopeByEmpresaPrincipal($query);
    }
}
