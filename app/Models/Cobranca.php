<?php

namespace App\Models;

use App\Enums\CanalCobranca;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cobranca extends Model
{
    protected $fillable = ['canal_envio', 'contatos_enviados', 'tipo', 'data_envio'];

    protected $casts = [
        'canal_envio' => CanalCobranca::class,
        'data_envio' => 'datetime',
    ];

    public function boleto(): BelongsTo
    {
        return $this->belongsTo(Boleto::class);
    }
}
