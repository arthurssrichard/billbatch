<?php

namespace App\View\Composers;

use App\Models\Usuario;
use App\Services\OnboardingService;
use Illuminate\View\View;

class OnboardingPopupComposer
{
    public function compose(View $view): void
    {
        $recemCriada = app()->bound(Usuario::class)
            ? OnboardingService::empresaRecemCriada(app(Usuario::class))
            : false;

        $view->with('onboardingRecemCriada', $recemCriada);
    }
}
