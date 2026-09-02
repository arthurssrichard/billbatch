<?php

use App\Enums\TipoCobranca;
use App\Models\Usuario;
use Illuminate\Support\Str;

test('tipo é do tipo enum', function () {
    $modeloMensagemCobranca = geraModeloMensagemCobranca();
    expect($modeloMensagemCobranca->tipo)->toBe(TipoCobranca::PRIMEIRO_ENVIO);
});

function geraModeloMensagemCobranca()
{
    $usuario = Usuario::create([
        'nome' => 'Teste',
        'uuid' => Str::uuid(),
        'ultima_atividade' => now(),
    ]);

    $empresa = $usuario->empresas()->create([
        'nome' => 'Empresa Teste',
    ]);

    $modeloMensagemCobranca = $empresa->modeloMensagemCobrancas()->create([
        'tipo' => TipoCobranca::PRIMEIRO_ENVIO,
        'assunto' => 'Boleto com vencimento em {mes}',
        'corpo' => '{saudacao}! Segue o boleto com vencimento no mês de {mes}',
    ]);

    return $modeloMensagemCobranca;
}
