<?php

namespace App\Livewire\Empresas\Envios;

use App\Enums\CanalCobranca;
use App\Enums\CobrancaStatus;
use App\Enums\TipoCobranca;
use App\Jobs\EnviarBoletoEmailJob;
use App\Models\Boleto;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Services\GerarBoletoFakeService;
use App\Services\ProcessarBoletoService;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use setasign\Fpdi\Fpdi;

class NovoEnvio extends Component
{
    public Empresa $empresa;

    public int $fase = 1;

    /** @var array<string, string> grupo => caminho do arquivo bruto */
    public array $boletosBrutos = [];

    /** @var array<int, array{grupo: string, caminho: string, paginas: int}> */
    public array $cardsGrupos = [];

    /** @var array<string, array> grupo => resultado de ProcessarBoletoService */
    public array $resultadosProcessados = [];

    public function simularEnvio(): void
    {
        $this->boletosBrutos = GerarBoletoFakeService::gerarDeTodosClientes($this->empresa);

        $this->cardsGrupos = collect($this->boletosBrutos)
            ->map(fn ($caminho, $grupo) => [
                'grupo' => $grupo,
                'caminho' => $caminho,
                'paginas' => $this->contarPaginas($caminho),
            ])
            ->values()
            ->toArray();

        $this->fase = 2;
    }

    public function processar(): void
    {
        $this->resultadosProcessados = collect($this->boletosBrutos)
            ->flatMap(fn ($caminho, $grupo) => ProcessarBoletoService::processar($this->empresa, $caminho, $grupo))
            ->toArray();

        $this->fase = 3;
    }

    public function voltar(): void
    {
        $this->fase = max(1, $this->fase - 1);
    }

    public function confirmarEnvio(): void
    {
        $criados = 0;
        $ignorados = 0;

        $limiteHora = $this->empresa->email->limite_emails_hora ?? 100;
        $segundosPorEnvio = 3600 / $limiteHora; // 36s quando limite = 100
        $segundosAcumulados = 0;

        foreach ($this->resultadosProcessados as $resultado) {
            if (! $resultado['cliente']) {
                $ignorados++;

                continue;
            }

            $boleto = Cliente::find($resultado['cliente']['id'])->boletos()->create([
                'empresa_id' => $this->empresa->id,
                'codigo_barras' => $resultado['codigo_barras'],
                'grupo' => $resultado['grupo'],
                'caminho_arquivo' => $resultado['caminho_arquivo'],
                'enviado' => false,
                'pago' => false,
                'data_emissao' => now(),
            ]);

            // Envia e incrementa segundos de throttle nos jobs
            $segundosAcumulados = $this->enviarEmailParaCliente($boleto, $segundosPorEnvio, $segundosAcumulados);

            $criados++;
        }

        session()->flash('sucesso', "{$criados} boletos criados. {$ignorados} ignorados por falta de identificação.");

        $this->redirectRoute('empresas.envios.index', $this->empresa);
    }

    private function enviarEmailParaCliente(Boleto $boleto, float $segundosPorEnvio, float $segundosAcumulados)
    {
        $cobranca = $boleto->cobrancas()->create([
            'canal_envio' => CanalCobranca::EMAIL,
            'tipo' => TipoCobranca::PRIMEIRO_ENVIO,
            'status' => CobrancaStatus::PENDENTE,
        ]);

        EnviarBoletoEmailJob::dispatch($cobranca)
            ->delay(now()->addSeconds($segundosAcumulados));

        $numeroDeEnvios = max($boleto->cliente->contatos()->count(), 1);
        $segundosAcumulados += $segundosPorEnvio * $numeroDeEnvios;

        return $segundosAcumulados;
    }

    private function contarPaginas(string $caminho): int
    {
        $pdf = new Fpdi;

        return $pdf->setSourceFile(
            Storage::disk('public')->path($caminho)
        );
    }

    public function getResultadosAgrupadosProperty()
    {
        return collect($this->resultadosProcessados)->groupBy('grupo');
    }

    public function render()
    {
        return view('livewire.empresas.envios.novo-envio')->layout('components.layout', ['title' => 'Novo envio']);
    }
}
