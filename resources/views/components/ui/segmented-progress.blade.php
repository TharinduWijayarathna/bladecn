@props([
    'value' => 0,
    'max' => 100,
    'segments' => 20,
    'showValue' => false,
    'label' => 'Progress',
    'class' => '',
])

@php
    $max = max(1, (float) $max);
    $value = min($max, max(0, (float) $value));
    $segments = max(2, min(100, (int) $segments));
    $percent = (int) round($value / $max * 100);
    $filled = (int) round($value / $max * $segments);
    $showValue = filter_var($showValue, FILTER_VALIDATE_BOOLEAN);
@endphp

{{--
    A progress bar split into equal segments, filled with the primary colour. Server-rendered and static:
    change `value` to move it.
--}}
<div data-slot="segmented-progress" {{ $attributes->merge(['class' => cn('flex w-full items-center gap-3', $class)]) }}>
    <div role="progressbar" aria-label="{{ $label }}" aria-valuemin="0" aria-valuemax="{{ $max + 0 }}" aria-valuenow="{{ $value + 0 }}"
        class="flex h-2 flex-1 gap-0.5">
        @for ($i = 0; $i < $segments; $i++)
            <span data-slot="segmented-progress-segment" data-filled="{{ $i < $filled ? 'true' : 'false' }}"
                class="h-full flex-1 rounded-[2px] bg-muted transition-colors data-[filled=true]:bg-primary"></span>
        @endfor
    </div>
    @if ($showValue)
        <span data-slot="segmented-progress-value" class="w-10 shrink-0 text-right text-sm font-medium tabular-nums">{{ $percent }}%</span>
    @endif
</div>
