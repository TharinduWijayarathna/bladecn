@props([
    'class' => '',
    'value' => session('appearance', 'system'),
])

@php
    $tabsId = uniqid('appearance-tabs-');
    $currentValue = $value;
@endphp

<div class="{{ cn('inline-flex gap-1 rounded-lg bg-neutral-100 p-1 dark:bg-neutral-800', $class) }}" data-appearance-tabs="{{ $tabsId }}">
    @php
        $tabs = [
            ['value' => 'light', 'icon' => 'sun', 'label' => 'Light'],
            ['value' => 'dark', 'icon' => 'moon', 'label' => 'Dark'],
            ['value' => 'system', 'icon' => 'monitor', 'label' => 'System'],
        ];
    @endphp

    @foreach($tabs as $tab)
        @php
            $isActive = $currentValue === $tab['value'];
        @endphp
        <button
            type="button"
            data-appearance-value="{{ $tab['value'] }}"
            class="flex items-center rounded-md px-3.5 py-1.5 transition-colors appearance-tab {{ $isActive ? 'bg-white shadow-xs dark:bg-neutral-700 dark:text-neutral-100' : 'text-neutral-500 hover:bg-neutral-200/60 hover:text-black dark:text-neutral-400 dark:hover:bg-neutral-700/60' }}"
        >
            @if($tab['icon'] === 'sun')
                <x-icons.sun class="-ml-1 size-4" />
            @elseif($tab['icon'] === 'moon')
                <x-icons.moon class="-ml-1 size-4" />
            @else
                <x-icons.monitor class="-ml-1 size-4" />
            @endif
            <span class="ml-1.5 text-sm">{{ $tab['label'] }}</span>
        </button>
    @endforeach
</div>

@push('scripts')
<script>
    (function() {
        if (window.appearanceTabsInitialized) return;
        window.appearanceTabsInitialized = true;

        function getAppearance() {
            if (typeof localStorage !== 'undefined') {
                return localStorage.getItem('appearance') || 'system';
            }
            return 'system';
        }

        function setAppearance(mode) {
            if (typeof localStorage !== 'undefined') {
                localStorage.setItem('appearance', mode);
            }

            // Set cookie for SSR
            const maxAge = 365 * 24 * 60 * 60;
            document.cookie = `appearance=${mode};path=/;max-age=${maxAge};SameSite=Lax`;

            applyTheme(mode);
            updateActiveTab();
        }

        function prefersDark() {
            return window.matchMedia('(prefers-color-scheme: dark)').matches;
        }

        function applyTheme(appearance) {
            const isDark = appearance === 'dark' || (appearance === 'system' && prefersDark());
            document.documentElement.classList.toggle('dark', isDark);
        }

        function updateActiveTab() {
            const currentAppearance = getAppearance();
            document.querySelectorAll('[data-appearance-tabs]').forEach(tabsContainer => {
                tabsContainer.querySelectorAll('.appearance-tab').forEach(button => {
                    const value = button.getAttribute('data-appearance-value');
                    if (value === currentAppearance) {
                        button.classList.add('bg-white', 'shadow-xs', 'dark:bg-neutral-700', 'dark:text-neutral-100');
                        button.classList.remove('text-neutral-500', 'hover:bg-neutral-200/60', 'hover:text-black', 'dark:text-neutral-400', 'dark:hover:bg-neutral-700/60');
                    } else {
                        button.classList.remove('bg-white', 'shadow-xs', 'dark:bg-neutral-700', 'dark:text-neutral-100');
                        button.classList.add('text-neutral-500', 'hover:bg-neutral-200/60', 'hover:text-black', 'dark:text-neutral-400', 'dark:hover:bg-neutral-700/60');
                    }
                });
            });
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Check if there's a server-provided value first
            const serverAppearance = '{{ $currentValue }}';
            const savedAppearance = serverAppearance || getAppearance();

            // If we have a server value, sync it to localStorage
            if (serverAppearance && typeof localStorage !== 'undefined') {
                localStorage.setItem('appearance', serverAppearance);
            }

            applyTheme(savedAppearance);
            updateActiveTab();

            // Listen for system theme changes
            if (window.matchMedia) {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function() {
                    const currentAppearance = getAppearance();
                    if (currentAppearance === 'system') {
                        applyTheme('system');
                    }
                });
            }

            // Handle tab clicks
            document.querySelectorAll('.appearance-tab').forEach(button => {
                button.addEventListener('click', function() {
                    const value = this.getAttribute('data-appearance-value');
                    setAppearance(value);

                    // Update hidden input if it exists (for form submission)
                    const appearanceInput = document.getElementById('appearance-input');
                    if (appearanceInput) {
                        appearanceInput.value = value;
                    }
                });
            });
        });
    })();
</script>
@endpush

