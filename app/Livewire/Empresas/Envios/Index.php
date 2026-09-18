<?php

namespace App\Livewire\Empresas\Envios;

use App\Models\Empresa;
use Livewire\Component;

class Index extends Component
{
    public Empresa $empresa;

    public function render()
    {
        return view('livewire.empresas.envios.index')->layout('components.layout', ['title' => 'Envios']);
    }
}
