<?php

namespace Database\Factories;

use App\Models\Email;
use App\Models\Empresa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Email>
 */
class EmailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $secure = fake()->randomElement(['tls', 'ssl']);
        $port = match ($secure) {
            'tls' => '587',
            'ssl' => '465',
            default => '25'
        };

        return [
            'empresa_id' => Empresa::factory(),
            'limite_emails_hora' => fake()->randomELement([100, 150, 200, 300]),
            'remetente_nome' => fake()->company(),
            'remetente_endereco' => fake()->safeEmail(),
            'remetente_senha' => fake()->password(),
            'smtp_servidor' => 'smtp.'.fake()->domainName(),
            'smtp_secure' => $secure,
            'smtp_porta' => $port,
        ];
    }
}
