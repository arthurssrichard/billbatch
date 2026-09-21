<?php

namespace App\Services;

use App\Models\ConfiguracaoParser;
use Smalot\PdfParser\Parser as PdfTextParser;

class LayoutParserService
{
    public function __construct(private ConfiguracaoParser $config) {}

    public function extrairDadosDaPagina(string $caminhoPdfPagina): array
    {
        $parser = new PdfTextParser;
        $pdf = $parser->parseFile($caminhoPdfPagina);
        $texto = $pdf->getText();

        return [
            'nome_cliente' => $this->extrair($texto, $this->config->regex_nome_cliente),
            'codigo_barras' => $this->extrair($texto, $this->config->regex_codigo_barras),
        ];
    }

    private function extrair(string $texto, string $regex): ?string
    {
        if (preg_match($regex, $texto, $matches)) {
            return trim($matches[1] ?? $matches[0]);
        }

        return null;
    }
}
