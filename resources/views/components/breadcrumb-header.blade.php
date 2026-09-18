@props(['trail' => []])

<header class="px-10 py-5 border-b border-mist-800">
    <nav class="flex items-center gap-2 text-sm text-mist-400 overflow-x-auto whitespace-nowrap">
        @foreach ($trail as $i => $item)
            @if ($i > 0)
                <span class="text-mist-600">→</span>
            @endif

            @if (! empty($item['url']) && $i < count($trail) - 1)
                <a href="{{ $item['url'] }}" class="hover:text-mist-200 transition-colors">{{ $item['label'] }}</a>
            @else
                <span class="text-mist-100">{{ $item['label'] }}</span>
            @endif
        @endforeach
    </nav>
</header>
