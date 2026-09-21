<?php

namespace App\Services;

use App\Enums\LogStatus;
use App\Models\Cliente;
use App\Models\Empresa;
use Illuminate\Support\Str;

class IdentificarClienteService
{
    /**
     * Busca, na empresa informada, o cliente cujo nome corresponde ao nome
     * extraído do boleto (comparação exata, ignorando maiúsculas/minúsculas).
     * Registra um log de erro quando nenhum cliente é encontrado.
     */
    public static function identificar(Empresa $empresa, string $nomeExtraido): ?Cliente
    {
        $nomeNormalizado = self::normalizar($nomeExtraido);

        $cliente = $empresa->clientes()
            ->get()
            ->first(fn ($cliente) => self::normalizar($cliente->nome) === $nomeNormalizado);

        if (! $cliente) {
            self::registrarErro($empresa, "Nenhum cliente encontrado com o nome \"{$nomeExtraido}\".");
        }

        return $cliente;
    }

    /**
     * Registra, no histórico de logs da empresa, uma falha ocorrida
     * durante a identificação de um cliente a partir do boleto.
     */
    private static function registrarErro(Empresa $empresa, string $mensagem): void
    {
        $empresa->logs()->create([
            'status' => LogStatus::ERROR,
            'nome' => 'Falha na identificação de cliente',
            'arquivo_origem' => self::class,
            'mensagem' => $mensagem,
        ]);
    }

    /**
     * Normaliza a string, deixando o texto lower para comparar
     * nome do cliente encontrado no banco de dados com encontrado no boleto
     */
    private static function normalizar(string $texto): string
    {
        return Str::of($texto)->ascii()->lower()->trim()->toString();
    }
}
