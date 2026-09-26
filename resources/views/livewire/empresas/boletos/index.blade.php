<div{{ $this->temEnvioEmAndamento ? ' wire:poll.3s' : '' }}>
    <x-breadcrumb-header :items="[$empresa->nome, 'Boletos']" />

    <main>
        @forelse ($this->grupos as $grupo)
            <div class="mb-8 rounded-lg border border-mist-800">
                {{-- Cabeçalho do grupo --}}
                <div class="flex items-center justify-between border-b border-mist-800 bg-mist-900 px-4 py-3">
                    <div>
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
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-mist-500">
                            <th class="px-4 py-2">Cliente</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Pago</th>
                            <th class="px-4 py-2">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($grupo['boletos'] as $boleto)
                            <tr class="border-t border-mist-800">
                                <td class="px-4 py-2">{{ $boleto->cliente->nome }}</td>
                                <td class="px-4 py-2">
                                    @include('livewire.empresas.boletos.partials.status-icon', ['cobranca' => $boleto->ultimaCobranca])
                                </td>
                                <td class="px-4 py-2">
                                    {{-- toggle de pago, igual já existe em algum lugar do app --}}
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