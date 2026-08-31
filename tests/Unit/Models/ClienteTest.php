<?php

use App\Models\Empresa;
use App\Models\Usuario;
use Illuminate\Support\Str;

test('canais_envio retorna um array', function () {
    $usuario = Usuario::create([
        'nome' => 'Teste',
        'uuid' => Str::uuid(),
        'ultima_atividade' => now(),
    ]);

    $empresa = Empresa::create([
        'usuario_id' => $usuario->id,
        'nome' => 'Empresa Teste',
    ]);

    $cliente = $empresa->clientes()->create([
        'nome' => 'Cliente teste',
        'identificador_externo' => '135',
        'cnpj' => '34.028.316/0001-03',
        'canais_envio' => ['whatsapp', 'email'],
    ]);

    expect($cliente->canais_envio)
        ->toBeArray()
        ->toBe(['whatsapp', 'email']);
});
