<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cliente extends Model
{
    protected $fillable = ['identificador_externo', 'nome', 'canais_envio', 'cnpj'];

    protected function casts(): array
    {
        return [
            'canais_envio' => 'array',
        ];
    }

    protected function contatos(): HasMany
    {
        return $this->hasMany(Contato::class);
    }

    protected function boletos(): HasMany
    {
        return $this->hasMany(Boleto::class);
    }

    protected function empresa(): BelongsTo
    {
        return $this->belongsTO(Empresa::class);
    }

}
