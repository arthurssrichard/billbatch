<?php

use App\Models\Log;

test('visitante só enxerga seus próprios logs', function () {
    assertScopeIsolaPorUsuario(
        '/teste-logs',
        function ($empresa) {
            Log::factory()->count(3)->create(['empresa_id' => $empresa->id]);

            return $empresa->logs()->count();
        },
        Log::class,
    );
});
