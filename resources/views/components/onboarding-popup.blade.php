@props(['recemCriada' => false])

<div
    x-data="onboardingPopup({{ $recemCriada ? 'true' : 'false' }})"
    x-cloak
>
    {{-- Ícone fixo --}}
    <button
        @click="abrir()"
        type="button"
        class="fixed bottom-6 right-6 z-40 size-12 rounded-full bg-amber-600 hover:bg-amber-500 text-mist-950 shadow-lg shadow-black/40 flex items-center justify-center transition-colors"
        aria-label="Abrir guia de boas-vindas"
    >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.86.414-1.56 1.213-1.56 2.185v.75M12 17.25h.007v.008H12v-.008Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" />
        </svg>
    </button>

    {{-- Popup --}}
    <div
        x-show="aberto"
        x-transition.opacity
        class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4"
        @keydown.escape.window="fechar()"
    >
        <div
            @click.away="fechar()"
            class="bg-mist-900 border border-mist-800 rounded-sm w-full max-w-lg"
        >
            {{-- Abas --}}
            <div class="flex border-b border-mist-800">
                <button
                    type="button"
                    @click="abaAtiva = 'carregamento'"
                    class="flex-1 px-4 py-3 text-sm font-medium transition-colors"
                    :class="abaAtiva === 'carregamento' ? 'text-amber-500 border-b-2 border-amber-600' : 'text-mist-500 hover:text-mist-300'"
                >
                    Carregamento
                </button>
                <button
                    type="button"
                    @click="irParaGuia()"
                    :disabled="!carregamentoConcluido"
                    class="flex-1 px-4 py-3 text-sm font-medium transition-colors"
                    :class="abaAtiva === 'guia' ? 'text-amber-500 border-b-2 border-amber-600' : (carregamentoConcluido ? 'text-mist-500 hover:text-mist-300' : 'text-mist-700 cursor-not-allowed')"
                >
                    Guia
                </button>

                <button
                    type="button"
                    @click="fechar()"
                    class="px-4 text-mist-500 hover:text-mist-200 transition-colors"
                    aria-label="Fechar"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Aba: Carregamento --}}
            <div x-show="abaAtiva === 'carregamento'" class="p-6">
                <h2 class="font-sans font-semibold text-mist-50 mb-4">Preparando seu ambiente</h2>

                <div class="font-mono text-sm space-y-1.5">
                    <template x-for="(passo, i) in passos" :key="i">
                        <div
                            x-show="passoAtual > i"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 -translate-x-1"
                            x-transition:enter-end="opacity-100 translate-x-0"
                            class="flex items-center gap-2 text-mist-300"
                            :style="{ paddingLeft: (passo.nivel * 1.25) + 'rem' }"
                        >
                            <span class="text-mist-600" x-text="passo.nivel === 0 ? '' : '└─'"></span>
                            <span x-text="passo.label"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="size-3.5 text-green-500 shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        </div>
                    </template>
                </div>

                <div x-show="!carregamentoConcluido" class="mt-4 text-xs text-mist-500">
                    Configurando estrutura inicial...
                </div>

                <div x-show="carregamentoConcluido" x-transition class="mt-6 flex justify-end">
                    <button
                        type="button"
                        @click="irParaGuia()"
                        class="bg-amber-600 hover:bg-amber-500 text-mist-950 font-sans font-medium text-md px-4 py-2 rounded-sm transition-colors"
                    >
                        Prosseguir para o guia →
                    </button>
                </div>
            </div>

            {{-- Aba: Guia --}}
            <div x-show="abaAtiva === 'guia'" class="p-6">
                <template x-for="(slide, i) in slides" :key="i">
                    <div x-show="slideAtual === i">
                        <h2 class="font-sans font-semibold text-mist-50 mb-2" x-text="slide.titulo"></h2>
                        <p class="text-md font-sans text-mist-400 leading-relaxed min-h-20" x-text="slide.texto"></p>
                    </div>
                </template>

                <div class="flex items-center justify-between mt-6">
                    <button
                        type="button"
                        @click="slideAnterior()"
                        :disabled="slideAtual === 0"
                        class="size-8 rounded-full border border-mist-800 flex items-center justify-center text-mist-400 hover:text-mist-100 hover:border-mist-700 disabled:opacity-30 disabled:hover:text-mist-400 disabled:hover:border-mist-800 transition-colors"
                        aria-label="Slide anterior"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                    </button>

                    <div class="flex gap-2">
                        <template x-for="(slide, i) in slides" :key="i">
                            <button
                                type="button"
                                @click="irParaSlide(i)"
                                class="size-2 rounded-full transition-colors"
                                :class="slideAtual === i ? 'bg-amber-500' : 'bg-mist-700 hover:bg-mist-600'"
                                :aria-label="'Ir para slide ' + (i + 1)"
                            ></button>
                        </template>
                    </div>

                    <button
                        type="button"
                        @click="proximoSlide()"
                        :disabled="slideAtual === slides.length - 1"
                        class="size-8 rounded-full border border-mist-800 flex items-center justify-center text-mist-400 hover:text-mist-100 hover:border-mist-700 disabled:opacity-30 disabled:hover:text-mist-400 disabled:hover:border-mist-800 transition-colors"
                        aria-label="Próximo slide"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
