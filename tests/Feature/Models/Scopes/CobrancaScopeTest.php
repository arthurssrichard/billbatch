<?php

use App\Models\Cliente;
use App\Models\Cobranca;
use App\Models\Boleto;


test('visitante só enxerga suas proprias cobrancas', function () {
    assertScopeIsolaPorUsuario(
        '/teste-cobrancas',
        function ($empresa) {
            $cliente = Cliente::factory()->create(['empresa_id' => $empresa->id]);
            $boleto = Boleto::factory()->create([
                'empresa_id' => $empresa->id,
                'cliente_id' => $cliente->id,
            ]);
            Cobranca::factory()->count(3)->create([
                'boleto_id' => $boleto->id,
            ]);
            return $boleto->cobrancas()->count();
        },
        Cobranca::class,
    );
});
