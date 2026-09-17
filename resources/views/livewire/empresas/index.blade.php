<div>
    <x-dashboard-header title="Empresas" />

    <main>
        <div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-3 gap-4">
            @foreach ($empresas as $empresa)
                <a href="{{ route('empresas.show', $empresa) }}" class="group">
                    <div class="bg-mist-900 border border-mist-800 rounded-sm group-hover:border-amber-700 transition-colors p-5 flex flex-col gap-y-2 h-52">
                        <h3 class="font-sans font-semibold text-mist-50 text-xl">{{ $empresa->nome }}</h3>
                        <span class="text-mist-400 text-sm">{{ $empresa->clientes_count }} Clientes</span>
                    </div>
                </a>
            @endforeach
        </div>
    </main>
</div>