<?php

namespace App\Livewire\Empresas\Boletos;

use App\Models\Empresa;
use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Enums\CobrancaStatus;

class Index extends Component
{


    public function render()
    {
        return view('livewire.empresas.boletos.index')->layout('components.layout', ['title' => 'Boletos']);
    }
}
