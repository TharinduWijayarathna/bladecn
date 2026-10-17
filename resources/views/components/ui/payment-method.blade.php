@props([
    'brand' => 'card',
    'last4' => '',
    'expires' => null,
    'holder' => null,
    'default' => false,
    'class' => '',
])

@php
    $brands = ['visa' => 'Visa', 'mastercard' => 'Mastercard', 'amex' => 'Amex', 'discover' => 'Discover', 'card' => 'Card'];
    $brandKey = strtolower((string) $brand);
    $brandLabel = $brands[$brandKey] ?? ucfirst($brandKey);
    $last4 = substr(preg_replace('/\D/', '', (string) $last4), -4);
    $default = filter_var($default, FILTER_VALIDATE_BOOLEAN);
@endphp

{{--
    A saved payment method row: brand tile, masked number, expiry and holder. Only the last four digits are
    ever rendered. Put Edit / Remove buttons in the `actions` slot.
--}}
<div data-slot="payment-method" {{ $attributes->merge(['class' => cn('flex items-center gap-4 rounded-lg border bg-card p-4 text-card-foreground', $class)]) }}>
    <div data-slot="payment-method-brand" class="{{ cn('flex h-10 w-14 shrink-0 items-center justify-center rounded-md border', $brandKey === 'visa' ? 'bg-white' : 'bg-muted text-muted-foreground') }}">
        @if ($brandKey === 'visa')
            <x-icons.visa-logo class="h-4 w-auto" aria-hidden="true" />
        @else
            <x-icons.credit-card class="size-5" aria-hidden="true" />
        @endif
        <span class="sr-only">{{ $brandLabel }}</span>
    </div>
    <div class="grid min-w-0 flex-1 gap-0.5">
        <div class="flex flex-wrap items-center gap-2 text-sm font-medium">
            <span>{{ $brandLabel }} <span aria-hidden="true">•••• </span><span class="sr-only">ending in </span>{{ $last4 }}</span>
            @if ($default)
                <x-ui.badge variant="outline">Default</x-ui.badge>
            @endif
        </div>
        @if (filled($expires) || filled($holder))
            <p class="truncate text-xs text-muted-foreground">
                @if (filled($expires))Expires {{ $expires }}@endif
                @if (filled($expires) && filled($holder)) · @endif
                @if (filled($holder)){{ $holder }}@endif
            </p>
        @endif
    </div>
    @isset($actions)
        <div class="flex shrink-0 items-center gap-2">{{ $actions }}</div>
    @endisset
</div>
