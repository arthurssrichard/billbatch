<?php

use App\Models\Cliente;

test('visitante só enxerga seus próprios clientes', function () {
    assertScopeIsolaPorUsuario(
        '/teste-clientes',
        function ($empresa) {
            Cliente::factory()->count(3)->create(['empresa_id' => $empresa->id]);

            return $empresa->clientes()->count();
        },
        Cliente::class,
    );
});
