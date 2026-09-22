<div>
    <x-breadcrumb-header :trail="[
        ['label' => $empresa->nome, 'url' => route('empresas.show', $empresa)],
        ['label' => 'Envios', 'url' => route('empresas.envios.index', $empresa)],
        ['label' => 'Novo envio'],
    ]" />

    <main class="p-6 sm:p-10 lg:p-16">
        <div class="flex justify-center items-center gap-2 mb-8 text-sm">
            @foreach (['Upload', 'Revisão', 'Processado'] as $i => $label)
            <div class="flex items-center gap-2 {{ $fase === $i + 1 ? 'text-amber-500' : ($fase > $i + 1 ? 'text-mist-300' : 'text-mist-600') }}">
                <span class="size-5 rounded-full border {{ $fase > $i + 1 ? 'bg-mist-700 border-mist-600' : ($fase === $i + 1 ? 'border-amber-600' : 'border-mist-700') }} flex items-center justify-center text-xs">
                    {{ $i + 1 }}
                </span>
                {{ $label }}
            </div>
                @if ($i < 2)
                    <span class="text-mist-700">—</span>
                @endif
            @endforeach
        </div>
        {{-- Fase 1 --}}
        @if ($fase === 1)
            <div class="flex flex-col items-center justify-center border border-dashed border-mist-800 rounded-sm py-20 gap-3 max-w-2xl mx-auto">
                <button
                    wire:click="simularEnvio"
                    class="bg-amber-600 hover:bg-amber-500 text-mist-950 font-sans font-semibold px-6 py-3 rounded-sm transition-colors"
                >
                    Simular envio de boletos
                </button>
                <p class="text-sm text-mist-500 text-center max-w-sm">
                    Boletos fictícios serão gerados automaticamente para este teste, em vez de exigir o envio de um arquivo real.
                </p>
            </div>
        @endif

        {{-- Fase 2 --}}
        @if ($fase === 2)
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-sans font-semibold text-xl text-mist-50">Arquivos recebidos</h2>
                <button wire:click="processar" class="bg-amber-600 hover:bg-amber-500 text-mist-950 font-sans font-medium px-5 py-2 rounded-sm transition-colors">
                    Processar
                </button>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($cardsGrupos as $card)
                <div
                    x-data="{ open: false }"
                    @keydown.escape.window="open = false"
                    class="bg-mist-900 border border-mist-800 rounded-sm p-5 flex items-start justify-between gap-3 cursor-pointer hover:border-amber-700 transition-colors"
                    @click="open = true"
                >
                    <div class="flex flex-col gap-2">
                        <span class="font-sans font-medium text-mist-50">{{ $card['grupo'] }}</span>
                        <span class="text-sm text-mist-400">{{ $card['paginas'] }} páginas</span>
                    </div>

                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-8 text-mist-600 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>

                    <div x-cloak x-show="open" x-transition class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-6">
                        <div @click.away="open = false" class="bg-mist-900 border border-mist-800 rounded-sm w-full max-w-3xl h-[80vh]">
                            <embed src="{{ Storage::disk('public')->url($card['caminho']) }}" type="application/pdf" class="w-full h-full rounded-sm">
                        </div>
                    </div>
                </div>
            @endforeach
            </div>

            <button wire:click="voltar" class="mt-6 text-sm text-mist-400 hover:text-mist-200">← Voltar</button>
        @endif

        {{-- Fase 3 --}}
        @if ($fase === 3)
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-sans font-semibold text-xl text-mist-50 mb-6">Boletos processados</h2>
                <button
                    wire:click="confirmarEnvio"
                    wire:confirm="Confirmar o envio destes boletos?"
                    class="bg-amber-600 hover:bg-amber-500 text-mist-950 font-sans font-medium px-5 py-2 rounded-sm transition-colors"
                >
                    Confirmar envio
                </button>
            </div>
            

            @foreach ($this->resultadosAgrupados as $grupo => $resultados)
                <div class="mb-8">
                    <h3 class="font-sans font-medium text-mist-300 mb-2">{{ $grupo }}</h3>

                    <div class="overflow-x-auto border border-mist-800 rounded-sm">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-mist-800 text-mist-400">
                                    <th class="px-4 py-3 font-medium">Cliente identificado</th>
                                    <th class="px-4 py-3 font-medium">E-mails</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($resultados as $resultado)
                                    <tr class="border-b border-mist-800/50">
                                        <td class="px-4 py-3">
                                            @if ($resultado['cliente'])
                                                <span class="text-mist-100">{{ $resultado['cliente']['nome'] }}</span>
                                            @else
                                                <span class="text-red-400">Não identificado</span>
                                                @if ($resultado['nome_cliente_extraido'])
                                                    <span class="text-mist-500 text-xs block">({{ $resultado['nome_cliente_extraido'] }})</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex flex-wrap gap-1">
                                                @forelse ($resultado['cliente']['contatos'] ?? [] as $contato)
                                                    <span class="bg-mist-800 text-mist-300 text-xs px-2 py-1 rounded-full">
                                                        {{ $contato['endereco_email'] }}
                                                    </span>
                                                @empty
                                                    <span class="text-mist-600 text-xs">—</span>
                                                @endforelse
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach

            <button wire:click="voltar" class="text-sm text-mist-400 hover:text-mist-200">← Voltar</button>
        @endif

    </main>
</div>