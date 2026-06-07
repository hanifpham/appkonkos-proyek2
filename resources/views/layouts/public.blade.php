<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Appkonkos') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/appkonkos.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/appkonkos.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <style>
        .material-symbols-outlined {
            font-family: 'Material Symbols Outlined';
            font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
            font-style: normal;
            font-weight: normal;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            -webkit-font-feature-settings: 'liga';
            font-feature-settings: 'liga';
            -webkit-font-smoothing: antialiased;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
</head>

<body class="app-public-theme min-h-screen bg-[#ffffff] font-[Inter] text-[#090a0b] antialiased transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">
    @include('public.partials.navbar')

    <main class="pt-16 transition-colors duration-300 dark:bg-slate-950">
        {{-- Page Loading Bar --}}
        <div
            wire:loading.class="opacity-100"
            wire:loading.remove.class="opacity-0"
            class="fixed top-0 left-0 right-0 z-[9999] opacity-0 transition-opacity duration-300">
            <div class="h-[3px] w-full overflow-hidden bg-slate-200/50 dark:bg-slate-800/50">
                <div class="h-full w-full animate-[loading-bar_1.5s_ease-in-out_infinite] bg-gradient-to-r from-[#1967d2] via-[#3B82F6] to-[#1967d2]"></div>
            </div>
        </div>

        {{ $slot }}
    </main>

    @include('public.partials.footer')

    @livewireScripts

    <style>
        @keyframes loading-bar {
            0% {
                transform: translateX(-100%);
            }

            50% {
                transform: translateX(0%);
            }

            100% {
                transform: translateX(100%);
            }
        }
    </style>
</body>

</html>