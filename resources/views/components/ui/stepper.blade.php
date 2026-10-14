@props([
    'steps' => [],
    'current' => 0,
    'orientation' => 'horizontal',
    'clickable' => false,
    'label' => 'Progress',
    'class' => '',
])

@php
    $vertical = $orientation === 'vertical';
    $current = (int) $current;
    // `clickable`: false (static), true (any step) or 'completed' (only steps already done).
    $clickMode = $clickable === 'completed' ? 'completed' : (filter_var($clickable, FILTER_VALIDATE_BOOLEAN) ? 'any' : null);
    $steps = collect($steps)->values()->map(fn ($step) => is_array($step)
        ? ['title' => $step['title'] ?? '', 'description' => $step['description'] ?? null]
        : ['title' => (string) $step, 'description' => null])->all();
    $stateOf = fn (int $i) => $i < $current ? 'completed' : ($i === $current ? 'current' : 'upcoming');
    $statusText = ['completed' => 'Completed', 'current' => 'Current step', 'upcoming' => 'Not started'];
@endphp

{{-- A progress indicator for multi-step flows. `current` is the zero-based index of the active step; works with `x-model`. --}}
<nav data-slot="stepper" data-orientation="{{ $orientation }}" aria-label="{{ $label }}" x-modelable="current"
    x-data="{
        current: @js($current),
        mode: @js($clickMode),
        state(i) { return i < this.current ? 'completed' : (i === this.current ? 'current' : 'upcoming') },
        status(i) { return @js($statusText)[this.state(i)] },
        canGo(i) { return this.mode === 'any' || (this.mode === 'completed' && i <= this.current) },
        go(i) {
            if (! this.canGo(i) || i === this.current) return;
            this.current = i;
            this.$nextTick(() => this.$root.dispatchEvent(new CustomEvent('change', { bubbles: true, detail: { value: i } })));
        },
    }"
    {{ $attributes->merge(['class' => cn('w-full', $class)]) }}>
    <ol role="list" class="{{ $vertical ? 'flex flex-col' : 'flex w-full items-start' }}">
        @foreach ($steps as $i => $step)
            @php $state = $stateOf($i); @endphp
            <li data-slot="stepper-item" data-state="{{ $state }}" :data-state="state({{ $i }})"
                @if ($state === 'current') aria-current="step" @endif :aria-current="state({{ $i }}) === 'current' ? 'step' : null"
                class="group relative {{ $vertical ? 'flex gap-3 pb-8 last:pb-0' : 'flex flex-1 flex-col items-center px-2 text-center' }}">
                @unless ($loop->last)
                    <span aria-hidden="true" data-slot="stepper-separator"
                        class="absolute bg-border transition-colors group-data-[state=completed]:bg-primary {{ $vertical ? 'left-4 top-10 bottom-2 w-0.5 -translate-x-1/2' : 'left-[calc(50%+1.5rem)] top-4 h-0.5 w-[calc(100%-3rem)] -translate-y-1/2' }}"></span>
                @endunless
                @if ($clickMode)
                    <button type="button" @click="go({{ $i }})" :disabled="! canGo({{ $i }})" @disabled($clickMode === 'completed' && $i > $current)
                @else
                    <div
                @endif
                    data-slot="stepper-trigger"
                    class="flex rounded-md text-left outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50 {{ $vertical ? 'gap-3' : 'flex-col items-center gap-2 text-center' }} {{ $clickMode ? 'cursor-pointer disabled:cursor-not-allowed' : '' }}">
                    <span data-slot="stepper-indicator"
                        class="relative z-10 flex size-8 shrink-0 items-center justify-center rounded-full border-2 bg-background text-sm font-medium transition-colors border-border text-muted-foreground group-data-[state=current]:border-primary group-data-[state=current]:text-primary group-data-[state=current]:ring-4 group-data-[state=current]:ring-primary/15 group-data-[state=completed]:border-primary group-data-[state=completed]:bg-primary group-data-[state=completed]:text-primary-foreground">
                        <span class="group-data-[state=completed]:hidden">{{ $i + 1 }}</span>
                        <x-icons.check class="hidden size-4 group-data-[state=completed]:block" aria-hidden="true" />
                    </span>
                    <span class="flex flex-col gap-0.5 {{ $vertical ? 'pt-1' : '' }}">
                        <span data-slot="stepper-title" class="text-sm font-medium leading-tight text-foreground group-data-[state=upcoming]:text-muted-foreground">{{ $step['title'] }}</span>
                        @if ($step['description'])
                            <span data-slot="stepper-description" class="text-xs text-muted-foreground">{{ $step['description'] }}</span>
                        @endif
                        <span class="sr-only" x-text="status({{ $i }})">{{ $statusText[$state] }}</span>
                    </span>
                @if ($clickMode)
                    </button>
                @else
                    </div>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
