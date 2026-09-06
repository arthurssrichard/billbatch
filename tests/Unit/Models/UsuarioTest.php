<?php

use App\Models\Usuario;

test('usuario tem muitas empresas', function () {
    $usuario = Usuario::factory()->create();

    $usuario->empresas()->create([
        'usuario_id' => $usuario->id,
        'nome' => 'Empresa Teste',
    ]);

    expect($usuario->empresas)->toHaveCount(1);
});

test('ultima atividade é convertida para datetime', function () {
    $usuario = Usuario::factory()->create();
    expect($usuario->ultima_atividade)->toBeInstanceOf('datetime');
});
