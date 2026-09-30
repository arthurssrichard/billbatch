<div
    x-data="toastStack()"
    x-on:toast.window="add($event.detail)"
    class="pointer-events-none fixed right-4 top-4 z-50 flex w-80 flex-col gap-2"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-8"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-8"
            @mouseenter="pausar(toast.id)"
            @mouseleave="if (!toast.travado) retomar(toast.id)"
            @click="toast.travado = true; pausar(toast.id)"
            class="pointer-events-auto relative overflow-hidden rounded-md border px-4 py-3 text-sm shadow-lg"
            :class="{
                'border-green-800 bg-green-950 text-green-200': toast.tipo === 'sucesso',
                'border-red-800 bg-red-950 text-red-200': toast.tipo === 'erro',
            }"
        >
            <button
                type="button"
                @click.stop="remover(toast.id)"
                class="absolute right-2 top-2 text-xs opacity-60 hover:opacity-100"
            >
                ×
            </button>

            <p class="pr-4" x-text="toast.mensagem"></p>

            <div
                class="absolute bottom-0 left-0 h-1 bg-current opacity-40"
                :style="`width: ${(toast.restante / toast.duracao) * 100}%`"
            ></div>
        </div>
    </template>
</div>

@once
    <script>
        function toastStack() {
            return {
                toasts: [],

                add({ tipo = 'sucesso', mensagem, duracao = 5000 }) {
                    const id = crypto.randomUUID();

                    this.toasts.push({ id, tipo, mensagem, duracao, restante: duracao, travado: false, pausado: false });

                    this.iniciarTimer(id);
                },

                iniciarTimer(id) {
                    const intervalo = setInterval(() => {
                        const toast = this.toasts.find((t) => t.id === id);

                        if (!toast) {
                            clearInterval(intervalo);
                            return;
                        }

                        if (toast.pausado) {
                            return;
                        }

                        toast.restante -= 100;

                        if (toast.restante <= 0) {
                            clearInterval(intervalo);
                            this.remover(id);
                        }
                    }, 100);
                },

                pausar(id) {
                    const toast = this.toasts.find((t) => t.id === id);
                    if (toast) toast.pausado = true;
                },

                retomar(id) {
                    const toast = this.toasts.find((t) => t.id === id);
                    if (toast) toast.pausado = false;
                },

                remover(id) {
                    this.toasts = this.toasts.filter((t) => t.id !== id);
                },
            };
        }
    </script>
@endonce
