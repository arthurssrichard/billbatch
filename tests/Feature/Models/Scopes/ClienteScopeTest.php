<?php

use App\Http\Middleware\VerifyVisitorToken;
use App\Models\Usuario;
use Illuminate\Support\Facades\Route;
use App\Models\Cliente;

test('visitante só enxerga seus próprios clientes', function () {
    assertScopeIsolaPorUsuario(
        '/teste-clientes',
        function ($empresa) {
            Cliente::factory()->count(3)->create(['empresa_id' => $empresa->id,]);
            return $empresa->clientes()->count();
        },
        Cliente::class,
    );
});
