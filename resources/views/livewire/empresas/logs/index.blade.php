<div>
    <x-breadcrumb-header :trail="[
        ['label' => $empresa->nome, 'url' => route('empresas.show', $empresa)],
        ['label' => 'Logs'],
    ]" />

    <main class="p-6 sm:p-10 lg:p-16">
        <div class="flex flex-col sm:flex-row gap-4 mb-4">
            <select
                wire:model.live="filtroStatus"
                class="bg-mist-900 border border-mist-800 rounded-sm px-4 py-2 text-mist-100 focus:outline-none focus:border-amber-700 sm:w-56"
            >
                <option value="">Todos os status</option>
                @foreach ($status as $status)
                    <option value="{{ $status->value }}">{{ ucfirst($status->value) }}</option>
                @endforeach
            </select>
        </div>

        <p class="text-sm text-mist-500 mb-3">
            Exibindo {{ $logs->firstItem() ?? 0 }}–{{ $logs->lastItem() ?? 0 }} de {{ $logs->total() }} logs
        </p>

        <div class="overflow-x-auto border border-mist-800 rounded-sm">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-mist-800 text-mist-400">
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Nome</th>
                        <th class="px-4 py-3 font-medium">Mensagem</th>
                        <th class="px-4 py-3 font-medium">Data</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr class="border-b border-mist-800/50">
                            <td class="px-4 py-3">
                                @php
                                    $cores = [
                                        'success' => 'text-emerald-400',
                                        'error' => 'text-red-400',
                                        'warning' => 'text-amber-400',
                                        'info' => 'text-mist-400',
                                    ];
                                @endphp
                                <span class="{{ $cores[$log->status->value] }} font-medium">
                                    {{ ucfirst($log->status->value) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-mist-100">{{ $log->nome }}</td>
                            <td class="px-4 py-3 text-mist-400">{{ $log->mensagem }}</td>
                            <td class="px-4 py-3 text-mist-500">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-mist-500">
                                Nenhum log registrado ainda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </main>
</div>