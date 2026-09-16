<?php

use App\Models\Cliente;
use App\Models\Contato;

test('visitante só enxerga seus próprios contatos', function () {
    assertScopeIsolaPorUsuario(
        '/teste-contatos',
        function ($empresa) {
            $cliente = Cliente::factory()->create(['empresa_id' => $empresa->id]);
            Contato::factory()->count(3)->create([
                'cliente_id' => $cliente->id,
            ]);

            return $cliente->contatos()->count();
        },
        Contato::class,
    );
});
