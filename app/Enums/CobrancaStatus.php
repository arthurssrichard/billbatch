<?php

namespace App\Enums;

enum CobrancaStatus: string
{
    case PENDENTE = 'pending';
    case ENVIANDO = 'sending';
    case ERRO = 'error';
    case ENVIADO = 'sent';
}
