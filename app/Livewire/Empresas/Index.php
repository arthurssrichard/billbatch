<?php

namespace App\Livewire\Empresas;

use App\Models\Empresa;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        dd(
            app()->bound(\App\Models\Usuario::class) ? app(\App\Models\Usuario::class)->id : 'não bound',
            Empresa::pluck('id', 'usuario_id')
        );

        return view('livewire.empresas.index', [
            'empresas' => Empresa::withCount('clientes')->get(),
        ])->layout('components.layout', ['title' => 'Empresas']);
    }
}
