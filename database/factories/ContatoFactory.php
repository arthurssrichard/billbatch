<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Contato;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contato>
 */
class ContatoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cliente_id' => Cliente::factory(),
            'endereco_email' => fake()->email(),
        ];
    }
}
