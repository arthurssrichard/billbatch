<?php

use App\Livewire\Empresas\Clientes\Show;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Usuario;
use Livewire\Livewire;

test('sincroniza contatos ao salvar: cria novos, atualiza existentes e remove os retirados', function () {
    $usuario = Usuario::factory()->create();
    app()->instance(Usuario::class, $usuario);

    $empresa = Empresa::factory()->for($usuario)->create();
    $cliente = Cliente::factory()->for($empresa)->create();
    $cliente->contatos()->delete(); // limpa os contatos aleatórios que o factory já gera

    $cliente->contatos()->create(['endereco_email' => 'antigo@empresa.com']);
    $cliente->contatos()->create(['endereco_email' => 'remover@empresa.com']);

    $component = Livewire::test(Show::class, ['empresa' => $empresa, 'cliente' => $cliente])
        ->set('contatos.0.endereco_email', 'atualizado@empresa.com')
        ->call('removerContato', 1)
        ->call('adicionarContato');

    $novoIndex = array_key_last($component->get('contatos'));

    $component->set("contatos.$novoIndex.endereco_email", 'novo@empresa.com')
        ->call('salvarAlteracoes');

    $emails = $cliente->contatos()->pluck('endereco_email')->sort()->values()->all();

    expect($emails)->toBe(['atualizado@empresa.com', 'novo@empresa.com']);
});

test('salva canais_envio como array a partir dos toggles marcados', function () {
    $usuario = Usuario::factory()->create();
    app()->instance(Usuario::class, $usuario);

    $empresa = Empresa::factory()->for($usuario)->create();
    $cliente = Cliente::factory()->for($empresa)->create(['canais_envio' => ['email']]);

    Livewire::test(Show::class, ['empresa' => $empresa, 'cliente' => $cliente])
        ->set('canaisEnvio.whatsapp', true)
        ->call('salvarAlteracoes');

    expect($cliente->fresh()->canais_envio)->toContain('email', 'whatsapp');
});

test('não salva um contato com e-mail inválido', function () {
    $usuario = Usuario::factory()->create();
    app()->instance(Usuario::class, $usuario);

    $empresa = Empresa::factory()->for($usuario)->create();
    $cliente = Cliente::factory()->for($empresa)->create();
    $cliente->contatos()->delete();

    Livewire::test(Show::class, ['empresa' => $empresa, 'cliente' => $cliente])
        ->call('adicionarContato')
        ->set('contatos.0.endereco_email', 'nao-e-um-email')
        ->call('salvarAlteracoes')
        ->assertHasErrors(['contatos.0.endereco_email' => 'email']);

    expect($cliente->contatos()->count())->toBe(0);
});
