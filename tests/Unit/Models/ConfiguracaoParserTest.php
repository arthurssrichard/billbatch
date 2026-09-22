<?php

use App\Models\Empresa;

test('empresa tem uma configuracao de parser', function () {
    $empresa = Empresa::factory()->completa()->create();

    expect($empresa->configuracaoParser)->not->toBeNull();
});
