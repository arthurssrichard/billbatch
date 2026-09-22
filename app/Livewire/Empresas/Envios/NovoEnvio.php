<?php

namespace App\Livewire\Empresas\Envios;

use App\Models\Empresa;
use App\Services\GerarBoletoFakeService;
use Livewire\Component;
use App\Services\ProcessarBoletoService;

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

    private function contarPaginas(string $caminho): int
    {
        $pdf = new \setasign\Fpdi\Fpdi();
        return $pdf->setSourceFile(
            \Illuminate\Support\Facades\Storage::disk('public')->path($caminho)
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
