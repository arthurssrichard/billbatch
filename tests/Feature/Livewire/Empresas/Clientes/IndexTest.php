<?php

use App\Livewire\Empresas\Clientes\Index;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Usuario;
use Livewire\Livewire;

test('busca filtra clientes por nome', function () {
    $usuario = Usuario::factory()->create();
    $empresa = Empresa::factory()->create(['usuario_id' => $usuario->id]);

    Cliente::factory()->create(['empresa_id' => $empresa->id, 'nome' => 'Empresa Alpha']);
    Cliente::factory()->create(['empresa_id' => $empresa->id, 'nome' => 'Empresa Beta']);

    Livewire::withCookie('visitor_token', $usuario->uuid)
        ->test(Index::class, ['empresa' => $empresa])
        ->set('busca', 'Alpha')
        ->assertSee('Empresa Alpha')
        ->assertDontSee('Empresa Beta');
});

test('filtro por grupo mostra só clientes daquele grupo', function () {
    $usuario = Usuario::factory()->create();
    $empresa = Empresa::factory()->create(['usuario_id' => $usuario->id]);

    Cliente::factory()->create(['empresa_id' => $empresa->id, 'nome' => 'Cliente A', 'grupo' => '150_10']);
    Cliente::factory()->create(['empresa_id' => $empresa->id, 'nome' => 'Cliente B', 'grupo' => '200_20']);

    Livewire::withCookie('visitor_token', $usuario->uuid)
        ->test(Index::class, ['empresa' => $empresa])
        ->call('toggleGrupo', '150_10')
        ->assertSee('Cliente A')
        ->assertDontSee('Cliente B');
});
