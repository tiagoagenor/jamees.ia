<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;

class EntidadeEndereco extends Model
{
    use HasUuid;

    protected $table = 'entidade_endereco';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'entidade_id',
        'entidade_tipo_id',
        'cep',
        'logradouro',
        'numero',
        'bairro',
        'cidade',
        'estado',
        'complemento',
    ];

    // Relationships
    public function entidade(): BelongsTo
    {
        return $this->belongsTo(Entidade::class, 'entidade_id');
    }

    // Accessors
    public function getCepFormatadoAttribute()
    {
        if (!$this->cep) return null;
        return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $this->cep);
    }

    public function getEnderecoCompletoAttribute()
    {
        $endereco = [];

        if ($this->logradouro) {
            $endereco[] = $this->logradouro;
        }

        if ($this->numero) {
            $endereco[] = $this->numero;
        }

        if ($this->complemento) {
            $endereco[] = $this->complemento;
        }

        if ($this->bairro) {
            $endereco[] = $this->bairro;
        }

        if ($this->cidade) {
            $endereco[] = $this->cidade;
        }

        if ($this->estado) {
            $endereco[] = $this->estado;
        }

        if ($this->cep) {
            $endereco[] = $this->cep_formatado;
        }

        return implode(', ', $endereco);
    }

    public function getEnderecoResumidoAttribute()
    {
        $endereco = [];

        if ($this->logradouro) {
            $endereco[] = $this->logradouro;
        }

        if ($this->numero) {
            $endereco[] = $this->numero;
        }

        if ($this->bairro) {
            $endereco[] = $this->bairro;
        }

        if ($this->cidade) {
            $endereco[] = $this->cidade;
        }

        return implode(', ', $endereco);
    }
}
