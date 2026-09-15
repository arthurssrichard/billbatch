<?php

use App\Http\Middleware\VerifyVisitorToken;
use App\Models\Empresa;
use Illuminate\Support\Facades\Route;

test('visitante só enxerga sua própria empresa', function () {
    Route::middleware(['web', VerifyVisitorToken::class])
        ->get('/teste-empresas', fn () => Empresa::all()->pluck('id'));

    $respostaA = $this->get('/teste-empresas');
    $empresasVisiveisParaA = $respostaA->json();

    $respostaB = $this->get('/teste-empresas');
    $empresasVisiveisParaB = $respostaB->json();

    expect($empresasVisiveisParaA)->not->toEqual($empresasVisiveisParaB);
});
