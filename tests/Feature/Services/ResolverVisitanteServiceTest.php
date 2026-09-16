<?php

use App\Http\Middleware\VerifyVisitorToken;
use App\Models\Usuario;
use Illuminate\Support\Facades\Route;

test('verifica se o ultima_atividade é atualizado', function () {
    Route::middleware('web', VerifyVisitorToken::class)->get('/teste-rota-fake', fn () => 'ok');

    $resposta = $visitorToken = $this->get('teste-rota-fake');
    $token = $resposta->getCookie('visitor_token')->getValue();
    $usuario = Usuario::where('uuid', $token)->firstOrFail();
    $primeiroHorario = $usuario->ultima_atividade;

    sleep(1);

    $this->withCookie('visitor_token', $token)->get('/teste-rota-fake');

    $usuario->refresh();
    $segundoHorario = $usuario->ultima_atividade;
    expect($segundoHorario)->not->toEqual($primeiroHorario);
});
