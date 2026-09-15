<?php

use App\Models\Email;

test('visitante só enxerga seus próprios emails', function () {
    assertScopeIsolaPorUsuario(
        '/teste-emails',
        function ($empresa) {
            Email::factory()->count(3)->create(['empresa_id' => $empresa->id]);

            return $empresa->emails()->count();
        },
        Email::class,
    );
});
