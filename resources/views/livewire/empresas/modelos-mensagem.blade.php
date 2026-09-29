<div x-data="{ abaAtiva: '{{ \App\Enums\TipoCobranca::PRIMEIRO_ENVIO->value }}' }">
    <x-breadcrumb-header :trail="[
        ['label' => $empresa->nome, 'url' => route('empresas.show', $empresa)],
        ['label' => 'Modelos de mensagem'],
    ]" />

    <main>
        <div class="mb-4 flex gap-2 border-b border-mist-800">
            @foreach (\App\Enums\TipoCobranca::cases() as $tipo)
                <button
                    type="button"
                    @click="abaAtiva = '{{ $tipo->value }}'"
                    :class="abaAtiva === '{{ $tipo->value }}' ? 'text-amber-500 border-amber-600' : 'text-mist-500 border-transparent hover:text-mist-300'"
                    class="border-b-2 px-1 pb-2 text-sm font-medium transition-colors"
                >
                    {{ ucfirst(str_replace('_', ' ', $tipo->value)) }}
                </button>
            @endforeach
        </div>

        @foreach (\App\Enums\TipoCobranca::cases() as $tipo)
            <div x-show="abaAtiva === '{{ $tipo->value }}'" x-cloak class="grid gap-6 md:grid-cols-2">
                <div class="rounded-lg border border-mist-800 p-4">
                    <label class="mb-1 block text-xs text-mist-500">Assunto</label>
                    <input
                        type="text"
                        wire:model.blur="modelos.{{ $tipo->value }}.assunto"
                        class="mb-4 w-full rounded-md border border-mist-800 bg-mist-900 px-3 py-2 text-sm"
                    >

                    <label class="mb-1 block text-xs text-mist-500">Corpo</label>
                    <textarea
                        wire:model.blur="modelos.{{ $tipo->value }}.corpo"
                        rows="10"
                        class="mb-4 w-full rounded-md border border-mist-800 bg-mist-900 px-3 py-2 text-sm"
                    ></textarea>

                    <p class="mb-4 text-xs text-mist-500">
                        Placeholders disponíveis: <code class="text-amber-500">{NOME_CLIENTE}</code>, <code class="text-amber-500">{SAUDACAO}</code>
                    </p>

                    <button
                        type="button"
                        wire:click="salvar('{{ $tipo->value }}')"
                        wire:loading.attr="disabled"
                        class="rounded-md bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-700 disabled:opacity-50"
                    >
                        Salvar
                    </button>
                </div>

                <div class="rounded-lg border border-mist-800 p-4">
                    <p class="mb-1 text-xs text-mist-500">Prévia (com dados de exemplo)</p>
                    <p class="mb-4 font-semibold">{{ $this->preview[$tipo->value]['assunto'] }}</p>
                    <p class="whitespace-pre-wrap text-sm text-mist-300">{{ $this->preview[$tipo->value]['corpo'] }}</p>
                </div>
            </div>
        @endforeach
    </main>
</div>
