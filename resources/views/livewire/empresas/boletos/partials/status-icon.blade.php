@php
    $status = $cobranca?->status;
@endphp

@if (! $cobranca || $status === \App\Enums\CobrancaStatus::PENDENTE)
    <div class="ripple-grid" title="Enviando...">
        <span></span><span></span><span></span>
        <span></span><span></span><span></span>
        <span></span><span></span><span></span>
    </div>

@elseif ($status === \App\Enums\CobrancaStatus::ENVIANDO)
    <div class="ripple-grid" title="Enviando...">
        <span></span><span></span><span></span>
        <span></span><span></span><span></span>
        <span></span><span></span><span></span>
    </div>

@elseif ($status === \App\Enums\CobrancaStatus::ERRO)
    <span
        class="inline-flex h-3.5 w-3.5 items-center justify-center rounded-sm bg-red-500"
        title="{{ $cobranca->logs->first()?->mensagem ?? 'Erro no envio' }}"
    >
        <svg class="h-2 w-2 text-white" fill="none" viewBox="0 0 8 8" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" d="M1 1l6 6M7 1l-6 6" />
        </svg>
    </span>

@else
    <span
        class="inline-flex h-3.5 w-3.5 items-center justify-center rounded-sm bg-green-500"
        title="Enviado"
    >
        <svg class="h-2 w-2 text-white" fill="none" viewBox="0 0 8 8" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M1 4l2 2 4-4" />
        </svg>
    </span>
@endif