<?php

namespace Database\Factories;

use App\Enums\CanalCobranca;
use App\Models\Cliente;
use App\Models\Contato;
use App\Models\Empresa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cliente>
 */
class ClienteFactory extends Factory
{
    public function configure(): static
    {
        return $this->afterCreating(function (Cliente $cliente) {
            Contato::factory()->count(fake()->numberBetween(1, 5))->create([
                'cliente_id' => $cliente->id,
            ]);
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'empresa_id' => Empresa::factory(),
            'identificador_externo' => fake()->uuid(),
            'nome' => fake()->name(),
            'grupo' => fake()->randomElement([100, 150, 200, 250]) . '_' . fake()->randomElement([5, 10, 15, 20, 25]),
            'canais_envio' => fake()->randomElements(CanalCobranca::cases(), fake()->numberBetween(1, count(CanalCobranca::cases()))),
            'cnpj' => fake()->unique()->regexify('^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$'),
        ];
    }
}
