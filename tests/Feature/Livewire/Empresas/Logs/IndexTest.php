<?php

use App\Enums\LogStatus;
use App\Livewire\Empresas\Logs\Index;
use App\Models\Empresa;
use App\Models\Log;
use App\Models\Usuario;
use Livewire\Livewire;

test('filtro por status mostra só logs daquele tipo', function () {
    $usuario = Usuario::factory()->create();
    $empresa = Empresa::factory()->create(['usuario_id' => $usuario->id]);

    Log::factory()->create(['empresa_id' => $empresa->id, 'nome' => 'Log de sucesso', 'status' => LogStatus::SUCCESS]);
    Log::factory()->create(['empresa_id' => $empresa->id, 'nome' => 'Log de erro', 'status' => LogStatus::ERROR]);

    Livewire::withCookie('visitor_token', $usuario->uuid)
        ->test(Index::class, ['empresa' => $empresa])
        ->set('filtroStatus', 'error')
        ->assertSee('Log de erro')
        ->assertDontSee('Log de sucesso');
});
