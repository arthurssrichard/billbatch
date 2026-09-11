<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\Empresa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\Renderers\PngRenderer;
use Picqer\Barcode\Types\TypeCode128;

class GerarBoletoFakeService
{
    public static function gerarDeTodosClientes(Empresa $empresa): array
    {
        $grupos = Cliente::query()
            ->where('empresa_id', $empresa->id)
            ->whereNotNull('grupo')
            ->distinct()
            ->pluck('grupo')
            ->toArray();

        $pdfs = [];

        foreach ($grupos as $grupo) {
            $pdfs[$grupo] = self::gerarParaGrupo($grupo, $empresa);
        }

        return $pdfs;
    }

    private static function gerarParaGrupo(string $grupo, Empresa $empresa): string
    {
        $clientes = Cliente::query()
            ->where('grupo', $grupo)
            ->get();

        $boletos = self::geraDadosBoletos($clientes);

        $pdf = Pdf::loadView('pdfs.boleto_template', [
            'boletos' => $boletos,
        ]);

        $nomeArquivo = strtoupper(now()->addMonth()->format('M y')).' '.$grupo.'.pdf';

        $caminho = "boletos_brutos/{$empresa->id}/{$nomeArquivo}";

        Storage::disk('public')->put($caminho, $pdf->output());

        return $caminho;
    }

    private static function geraDadosBoletos($clientes): array
    {
        $boletos = [];

        foreach ($clientes as $cliente) {
            $dadosGrupo = self::extrairDadosGrupo($cliente->grupo);
            $barcode = self::gerarCodigoBarras();

            $boletos[] = [
                'codigo_barras' => $barcode['codigo'],
                'barcode_base64' => $barcode['base64'],
                'nome_cliente' => $cliente->nome,
                'vencimento' => $dadosGrupo['vencimento'],
                'valor' => $dadosGrupo['valor'],
            ];
        }

        return $boletos;
    }

    private static function gerarCodigoBarras(): array
    {
        $codigo = '001'.random_int(1000000000000000, 9999999999999999);

        $barcode = (new TypeCode128)->getBarcode($codigo);

        $renderer = new PngRenderer;

        $png = $renderer->render($barcode, 400, 80);

        return [
            'codigo' => $codigo,
            'base64' => 'data:image/png;base64,'.base64_encode($png),
        ];
    }

    private static function extrairDadosGrupo(?string $grupo): array
    {
        $valores = [100, 150, 200, 250];
        $dias = [5, 10, 15, 20, 25];

        if (
            $grupo &&
            preg_match(
                '/^(100|150|200|250)_(5|10|15|20|25)(?:_|$)/i',
                $grupo,
                $matches
            )
        ) {
            $valor = (int) $matches[1];
            $dia = (int) $matches[2];
        } else {
            $valor = fake()->randomElement($valores);
            $dia = fake()->randomElement($dias);
        }

        $vencimento = now()
            ->startOfMonth()
            ->addDays($dia - 1)
            ->addMonth();

        return [
            'valor' => $valor,
            'vencimento' => $vencimento,
        ];
    }
}
