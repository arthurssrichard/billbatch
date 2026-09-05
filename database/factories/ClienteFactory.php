<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cliente>
 */
class ClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'empresa_id' => \App\Models\Empresa::factory(),
            'identificador_externo' => fake()->uuid(),
            'nome' => fake()->name(),
            'canais_envio' => fake()->randomElements(['email','whatsapp'],2),
            'cnpj' => fake()->unique()->regexify('^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$'),
        ];
    }
}
