@props([
    'keys' => ['success', 'error', 'warning', 'info', 'status'],
    'types' => [],
    'toast' => 'toast',
    'validation' => false,
    'errorBag' => 'default',
    'errorTitle' => null,
    'position' => 'bottom-right',
    'duration' => 4000,
    'max' => 3,
    'class' => '',
])

@php
    // Session flash key => toast type. `status` (Breeze / Fortify) shows as a success toast.
    $typeMap = array_merge(['success' => 'success', 'error' => 'error', 'warning' => 'warning', 'info' => 'info', 'status' => 'success'], (array) $types);
    $session = app('request')->hasSession() ? app('request')->session() : null;
    $toasts = [];

    $normalize = function ($flash, string $type) {
        // A flash value may be a string, ['message' => ..., 'description' => ...], or a list of those.
        $items = is_array($flash) && array_is_list($flash) ? $flash : [$flash];

        return collect($items)
            ->filter(fn ($item) => filled($item))
            ->map(fn ($item) => is_array($item) ? $item + ['type' => $type] : ['message' => (string) $item, 'type' => $type])
            ->values()
            ->all();
    };

    if ($session) {
        foreach ((array) $keys as $key) {
            if ($session->has($key)) {
                array_push($toasts, ...$normalize($session->get($key), $typeMap[$key] ?? 'default'));
            }
        }

        // The `<x-ui.toaster :flash="session('toast')">` convention keeps working.
        if ($toast && $session->has($toast)) {
            array_push($toasts, ...$normalize($session->get($toast), 'default'));
        }
    }

    // Validation errors: `validation` = true shows each message, 'first' only the first, 'summary' one toast with a count.
    $bag = $errors ?? null;
    if ($validation && $bag instanceof \Illuminate\Support\ViewErrorBag && $bag->getBag($errorBag)->isNotEmpty()) {
        $messages = $bag->getBag($errorBag)->all();

        if ($validation === 'summary') {
            $toasts[] = [
                'type' => 'error',
                'message' => $errorTitle ?? trans_choice('{1} There is :count error in the form.|[2,*] There are :count errors in the form.', count($messages), ['count' => count($messages)]),
                'description' => $messages[0],
            ];
        } else {
            foreach ($validation === 'first' ? array_slice($messages, 0, 1) : $messages as $message) {
                $toasts[] = array_filter(['type' => 'error', 'message' => $errorTitle ?? $message, 'description' => $errorTitle ? $message : null]);
            }
        }
    }
@endphp

{{--
    Place once in your layout instead of <x-ui.toaster>: flashed messages become toasts on the next page load.
    return back()->with('success', 'Profile updated.');  // or 'error', 'warning', 'info', 'status'
--}}
<x-ui.toaster :flash="$toasts" :position="$position" :duration="$duration" :max="$max" :class="$class" data-flash-toaster {{ $attributes }} />
