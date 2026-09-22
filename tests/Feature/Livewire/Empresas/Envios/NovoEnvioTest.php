<?php

use App\Livewire\Empresas\Envios\NovoEnvio;
use App\Models\Boleto;
use App\Models\Cliente;
use App\Models\ConfiguracaoParser;
use App\Models\Empresa;
use App\Models\Usuario;
use Livewire\Livewire;

test('fluxo completo do wizard: gera, processa e confirma envio', function () {
    $usuario = Usuario::factory()->create();
    $empresa = Empresa::factory()->create(['usuario_id' => $usuario->id]);
    ConfiguracaoParser::factory()->create(['empresa_id' => $empresa->id]);

    $cliente = Cliente::factory()->create([
        'empresa_id' => $empresa->id,
        'grupo' => '150_10',
        'nome' => 'Empresa Teste Ltda',
    ]);

    Livewire::withCookie('visitor_token', $usuario->uuid)
        ->test(NovoEnvio::class, ['empresa' => $empresa])
        ->assertSet('fase', 1)
        ->call('simularEnvio')
        ->assertSet('fase', 2)
        ->call('processar')
        ->assertSet('fase', 3)
        ->call('confirmarEnvio');

    expect(Boleto::count())->toBe(1)
        ->and(Boleto::first()->cliente_id)->toBe($cliente->id);
});

test('boletos sem cliente identificado não são persistidos', function () {
    $usuario = Usuario::factory()->create();
    $empresa = Empresa::factory()->create(['usuario_id' => $usuario->id]);
    ConfiguracaoParser::factory()->create([
        'empresa_id' => $empresa->id,
        'regex_nome_cliente' => '/PADRAO_QUE_NUNCA_VAI_BATER/',
    ]);

    Cliente::factory()->create(['empresa_id' => $empresa->id, 'grupo' => '150_10']);

    Livewire::withCookie('visitor_token', $usuario->uuid)
        ->test(NovoEnvio::class, ['empresa' => $empresa])
        ->call('simularEnvio')
        ->call('processar')
        ->call('confirmarEnvio');

    expect(Boleto::count())->toBe(0);
});

test('voltar retorna para a fase anterior', function () {
    $usuario = Usuario::factory()->create();
    $empresa = Empresa::factory()->create(['usuario_id' => $usuario->id]);
    ConfiguracaoParser::factory()->create(['empresa_id' => $empresa->id]);
    Cliente::factory()->create(['empresa_id' => $empresa->id, 'grupo' => '150_10']);

    Livewire::withCookie('visitor_token', $usuario->uuid)
        ->test(NovoEnvio::class, ['empresa' => $empresa])
        ->call('simularEnvio')
        ->assertSet('fase', 2)
        ->call('voltar')
        ->assertSet('fase', 1);
});
