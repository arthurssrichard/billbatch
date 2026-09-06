<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['identificador_externo', 'nome', 'canais_envio', 'cnpj'];

    protected function casts(): array
    {
        return [
            'canais_envio' => 'array',
        ];
    }

    public function contatos(): HasMany
    {
        return $this->hasMany(Contato::class);
    }

    public function boletos(): HasMany
    {
        return $this->hasMany(Boleto::class);
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

}
