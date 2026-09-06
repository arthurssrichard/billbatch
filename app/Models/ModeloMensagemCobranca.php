<?php

namespace App\Models;

use App\Enums\TipoCobranca;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModeloMensagemCobranca extends Model
{
    use HasFactory;

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
