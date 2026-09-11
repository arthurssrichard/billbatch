<?php

use App\Services\GerarBoletoFakeService;

function chamarExtrairDadosGrupo(?string $grupo): array
{
    $metodo = new ReflectionMethod(GerarBoletoFakeService::class, 'extrairDadosGrupo');

    return $metodo->invoke(null, $grupo);
}

test('extrai valor e dia de um grupo no formato esperado', function () {
    $dados = chamarExtrairDadosGrupo('150_10');

    expect($dados['valor'])->toBe(150)
        ->and($dados['vencimento']->day)->toBe(10);
});

test('extrai valor e dia mesmo com sufixo após o padrão', function () {
    $dados = chamarExtrairDadosGrupo('200_25_SETEMBRO');

    expect($dados['valor'])->toBe(200)
        ->and($dados['vencimento']->day)->toBe(25);
});

test('usa valor e dia aleatórios válidos quando o grupo não bate com o padrão', function () {
    $dados = chamarExtrairDadosGrupo('formato-invalido-qualquer');

    expect([100, 150, 200, 250])->toContain($dados['valor'])
        ->and([5, 10, 15, 20, 25])->toContain($dados['vencimento']->day);
});

test('usa valor e dia aleatórios válidos quando o grupo é nulo', function () {
    $dados = chamarExtrairDadosGrupo(null);

    expect([100, 150, 200, 250])->toContain($dados['valor'])
        ->and([5, 10, 15, 20, 25])->toContain($dados['vencimento']->day);
});

test('vencimento é sempre no mês seguinte', function () {
    $dados = chamarExtrairDadosGrupo('100_05');

    expect($dados['vencimento']->month)->toBe(now()->addMonth()->month)
        ->and($dados['vencimento']->year)->toBe(now()->year);
});
