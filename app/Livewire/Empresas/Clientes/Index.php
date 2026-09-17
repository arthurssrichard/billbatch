<?php

namespace App\Livewire\Empresas\Clientes;

use App\Models\Empresa;
use Livewire\Component;

class Index extends Component
{
    public Empresa $empresa;

    public function render()
    {
        return view('livewire.empresas.clientes.index')->layout('components.layout', ['title' => 'Clientes']);
    }
}