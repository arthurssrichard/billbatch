<?php

use App\Models\Empresa;
use App\Models\Usuario;

test('empresas.index mostra só as empresas do visitante atual', function () {
    $usuarioA = Usuario::factory()->create();
    $empresaA = Empresa::factory()->create(['usuario_id' => $usuarioA->id]);

    $usuarioB = Usuario::factory()->create();
    $empresaB = Empresa::factory()->create(['usuario_id' => $usuarioB->id]);

    dump($empresaA->nome, $empresaB->nome);
    $pagina = $this->withCookie('visitor_token', $usuarioA->uuid)->get('/empresas');

    $pagina->assertSee($empresaA->nome);
    $pagina->assertDontSee($empresaB->nome);
});
