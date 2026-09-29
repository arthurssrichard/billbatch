<div>
    <x-breadcrumb-header :trail="[
        ['label' => $empresa->nome, 'url' => route('empresas.show', $empresa)],
        ['label' => 'Boletos', 'url' => route('empresas.boletos.index', $empresa)],
        ['label' => $boleto->cliente->nome],
    ]" />

    <main>
        <div class="mb-8 rounded-lg border border-mist-800">
            {{-- Cabeçalho do boleto --}}
            <div class="sticky top-0 z-10 flex flex-col gap-3 border-b border-mist-800 bg-mist-900 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="font-mono text-sm font-semibold">{{ $boleto->cliente->nome }}</h3>
                    <p class="text-xs text-mist-500">Código de barras: {{ $boleto->codigo_barras }}</p>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-xs text-mist-400">
                        {{ $boleto->pago ? 'Boleto pago' : 'Boleto ainda não pago' }}
                    </span>
                    <x-toggle :checked="$boleto->pago" wire:click="togglePago" />
                </div>
            </div>

            {{-- Tabela de cobranças --}}
            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px] table-fixed text-sm">
                    <thead>
                        <tr class="text-left text-mist-500">
                            <th class="w-[10%] px-4 py-2">ID</th>
                            <th class="w-[25%] px-4 py-2">Data de envio</th>
                            <th class="w-[20%] px-4 py-2">Tipo</th>
                            <th class="w-[15%] px-4 py-2">Canal</th>
                            <th class="w-[30%] px-4 py-2">Contatos enviados</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->cobrancas as $cobranca)
                            <tr wire:key="cobranca-{{ $cobranca->id }}" class="border-t border-mist-800">
                                <td class="px-4 py-2">{{ $cobranca->id }}</td>
                                <td class="px-4 py-2">{{ $cobranca->created_at->format('d/m/Y H:i:s') }}</td>
                                <td class="px-4 py-2">{{ ucfirst(str_replace('_', ' ', $cobranca->tipo)) }}</td>
                                <td class="px-4 py-2">{{ ucfirst($cobranca->canal_envio->value) }}</td>
                                <td title="{{ $cobranca->contatos_enviados }}" class="truncate px-4 py-2">{{ $cobranca->contatos_enviados }}</td>
                            </tr>
                        @empty
                            <tr class="border-t border-mist-800">
                                <td colspan="5" class="px-4 py-6 text-center text-mist-500">Nenhuma cobrança realizada ainda.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Nova cobrança --}}
        <div class="rounded-lg border border-mist-800 p-4">
            <h3 class="mb-3 text-sm font-semibold">Nova cobrança</h3>

            <div class="flex flex-col gap-4 sm:flex-row sm:items-end">
                <div class="flex-1">
                    <label class="mb-1 block text-xs text-mist-500">Tipo</label>
                    <select wire:model="tipoSelecionado" class="w-full rounded-md border border-mist-800 bg-mist-900 px-3 py-2 text-sm">
                        @foreach (\App\Enums\TipoCobranca::cases() as $tipo)
                            <option value="{{ $tipo->value }}">{{ ucfirst(str_replace('_', ' ', $tipo->value)) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1">
                    <label class="mb-1 block text-xs text-mist-500">Canal</label>
                    <select wire:model="canalSelecionado" class="w-full rounded-md border border-mist-800 bg-mist-900 px-3 py-2 text-sm">
                        @foreach (\App\Enums\CanalCobranca::cases() as $canal)
                            <option value="{{ $canal->value }}">{{ ucfirst($canal->value) }}</option>
                        @endforeach
                    </select>
                </div>

                <button
                    type="button"
                    wire:click="realizarNovaCobranca"
                    wire:loading.attr="disabled"
                    class="rounded-md bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-700 disabled:opacity-50"
                >
                    Realizar nova cobrança
                </button>
            </div>
        </div>
    </main>
</div>
