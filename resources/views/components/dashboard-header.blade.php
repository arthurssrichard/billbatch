@props(['title', 'back' => null])

<header class="px-16 pt-12 pb-6 border-b border-mist-800">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            @if ($back)
                <a href="{{ $back }}" class="size-9 flex items-center justify-center rounded-full bg-mist-900 border border-mist-800 hover:border-amber-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-5 text-mist-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </a>
            @endif
            <h1 class="font-sans font-semibold text-2xl sm:text-3xl text-mist-50">{{ $title }}</h1>
        </div>

        @isset($actions)
            <div>{{ $actions }}</div>
        @endisset
    </div>
</header>