<?php

use App\Services\GerarBoletoFakeService;
use App\Services\PdfSplitterService;
use App\Models\Empresa;
use App\Models\Cliente;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;

test('divide um pdf multi-página em arquivos individuais por página', function () {
    Storage::fake('public');

    $empresa = Empresa::factory()->create();
    Cliente::factory()->count(3)->create([
        'empresa_id' => $empresa->id,
        'grupo' => '150_10',
    ]);

    $gerados = GerarBoletoFakeService::gerarDeTodosClientes($empresa);
    $caminhoOrigem = $gerados['150_10'];

    $paginas = PdfSplitterService::dividir($caminhoOrigem, 'boletos_processados/teste');

    expect($paginas)->toHaveCount(3);

    foreach ($paginas as $caminho) {
        Storage::disk('public')->assertExists($caminho);

        $pdf = new Fpdi();
        $totalPaginas = $pdf->setSourceFile(Storage::disk('public')->path($caminho));

        expect($totalPaginas)->toBe(1);
    }
});
