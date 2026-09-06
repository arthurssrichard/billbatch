<?php

use App\Models\Boleto;
use Illuminate\Support\Carbon;

test('data_emissao é convertida para datetime', function () {
    $boleto = Boleto::factory()->create();

    expect($boleto->data_emissao)->toBeInstanceOf(Carbon::class);
});
