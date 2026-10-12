@props([
    'action' => '',
    'method' => 'DELETE',
    'title' => 'Are you absolutely sure?',
    'description' => 'This action cannot be undone.',
    'trigger' => 'Delete',
    'triggerVariant' => 'destructive',
    'triggerSize' => 'default',
    'confirmText' => 'Delete',
    'cancelText' => 'Cancel',
    'confirmation' => null,
    'confirmationLabel' => null,
    'confirmationName' => null,
    'open' => false,
    'class' => '',
])

@php
    $method = strtoupper((string) $method);
    $formMethod = $method === 'GET' ? 'GET' : 'POST';
    $confirmation = filled($confirmation) ? (string) $confirmation : null;
    $inputId = 'confirm-delete-'.\Illuminate\Support\Str::random(8);
@endphp

{{--
    An alert dialog around a form: CSRF token + DELETE method spoofing by default. The trigger opens the dialog; the
    destructive action submits the form. With `confirmation`, the user must type that text first.
--}}
<form method="{{ $formMethod }}" action="{{ $action }}" data-slot="confirm-delete"
    @if ($confirmation) x-data="{ typed: '', expected: @js($confirmation) }" x-on:submit="if (typed !== expected) $event.preventDefault()" @endif
    {{ $attributes->merge(['class' => cn('inline-block', $class)]) }}>
    @if ($formMethod === 'POST')
        @csrf
    @endif
    @unless (in_array($method, ['GET', 'POST'], true))
        @method($method)
    @endunless
    <x-ui.alert-dialog :open="$open">
        @if ($trigger instanceof \Illuminate\View\ComponentSlot)
            {{-- <x-slot:trigger> replaces the whole trigger; use <x-ui.alert-dialog-trigger> (or anything with x-on:click="open = true"). --}}
            {{ $trigger }}
        @else
            <x-ui.alert-dialog-trigger :variant="$triggerVariant" :size="$triggerSize">{{ $trigger }}</x-ui.alert-dialog-trigger>
        @endif
        <x-ui.alert-dialog-content>
            <x-ui.alert-dialog-header>
                <x-ui.alert-dialog-title>{{ $title }}</x-ui.alert-dialog-title>
                <x-ui.alert-dialog-description>{{ $description }}</x-ui.alert-dialog-description>
            </x-ui.alert-dialog-header>
            {{ $slot }}
            @if ($confirmation)
                <div class="grid gap-2" data-slot="confirm-delete-confirmation">
                    <x-ui.label :for="$inputId">
                        @if ($confirmationLabel)
                            {{ $confirmationLabel }}
                        @else
                            Type <span class="font-semibold">{{ $confirmation }}</span> to confirm.
                        @endif
                    </x-ui.label>
                    <x-ui.input :id="$inputId" x-model="typed" autocomplete="off" autocapitalize="off" spellcheck="false"
                        :name="$confirmationName" />
                </div>
            @endif
            <x-ui.alert-dialog-footer>
                <x-ui.alert-dialog-cancel>{{ $cancelText }}</x-ui.alert-dialog-cancel>
                <x-ui.alert-dialog-action type="submit" variant="destructive" data-slot="confirm-delete-action"
                    x-bind:disabled="{{ $confirmation ? 'typed !== expected' : 'false' }}">{{ $confirmText }}</x-ui.alert-dialog-action>
            </x-ui.alert-dialog-footer>
        </x-ui.alert-dialog-content>
    </x-ui.alert-dialog>
</form>
