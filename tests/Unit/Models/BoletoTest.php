<?php

use App\Models\Usuario;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

test('data_emissao é convertida para datetime', function () {
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

    expect($boleto->data_emissao)->toBeInstanceOf(Carbon::class);
});
