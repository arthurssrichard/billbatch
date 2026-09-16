<?php

use App\Livewire\Onboarding;
use App\Models\Empresa;
use App\Models\Usuario;
use Illuminate\Support\Facades\Route;

Route::get('/', Onboarding::class)->name('onboarding');

// Rota provisória para não dar erro no OnboardingTest
Route::get('/empresas/{empresa}', function (Empresa $empresa) {
    return response()->json([
        'empresa_id' => $empresa->id,
        'empresa_nome' => $empresa->nome,
        'usuario_bound_no_container' => app()->bound(Usuario::class),
        'usuario_atual_id' => app()->bound(Usuario::class) ? app(Usuario::class)->id : null,
    ]);
})->name('empresas.show');
