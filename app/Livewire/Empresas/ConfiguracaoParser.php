<?php

namespace App\Livewire\Empresas;

use App\Models\Cliente;
use App\Models\ConfiguracaoParser as ConfiguracaoParserModel;
use App\Models\Empresa;
use App\Services\GerarBoletoFakeService;
use App\Services\LayoutParserService;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class ConfiguracaoParser extends Component
{
    public Empresa $empresa;

    public string $regexNomeCliente = '';

    public string $regexCodigoBarras = '';

    public ?string $caminhoBoletoTeste = null;

    public ?string $nomeClienteExtraido = null;

    public ?string $codigoBarrasExtraido = null;

    public ?bool $clienteEncontrado = null;

    public ?string $textoExtraidoDaPagina = null;

    public function mount(Empresa $empresa): void
    {
        $this->empresa = $empresa;

        $config = $empresa->configuracaoParser;

        if ($config) {
            $this->regexNomeCliente = $config->regex_nome_cliente;
            $this->regexCodigoBarras = $config->regex_codigo_barras;

            return;
        }

        $this->preencherComPadraoDeFabrica();
    }

    public function updatedRegexNomeCliente(): void
    {
        $this->limparResultadoTeste();
    }

    public function updatedRegexCodigoBarras(): void
    {
        $this->limparResultadoTeste();
    }

    public function salvar(): void
    {
        $this->validate($this->regrasDeValidacao());

        $this->empresa->configuracaoParser()->updateOrCreate(
            [],
            [
                'regex_nome_cliente' => $this->regexNomeCliente,
                'regex_codigo_barras' => $this->regexCodigoBarras,
            ]
        );

        session()->flash('sucesso', 'Configuração do parser salva.');
    }

    public function restaurarPadrao(): void
    {
        $this->preencherComPadraoDeFabrica();
        $this->limparResultadoTeste();
    }

    public function testar(): void
    {
        $this->validate($this->regrasDeValidacao());

        $caminho = GerarBoletoFakeService::gerarBoletoTeste($this->empresa);

        if (! $caminho) {
            $this->limparResultadoTeste();
            session()->flash('erro', 'Não há clientes cadastrados nesta empresa para gerar um boleto de teste.');

            return;
        }

        $configTemporaria = $this->empresa->configuracaoParser()->make([
            'regex_nome_cliente' => $this->regexNomeCliente,
            'regex_codigo_barras' => $this->regexCodigoBarras,
        ]);
        $configTemporaria->setRelation('empresa', $this->empresa);

        $parser = new LayoutParserService($configTemporaria);
        $caminhoAbsoluto = Storage::disk('public')->path($caminho);

        $this->textoExtraidoDaPagina = $parser->extrairTextoDaPagina($caminhoAbsoluto);
        $dados = $parser->extrairDadosDaPagina($caminhoAbsoluto);

        $dados = (new LayoutParserService($configTemporaria))
            ->extrairDadosDaPagina(Storage::disk('public')->path($caminho));

        $this->caminhoBoletoTeste = $caminho;
        $this->nomeClienteExtraido = $dados['nome_cliente'];
        $this->codigoBarrasExtraido = $dados['codigo_barras'];

        $this->clienteEncontrado = $this->nomeClienteExtraido !== null
            && Cliente::where('empresa_id', $this->empresa->id)
                ->where('nome', $this->nomeClienteExtraido)
                ->exists();
    }

    private function preencherComPadraoDeFabrica(): void
    {
        try {
            $padrao = ConfiguracaoParserModel::factory()->make();

            $this->regexNomeCliente = $padrao->regex_nome_cliente;
            $this->regexCodigoBarras = $padrao->regex_codigo_barras;
        } catch (\Throwable) {
            $this->regexNomeCliente = '';
            $this->regexCodigoBarras = '';
        }
    }

    private function limparResultadoTeste(): void
    {
        $this->caminhoBoletoTeste = null;
        $this->nomeClienteExtraido = null;
        $this->codigoBarrasExtraido = null;
        $this->clienteEncontrado = null;
        $this->textoExtraidoDaPagina = null;
    }

    private function regrasDeValidacao(): array
    {
        return [
            'regexNomeCliente' => ['required', 'string', $this->regraRegexValido()],
            'regexCodigoBarras' => ['required', 'string', $this->regraRegexValido()],
        ];
    }

    private function regraRegexValido(): \Closure
    {
        return function (string $attribute, string $value, \Closure $fail) {
            if (@preg_match($value, '') === false) {
                $fail("O campo {$attribute} não é uma expressão regular válida.");
            }
        };
    }

    public function render()
    {
        return view('livewire.empresas.configuracao-parser')->layout('components.layout', ['title' => 'Configurar Parser']);
    }
}
