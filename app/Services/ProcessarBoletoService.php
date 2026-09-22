<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\Empresa;
use Illuminate\Support\Facades\Storage;

class ProcessarBoletoService
{
    /**
     * Divide um PDF bruto (multi-página) em páginas, extrai os dados de
     * cada uma e tenta identificar o cliente correspondente. Não persiste
     * nenhum Boleto — apenas devolve os dados já processados, prontos
     * para revisão/confirmação (fase 3 do wizard).
     *
     * @return array<int, array{
     *     caminho_arquivo: string,
     *     nome_cliente_extraido: ?string,
     *     codigo_barras: ?string,
     *     cliente: ?Cliente
     * }>
     */
    public static function processar(Empresa $empresa, string $caminhoPdfBruto, string $grupo): array
    {
        $paginas = PdfSplitterService::dividir(
            $caminhoPdfBruto,
            "boletos_processados/{$empresa->id}/{$grupo}"
        );

        $parser = new LayoutParserService($empresa->configuracaoParser);

        $resultados = [];

        foreach ($paginas as $caminhoPagina) {
            $resultados[] = self::processarPagina($empresa, $parser, $caminhoPagina);
        }

        return $resultados;
    }

    /**
     * Extrai os dados de uma única página já separada e tenta
     * identificar o cliente correspondente ao nome extraído.
     */
    private static function processarPagina(Empresa $empresa, LayoutParserService $parser, string $caminhoPagina): array
    {
        $caminhoCompleto = Storage::disk('public')->path($caminhoPagina);
        $dados = $parser->extrairDadosDaPagina($caminhoCompleto);

        $cliente = $dados['nome_cliente']
            ? IdentificarClienteService::identificar($empresa, $dados['nome_cliente'])
            : null;

        return [
            'caminho_arquivo' => $caminhoPagina,
            'nome_cliente_extraido' => $dados['nome_cliente'],
            'codigo_barras' => $dados['codigo_barras'],
            'cliente' => $cliente,
        ];
    }
}
