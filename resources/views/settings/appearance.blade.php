@php
    $breadcrumbs = [
        ['label' => 'Settings', 'href' => route('settings.profile')],
        ['label' => 'Appearance', 'href' => route('settings.appearance')],
    ];
@endphp

<x-layout.app :breadcrumbs="$breadcrumbs">
    <x-slot name="title">Appearance settings</x-slot>

    <x-layout.settings>
        <form action="{{ route('settings.appearance.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <x-ui.heading-small title="Appearance settings" description="Update your account's appearance settings" />
                
                <div class="grid gap-2">
                    <x-ui.label>Theme</x-ui.label>
                    <x-ui.appearance-tabs :value="session('appearance', 'system')" />
                    <input type="hidden" name="appearance" id="appearance-input" value="{{ session('appearance', 'system') }}" />
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <x-ui.button type="submit">Save appearance</x-ui.button>
                
                @if(session('status') === 'appearance-updated')
                    <p id="savedMessage" class="text-sm text-neutral-600">Saved</p>
                @endif
            </div>
        </form>
    </x-layout.settings>
</x-layout.app>

@push('scripts')
<script>
    // Update hidden input when appearance tabs change
    document.addEventListener('DOMContentLoaded', function() {
        const appearanceInput = document.getElementById('appearance-input');
        const currentAppearance = '{{ session('appearance', 'system') }}';
        
        // Set initial value from session
        if (appearanceInput) {
            appearanceInput.value = currentAppearance;
        }
        
        // Listen for appearance tab clicks
        document.querySelectorAll('.appearance-tab').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const value = this.getAttribute('data-appearance-value');
                if (appearanceInput) {
                    appearanceInput.value = value;
                }
            });
        });
        
        // Show saved message with fade animation
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

