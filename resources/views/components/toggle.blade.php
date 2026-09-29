@props(['checked' => false])

<button
    type="button"
    role="switch"
    aria-checked="{{ $checked ? 'true' : 'false' }}"
    wire:loading.attr="disabled"
    {{ $attributes->class([
        'relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 focus:ring-offset-mist-950 disabled:opacity-50',
        'bg-green-600' => $checked,
        'bg-mist-700' => ! $checked,
    ]) }}
>
    <span
        class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-150 {{ $checked ? 'translate-x-6' : 'translate-x-1' }}"
    ></span>
</button>
