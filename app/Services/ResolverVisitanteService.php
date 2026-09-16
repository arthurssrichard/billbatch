<?php

namespace App\Services;

use App\Models\Usuario;

class ResolverVisitanteService
{
    public static function resolverOuCriar($token)
    {
        $usuario = Usuario::where('uuid', $token)->first();

        // Se não existir, cria
        if (! $usuario) {
            $usuario = Usuario::factory()
                ->create(['uuid' => $token]);
        }

        // Se existir, atualiza com base no horario da request
        if ($usuario) {
            $usuario->update(['ultima_atividade' => now()]);
        }

        return $usuario;
    }
}
