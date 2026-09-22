<div>
    <x-breadcrumb-header :trail="[
        ['label' => $empresa->nome, 'url' => route('empresas.show', $empresa)],
        ['label' => 'Envios', 'url' => route('empresas.envios.index', $empresa)],
        ['label' => 'Novo envio'],
    ]" />

    <main class="p-6 sm:p-10 lg:p-16">

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
                    <div class="bg-mist-900 border border-mist-800 rounded-sm p-5 flex flex-col gap-2">
                        <span class="font-sans font-medium text-mist-50">{{ $card['grupo'] }}</span>
                        <span class="text-sm text-mist-400">{{ $card['paginas'] }} páginas</span>
                    </div>
                @endforeach
            </div>

            <button wire:click="voltar" class="mt-6 text-sm text-mist-400 hover:text-mist-200">← Voltar</button>
        @endif

        {{-- Fase 3 --}}
        @if ($fase === 3)
            <h2 class="font-sans font-semibold text-xl text-mist-50 mb-6">Boletos processados</h2>

            <p class="text-mist-500">Em construção.</p>

            <button wire:click="voltar" class="mt-6 text-sm text-mist-400 hover:text-mist-200">← Voltar</button>
        @endif

    </main>
</div>