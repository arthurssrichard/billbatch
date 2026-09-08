<?php

namespace Database\Factories;

use App\Models\Boleto;
use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Boleto>
 */
class BoletoFactory extends Factory
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
            'empresa_id' => function (array $attributes) {
                return Cliente::find($attributes['cliente_id'])->empresa_id;
            },
            'codigo_barras' => fake()->unique()->regexify('[0-9]{5}\.[0-9]{5} [0-9]{5}\.[0-9]{6} [0-9]{5}\.[0-9]{6} [0-9]{1} [0-9]{14}'),
            'grupo' => fake()->randomElement([100, 150, 200, 250]).'_'.fake()->randomElement([5, 10, 15, 20, 25]).'_'.strtoupper(fake()->monthName()),
            'caminho_arquivo' => function (array $attributes) {
                return 'boletos/'.$attributes['grupo'].'/'.fake()->company().'.pdf';
            },
            'enviado' => fake()->boolean(),
            'pago' => fake()->boolean(),
            'data_emissao' => fake()->dateTimeThisYear()->format('Y-m-d'),
        ];
    }
}
