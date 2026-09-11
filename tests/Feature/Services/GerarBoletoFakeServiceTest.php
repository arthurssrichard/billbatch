<?php

use App\Models\Cliente;
use App\Models\Empresa;
use App\Services\GerarBoletoFakeService;
use Illuminate\Support\Facades\Storage;

test('gera um pdf por grupo distinto de clientes', function () {
    Storage::fake('public');

    $empresa = Empresa::factory()->create();
    Cliente::factory()->count(3)->create([
        'empresa_id' => $empresa->id,
        'grupo' => '150_10',
    ]);

    $resultado = GerarBoletoFakeService::gerarDeTodosClientes($empresa);

    Storage::disk('public')->assertExists($resultado['150_10']);
});

test('dois grupos em empresas diferentes não colidem', function () {
    Storage::fake('public');

    // Cria 2 empresas
    $empresa1 = Empresa::factory()->create();
    $empresa2 = Empresa::factory()->create();

    // Cria 3 clientes no mesmo grupo para a empresa 1
    Cliente::factory()->count(3)->create([
        'empresa_id' => $empresa1->id,
        'grupo' => '250_10',
    ]);

    // Cria 3 clientes no mesmo grupo para a empresa 2
    Cliente::factory()->count(3)->create([
        'empresa_id' => $empresa2->id,
        'grupo' => '250_10',
    ]);

    // Gera boletos para todos os clientes de cada empresa
    $resultadoEmpresa1 = GerarBoletoFakeService::gerarDeTodosClientes($empresa1);
    $resultadoEmpresa2 = GerarBoletoFakeService::gerarDeTodosClientes($empresa2);

    // Verifica se ambos existem de fato (para caso de por algum motivo um sobscrever o outro)
    Storage::disk('public')->assertExists($resultadoEmpresa1['250_10']);
    Storage::disk('public')->assertExists($resultadoEmpresa2['250_10']);

    // Confirma que tem caminhos diferentes
    expect($resultadoEmpresa1['250_10'])->not->toBe($resultadoEmpresa2['250_10']);
});
