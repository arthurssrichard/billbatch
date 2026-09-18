<?php

use App\Models\Empresa;


test('empresas.index mostra só as empresas do visitante atual', function () {
    $respostaA = $this->get('/');
    $empresaA = Empresa::first();

    $paginaA = $this->withCookie('visitor_token', $empresaA->usuario->uuid)
        ->get('/empresas');
    $paginaA->assertSee($empresaA->nome);

    $paginaB = $this->withCookie('visitor_token','')->get('/empresas');
    $paginaB->assertDontSee($empresaA->nome);
});