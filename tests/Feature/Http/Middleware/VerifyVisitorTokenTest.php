<?php

use App\Http\Middleware\VerifyVisitorToken;
use App\Models\Usuario;
use Illuminate\Support\Facades\Route;

test('cria um novo usuário quando não há cookie de visitante', function () {
    Route::middleware('web', VerifyVisitorToken::class)->get('/teste-rota-fake', fn () => 'ok');

    expect(Usuario::count())->toBe(0);

    $response = $this->get('/teste-rota-fake');
    $response->assertCookie('visitor_token');
    expect(Usuario::count())->toBe(1);
});

test('reaproveita o mesmo usuário quando o cookie já existe', function () {
    $usuario = Usuario::factory()->create();

    Route::middleware(['web', VerifyVisitorToken::class])->get('/teste-rota-fake', fn () => 'ok');

    $this->withCookie('visitor_token', $usuario->uuid)
        ->get('/teste-rota-fake');

    expect(Usuario::count())->toBe(1);
});
