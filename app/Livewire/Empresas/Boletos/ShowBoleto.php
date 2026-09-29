<?php

namespace App\Livewire\Empresas\Boletos;

use App\Enums\CanalCobranca;
use App\Enums\TipoCobranca;
use App\Jobs\EnviarBoletoEmailJob;
use App\Models\Boleto;
use App\Models\Empresa;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ShowBoleto extends Component
{
    public Empresa $empresa;

    public Boleto $boleto;

    public TipoCobranca $tipoSelecionado = TipoCobranca::PRIMEIRO_ENVIO;

    public CanalCobranca $canalSelecionado = CanalCobranca::EMAIL;

    public function mount(Empresa $empresa, Boleto $boleto): void
    {
        abort_unless($boleto->empresa_id === $empresa->id, 404);

        $this->empresa = $empresa;
        $this->boleto = $boleto;
    }

    #[Computed]
    public function cobrancas()
    {
        return $this->boleto->cobrancas()->latest('created_at')->get();
    }

    public function togglePago(): void
    {
        $this->boleto->update(['pago' => ! $this->boleto->pago]);
    }

    public function realizarNovaCobranca(): void
    {
        $contatosEnviados = $this->snapshotDeContatos();
        $cobranca = $this->boleto->cobrancas()->create([
            'tipo' => $this->tipoSelecionado,
            'canal_envio' => $this->canalSelecionado,
            'contatos_enviados' => $contatosEnviados,
        ]);

        EnviarBoletoEmailJob::dispatch($cobranca);

        unset($this->cobrancas);
    }

    private function snapshotDeContatos(): string
    {
        return $this->boleto->cliente->contatos->pluck('endereco_email')->implode(';');
    }

    public function render()
    {
        return view('livewire.empresas.boletos.show-boleto')->layout('components.layout', ['title' => 'Boleto']);
    }
}
