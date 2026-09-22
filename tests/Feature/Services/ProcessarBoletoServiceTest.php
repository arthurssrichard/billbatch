<?php

use App\Models\Boleto;
use App\Models\Cliente;
use App\Models\ConfiguracaoParser;
use App\Models\Empresa;
use App\Services\GerarBoletoFakeService;
use App\Services\ProcessarBoletoService;
use Illuminate\Support\Facades\Storage;

test('processa um pdf bruto e identifica os clientes de cada página', function () {
    Storage::fake('public');

    $empresa = Empresa::factory()->create();
    ConfiguracaoParser::factory()->create(['empresa_id' => $empresa->id]);

    $clienteA = Cliente::factory()->create([
        'empresa_id' => $empresa->id,
        'grupo' => '150_10',
        'nome' => 'Empresa A Ltda',
    ]);
    $clienteB = Cliente::factory()->create([
        'empresa_id' => $empresa->id,
        'grupo' => '150_10',
        'nome' => 'Empresa B Ltda',
    ]);

    $gerados = GerarBoletoFakeService::gerarDeTodosClientes($empresa);

    $resultados = ProcessarBoletoService::processar($empresa, $gerados['150_10'], '150_10');

    expect($resultados)->toHaveCount(2);

    $nomesIdentificados = collect($resultados)->pluck('cliente.nome')->filter()->values();
    expect($nomesIdentificados)->toContain($clienteA->nome, $clienteB->nome);
});

test('página com cliente não identificado retorna cliente nulo', function () {
    Storage::fake('public');

    $empresa = Empresa::factory()->create();
    ConfiguracaoParser::factory()->create([
        'empresa_id' => $empresa->id,
        'regex_nome_cliente' => '/PADRAO_QUE_NUNCA_VAI_BATER/',
    ]);

    Cliente::factory()->create(['empresa_id' => $empresa->id, 'grupo' => '150_10']);

    $gerados = GerarBoletoFakeService::gerarDeTodosClientes($empresa);

    $resultados = ProcessarBoletoService::processar($empresa, $gerados['150_10'], '150_10');

    expect($resultados[0]['cliente'])->toBeNull()
        ->and($resultados[0]['nome_cliente_extraido'])->toBeNull();
});

test('processar não persiste nenhum boleto no banco', function () {
    Storage::fake('public');

    $empresa = Empresa::factory()->create();
    ConfiguracaoParser::factory()->create(['empresa_id' => $empresa->id]);
    Cliente::factory()->create(['empresa_id' => $empresa->id, 'grupo' => '150_10']);

    $gerados = GerarBoletoFakeService::gerarDeTodosClientes($empresa);

    ProcessarBoletoService::processar($empresa, $gerados['150_10'], '150_10');

    expect(Boleto::count())->toBe(0);
});
