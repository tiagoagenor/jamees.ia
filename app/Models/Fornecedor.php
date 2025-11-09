<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasUuid;
use App\Helpers\PermissionHelper;

class Fornecedor extends Model
{
    use HasUuid;

    protected $table = 'fornecedor';
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
        'status',
    ];

    protected $casts = [
        'nascimento' => 'datetime',
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
        return $this->hasMany(FornecedorContato::class, 'fornecedor_id');
    }

    public function enderecos(): HasMany
    {
        return $this->hasMany(FornecedorEndereco::class, 'fornecedor_id');
    }

    // Scopes
    public function scopeDaEmpresa($query, $empresaId = null)
    {
        if (!$empresaId) {
            $empresaId = PermissionHelper::getEmpresaPrincipalId();
        }
        if ($empresaId) {
            return $query->where('empresa_id', $empresaId);
        }
        return $query;
    }

    public function scopeAtivos($query)
    {
        return $query->where('status', true);
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

    // Métodos para compatibilidade com views
    public function getTipoRelacionamentoIcon(): string
    {
        return 'fas fa-truck';
    }

    public function getTipoRelacionamentoColor(): string
    {
        return 'green';
    }

    public function getTipoRelacionamentoLabel(): string
    {
        return 'Fornecedor';
    }
}
