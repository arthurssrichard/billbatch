<?php

use App\Livewire\Empresas\Boletos\Index;
use App\Livewire\Empresas\Boletos\ShowBoleto;
use App\Models\Boleto;
use App\Models\Empresa;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Livewire;

test('não deixa o toggleBoleto alterar o status de um boleto pertencente a outra empresa', function () {
    $empresaA = Empresa::factory()->create();
    $empresaB = Empresa::factory()->create();

    $boletoDeB = Boleto::factory()->for($empresaB)->create(['pago' => false]);

    expect(fn () => Livewire::test(Index::class, ['empresa' => $empresaA])
        ->call('togglePago', $boletoDeB->id)
    )->toThrow(ModelNotFoundException::class);

    expect($boletoDeB->fresh()->pago)->toBeFalse();
});

test('retorna 404 quando o boleto na rota não pertence à empresa correspondente', function () {
    $empresaA = Empresa::factory()->create();
    $empresaB = Empresa::factory()->create();

    $boletoDeB = Boleto::factory()->for($empresaB)->create();

    Livewire::test(ShowBoleto::class, ['empresa' => $empresaA, 'boleto' => $boletoDeB])
        ->assertStatus(404);
});
