<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Boleto extends Model
{
    use HasFactory;

    protected $fillable = ['empresa_id','codigo_barras','grupo','caminho_arquivo','enviado','pago','data_emissao'];

    protected function casts(): array
    {
        return [
            'data_emissao' => 'datetime',
        ];
    }

    public function cobrancas(): HasMany
    {
        return $this->hasMany(Cobranca::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function empresas(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }
}
