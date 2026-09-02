<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
    protected $fillable = ['usuario_id', 'nome'];

    protected function casts(): array
    {
        return [];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }

    public function clientes(): HasMany
    {
        return $this->hasMany(Cliente::class);
    }

    public function boletos(): HasMany
    {
        return $this->hasMany(Boleto::class);
    }

    public function modeloMensagemCobrancas(): HasMany
    {
        return $this->hasMany(ModeloMensagemCobranca::class);
    }

    public function emails(): HasMany
    {
        return $this->hasMany(Email::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(Log::class);
    }
}
