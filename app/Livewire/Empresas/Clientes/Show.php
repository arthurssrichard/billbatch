<?php

namespace App\Livewire\Empresas\Clientes;

use App\Enums\CanalCobranca;
use App\Models\Cliente;
use App\Models\Empresa;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Show extends Component
{
    public Empresa $empresa;

    public Cliente $cliente;

    public string $nome = '';

    public string $identificadorExterno = '';

    public ?string $grupo = null;

    public string $cnpj = '';

    public array $canaisEnvio = [];

    public array $contatos = [];

    public function mount(Empresa $empresa, Cliente $cliente): void
    {
        $this->empresa = $empresa;
        $this->cliente = $cliente;

        $this->nome = $cliente->nome;
        $this->identificadorExterno = $cliente->identificador_externo;
        $this->grupo = $cliente->grupo;
        $this->cnpj = $cliente->cnpj;

        $this->canaisEnvio = collect(CanalCobranca::cases())
            ->mapWithKeys(fn ($canal) => [
                $canal->value => in_array($canal->value, $cliente->canais_envio ?? []),
            ])
            ->toArray();

        $this->contatos = $cliente->contatos()
            ->get()
            ->map(fn ($contato) => ['id' => $contato->id, 'endereco_email' => $contato->endereco_email])
            ->toArray();
    }

    public function adicionarContato(): void
    {
        $this->contatos[] = ['id' => null, 'endereco_email' => ''];
    }

    public function removerContato(int $index): void
    {
        unset($this->contatos[$index]);
        $this->contatos = array_values($this->contatos);
    }

    public function salvarAlteracoes(): void
    {
        $this->contatos = array_values(array_filter(
            $this->contatos,
            fn ($contato) => filled($contato['endereco_email']),
        ));

        $this->validate([
            'contatos.*.endereco_email' => ['email'],
        ]);

        $this->cliente->update([
            'nome' => $this->nome,
            'identificador_externo' => $this->identificadorExterno,
            'grupo' => $this->grupo,
            'cnpj' => $this->cnpj,
            'canais_envio' => collect($this->canaisEnvio)->filter()->keys()->values()->toArray(),
        ]);

        $idsExistentes = collect($this->contatos)->pluck('id')->filter()->all();

        $this->cliente->contatos()
            ->whereNotIn('id', $idsExistentes)
            ->delete();

        foreach ($this->contatos as $contato) {
            if (blank($contato['endereco_email'])) {
                continue;
            }

            if ($contato['id']) {
                $this->cliente->contatos()
                    ->where('id', $contato['id'])
                    ->update(['endereco_email' => $contato['endereco_email']]);
            } else {
                $this->cliente->contatos()->create(['endereco_email' => $contato['endereco_email']]);
            }
        }

        // recarrega os contatos já com ids definidos, pra não perder o vínculo se salvar de novo
        $this->contatos = $this->cliente->contatos()
            ->get()
            ->map(fn ($contato) => ['id' => $contato->id, 'endereco_email' => $contato->endereco_email])
            ->toArray();
    }

    #[Computed]
    public function boletos()
    {
        return $this->cliente->boletos()
            ->with('ultimaCobranca')
            ->withCount('cobrancas')
            ->orderByDesc('created_at')
            ->get();
    }

    public function render()
    {
        return view('livewire.empresas.clientes.show')->layout('components.layout', ['title' => $this->cliente->nome]);
    }
}
