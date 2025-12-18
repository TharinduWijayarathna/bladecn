@php
    $breadcrumbs = [
        ['label' => 'Settings', 'href' => route('settings.profile')],
        ['label' => 'Profile', 'href' => route('settings.profile')],
    ];
@endphp

<x-layout.app :breadcrumbs="$breadcrumbs">
    <x-slot name="title">Profile settings</x-slot>

    <x-layout.settings>
        <div class="space-y-6">
            <x-ui.heading-small title="Profile information" description="Update your name and email address" />

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PATCH')

                <div class="grid gap-2">
                    <x-ui.avatar-upload
                        id="avatar"
                        name="avatar"
                        :defaultUrl="auth()->user()->avatar ?? ''"
                        :fallbackText="getInitials(auth()->user()->name)"
                    />
                </div>

                <div class="grid gap-2">
                    <x-ui.label for="name">Name</x-ui.label>
                    <x-ui.input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', auth()->user()->name) }}"
                        required
                        autocomplete="name"
                        placeholder="Full name"
                    />
                    @error('name')
                        <p class="mt-2 text-sm text-destructive">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-2">
                    <x-ui.label for="email">Email address</x-ui.label>
                    <x-ui.input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email', auth()->user()->email) }}"
                        required
                        autocomplete="username"
                        placeholder="Email address"
                    />
                    @error('email')
                        <p class="mt-2 text-sm text-destructive">{{ $message }}</p>
                    @enderror
                </div>

                @if(auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && auth()->user()->email_verified_at === null)
                    <div>
                        <p class="-mt-4 text-sm text-muted-foreground">
                            Your email address is unverified.
                            <span class="inline">
                                <form action="{{ route('verification.send') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current dark:decoration-neutral-500">
                                        Click here to resend the verification email.
                                    </button>
                                </form>
                            </span>
                        </p>

                        @if(session('status') === 'verification-link-sent')
                            <div class="mt-2 text-sm font-medium text-green-600">
                                A new verification link has been sent to your email address.
                            </div>
                        @endif
                    </div>
                @endif

                <div class="flex items-center gap-4">
                    <x-ui.button type="submit" :disabled="false">Save</x-ui.button>

                    @if(session('status') === 'profile-updated')
                        <p id="savedMessage" class="text-sm text-neutral-600">Saved</p>
                    @endif
                </div>
            </form>
        </div>

        <x-settings.delete-user />
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

