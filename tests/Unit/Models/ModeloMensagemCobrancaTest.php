<?php

use App\Enums\TipoCobranca;
use App\Models\ModeloMensagemCobranca;

test('tipo é do tipo enum', function () {
    $modeloMensagemCobranca = ModeloMensagemCobranca::factory()->create();
    expect($modeloMensagemCobranca->tipo)->toBeInstanceOf(TipoCobranca::class);
});
