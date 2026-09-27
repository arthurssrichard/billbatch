<?php

use App\Enums\CobrancaStatus;
use App\ENums\TipoCobranca;
use App\Jobs\EnviarBoletoEmailJob;
use App\Mail\CobrancaMail;
use App\Models\Boleto;
use App\Models\Cobranca;
use App\Models\Empresa;
use App\Models\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

test('marca a cobrança como enviada e preenche data_envio/contatos_enviados', function () {
    Mail::fake();
    Storage::fake('public');

    $empresa = Empresa::factory()->completa()->create();
    $cliente = $empresa->clientes()->first();
    $cliente->update(['canais_envio' => ['email']]);

    Storage::disk('public')->put('boletos/teste.pdf', 'conteudo fake');

    $boleto = Boleto::factory()->create([
        'cliente_id' => $cliente->id,
        'empresa_id' => $empresa->id,
        'caminho_arquivo' => 'boletos/teste.pdf',
    ]);

    $cobranca = Cobranca::factory()->create([
        'boleto_id' => $boleto->id,
        'tipo' => TipoCobranca::PRIMEIRO_ENVIO,
        'status' => CobrancaStatus::PENDENTE,
    ]);

    (new EnviarBoletoEmailJob($cobranca))->handle();

    $cobranca->refresh();

    expect($cobranca->status)->toBe(CobrancaStatus::ENVIADO);
    expect($cobranca->data_envio)->not->toBeNull();
    expect($cobranca->contatos_enviados)->not->toBeEmpty();
    // expect(Log::where('cobranca_id', $cobranca->id)->count())->toBe(0);

    $enderecos = $cliente->contatos->pluck('endereco_email')->all();
    Mail::assertSent(CobrancaMail::class, function ($mail) use ($enderecos) {
        return $mail->hasTo($enderecos) && count($mail->attachments()) === 1;
    });
});
