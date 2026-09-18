<?php

namespace App\Livewire\Empresas;

use App\Models\Empresa;
use Livewire\Component;

class Show extends Component
{
    public Empresa $empresa;

    public function render()
    {
        return view('livewire.empresas.show', [
            'empresa' => $this->empresa,
        ])->layout('components.layout', ['title' => $this->empresa->nome]);
    }
}
