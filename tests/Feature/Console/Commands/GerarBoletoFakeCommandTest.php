<?php

use App\Models\Cliente;
use App\Models\Empresa;
use Illuminate\Support\Facades\Storage;

test('falha graciosamente quando --empresa não existe', function () {
    Storage::fake('public');

    $this->artisan('boletos:gerar-fake', ['--empresa' => 9999])
        ->assertFailed();
});

test('gera boletos para a empresa especificada via --empresa', function () {
    Storage::fake('public');

    $empresa = Empresa::factory()
        ->has(Cliente::factory()->count(2)->state(['grupo' => '150_10']))
        ->create();

    $this->artisan('boletos:gerar-fake', ['--empresa' => $empresa->id])
        ->assertSuccessful();

    Storage::disk('public')->assertExists(
        Storage::disk('public')->allFiles("boletos_brutos/{$empresa->id}")[0] ?? ''
    );
});

test('sem --empresa, processa ao menos uma empresa existente', function () {
    Storage::fake('public');

    Empresa::factory()
        ->has(Cliente::factory()->count(2)->state(['grupo' => '150_10']))
        ->count(3)
        ->create();

    $this->artisan('boletos:gerar-fake')
        ->assertSuccessful();
});
