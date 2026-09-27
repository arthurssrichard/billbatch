<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\Empresa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
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
            $grupoGerado = self::gerarParaGrupo($grupo, $empresa);
            $pdfs[$grupo] = $grupoGerado['caminho'];
        }

        return $pdfs;
    }

    private static function gerarParaGrupo(string $grupo, Empresa $empresa): array
    {
        $clientes = Cliente::query()
            ->where('grupo', $grupo)
            ->get();

        $dadosGrupo = self::extrairDadosGrupo($grupo);

        $vencimento = self::proximoVencimentoDisponivel($grupo, $empresa, $dadosGrupo['vencimento']);

        $boletos = self::geraDadosBoletos($clientes, $dadosGrupo['valor'], $vencimento);

        $pdf = Pdf::loadView('pdfs.boleto_template', [
            'boletos' => $boletos,
        ]);

        $nomeArquivo = strtoupper($vencimento->format('M y')).' '.$grupo.'.pdf';

        $caminho = "boletos_brutos/{$empresa->id}/{$nomeArquivo}";

        Storage::disk('public')->put($caminho, $pdf->output());

        return [
            'nome_grupo' => $nomeArquivo,
            'caminho' => $caminho,
        ];
    }

    /**
     * Descobre o próximo mês de vencimento ainda não usado para esse grupo,
     * partindo do vencimento "natural" calculado por extrairDadosGrupo().
     * Evita gerar dois boletos do mesmo grupo com o mesmo vencimento.
     */
    private static function proximoVencimentoDisponivel(string $grupo, Empresa $empresa, Carbon $vencimentoBase): Carbon
    {
        $vencimento = $vencimentoBase->copy();

        $mesesUsados = self::mesesJaGerados($grupo, $empresa);

        while (in_array($vencimento->format('Y-m'), $mesesUsados, true)) {
            $vencimento->addMonth();
        }

        return $vencimento;
    }

    private static function mesesJaGerados(string $grupo, Empresa $empresa): array
    {
        $arquivos = Storage::disk('public')->files("boletos_brutos/{$empresa->id}");

        $meses = [];

        foreach ($arquivos as $arquivo) {
            $nome = basename($arquivo);

            if (preg_match('/^([A-Za-z]{3} \d{2}) '.preg_quote($grupo, '/').'\.pdf$/i', $nome, $m)) {
                $mesAno = ucfirst(strtolower($m[1]));
                $meses[] = Carbon::createFromFormat('M y', $mesAno)->format('Y-m');
            }
        }

        return $meses;
    }

    private static function geraDadosBoletos($clientes, int $valor, Carbon $vencimento): array
    {
        $boletos = [];

        foreach ($clientes as $cliente) {
            $barcode = self::gerarCodigoBarras();

            $boletos[] = [
                'codigo_barras' => $barcode['codigo'],
                'barcode_base64' => $barcode['base64'],
                'nome_cliente' => $cliente->nome,
                'vencimento' => $vencimento,
                'valor' => $valor,
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
