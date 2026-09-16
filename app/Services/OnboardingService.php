<?php

namespace App\Services;

use App\Models\Empresa;
use App\Models\Usuario;

class OnboardingService
{
    public static function garantirEmpresaAtiva(Usuario $usuario): Empresa
    {
        if ($usuario->empresas->isEmpty()) {
            $empresa = Empresa::factory()->completa()->create(['usuario_id' => $usuario->id]);
            GerarBoletoFakeService::gerarDeTodosClientes($empresa);

            return $empresa;
        }

        return $usuario->empresas->first();
    }
}
