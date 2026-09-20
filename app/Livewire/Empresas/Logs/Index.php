<?php

namespace App\Livewire\Empresas\Logs;

use App\Enums\LogStatus;
use App\Models\Empresa;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public Empresa $empresa;

    public string $filtroStatus = '';

    public function updatedFiltroStatus(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $logs = $this->empresa->logs()
            ->when($this->filtroStatus, fn ($q) => $q->where('status', $this->filtroStatus))
            ->latest()
            ->paginate(15);

        return view('livewire.empresas.logs.index', [
            'logs' => $logs,
            'status' => LogStatus::cases(),
        ])->layout('components.layout', ['title' => 'Logs: '.$this->empresa->nome]);
    }
}
