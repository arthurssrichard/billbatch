<div>
    <x-dashboard-header :title="$empresa->nome" :back="route('empresas.index')" />

    <main>
        <div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-3 gap-4">
            @php
                $cards = [
                    [
                        'label' => 'Envios',
                        'route' => 'empresas.boletos.index',
                        'icon' => 'M6 12 3.269 3.126A59.768 59.768 0 0 1 21.485 12 59.77 59.77 0 0 1 3.27 20.876L5.999 12Zm0 0h7.5',
                    ],
                    [
                        'label' => 'Clientes',
                        'route' => 'empresas.clientes.index',
                        'icon' => 'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z',
                    ],
                    [
                        'label' => 'Logs',
                        'route' => 'empresas.logs.index',
                        'icon' => 'M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z',
                    ],
                    [
                        'label' => 'Modelos de mensagem de email',
                        'route' => 'empresas.modelos-mensagem',
                        'icon' => 'M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75',
                    ],
                    [
                        'label' => 'Parser de boletos',
                        'route' => 'empresas.configuracao-parser',
                        'icon' => 'M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75',
                    ],
                ];
            @endphp

            @foreach ($cards as $card)
                <a href="{{ route($card['route'], $empresa) }}" class="group">
                    <div class="bg-mist-900 border rounded-sm border-mist-800 group-hover:border-amber-700 transition-colors p-5 h-52 flex flex-col space-y-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-8 text-mist-500 group-hover:text-amber-500 transition-colors">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}" />
                        </svg>

                        <h3 class="font-sans font-medium text-mist-50 text-lg">{{ $card['label'] }}</h3>
                    </div>
                </a>
            @endforeach
        </div>
    </main>
</div>
