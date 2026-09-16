<?php

use App\Livewire\Onboarding;
use App\Models\Empresa;
use Illuminate\Support\Facades\Route;

Route::get('/', Onboarding::class)->name('onboarding');

// Rota provisória para não dar erro no OnboardingTest
Route::get('/empresas/{empresa}', fn (Empresa $empresa) => "Empresa: {$empresa->nome}")
    ->name('empresas.show');
