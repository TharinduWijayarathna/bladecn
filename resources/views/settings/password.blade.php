@php
    $breadcrumbs = [
        ['label' => 'Settings', 'href' => route('settings.profile')],
        ['label' => 'Password', 'href' => route('settings.password')],
    ];
@endphp

<x-layout.app :breadcrumbs="$breadcrumbs">
    <x-slot name="title">Password settings</x-slot>

    <x-layout.settings>
        <div class="space-y-6">
            <x-ui.heading-small title="Update password" description="Ensure your account is using a long, random password to stay secure" />

            <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid gap-2">
                    <x-ui.label for="current_password">Current password</x-ui.label>
                    <x-ui.input-group>
                        <x-ui.input-group-password
                            id="current_password"
                            name="current_password"
                            required
                            autocomplete="current-password"
                            placeholder="Current password"
                        />
                    </x-ui.input-group>
                    @error('current_password')
                        <p class="mt-2 text-sm text-destructive">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-2">
                    <x-ui.label for="password">New password</x-ui.label>
                    <x-ui.input-group>
                        <x-ui.input-group-password
                            id="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="New password"
                        />
                    </x-ui.input-group>
                    @error('password')
                        <p class="mt-2 text-sm text-destructive">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-2">
                    <x-ui.label for="password_confirmation">Confirm password</x-ui.label>
                    <x-ui.input-group>
                        <x-ui.input-group-password
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="Confirm password"
                        />
                    </x-ui.input-group>
                    @error('password_confirmation')
                        <p class="mt-2 text-sm text-destructive">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-4">
                    <x-ui.button type="submit">Save password</x-ui.button>

                    @if(session('status') === 'password-updated')
                        <p id="savedMessage" class="text-sm text-neutral-600">Saved</p>
                    @endif
                </div>
            </form>
        </div>
    </x-layout.settings>
</x-layout.app>

@push('scripts')
<script>
    // Show saved message with fade animation
    document.addEventListener('DOMContentLoaded', function() {
        const savedMessage = document.getElementById('savedMessage');
        if (savedMessage) {
            setTimeout(function() {
                savedMessage.style.transition = 'opacity 0.3s ease-in-out';
                savedMessage.style.opacity = '0';
                setTimeout(function() {
                    savedMessage.remove();
                }, 300);
            }, 2000);
        }
    });
</script>
@endpush

