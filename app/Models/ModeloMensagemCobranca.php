<?php

namespace App\Models;

use App\Enums\TipoCobranca;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModeloMensagemCobranca extends Model
{
    protected $fillable = ['tipo', 'assunto', 'corpo'];

    protected function casts(): array
    {
        return [
            'tipo' => TipoCobranca::class,
        ];
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }
}
