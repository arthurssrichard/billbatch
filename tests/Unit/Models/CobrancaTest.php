<?php

use App\Enums\CanalCobranca;
use App\Enums\TipoCobranca;
use App\Models\Cobranca;
use Illuminate\Support\Carbon;

test('canal_envio é do tipo enum', function () {
    $cobranca = Cobranca::factory()->create();
    expect($cobranca->canal_envio)->toBeInstanceOf(CanalCobranca::class);
});

test('tipo é do tipo enum', function () {
    $cobranca = Cobranca::factory()->create();
    expect($cobranca->tipo)->toBeInstanceOf(TipoCobranca::class);
});

test('data_envio é convertido para datetime', function () {
    $cobranca = Cobranca::factory()->create();
    expect($cobranca->data_envio)->toBeInstanceOf(Carbon::class);
});
