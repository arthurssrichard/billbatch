<div>
    <x-dashboard-header :title="$empresa->nome" :back="route('empresas.index')" />

    <main>
        <div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-3 gap-4">
            @php
                $cards = [
                    ['label' => 'Envios', 'route' => 'empresas.envios.index'],
                    ['label' => 'Fontes de dados', 'route' => 'empresas.fontes-dados.index'],
                    ['label' => 'Clientes', 'route' => 'empresas.clientes.index'],
                    ['label' => 'Logs', 'route' => 'empresas.logs.index'],
                ];
            @endphp

            @foreach ($cards as $card)
                <a href="{{ route($card['route'], $empresa) }}" class="group">
                    <div class="bg-mist-900 border rounded-sm border-mist-800 group-hover:border-amber-700 transition-colors p-5 h-52 flex items-start">
                        <h3 class="font-sans font-medium text-mist-50 text-lg">{{ $card['label'] }}</h3>
                    </div>
                </a>
            @endforeach
        </div>
    </main>
</div>
