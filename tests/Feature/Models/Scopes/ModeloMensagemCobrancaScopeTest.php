<?php

use App\Models\ModeloMensagemCobranca;

test('visitante só enxerga seus próprios modelos de mensagem de cobrança', function () {
    assertScopeIsolaPorUsuario(
        '/teste-modelos-mensagem-cobranca',
        function ($empresa) {
            ModeloMensagemCobranca::factory()->count(3)->create(['empresa_id' => $empresa->id]);

            return $empresa->modeloMensagemCobrancas()->count();
        },
        ModeloMensagemCobranca::class,
    );
});
