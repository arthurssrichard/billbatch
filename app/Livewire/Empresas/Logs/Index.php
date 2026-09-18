<?php

namespace App\Livewire\Empresas\Logs;

use App\Models\Empresa;
use Livewire\Component;

class Index extends Component
{
    public Empresa $empresa;

    public function render()
    {
        return view('livewire.empresas.logs.index')->layout('components.layout', ['title' => 'Logs']);
    }
}
