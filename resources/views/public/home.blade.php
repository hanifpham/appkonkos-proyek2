<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="APPKONKOS — Platform pencari kos dan kontrakan terpercaya di Indonesia. Temukan hunian nyaman dengan harga terjangkau.">

    <title>Appkonkos | Temukan Hunian Nyaman, Transaksi Aman</title>

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
            -webkit-font-smoothing: antialiased;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="app-public-theme min-h-screen bg-[#ffffff] font-[Inter] text-[#090a0b] antialiased transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">

    {{-- ==================== 1. NAVBAR ==================== --}}
    @include('public.partials.navbar')

    {{-- ==================== 2. HERO SECTION ==================== --}}
    @include('public.partials.hero')

    {{-- ==================== 3. PROMO CAROUSEL ==================== --}}
    @include('public.partials.promo')

    {{-- ==================== 4. REKOMENDASI PROPERTI (LIVEWIRE) ==================== --}}
    <livewire:front.beranda />

    {{-- ==================== 6. AREA POPULER ==================== --}}
    @include('public.partials.area')

    {{-- ==================== 7. SEKITAR KAMPUS ==================== --}}
    @include('public.partials.kampus')

    {{-- ==================== 8. FOOTER ==================== --}}
    @include('public.partials.footer')

    @livewireScripts
</body>

</html>
