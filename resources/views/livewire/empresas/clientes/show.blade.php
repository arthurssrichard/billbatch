<div>
    <x-breadcrumb-header :trail="[
        ['label' => $empresa->nome, 'url' => route('empresas.show', $empresa)],
        ['label' => 'Clientes', 'url' => route('empresas.clientes.index', $empresa)],
        ['label' => $cliente->nome],
    ]" />

    <main>
        <div class="mb-8 grid gap-6 md:grid-cols-2">
            <div class="rounded-lg border border-mist-800 p-4">
                <label class="mb-1 block text-xs text-mist-500">Nome</label>
                <input type="text" wire:model="nome" class="mb-4 w-full rounded-md border border-mist-800 bg-mist-900 px-3 py-2 text-sm">

                <label class="mb-1 block text-xs text-mist-500">Identificador externo</label>
                <input type="text" wire:model="identificadorExterno" class="mb-4 w-full rounded-md border border-mist-800 bg-mist-900 px-3 py-2 text-sm">

                <label class="mb-1 block text-xs text-mist-500">Grupo</label>
                <input type="text" wire:model="grupo" class="mb-4 w-full rounded-md border border-mist-800 bg-mist-900 px-3 py-2 text-sm">

                <label class="mb-1 block text-xs text-mist-500">CNPJ</label>
                <input type="text" wire:model="cnpj" class="mb-1 w-full rounded-md border border-mist-800 bg-mist-900 px-3 py-2 text-sm">

                <p class="mt-3 text-xs text-mist-500">
                    Criado em: {{ $cliente->created_at->format('d/m/Y H:i') }} ·
                    Atualizado em: {{ $cliente->updated_at->format('d/m/Y H:i') }}
                </p>
            </div>

            <div class="rounded-lg border border-mist-800 p-4">
                <h3 class="mb-3 text-sm font-semibold">Preferências de envio</h3>
                @foreach (\App\Enums\CanalCobranca::cases() as $canal)
                    <label class="mb-3 flex items-center gap-2 text-sm">
                        <x-toggle :checked="$canaisEnvio[$canal->value]" wire:click="$toggle('canaisEnvio.{{ $canal->value }}')" />
                        Envio via {{ ucfirst($canal->value) }}
                    </label>
                @endforeach

                <h3 class="mb-3 mt-6 text-sm font-semibold">Contatos</h3>
                @foreach ($contatos as $index => $contato)
                    <div class="mb-2 flex items-center gap-2">
                        <input
                            type="email"
                            wire:model="contatos.{{ $index }}.endereco_email"
                            placeholder="email@exemplo.com"
                            class="flex-1 rounded-md border border-mist-800 bg-mist-900 px-3 py-2 text-sm"
                        >
                        <button type="button" wire:click="removerContato({{ $index }})" class="text-mist-500 hover:text-red-500">
                            ×
                        </button>
                    </div>
                @endforeach

                <button type="button" wire:click="adicionarContato" class="mb-4 text-sm text-amber-500 hover:text-amber-400">
                    + Adicionar e-mail
                </button>
            </div>
            <button
                type="button"
                wire:click="salvarAlteracoes"
                wire:loading.attr="disabled"
                class="w-fit rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-500 disabled:opacity-50"
            >
                Salvar alterações
            </button>
        </div>
        <hr class="border-mist-800 py-4">
        <div class="rounded-lg border border-mist-800">
            <div class="border-b border-mist-800 bg-mist-900 px-4 py-3">
                <h3 class="text-sm font-semibold">Boletos</h3>
            </div>

            <table class="w-full table-fixed text-sm">
                <thead>
                    <tr class="text-left text-mist-500">
                        <th class="w-[8%] px-4 py-2">ID</th>
                        <th class="w-[10%] px-4 py-2">Mês</th>
                        <th class="w-[18%] px-4 py-2">Ação</th>
                        <th class="w-[22%] px-4 py-2">Último envio</th>
                        <th class="w-[12%] px-4 py-2">Pago</th>
                        <th class="w-[18%] px-4 py-2">Qtd. cobranças</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->boletos as $boleto)
                        <tr class="border-t border-mist-800">
                            <td class="px-4 py-2 text-mist-400">{{ $boleto->id }}</td>
                            <td class="px-4 py-2 uppercase text-mist-400">{{ $boleto->created_at->translatedFormat('M') }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ route('empresas.boletos.show', [$empresa, $boleto]) }}" class="text-amber-600">Acessar envios</a>
                            </td>
                            <td class="px-4 py-2">
                                {{ $boleto->ultimaCobranca?->data_envio?->format('d/m/Y H:i') ?? '—' }}
                            </td>
                            <td class="px-4 py-2">
                                @if ($boleto->pago)
                                    @include('livewire.empresas.boletos.partials.status-icon', ['cobranca' => $boleto->ultimaCobranca])
                                @else
                                    <span class="text-mist-700">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $boleto->cobrancas_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-mist-500">Nenhum boleto para esse cliente ainda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</div>