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

    /**
     * Indica se a empresa ativa do usuário foi criada há pouco tempo
     * (dentro da janela de "primeira visita"), para decidir se o
     * popup de onboarding deve rodar a animação de carregamento
     * ou abrir direto no guia.
     */
    public static function empresaRecemCriada(Usuario $usuario): bool
    {
        $empresa = $usuario->empresas->first();

        if (! $empresa) {
            return false;
        }

        return $empresa->created_at->greaterThan(now()->subSeconds(30));
    }
}
