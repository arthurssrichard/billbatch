<?php

namespace Database\Factories;

use App\Models\ConfiguracaoParser;
use App\Models\Empresa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ConfiguracaoParser>
 */
class ConfiguracaoParserFactory extends Factory
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
            'regex_nome_cliente' => '/PAGADOR\s*\n\s*(.+)/i',
            'regex_codigo_barras' => '/\d{5}\.\d{5}\s\d{5}\.\d{6}\s\d{5}\.\d{6}\s\d\s\d{14}/',
        ];
    }
}
