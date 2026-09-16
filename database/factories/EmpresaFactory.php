<?php

namespace Database\Factories;

use App\Enums\TipoCobranca;
use App\Models\Cliente;
use App\Models\Contato;
use App\Models\Email;
use App\Models\Empresa;
use App\Models\Log;
use App\Models\ModeloMensagemCobranca;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Empresa>
 */
class EmpresaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'usuario_id' => Usuario::factory(),
            'nome' => fake()->company(),
        ];
    }

    public function completa(): static
    {
        return $this
            ->has(Cliente::factory()->count(fake()->numberBetween(5, 30))
                ->has(Contato::factory()->count(fake()->numberBetween(1, 5))))
            ->has(ModeloMensagemCobranca::factory(['tipo' => TipoCobranca::PRIMEIRO_ENVIO]))
            ->has(ModeloMensagemCobranca::factory(['tipo' => TipoCobranca::AVISO]))
            ->has(ModeloMensagemCobranca::factory(['tipo' => TipoCobranca::COBRANCA]))
            ->has(Email::factory())
            ->has(Log::factory()->count(fake()->numberBetween(6, 30)));
    }
}
