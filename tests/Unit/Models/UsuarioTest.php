<?php

use App\Models\Usuario;
use Illuminate\Support\Str;

test('usuario tem muitas empresas', function () {
    $usuario = Usuario::create([
        'nome' => 'Teste',
        'uuid' => Str::uuid(),
        'ultima_atividade' => now(),
    ]);

    $usuario->empresas()->create([
        'usuario_id' => $usuario->id,
        'nome' => 'Empresa Teste',
    ]);

    expect($usuario->empresas)->toHaveCount(1);
});

test('ultima atividade é convertida para datetime', function () {
    $usuario = Usuario::create([
        'nome' => 'Teste',
        'uuid' => Str::uuid(),
        'ultima_atividade' => now(),
    ]);

    expect($usuario->ultima_atividade)->toBeInstanceOf('datetime');
});
