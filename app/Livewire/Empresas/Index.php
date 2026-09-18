<?php

namespace App\Livewire\Empresas;

use App\Models\Empresa;
use App\Models\Usuario;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        dd(
            \App\Models\Empresa::pluck('nome'),
        );

        return view('livewire.empresas.index', [
            'empresas' => Empresa::withCount('clientes')->get(),
        ])->layout('components.layout', ['title' => 'Empresas']);
    }
}
