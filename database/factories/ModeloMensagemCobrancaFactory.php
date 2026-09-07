<?php

namespace Database\Factories;

use App\Enums\TipoCobranca;
use App\Models\Empresa;
use App\Models\ModeloMensagemCobranca;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ModeloMensagemCobranca>
 */
class ModeloMensagemCobrancaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'empresa_id' => Empresa::factory(),
            'tipo' => fake()->randomElement(TipoCobranca::cases()),
            'assunto' => fake()->sentence(3, true),
            'corpo' => fake()->sentence(),
        ];
    }
}
