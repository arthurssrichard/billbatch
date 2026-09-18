<?php

namespace App\Livewire;

use App\Models\Usuario;
use App\Services\OnboardingService;
use Livewire\Component;

class Onboarding extends Component
{
    public function mount()
    {

        $empresa = OnboardingService::garantirEmpresaAtiva(app(Usuario::class));

        return redirect()->route('empresas.index');
    }

    public function render()
    {
        return view('livewire.onboarding');
    }
}
