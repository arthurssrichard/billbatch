<?php

namespace App\Livewire\Empresas;

use App\Models\Boleto;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Services\SubstituirPlaceholdersService;
use App\Traits\ValidatesWithToast;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ModelosMensagem extends Component
{
    use ValidatesWithToast;

    public Empresa $empresa;

    #[Validate([
        'modelos.*.assunto' => ['required'],
    ], onUpdate: false)]
    public array $modelos = [];

    public function mount(Empresa $empresa): void
    {
        $this->empresa = $empresa;

        $this->modelos = $empresa->modeloMensagemCobrancas()
            ->get()
            ->mapWithKeys(fn ($modelo) => [
                $modelo->tipo->value => [
                    'id' => $modelo->id,
                    'assunto' => $modelo->assunto,
                    'corpo' => $modelo->corpo,
                ],
            ])
            ->toArray();
    }

    public function salvar(string $tipo): void
    {
        if (! $this->validateWithToast([
            'modelos\.\d+\.assunto' => 'assunto',
            'modelos\.\d+\.corpo' => 'corpo',
        ], 'Modelo de mensagem')) {
            return;
        }

        $dados = $this->modelos[$tipo];

        $this->empresa->modeloMensagemCobrancas()
            ->where('id', $dados['id'])
            ->update([
                'assunto' => $dados['assunto'],
                'corpo' => $dados['corpo'],
            ]);
    }

    #[Computed]
    public function preview(): array
    {
        $boletoFake = new Boleto;
        $boletoFake->setRelation('cliente', new Cliente(['nome' => 'Cliente de Teste']));

        return collect($this->modelos)->map(fn ($dados) => [
            'assunto' => SubstituirPlaceholdersService::substituir($dados['assunto'], $boletoFake),
            'corpo' => SubstituirPlaceholdersService::substituir($dados['corpo'], $boletoFake),
        ])->toArray();
    }

    public function render()
    {
        return view('livewire.empresas.modelos-mensagem')->layout('components.layout', ['title' => 'Modelos de mensagem']);
    }
}
