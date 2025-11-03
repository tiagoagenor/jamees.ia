<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasUuid;
use App\Enums\EntidadeTipoEnum;

class Entidade extends Model
{
    use HasUuid;

    protected $table = 'entidade';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'empresa_id',
        'tipo_contribuinte_id',
        'usuario_id',
        'nome_fantasia',
        'razao_social',
        'documento',
        'inscricao_suframa',
        'inscricao_estadual',
        'inscricao_estadual_isenta',
        'inscricao_municipal',
        'cnae',
        'regime_tributario',
        'regime_especial',
        'nome',
        'email',
        'tipo_contribuinte',
        'rg',
        'nascimento',
        'telefone_comercial',
        'celular',
        'site',
        'observacao',
        'tipo_pessoa',
        'tipo_relacionamento',
        'status',
    ];

    protected $casts = [
        'nascimento' => 'datetime',
        'tipo_relacionamento' => EntidadeTipoEnum::class,
        'status' => 'boolean',
    ];

    // Relationships
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function contatos(): HasMany
    {
        return $this->hasMany(EntidadeContato::class, 'entidade_id');
    }

    public function enderecos(): HasMany
    {
        return $this->hasMany(EntidadeEndereco::class, 'entidade_id');
    }

    // Scopes
    public function scopeDaEmpresa($query, $empresaId = null)
    {
        if (!$empresaId) {
            $empresaId = \App\Helpers\PermissionHelper::getEmpresaPrincipalId();
        }
        if ($empresaId) {
            return $query->where('empresa_id', $empresaId);
        }
        return $query;
    }

    public function scopePorTipo($query, EntidadeTipoEnum $tipo)
    {
        return $query->where('tipo_relacionamento', $tipo->value);
    }

    public function scopeAtivos($query)
    {
        return $query->where('status', true);
    }

    public function scopeClientes($query)
    {
        return $query->where('tipo_relacionamento', EntidadeTipoEnum::CLIENTE->value);
    }

    public function scopeFornecedores($query)
    {
        return $query->where('tipo_relacionamento', EntidadeTipoEnum::FORNECEDOR->value);
    }

    public function scopeFuncionarios($query)
    {
        return $query->where('tipo_relacionamento', EntidadeTipoEnum::FUNCIONARIO->value);
    }

    public function scopeTransportadoras($query)
    {
        return $query->where('tipo_relacionamento', EntidadeTipoEnum::TRANSPORTADORA->value);
    }

    // Accessors
    public function getNomeCompletoAttribute()
    {
        if ($this->tipo_pessoa == 2) { // PJ
            return $this->razao_social ?: $this->nome_fantasia;
        }
        return $this->nome;
    }

    public function getDocumentoFormatadoAttribute()
    {
        if (!$this->documento) return null;

        if ($this->tipo_pessoa == 2) { // PJ - CNPJ
            return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $this->documento);
        } else { // PF - CPF
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $this->documento);
        }
    }

    public function getTelefoneComercialFormatadoAttribute()
    {
        if (!$this->telefone_comercial) return null;
        return preg_replace('/(\d{2})(\d{4,5})(\d{4})/', '($1) $2-$3', $this->telefone_comercial);
    }

    public function getCelularFormatadoAttribute()
    {
        if (!$this->celular) return null;
        return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $this->celular);
    }

    // Methods
    public function isPessoaFisica(): bool
    {
        return $this->tipo_pessoa == 1;
    }

    public function isPessoaJuridica(): bool
    {
        return $this->tipo_pessoa == 2;
    }

    public function isAtivo(): bool
    {
        return $this->status == true;
    }

    public function getTipoRelacionamentoLabel(): string
    {
        return $this->tipo_relacionamento->getLabel();
    }

    public function getTipoRelacionamentoIcon(): string
    {
        return $this->tipo_relacionamento->getIcon();
    }

    public function getTipoRelacionamentoColor(): string
    {
        return $this->tipo_relacionamento->getColor();
    }
}
