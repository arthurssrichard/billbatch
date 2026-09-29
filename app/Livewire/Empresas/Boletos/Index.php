<?php

namespace App\Livewire\Empresas\Boletos;

use App\Enums\CobrancaStatus;
use App\Models\Empresa;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Index extends Component
{
    public Empresa $empresa;

    public function mount(Empresa $empresa): void
    {
        $this->empresa = $empresa;
    }

    #[Computed]
    public function grupos()
    {
        return $this->empresa->boletos()
            ->with(['cliente', 'ultimaCobranca.logs'])
            ->get()
            ->groupBy('grupo')
            ->map(function ($boletosDoGrupo, $nomeGrupo) {
                $datasEnvio = $boletosDoGrupo
                    ->pluck('ultimaCobranca.data_envio')
                    ->filter();

                return [
                    'nome' => $nomeGrupo,
                    'data_inicio' => $datasEnvio->min(),
                    'data_fim' => $datasEnvio->max(),
                    'data_criacao' => $boletosDoGrupo->min('created_at'),
                    'quantidade' => $boletosDoGrupo->count(),
                    'boletos' => $boletosDoGrupo,
                ];
            })
            ->sortByDesc(fn ($grupo) => $grupo['data_criacao'])
            ->values();
    }

    #[Computed]
    public function temEnvioEmAndamento(): bool
    {
        return $this->empresa->boletos()
            ->whereHas('ultimaCobranca', fn ($q) => $q->whereIn('status', [
                CobrancaStatus::PENDENTE,
                CobrancaStatus::ENVIANDO,
            ]))
            ->exists();
    }

    public function togglePago(int $boletoId): void
    {
        $boleto = $this->empresa->boletos()->findOrFail($boletoId);

        $boleto->update(['pago' => ! $boleto->pago]);

        unset($this->grupos);
    }

    public function render()
    {
        return view('livewire.empresas.boletos.index')->layout('components.layout', ['title' => 'Boletos']);
    }
}
