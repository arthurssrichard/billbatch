<div>
    <x-breadcrumb-header :trail="[
        ['label' => $empresa->nome, 'url' => route('empresas.show', $empresa)],
        ['label' => 'Clientes'],
    ]" />

    <main>
        <div class="flex flex-col sm:flex-row gap-4 mb-4">
            <input
                type="text"
                wire:model.live.debounce.400ms="busca"
                placeholder="Buscar por nome..."
                class="bg-mist-900 border border-mist-800 rounded-sm px-4 py-2 text-mist-100 placeholder:text-mist-500 focus:outline-none focus:border-amber-700 flex-1"
            >

            <div x-data="{ open: false }" class="relative">
                <button
                    @click="open = !open"
                    type="button"
                    class="w-full sm:w-56 flex items-center justify-between gap-2 bg-mist-900 border border-mist-800 rounded-sm px-4 py-2 text-mist-100 hover:border-mist-700 transition-colors"
                >
                    <span>
                        Grupo
                        @if (count($gruposSelecionados) > 0)
                            <span class="text-amber-500">({{ count($gruposSelecionados) }})</span>
                        @endif
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-4 text-mist-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                <div
                    x-cloak
                    x-show="open"
                    @click.away="open = false"
                    x-transition
                    class="absolute right-0 mt-1 w-56 max-h-64 overflow-y-auto bg-mist-900 border border-mist-800 rounded-sm shadow-lg z-20"
                >
                    @forelse ($grupos as $grupo)
                        <button
                            type="button"
                            wire:click="toggleGrupo('{{ $grupo }}')"
                            class="w-full flex items-center gap-2 px-4 py-2 text-left text-sm hover:bg-mist-800 transition-colors {{ in_array($grupo, $gruposSelecionados) ? 'text-amber-500' : 'text-mist-300' }}"
                        >
                            <span class="size-3.5 border border-mist-700 rounded-sm flex items-center justify-center {{ in_array($grupo, $gruposSelecionados) ? 'bg-amber-600 border-amber-600' : '' }}">
                                @if (in_array($grupo, $gruposSelecionados))
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" class="size-2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                @endif
                            </span>
                            {{ $grupo }}
                        </button>
                    @empty
                        <p class="px-4 py-3 text-sm text-mist-500">Nenhum grupo cadastrado.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <p class="text-sm text-mist-500 mb-3">
            Exibindo {{ $clientes->firstItem() ?? 0 }}–{{ $clientes->lastItem() ?? 0 }} de {{ $clientes->total() }} clientes
        </p>

        <div class="overflow-x-auto border border-mist-800 rounded-sm">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-mist-800 text-mist-400">
                        <th class="px-4 py-3 font-medium">Nome</th>
                        <th class="px-4 py-3 font-medium">Identificador</th>
                        <th class="px-4 py-3 font-medium">Grupo</th>
                        <th class="px-4 py-3 font-medium">Envio</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clientes as $cliente)
                        <tr class="border-b border-mist-800/50">
                            <td class="px-4 py-3 text-mist-100">{{ $cliente->nome }}</td>
                            <td class="px-4 py-3 text-mist-400">{{ $cliente->identificador_externo }}</td>
                            <td class="px-4 py-3 text-mist-400">{{ $cliente->grupo ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-1">
                                    @foreach (\App\Enums\CanalCobranca::cases() as $canal)
                                        <span class="{{ in_array($canal->value, $cliente->canais_envio ?? []) ? 'text-amber-500' : 'text-mist-700' }}">
                                            @if ($canal->value === 'email')
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                                </svg>
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-mist-500">
                                Nenhum cliente cadastrado ainda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $clientes->links() }}
        </div>
    </main>
</div>