<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasUuid;

class Banco extends Model
{
    use HasUuid;

    protected $table = 'bancos';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'nome_institucional',
        'nome_normalizado',
        'numero_banco',
        'imagem',
        'url',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // Relationships
    public function contasEmpresa(): HasMany
    {
        return $this->hasMany(ContaEmpresa::class, 'banco_id');
    }

    // Scopes
    public function scopeAtivos($query)
    {
        return $query->where('status', true);
    }

    public function scopePorNumero($query, $numero)
    {
        return $query->where('numero_banco', $numero);
    }

    public function scopeBuscarPorNome($query, $nome)
    {
        return $query->where(function($q) use ($nome) {
            $q->where('nome_institucional', 'like', "%{$nome}%")
              ->orWhere('nome_normalizado', 'like', "%{$nome}%");
        });
    }

    // Methods
    public function isAtivo(): bool
    {
        return $this->status == true;
    }

    public function getNomeCompleto(): string
    {
        return $this->nome_institucional;
    }

    public function getNomeResumido(): string
    {
        return $this->nome_normalizado;
    }

    public function getNumeroFormatado(): string
    {
        return str_pad($this->numero_banco, 3, '0', STR_PAD_LEFT);
    }

    public function getImagemUrl(): string
    {
        if ($this->imagem) {
            return asset('storage/bancos/' . $this->imagem);
        }
        return asset('images/default-bank.png');
    }

    public function temUrl(): bool
    {
        return !empty($this->url) && $this->url !== 'null';
    }

    public function getUrlFormatada(): string
    {
        if (!$this->temUrl()) {
            return '#';
        }

        if (!str_starts_with($this->url, 'http')) {
            return 'https://' . $this->url;
        }

        return $this->url;
    }
}
