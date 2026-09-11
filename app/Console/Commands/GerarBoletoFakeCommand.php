<?php

namespace App\Console\Commands;

use App\Models\Empresa;
use App\Services\GerarBoletoFakeService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('boletos:gerar-fake {--empresa= : ID de uma empresa específica. Se omitido, gera para um número aleatório de empresas existentes.}')]
#[Description('Gera PDFs de boletos fake para uma ou mais empresas existentes.')]
class GerarBoletoFakeCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $empresaId = $this->option('empresa');

        if ($empresaId) {
            $empresa = Empresa::find($empresaId);

            if (! $empresa) {
                $this->error("Empresa com ID {$empresaId} não encontrada.");

                return self::FAILURE;
            }

            $empresas = collect([$empresa]);
        } else {
            $empresas = Empresa::inRandomOrder()
                ->take(fake()->numberBetween(1, Empresa::count()))
                ->get();
        }

        if ($empresas->isEmpty()) {
            $this->warn('Nenhuma empresa encontrada no banco. Rode o seeder primeiro.');

            return self::FAILURE;
        }

        foreach ($empresas as $empresa) {
            $this->info("Gerando boletos fake para: {$empresa->nome}");

            $gerados = GerarBoletoFakeService::gerarDeTodosClientes($empresa);

            foreach ($gerados as $grupo => $caminho) {
                $this->line("  → {$grupo}: {$caminho}");
            }
        }

        return self::SUCCESS;
    }
}
