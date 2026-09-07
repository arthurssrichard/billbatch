<?php

namespace Database\Seeders;

use App\Enums\TipoCobranca;
use App\Models\ModeloMensagemCobranca;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Email;
use App\Models\Contato;
use App\Models\Log;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuario = Usuario::factory()
            ->has(
                Empresa::factory()
                    ->has(
                        Cliente::factory()->count(fake()->numberBetween(5,30))
                        ->has(Contato::factory()->count(fake()->numberBetween(1,5)))
                    )
                    ->has(ModeloMensagemCobranca::factory(['tipo' => TipoCobranca::PRIMEIRO_ENVIO]))
                    ->has(ModeloMensagemCobranca::factory(['tipo' => TipoCobranca::AVISO]))
                    ->has(ModeloMensagemCobranca::factory(['tipo' => TipoCobranca::COBRANCA]))
                    ->has(Email::factory())
                    ->has(Log::factory()->count(fake()->numberBetween(6,30)))
            )
            ->create();
    }
}
