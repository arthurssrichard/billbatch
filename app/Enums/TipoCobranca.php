<?php

namespace App\Enums;

enum TipoCobranca: string
{
    case PRIMEIRO_ENVIO = 'primeiro_envio';
    case AVISO = 'aviso';
    case COBRANCA = 'cobranca';
}
