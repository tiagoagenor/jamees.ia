<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasUuid;

class EntidadeContato extends Model
{
    use HasUuid;

    protected $table = 'entidade_contato';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'entidade_id',
        'entidade_tipo_id',
        'nome',
        'contato',
        'cargo',
        'observacao',
    ];

    // Relationships
    public function entidade(): BelongsTo
    {
        return $this->belongsTo(Entidade::class, 'entidade_id');
    }

    // Accessors
    public function getContatoFormatadoAttribute()
    {
        if (!$this->contato) return null;

        // Se contém @, é email
        if (strpos($this->contato, '@') !== false) {
            return $this->contato;
        }

        // Se contém apenas números, é telefone
        if (preg_match('/^\d+$/', $this->contato)) {
            $digits = preg_replace('/\D/', '', $this->contato);
            if (strlen($digits) == 11) {
                return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $digits);
            } elseif (strlen($digits) == 10) {
                return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $digits);
            }
        }

        return $this->contato;
    }

    public function getTipoContatoAttribute()
    {
        if (strpos($this->contato, '@') !== false) {
            return 'email';
        }
        if (preg_match('/^\d+$/', $this->contato)) {
            return 'telefone';
        }
        return 'outro';
    }
}
