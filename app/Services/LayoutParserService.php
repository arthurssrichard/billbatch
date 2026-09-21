<?php

namespace App\Services;

use App\Enums\LogStatus;
use App\Models\ConfiguracaoParser;
use App\Models\Empresa;
use Smalot\PdfParser\Parser as PdfTextParser;

class LayoutParserService
{
    public function __construct(private ConfiguracaoParser $config) {}

    /**
     * Recebe o caminho de um PDF de uma página, converte pra texto e retorna nome do cliente na pagina e o codigo de barras.
     *
     * @return array<string,string> nome dos clientes, e codigo de narras
     */
    public function extrairDadosDaPagina(string $caminhoPdfPagina): array
    {
        $parser = new PdfTextParser;
        $pdf = $parser->parseFile($caminhoPdfPagina);
        $texto = $pdf->getText();

        $nomeCliente = $this->extrair($texto, $this->config->regex_nome_cliente);
        $codigoBarras = $this->extrair($texto, $this->config->regex_codigo_barras);

        if (! $nomeCliente) {
            self::registrarErro($this->config->empresa, "Não foi possível extrair nome do cliente no arquivo $caminhoPdfPagina");
        }
        if (! $codigoBarras) {
            self::registrarErro($this->config->empresa, "Não foi possível extrair codigo de barras no arquivo $caminhoPdfPagina");
        }

        return [
            'nome_cliente' => $nomeCliente,
            'codigo_barras' => $codigoBarras,
        ];
    }

    /**
     * Recebe o texto parseado de uma página de PDF, um padrão Regex e extrái o que o padrão identificar.
     */
    private function extrair(string $texto, string $regex): ?string
    {
        if (preg_match($regex, $texto, $matches)) {
            return trim($matches[1] ?? $matches[0]);
        }

        return null;
    }

    private static function registrarErro(Empresa $empresa, string $mensagem): void
    {
        $empresa->logs()->create([
            'status' => LogStatus::ERROR,
            'nome' => 'Falha na identificação de dado no boleto',
            'arquivo_origem' => self::class,
            'mensagem' => $mensagem,
        ]);
    }
}
