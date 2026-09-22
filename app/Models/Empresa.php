<?php

namespace App\Models;

use App\Models\Scopes\IsolamentoPorUsuarioScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = ['nome'];

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

    protected static function booted(): void
    {
        static::addGlobalScope(new IsolamentoPorUsuarioScope);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        if (! app()->bound(Usuario::class)) {
            abort(404);
        }

        return $this->where($field ?? $this->getRouteKeyName(), $value)
            ->where('usuario_id', app(Usuario::class)->id)
            ->firstOrFail();
    }

    public function configuracaoParser(): HasOne
    {
        return $this->hasOne(ConfiguracaoParser::class);
    }
}
