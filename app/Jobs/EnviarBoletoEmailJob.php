<?php

namespace App\Jobs;

use App\Enums\CobrancaStatus;
use App\Enums\LogStatus;
use App\Enums\TipoCobranca;
use App\Mail\CobrancaMail;
use App\Models\Boleto;
use App\Models\Cliente;
use App\Models\Cobranca;
use App\Models\Empresa;
use App\Models\ModeloMensagemCobranca;
use App\Services\SubstituirPlaceholdersService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Throwable;

class EnviarBoletoEmailJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    private Cobranca $cobranca;

    private ModeloMensagemCobranca $modeloMensagemCobranca;

    private Boleto $boleto;

    private Cliente $cliente;

    private Empresa $empresa;

    /**
     * Create a new job instance.
     */
    public function __construct(Cobranca $cobranca)
    {
        $this->cobranca = $cobranca;
        $this->boleto = $cobranca->boleto;
        $this->cliente = $this->boleto->cliente;
        $this->empresa = $this->cliente->empresa;
        $this->modeloMensagemCobranca = $this->empresa->modeloMensagemCobrancas()
            ->where('tipo', $cobranca->tipo)
            ->first();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->cobranca->update(['status' => CobrancaStatus::ENVIANDO]);
        // Guard functions
        if ($this->cobranca->boleto->cliente->contatos()->count() === 0) {
            $this->registrarErro($this->empresa, "Cliente {$this->cliente->nome} não tem contatos");

            return;
        }

        if (! in_array('email', $this->cliente->canais_envio ?? [])) {
            $this->registrarErro($this->empresa, "Cliente {$this->cliente->nome} não está com envio de e-mail habilitado");

            return;
        }

        if (! $this->modeloMensagemCobranca) {
            $this->registrarErro($this->empresa, "Empresa {$this->empresa->nome} não possui modelo de mensagem cadastrado");

            return;
        }

        // Se tudo certo, envia o e-mail
        $enderecos = $this->cliente->contatos->pluck('endereco_email')->all();
        Mail::to($enderecos)->send(new CobrancaMail(
            SubstituirPlaceholdersService::substituir($this->modeloMensagemCobranca->assunto, $this->cobranca->boleto),
            SubstituirPlaceholdersService::substituir($this->modeloMensagemCobranca->corpo, $this->cobranca->boleto),
            $this->cobranca->boleto->caminho_arquivo,
        ));

        $this->cobranca->update([
            'status' => CobrancaStatus::ENVIADO,
            'data_envio' => now(),
            'contatos_enviados' => implode(';', $enderecos),
        ]);

        if ($this->cobranca->tipo === TipoCobranca::PRIMEIRO_ENVIO) {
            $this->boleto->update(['enviado' => true]);
        }

        $enderecosEmTexto = implode(', ', $enderecos);
        $this->empresa->logs()->create([
            'cobranca_id' => $this->cobranca->id,
            'status' => LogStatus::SUCCESS,
            'nome' => 'E-mail enviado com sucesso',
            'arquivo_origem' => self::class,
            'mensagem' => "Enviado para $enderecosEmTexto do cliente {$this->cliente->nome}",
        ]);
    }

    public function backoff(): array
    {
        $intervalo = (int) ceil($this->empresa->email->intervaloEnvioSegundos());

        return [$intervalo, $intervalo * 2, $intervalo * 4];
    }

    private function registrarErro(Empresa $empresa, string $mensagem): void
    {
        $this->cobranca->update(['status' => CobrancaStatus::ERRO]);

        $empresa->logs()->create([
            'empresa_id' => $this->empresa->id,
            'cobranca_id' => $this->cobranca->id,
            'status' => LogStatus::ERROR,
            'nome' => 'Erro ao enviar e-mail',
            'arquivo_origem' => self::class,
            'mensagem' => $mensagem,
        ]);
    }

    public function failed(Throwable $exception): void
    {
        $this->cobranca->update(['status' => CobrancaStatus::ERRO]);

        $this->empresa->logs()->create([
            'empresa_id' => $this->empresa->id,
            'cobranca_id' => $this->cobranca->id,
            'status' => LogStatus::ERROR,
            'nome' => 'Erro inesperado ao enviar e-mail',
            'arquivo_origem' => self::class,
            'mensagem' => "Cliente: {$this->cliente->nome}".$exception->getMessage(),
        ]);
    }
}
