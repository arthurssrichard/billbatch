<?php

use App\Models\Cliente;
use App\Models\ConfiguracaoParser;
use App\Models\Empresa;
use App\Services\GerarBoletoFakeService;
use App\Services\LayoutParserService;
use App\Services\PdfSplitterService;
use Illuminate\Support\Facades\Storage;

test('extrai nome do cliente e código de barras de uma página', function () {
    Storage::fake('public');

    $empresa = Empresa::factory()->create();
    $cliente = Cliente::factory()->create([
        'empresa_id' => $empresa->id,
        'grupo' => '150_10',
        'nome' => 'Empresa Teste Ltda',
    ]);

    $config = ConfiguracaoParser::factory()->create(['empresa_id' => $empresa->id]);
    
    $gerados = GerarBoletoFakeService::gerarDeTodosClientes($empresa);
    $paginas = PdfSplitterService::dividir($gerados['150_10'], 'boletos_processados/teste');
    

    $parser = new LayoutParserService($config);
    $dados = $parser->extrairDadosDaPagina(Storage::disk('public')->path($paginas[0]));
    
    expect($dados['nome_cliente'])->toBe($cliente->nome)
        ->and($dados['codigo_barras'])->not->toBeNull();
});

test('retorna null quando o regex não encontra nada no texto', function () {
    $empresa = Empresa::factory()->create();
    $config = ConfiguracaoParser::factory()->create([
        'empresa_id' => $empresa->id,
        'regex_nome_cliente' => '/PADRAO_QUE_NUNCA_VAI_BATER_XYZ/',
    ]);

    $parser = new LayoutParserService($config);
    $resultado = (new ReflectionMethod($parser, 'extrair'))
        ->invoke($parser, 'texto qualquer sem relação', $config->regex_nome_cliente);

    expect($resultado)->toBeNull();
});
