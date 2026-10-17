@props([
    'name' => '',
    'price' => null,
    'interval' => null,
    'badge' => null,
    'description' => null,
    'renews' => null,
    'usage' => null,
    'usageLabel' => 'Usage',
    'class' => '',
])

@php
    $renewsText = null;
    if (filled($renews)) {
        try {
            $renewsText = \Illuminate\Support\Carbon::parse($renews)->format('F j, Y');
        } catch (\Throwable) {
            $renewsText = $renews;
        }
    }
    $usage = is_numeric($usage) ? (int) min(100, max(0, $usage)) : null;
@endphp

{{--
    A subscription plan summary: name, optional badge, price per interval, renewal date and usage.
    Put buttons or links in the `footer` slot.
--}}
<x-ui.card data-slot="plan-card" {{ $attributes->merge(['class' => cn('gap-4', $class)]) }}>
    <x-ui.card-header>
        <x-ui.card-title class="flex items-center gap-2">
            {{ $name }}
            @if (filled($badge))
                <x-ui.badge variant="secondary">{{ $badge }}</x-ui.badge>
            @endif
        </x-ui.card-title>
        @if (filled($description))
            <x-ui.card-description>{{ $description }}</x-ui.card-description>
        @endif
        @if (filled($price))
            <x-ui.card-action class="text-right">
                <span data-slot="plan-card-price" class="text-2xl font-semibold tracking-tight tabular-nums">{{ $price }}</span>
                @if (filled($interval))
                    <span class="text-sm text-muted-foreground">/ {{ $interval }}</span>
                @endif
            </x-ui.card-action>
        @endif
    </x-ui.card-header>
    @if ($renewsText || $usage !== null || trim($slot) !== '')
        <x-ui.card-content class="grid gap-3">
            @if ($renewsText)
                <p class="text-sm text-muted-foreground">Renews on {{ $renewsText }}</p>
            @endif
            @if ($usage !== null)
                <div class="grid gap-1.5">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-muted-foreground">{{ $usageLabel }}</span>
                        <span class="font-medium tabular-nums">{{ $usage }}%</span>
                    </div>
                    <x-ui.progress :value="$usage" class="h-2" role="progressbar" aria-label="{{ $usageLabel }}" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $usage }}" />
                </div>
            @endif
            {{ $slot }}
        </x-ui.card-content>
    @endif
    @isset($footer)
        <x-ui.card-footer class="justify-end gap-2 border-t pt-4">{{ $footer }}</x-ui.card-footer>
    @endisset
</x-ui.card>
