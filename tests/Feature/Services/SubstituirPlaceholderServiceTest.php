<?php

use App\Models\Boleto;
use App\Models\Cliente;
use App\Services\SubstituirPlaceholdersService;
use Carbon\Carbon;

test('substitui NOME_CLIENTE pelo nome do cliente do boleto', function () {
    $cliente = Cliente::factory()
        ->has(Boleto::factory())
        ->create(['nome' => 'João da Silva']);
    $boleto = $cliente->boletos()->first();

    $resultado = SubstituirPlaceholdersService::substituir(
        'Olá, {NOME_CLIENTE}!',
        $boleto,
    );

    expect($resultado)->toBe('Olá, João da Silva!');
});

test('substitui SAUDACAO de acordo com o horário do dia', function () {
    $cliente = Cliente::factory()
        ->has(Boleto::factory())
        ->create();
    $boleto = $cliente->boletos()->first();

    Carbon::setTestNow('2026-01-01 08:00:00');
    expect(SubstituirPlaceholdersService::substituir('{SAUDACAO}', $boleto))->toBe('Bom dia');

    Carbon::setTestNow('2026-01-01 14:00:00');
    expect(SubstituirPlaceholdersService::substituir('{SAUDACAO}', $boleto))->toBe('Boa tarde');

    Carbon::setTestNow('2026-01-01 20:00:00');
    expect(SubstituirPlaceholdersService::substituir('{SAUDACAO}', $boleto))->toBe('Boa noite');

    Carbon::setTestNow();
});
