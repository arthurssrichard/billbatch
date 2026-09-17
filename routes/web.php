<?php

use App\Livewire\Onboarding;
use App\Livewire\Empresas\Index as EmpresasIndex;
use App\Livewire\Empresas\Show as EmpresasShow;
use App\Livewire\Empresas\Clientes\Index as ClientesIndex;
use App\Livewire\Empresas\Logs\Index as LogsIndex;
use App\Livewire\Empresas\FontesDados\Index as FontesDadosIndex;
use App\Livewire\Empresas\Envios\Index as EnviosIndex;
use Illuminate\Support\Facades\Route;

Route::get('/', Onboarding::class)->name('onboarding');

Route::get('/empresas', EmpresasIndex::class)->name('empresas.index');
Route::get('/empresas/{empresa}', EmpresasShow::class)->name('empresas.show');
Route::get('/empresas/{empresa}/clientes', ClientesIndex::class)->name('empresas.clientes.index');
Route::get('/empresas/{empresa}/logs', LogsIndex::class)->name('empresas.logs.index');
Route::get('/empresas/{empresa}/fontes-dados', FontesDadosIndex::class)->name('empresas.fontes-dados.index');
Route::get('/empresas/{empresa}/envios', EnviosIndex::class)->name('empresas.envios.index');