<div>
    <x-breadcrumb-header :trail="[
        ['label' => $empresa->nome, 'url' => route('empresas.show', $empresa)],
        ['label' => 'Configurar parser'],
    ]" />

    <main class="p-6 sm:p-10 lg:p-16">
        @if (session('sucesso'))
            <div class="mb-6 border border-emerald-800 bg-emerald-950/40 text-emerald-300 text-sm rounded-sm px-4 py-3">
                {{ session('sucesso') }}
            </div>
        @endif

        @if (session('erro'))
            <div class="mb-6 border border-red-800 bg-red-950/40 text-red-300 text-sm rounded-sm px-4 py-3">
                {{ session('erro') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            {{-- Coluna esquerda: formulário --}}
            <div class="flex flex-col gap-6">
                <div>
                    <label class="block text-sm font-medium text-mist-300 mb-2">Regex nome do cliente</label>
                    <textarea
                        wire:model.live="regexNomeCliente"
                        rows="3"
                        class="w-full bg-mist-900 border border-mist-800 rounded-sm p-3 font-mono text-sm text-mist-100 focus:outline-none focus:border-amber-700"
                    ></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-mist-300 mb-2">Regex código de barras</label>
                    <textarea
                        wire:model.live="regexCodigoBarras"
                        rows="3"
                        class="w-full bg-mist-900 border border-mist-800 rounded-sm p-3 font-mono text-sm text-mist-100 focus:outline-none focus:border-amber-700"
                    ></textarea>
                </div>

                <div class="flex items-center gap-3">
                    <button
                        wire:click="salvar"
                        class="bg-emerald-600 hover:bg-emerald-500 text-mist-950 font-sans font-semibold px-5 py-2 rounded-sm transition-colors"
                    >
                        Salvar
                    </button>
                    <button
                        wire:click="restaurarPadrao"
                        class="bg-mist-800 hover:bg-mist-700 text-mist-300 font-sans font-medium px-5 py-2 rounded-sm transition-colors"
                    >
                        Restaurar padrão
                    </button>
                </div>
            </div>

            {{-- Coluna direita: teste --}}
            <div class="flex flex-col gap-4">
                <button
                    wire:click="testar"
                    wire:loading.attr="disabled"
                    @disabled(trim($regexNomeCliente) === '' || trim($regexCodigoBarras) === '')
                    class="self-start bg-amber-600 hover:bg-amber-500 disabled:bg-mist-800 disabled:text-mist-600 disabled:cursor-not-allowed text-mist-950 font-sans font-semibold px-5 py-2 rounded-sm transition-colors"
                >
                    Testar padrões
                </button>

                @if ($caminhoBoletoTeste)
                <div x-data="{ aba: 'pdf' }">
                    <div class="flex gap-4 mb-3 border-b border-mist-800">
                        <button
                            type="button"
                            @click="aba = 'pdf'"
                            :class="aba === 'pdf' ? 'text-amber-500 border-amber-600' : 'text-mist-500 border-transparent hover:text-mist-300'"
                            class="text-xs font-medium border-b-2 pb-2 transition-colors"
                        >
                            Boleto gerado
                        </button>
                        <button
                            type="button"
                            @click="aba = 'texto'"
                            :class="aba === 'texto' ? 'text-amber-500 border-amber-600' : 'text-mist-500 border-transparent hover:text-mist-300'"
                            class="text-xs font-medium border-b-2 pb-2 transition-colors"
                        >
                            Mostrar como texto extraído
                        </button>
                    </div>

                    <div x-show="aba === 'pdf'" class="border border-mist-800 rounded-sm overflow-hidden h-64">
                        <embed src="{{ Storage::disk('public')->url($caminhoBoletoTeste) }}" type="application/pdf" class="w-full h-full">
                    </div>

                    <div x-show="aba === 'texto'" x-cloak class="border border-mist-800 rounded-sm h-64 overflow-auto bg-mist-950 p-3">
                        <pre class="text-xs text-mist-300 whitespace-pre-wrap font-mono">{{ $textoExtraidoDaPagina }}</pre>
                    </div>
                </div>


                    <div class="flex flex-col gap-3">
                        <div>
                            <span class="text-sm text-mist-400">Nome do cliente identificado</span>
                            <div class="mt-1 bg-mist-900 border border-mist-800 rounded-sm px-3 py-2 text-sm {{ $nomeClienteExtraido ? 'text-mist-100' : 'text-red-400' }}">
                                {{ $nomeClienteExtraido ?? 'Não identificado' }}
                            </div>
                        </div>

                        <div>
                            <span class="text-sm text-mist-400">Código de barras identificado</span>
                            <div class="mt-1 bg-mist-900 border border-mist-800 rounded-sm px-3 py-2 text-sm {{ $codigoBarrasExtraido ? 'text-mist-100' : 'text-red-400' }}">
                                {{ $codigoBarrasExtraido ?? 'Não identificado' }}
                            </div>
                        </div>

                        <div>
                            <span class="text-sm text-mist-400">Cliente encontrado no sistema</span>
                            <div class="mt-1 text-sm font-medium {{ $clienteEncontrado ? 'text-emerald-400' : 'text-red-400' }}">
                                {{ $clienteEncontrado ? 'Sim' : 'Não' }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="border border-dashed border-mist-800 rounded-sm h-64 flex flex-col items-center justify-center gap-2 text-mist-600">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-10">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        <span class="text-sm">Nenhum teste realizado ainda</span>
                    </div>
                @endif
            </div>
        </div>
    </main>
</div>