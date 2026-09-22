<?php

use App\Models\ConfiguracaoParser;

test('visitante só enxerga sua própria configuração de parser', function () {
    assertScopeIsolaPorUsuario(
        '/teste-configuracao-parser',
        function ($empresa) {
            ConfiguracaoParser::factory()->create([
                'empresa_id' => $empresa->id,
            ]);

            return $empresa->configuracaoParser->count();
        },
        ConfiguracaoParser::class,
    );
});
