<?php

use App\Models\Boleto;
use App\Models\Cliente;

test('visitante só enxerga seus próprios boletos', function () {
    assertScopeIsolaPorUsuario(
        '/teste-boletos',
        function ($empresa) {
            $cliente = Cliente::factory()->create(['empresa_id' => $empresa->id]);
            Boleto::factory()->count(3)->create([
                'empresa_id' => $empresa->id,
                'cliente_id' => $cliente->id,
            ]);
            return $cliente->boletos()->count();
        },
        Boleto::class,
    );
});
