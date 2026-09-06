<?php

namespace Database\Factories;

use App\Enums\TipoCobranca;
use App\Enums\CanalCobranca;
use App\Models\Boleto;
use App\Models\Contato;
use App\Models\Cobranca;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cobranca>
 */
class CobrancaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'boleto_id' => Boleto::factory(),
            'canal_envio' => fake()->randomElement(CanalCobranca::cases()),
            'contatos_enviados' => function(array $attributes){
                return Boleto::find($attributes['boleto_id'])->cliente->contatos->pluck('endereco_email')->implode(';');
            },
            'tipo' => fake()->randomElement(TipoCobranca::cases()),
            'data_envio' => now()
        ];
    }
}
