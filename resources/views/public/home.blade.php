<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="APPKONKOS — Platform pencari kos dan kontrakan terpercaya di Indonesia. Temukan hunian nyaman dengan harga terjangkau.">

    <title>APPKONKOS — Temukan Hunian Nyaman, Transaksi Aman</title>

    <link rel="icon" type="image/png" href="{{ asset('images/appkonkos.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/appkonkos.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-white font-[Inter] text-slate-900 antialiased">

{{-- ==================== 1. NAVBAR ==================== --}}
@include('public.partials.navbar')

{{-- ==================== 2. HERO SECTION ==================== --}}
@include('public.partials.hero')

{{-- ==================== 3. PROMO CAROUSEL ==================== --}}
@include('public.partials.promo')

{{-- ==================== 4. REKOMENDASI KOS ==================== --}}
@include('public.partials.kos')

{{-- ==================== 5. REKOMENDASI KONTRAKAN ==================== --}}
@include('public.partials.kontrakan')

{{-- ==================== 6. AREA POPULER ==================== --}}
@include('public.partials.area')

{{-- ==================== 7. SEKITAR KAMPUS ==================== --}}
@include('public.partials.kampus')

{{-- ==================== 8. FOOTER ==================== --}}
@include('public.partials.footer')

</body>
</html>
