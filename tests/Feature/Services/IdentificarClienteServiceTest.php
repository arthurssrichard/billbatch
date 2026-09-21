<?php

use App\Enums\LogStatus;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Log;
use App\Services\IdentificarClienteService;

test('identifica cliente mesmo com diferença de acentuação e caixa', function () {
    $empresa = Empresa::factory()->create();
    $cliente = Cliente::factory()->create([
        'empresa_id' => $empresa->id,
        'nome' => 'José da Silva Ltda',
    ]);

    $identificado = IdentificarClienteService::identificar($empresa, 'JOSE DA SILVA LTDA');

    expect($identificado->id)->toBe($cliente->id);
});

test('identifica cliente pelo nome exato', function () {
    $empresa = Empresa::factory()->create();
    $cliente = Cliente::factory()->create([
        'empresa_id' => $empresa->id,
        'nome' => 'Empresa Teste Ltda',
    ]);

    $identificado = IdentificarClienteService::identificar($empresa, 'EMPRESA TESTE LTDA');

    expect($identificado->id)->toBe($cliente->id)
        ->and(Log::where('status', LogStatus::ERROR)->count())->toBe(0);
});

test('registra log de erro quando não encontra cliente correspondente', function () {
    $empresa = Empresa::factory()->create();
    Cliente::factory()->create(['empresa_id' => $empresa->id, 'nome' => 'Outra Empresa']);

    $identificado = IdentificarClienteService::identificar($empresa, 'Nome Que Não Existe');

    expect($identificado)->toBeNull()
        ->and(Log::where('status', LogStatus::ERROR)->count())->toBe(1);
});
