<?php

namespace App\Services;

use App\Models\Boleto;
use Carbon\Carbon;

class SubstituirPlaceholdersService
{
    public static function substituir(string $texto, Boleto $boleto)
    {
        $textoFinal = str_replace('{NOME_CLIENTE}', $boleto->cliente->nome, $texto);
        $textoFinal = str_replace('{SAUDACAO}', self::getSaudacao(), $textoFinal);

        return $textoFinal;
    }

    private static function getSaudacao()
    {
        $horaAtual = Carbon::now()->hour;

        if ($horaAtual >= 6 && $horaAtual < 12) {
            $saudacao = 'Bom dia';
        } elseif ($horaAtual >= 12 && $horaAtual < 18) {
            $saudacao = 'Boa tarde';
        } else {
            $saudacao = 'Boa noite';
        }

        return $saudacao;
    }
}
