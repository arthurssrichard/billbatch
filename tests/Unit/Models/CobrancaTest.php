<?php

use App\Enums\CanalCobranca;
use App\Enums\TipoCobranca;
use App\Models\Usuario;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

test('canal_envio é do tipo enum', function () {
    $cobranca = geraCobranca();
    expect($cobranca->canal_envio)->toBe(CanalCobranca::EMAIL);
});

test('tipo é do tipo enum', function () {
    $cobranca = geraCobranca();
    expect($cobranca->tipo)->toBe(TipoCobranca::PRIMEIRO_ENVIO);
});

test('data_envio é convertido para datetime', function () {
    $cobranca = geraCobranca();
    expect($cobranca->data_envio)->toBeInstanceOf(Carbon::class);
});

function geraCobranca()
{
    $usuario = Usuario::create([
        'nome' => 'Teste',
        'uuid' => Str::uuid(),
        'ultima_atividade' => now(),
    ]);

    $empresa = $usuario->empresas()->create([
        'nome' => 'Empresa Teste',
    ]);

    $cliente = $empresa->clientes()->create([
        'nome' => 'Cliente teste',
        'identificador_externo' => '135',
        'cnpj' => '34.028.316/0001-03',
        'canais_envio' => ['whatsapp', 'email'],
    ]);

    $boleto = $cliente->boletos()->create([
        'empresa_id' => $empresa->id,
        'codigo_barras' => '00190.00009 01234.567808 00000.999900 1 8900000000100',
        'grupo' => '1590_ABRIL',
        'caminho_arquivo' => '/pdfs/abril/1590_ABRIL/cliente_teste.pdf',
        'data_emissao' => '2026-12-31',
    ]);

    $cobranca = $boleto->cobrancas()->create([
        'canal_envio' => CanalCobranca::EMAIL,
        'contatos_enviados' => 'mailtest@mail.com;mailtest@mail.com',
        'tipo' => TipoCobranca::PRIMEIRO_ENVIO,
        'data_envio' => '2027-01-05',
    ]);

    return $cobranca;
}
