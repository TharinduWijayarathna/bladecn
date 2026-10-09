@props([
    'name' => null,
    'messages' => [],
    'bag' => 'default',
    'class' => '',
])

@php
    // Messages come from the slot, a `messages` array, or Laravel's shared error bag for `name`.
    $messages = collect($messages instanceof \Illuminate\Support\MessageBag ? $messages->all() : (array) $messages);

    if ($name !== null && isset($__env) && ($bagErrors = $__env->shared('errors')) instanceof \Illuminate\Support\ViewErrorBag) {
        $messages = $messages->merge($bagErrors->getBag($bag)->get($name));
    }

    $messages = $messages->filter()->unique()->values();
@endphp

@if ($slot->isNotEmpty() || $messages->isNotEmpty())
    <div role="alert" data-slot="field-error" {{ $attributes->merge(['class' => cn('text-sm font-normal text-destructive', $class)]) }}>
        @if ($slot->isNotEmpty())
            {{ $slot }}
        @elseif ($messages->count() === 1)
            {{ $messages->first() }}
        @else
            <ul class="ml-4 flex list-disc flex-col gap-1">
                @foreach ($messages as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        @endif
    </div>
@endif
