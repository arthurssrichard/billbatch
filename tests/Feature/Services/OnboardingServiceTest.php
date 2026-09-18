<?php

use App\Models\Empresa;
use App\Models\Usuario;
use Illuminate\Support\Facades\Storage;

test('primeira visita cria uma empresa completa com boletos fake', function () {
    $this->withoutExceptionHandling();
    expect(Empresa::count())->toBe(0);
    $resposta = $this->get('/');
    $resposta->assertRedirectToRoute('empresas.index');

    $usuario = Usuario::first();
    $empresa = $usuario->empresas->first();

    expect($empresa)->not->toBeNull()
        ->and($empresa->clientes)->not->toBeEmpty()
        ->and($empresa->modeloMensagemCobrancas)->toHaveCount(3);

    $arquivos = Storage::disk('public')->allFiles("boletos_brutos/{$empresa->id}");
    expect($arquivos)->not->toBeEmpty();
});

test('segunda visita não cria uma nova empresa', function () {
    expect(Empresa::count())->toBe(0);
    $this->withoutExceptionHandling();
    $respostaA = $this->get('/');
    $respostaA->assertRedirectToRoute('empresas.index');
    $token = $respostaA->getCookie('visitor_token')->getValue();

    expect(Empresa::count())->toBe(1);

    $this->withCookie('visitor_token', $token)->get('/');

    expect(Empresa::count())->toBe(1);
});
