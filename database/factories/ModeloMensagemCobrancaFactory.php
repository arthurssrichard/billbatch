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
            'assunto' => '',
            'corpo' => '',
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (ModeloMensagemCobranca $modelo) {
            if ($modelo->assunto !== '' || $modelo->corpo !== '') {
                return; // já veio com texto customizado explicitamente, não sobrescreve
            }

            [$modelo->assunto, $modelo->corpo] = $this->textoPadrao($modelo->tipo);
        });
    }

    private function textoPadrao(TipoCobranca $tipo): array
    {
        return match ($tipo) {
            TipoCobranca::PRIMEIRO_ENVIO => [
                'Seu boleto já está disponível',
                "{SAUDACAO}, {NOME_CLIENTE}!\n\nSegue o boleto referente à sua cobrança deste mês. Qualquer dúvida, estamos à disposição.",
            ],
            TipoCobranca::AVISO => [
                'Lembrete: seu boleto vence em breve',
                "{SAUDACAO}, {NOME_CLIENTE}!\n\nPassando para lembrar que o boleto enviado anteriormente está próximo do vencimento. Evite juros e mantenha seus pagamentos em dia.",
            ],
            TipoCobranca::COBRANCA => [
                'Boleto em atraso — regularize sua situação',
                "{SAUDACAO}, {NOME_CLIENTE}!\n\nIdentificamos que o boleto enviado anteriormente ainda não foi pago e já está em atraso. Pedimos a gentileza de regularizar o quanto antes para evitar a suspensão dos serviços.",
            ],
        };
    }
}
