<?php

namespace App\Livewire\Empresas;

use App\Models\Empresa;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.empresas.index', [
            'empresas' => Empresa::withCount('clientes')->get(),
        ])->layout('components.layout', ['title' => 'Empresas']);
    }
}
