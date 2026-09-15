<?php

namespace App\Models\Concerns;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Builder;

trait PertenceAoUsuarioAtual
{
    protected static function bootPertenceAoUsuarioAtual(): void
    {
        static::addGlobalScope('usuario_atual', function (Builder $builder) {
            if (app()->bound(Usuario::class)) {
                $builder->whereHas(
                    static::caminhoAteEmpresa(),
                    fn (Builder $q) => $q->where('usuario_id', app(Usuario::class)->id)
                );
            }
        });
    }

    abstract protected static function caminhoAteEmpresa(): string;
}
