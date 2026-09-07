<?php

use App\Enums\CanalCobranca;
use App\Models\Cliente;

test('canais_envio retorna um array', function () {
    $cliente = Cliente::factory()->create();

    expect($cliente->canais_envio)->toBeArray();
});
test('canais_envio tem valores válidos', function () {
    $cliente = Cliente::factory()->create();
    foreach ($cliente->canais_envio as $canalEnvio) {
        expect(fn () => CanalCobranca::from($canalEnvio))->not->toThrow(ValueError::class);
    }
});
