@props([
    'title' => null,
    'description' => null,
    'appearance' => 'system',
    'backgroundImage' => null,
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

<body class="font-sans antialiased">
    {{-- Flash messages --}}
    {{-- @if (session('success'))
        <div class="fixed top-5 right-5 z-50 bg-green-500 text-white px-4 py-2 rounded shadow">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="fixed top-5 right-5 z-50 bg-red-500 text-white px-4 py-2 rounded shadow">
            {{ session('error') }}
        </div>
    @elseif(session('warning'))
        <div class="fixed top-5 right-5 z-50 bg-yellow-500 text-black px-4 py-2 rounded shadow">
            {{ session('warning') }}
        </div>
    @elseif(session('info'))
        <div class="fixed top-5 right-5 z-50 bg-blue-500 text-white px-4 py-2 rounded shadow">
            {{ session('info') }}
        </div>
    @endif --}}

    <div
        class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
        {{-- Left Side: background + logo --}}
        <div class="relative hidden h-full flex-col bg-[#0E121B] p-10 text-white lg:flex dark:border-r">
            <img src="{{ $backgroundImage ?? 'https://picsum.photos/seed/picsum/1920/1080' }}" alt="background-image"
                class="pointer-events-none absolute inset-0 h-full w-full object-cover" />
            <a href="{{ route('home') }}" class="relative z-20 flex items-center text-lg font-medium">
                <x-icons.app-logo />
                {{ config('app.name') }}
            </a>
        </div>

        {{-- Right Side: Auth form --}}
        <div class="w-full lg:p-8">
            {{-- Center Column --}}
            <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                <a href="{{ route('home') }}"
                    class="relative z-20 flex items-center justify-center font-medium lg:hidden">
                    <x-icons.app-logo class="!size-6 !sm:size-8 !text-foreground" />
                    {{ config('app.name') }}
                </a>

                <div class="flex flex-col items-center gap-2 text-center">
                    @if ($title)
                        <h1 class="text-xl font-medium">{{ $title }}</h1>
                    @endif
                    @if ($description)
                        <p class="text-sm text-muted-foreground">{{ $description }}</p>
                    @endif
                </div>

                {{-- Slot for form content --}}
                {{ $slot }}
            </div>
        </div>
    </div>
    @stack('scripts')
</body>

</html>
