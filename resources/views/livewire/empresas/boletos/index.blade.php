<div{{ $this->temEnvioEmAndamento ? ' wire:poll.3s' : '' }}>
    <x-breadcrumb-header :trail="[
        ['label' => $empresa->nome, 'url' => route('empresas.show', $empresa)],
        ['label' => 'Boletos'],
    ]" />
    <main>
        <div class="py-6">
            <a class="rounded-md bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-700" href="{{ route('empresas.boletos.novo', $empresa) }}">Novo envio em massa</a>
        </div>
        @forelse ($this->grupos as $grupo)
            <div class="mb-8 rounded-lg border border-mist-800">
                {{-- Cabeçalho do grupo --}}
                <div class="flex items-center justify-between border-b border-mist-800 bg-mist-900 px-4 py-3">
                    <div class="flex flex-col sm:flex-col md:flex-col lg:flex-row space-x-3 items-center">
                        <h3 class="font-mono text-sm font-semibold">{{ $grupo['nome'] }}</h3>
                        <p class="text-xs text-mist-500">
                            {{ $grupo['data_inicio']?->format('d/m/Y H:i') ?? 'Ainda não iniciado' }}
                            @if ($grupo['data_fim'])
                                — {{ $grupo['data_fim']->format('H:i') }}
                            @endif
                        </p>
                    </div>
                    <span class="text-xs text-mist-500">{{ $grupo['quantidade'] }} boletos</span>
                </div>
                {{-- Tabela do grupo --}}
                <table class="w-full table-fixed text-sm">
                    <thead>
                        <tr class="text-left text-mist-500">
                            <th class="w-[40%] px-4 py-2">Cliente</th>
                            <th class="w-[15%] px-4 py-2">Status</th>
                            <th class="w-[15%] px-4 py-2">Pago</th>
                            <th class="w-[30%] px-4 py-2">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($grupo['boletos'] as $boleto)
                            <tr class="border-t border-mist-800">
                                <td class="truncate px-4 py-2">{{ $boleto->cliente->nome }}</td>
                                <td class="px-4 py-2">
                                    @include('livewire.empresas.boletos.partials.status-icon', ['cobranca' => $boleto->ultimaCobranca])
                                </td>
                                <td class="px-4 py-2">
                                    <x-toggle :checked="$boleto->pago" wire:click="togglePago({{ $boleto->id }})" />
                                </td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('empresas.boletos.show', [$empresa, $boleto]) }}" class="text-amber-600">Cobranças</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <p class="text-mist-500">Nenhum boleto enviado ainda.</p>
        @endforelse
    </main>
</div>