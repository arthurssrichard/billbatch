<?php

namespace App\Livewire\Empresas\Clientes;

use App\Models\Empresa;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public Empresa $empresa;

    public string $busca = '';

    public array $gruposSelecionados = [];

    public function updatedBusca(): void
    {
        $this->resetPage();
    }

    public function updatedGruposSelecionados(): void
    {
        $this->resetPage();
    }

    public function toggleGrupo(string $grupo): void
    {
        if (in_array($grupo, $this->gruposSelecionados)) {
            $this->gruposSelecionados = array_values(array_diff($this->gruposSelecionados, [$grupo]));
        } else {
            $this->gruposSelecionados[] = $grupo;
        }

        $this->resetPage();
    }

    public function render()
    {
        $grupos = $this->empresa->clientes()
            ->whereNotNull('grupo')
            ->distinct()
            ->orderBy('grupo')
            ->pluck('grupo');

        $clientes = $this->empresa->clientes()
            ->when($this->busca, fn ($q) => $q->where('nome', 'like', "%{$this->busca}%"))
            ->when($this->gruposSelecionados, fn ($q) => $q->whereIn('grupo', $this->gruposSelecionados))
            ->orderBy('nome')
            ->paginate(10);

        return view('livewire.empresas.clientes.index', [
            'clientes' => $clientes,
            'grupos' => $grupos,
        ])->layout('components.layout', ['title' => 'Clientes: '.$this->empresa->nome]);
    }
}
