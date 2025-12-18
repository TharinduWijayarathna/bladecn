@props([
    'title' => config('app.name'),
    'breadcrumbs' => [],
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Inline script to detect system dark mode preference and apply it immediately --}}
    <script>
        (function() {
            const appearance = '{{ $appearance ?? 'system' }}';

            if (appearance === 'system') {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                if (prefersDark) {
                    document.documentElement.classList.add('dark');
                }
            }
        })();
    </script>

    {{-- Inline style to set the HTML background color based on our theme in app.css --}}
    <style>
        html {
            background-color: oklch(1 0 0);
        }

        html.dark {
            background-color: oklch(0.145 0 0);
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
    @stack('styles')

    <x-layout.head :title="$title">
        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    </x-layout.head>

    {{-- Tailwind / JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased" x-data="{ sidebarOpen: false }">
    <div class="bg-background min-h-screen flex">
        {{-- Sidebar --}}
        <x-layout.app-sidebar />

        {{-- Main Content --}}
        <main class="bg-background relative flex w-full flex-1 flex-col md:ml-64 md:rounded-xl md:shadow-sm">
            {{-- Header --}}
            <x-layout.app-header :breadcrumbs="$breadcrumbs" />

            <div class="flex flex-1 flex-col">
                <div class="flex flex-1 flex-col gap-2">
                    <div class="flex flex-col gap-4 px-4 py-4 md:gap-6 md:py-6">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </main>
    </div>

    @stack('scripts')
    
    {{-- Theme update function for header dropdown --}}
    <script>
        function updateTheme(mode) {
            // Update localStorage
            if (typeof localStorage !== 'undefined') {
                localStorage.setItem('appearance', mode);
            }
            
            // Set cookie
            const maxAge = 365 * 24 * 60 * 60;
            document.cookie = `appearance=${mode};path=/;max-age=${maxAge};SameSite=Lax`;
            
            // Apply theme immediately
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const isDark = mode === 'dark' || (mode === 'system' && prefersDark);
            document.documentElement.classList.toggle('dark', isDark);
            
            // Submit to server to save in session
            fetch('{{ route('settings.appearance.update') }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ appearance: mode })
            }).catch(err => {
                console.error('Failed to save appearance preference:', err);
            });
        }
    </script>
</body>

</html>
