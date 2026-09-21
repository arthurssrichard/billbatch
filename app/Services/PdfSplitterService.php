<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;

class PdfSplitterService
{
    /**
     * Divide um PDF multi-página em arquivos individuais, um por página.
     *
     * @return array<int, string> caminhos dos arquivos gerados, na ordem das páginas
     */
    public static function dividir(string $caminhoPdfOrigem, string $pastaDestino): array
    {
        $conteudoOrigem = Storage::disk('public')->path($caminhoPdfOrigem);

        $pdf = new Fpdi;
        $totalPaginas = $pdf->setSourceFile($conteudoOrigem);

        $caminhosGerados = [];

        for ($pagina = 1; $pagina <= $totalPaginas; $pagina++) {
            $novoPdf = new Fpdi;
            $novoPdf->setSourceFile($conteudoOrigem);
            $templateId = $novoPdf->importPage($pagina);
            $tamanho = $novoPdf->getTemplateSize($templateId);

            $novoPdf->AddPage(
                $tamanho['orientation'],
                [$tamanho['width'], $tamanho['height']]
            );
            $novoPdf->useTemplate($templateId);

            $nomeArquivo = "pagina-{$pagina}.pdf";
            $caminhoRelativo = "{$pastaDestino}/{$nomeArquivo}";

            Storage::disk('public')->put(
                $caminhoRelativo,
                $novoPdf->Output('S') // 'S' = retorna como string, sem salvar em disco diretamente
            );

            $caminhosGerados[] = $caminhoRelativo;
        }

        return $caminhosGerados;
    }
}
