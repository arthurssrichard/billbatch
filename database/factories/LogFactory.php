<?php

namespace Database\Factories;

use App\Enums\LogStatus;
use App\Models\Empresa;
use App\Models\Log;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Log>
 */
class LogFactory extends Factory
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
            'status' => fake()->randomElement(LogStatus::cases()),
            'arquivo_origem' => 'app/'.fake()->word().'/'.fake()->word().'.php',
            'nome' => fake()->sentence(6),
            'mensagem' => fake()->sentence(12),
        ];
    }
}
