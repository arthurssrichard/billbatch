<?php

use App\Http\Middleware\VerifyVisitorToken;
use App\Models\Empresa;
use App\Models\Usuario;
use Illuminate\Support\Facades\Route;

test('visitante acessa sua própria empresa normalmente', function () {
    Route::middleware(['web', VerifyVisitorToken::class])
        ->get('/teste-empresa-binding/{empresa}', fn (Empresa $empresa) => $empresa->id);

    $usuario = Usuario::factory()->create();
    $empresa = Empresa::factory()->create(['usuario_id' => $usuario->id]);

    $response = $this->withCookie('visitor_token', $usuario->uuid)
        ->get("/teste-empresa-binding/{$empresa->id}");

    $response->assertOk();
});

test('visitante não consegue acessar empresa de outro dono via url', function () {
    Route::middleware(['web', VerifyVisitorToken::class])
        ->get('/teste-empresa-binding/{empresa}', fn (Empresa $empresa) => $empresa->id);

    $usuarioA = Usuario::factory()->create();
    $usuarioB = Usuario::factory()->create();
    $empresaDoB = Empresa::factory()->create(['usuario_id' => $usuarioB->id]);

    $notFoundResponse = $this->withCookie('visitor_token', $usuarioA->uuid)
        ->get("/teste-empresa-binding/{$empresaDoB->id}");
    $notFoundResponse->assertNotFound();

    $okResponse = $this->withCookie('visitor_token', $usuarioB->uuid)
        ->get("/teste-empresa-binding/{$empresaDoB->id}");
    $okResponse->assertSuccessful();
});
