@props([
    'position' => 'bottom-right',
    'duration' => 4000,
    'flash' => null,
    'max' => 3,
    'class' => '',
])

@php
    $positions = [
        'top-left' => 'top-0 left-0 items-start',
        'top-center' => 'top-0 left-1/2 -translate-x-1/2 items-center',
        'top-right' => 'top-0 right-0 items-end',
        'bottom-left' => 'bottom-0 left-0 items-start flex-col-reverse',
        'bottom-center' => 'bottom-0 left-1/2 -translate-x-1/2 items-center flex-col-reverse',
        'bottom-right' => 'bottom-0 right-0 items-end flex-col-reverse',
    ];
    $fromTop = str_starts_with($position, 'top');

    // `flash` accepts a string, ['message' => ..., 'type' => ...], or a list of those (e.g. session('toast')).
    $initial = collect(is_array($flash) && array_is_list($flash) ? $flash : array_filter([$flash]))
        ->map(fn ($toast) => is_string($toast) ? ['message' => $toast] : (array) $toast)
        ->values();
@endphp

{{--
    Place once per page, e.g. in your layout. Then from JS: toast('Saved'), toast.success('Saved', { description }),
    toast.error(...), toast.info(...), toast.warning(...); from Alpine: $dispatch('toast', { message: 'Saved' });
    from PHP: <x-ui.toaster :flash="session('toast')" />.
--}}
<section aria-label="Notifications" tabindex="-1" data-slot="toaster" data-position="{{ $position }}"
    x-data="{
        toasts: [],
        duration: @js((int) $duration),
        max: @js((int) $max),
        paused: false,
        add(input) {
            const toast = typeof input === 'string' ? { message: input } : { ...input };
            toast.id = Date.now() + Math.random();
            toast.type ||= 'default';
            toast.visible = false;
            toast.remaining = toast.duration ?? this.duration;
            this.toasts.push(toast);
            const reactive = this.toasts[this.toasts.length - 1];
            if (this.toasts.length > this.max) this.dismiss(this.toasts[0].id);
            this.$nextTick(() => requestAnimationFrame(() => reactive.visible = true));
            this.schedule(reactive);
        },
        schedule(toast) {
            if (! isFinite(toast.remaining) || toast.remaining <= 0) return;
            toast.started = Date.now();
            clearTimeout(toast.timer);
            toast.timer = setTimeout(() => this.dismiss(toast.id), toast.remaining);
        },
        pause() {
            this.paused = true;
            this.toasts.forEach(t => { clearTimeout(t.timer); t.remaining -= Date.now() - (t.started ?? Date.now()); });
        },
        resume() {
            this.paused = false;
            this.toasts.forEach(t => this.schedule(t));
        },
        dismiss(id) {
            const toast = this.toasts.find(t => t.id === id);
            if (! toast) return;
            clearTimeout(toast.timer);
            toast.visible = false;
            setTimeout(() => this.toasts = this.toasts.filter(t => t.id !== id), 200);
        },
        runAction(toast) {
            if (typeof toast.action?.onClick === 'function') toast.action.onClick();
            else if (toast.action?.event) window.dispatchEvent(new CustomEvent(toast.action.event, { detail: toast }));
            this.dismiss(toast.id);
        },
        init() {
            @js($initial).forEach(toast => this.add(toast));
        },
    }"
    @toast.window="add($event.detail)" @bladecn-toast.window="add($event.detail)"
    {{ $attributes->merge(['class' => cn('pointer-events-none fixed z-[100] flex max-h-screen w-full flex-col gap-2 p-4 sm:max-w-[420px]', $positions[$position] ?? $positions['bottom-right'], $class)]) }}>
    <template x-for="toast in toasts" :key="toast.id">
        <div role="status" aria-live="polite" aria-atomic="true" data-slot="toast" :data-type="toast.type"
            @mouseenter="pause()" @mouseleave="resume()" @focusin="pause()" @focusout="resume()"
            :class="toast.visible ? 'opacity-100 translate-y-0' : 'opacity-0 {{ $fromTop ? '-translate-y-2' : 'translate-y-2' }}'"
            class="pointer-events-auto relative flex w-full items-start gap-3 rounded-lg border bg-popover p-4 pr-8 text-sm text-popover-foreground shadow-lg transition-all duration-200 sm:w-[356px]">
            <template x-if="toast.type === 'success'">
                <span class="mt-0.5 text-emerald-600 dark:text-emerald-400"><x-icons.circle-check class="size-4" /></span>
            </template>
            <template x-if="toast.type === 'error'">
                <span class="mt-0.5 text-destructive"><x-icons.circle-alert class="size-4" /></span>
            </template>
            <template x-if="toast.type === 'warning'">
                <span class="mt-0.5 text-amber-600 dark:text-amber-400"><x-icons.triangle-alert class="size-4" /></span>
            </template>
            <template x-if="toast.type === 'info'">
                <span class="mt-0.5 text-sky-600 dark:text-sky-400"><x-icons.info class="size-4" /></span>
            </template>
            <div class="grid flex-1 gap-1">
                <div class="font-medium" data-slot="toast-title" x-text="toast.message"></div>
                <template x-if="toast.description">
                    <div class="text-muted-foreground" data-slot="toast-description" x-text="toast.description"></div>
                </template>
            </div>
            <template x-if="toast.action">
                <button type="button" @click="runAction(toast)" x-text="toast.action.label"
                    class="inline-flex h-7 shrink-0 items-center rounded-md bg-primary px-2.5 text-xs font-medium text-primary-foreground hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50"></button>
            </template>
            <button type="button" aria-label="Close" @click="dismiss(toast.id)"
                class="absolute right-2 top-2 rounded-sm p-0.5 opacity-60 transition-opacity hover:opacity-100 focus-visible:outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50">
                <x-icons.x class="size-3.5" />
            </button>
        </div>
    </template>
</section>

@pushOnce('scripts')
    <script>
        (function() {
            if (window.toast) return;
            const send = (type) => (message, options = {}) =>
                window.dispatchEvent(new CustomEvent('bladecn-toast', { detail: { ...options, message, type } }));
            window.toast = Object.assign(send('default'), {
                success: send('success'),
                error: send('error'),
                warning: send('warning'),
                info: send('info'),
            });
        })();
    </script>
@endPushOnce
