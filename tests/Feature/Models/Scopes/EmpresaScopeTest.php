<?php

use App\Models\Empresa;

test('visitante só enxerga sua própria empresa', function () {
    assertScopeIsolaPorUsuario(
        '/teste-empresas',
        fn ($empresa) => 1,
        Empresa::class,
    );
});
