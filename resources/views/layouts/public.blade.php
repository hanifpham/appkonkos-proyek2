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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @livewireStyles
</head>
<body class="min-h-screen bg-[#ffffff] font-[Inter] text-[#090a0b] antialiased">
    @include('public.partials.navbar')

    <main class="pt-16">
        {{ $slot }}
    </main>

    @include('public.partials.footer')
    
    @livewireScripts
</body>
</html>
