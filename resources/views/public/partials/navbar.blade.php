{{-- Navbar - Premium Glassmorphism --}}
<nav x-data="{ mobileOpen: false, cariOpen: false, scrolled: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 10)"
     :class="scrolled ? 'bg-white/80 backdrop-blur-2xl shadow-sm border-slate-200/50' : 'bg-white/95 backdrop-blur-md shadow-none border-transparent'"
     class="fixed inset-x-0 top-0 z-50 border-b transition-all duration-300">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="group flex items-center gap-2.5" id="nav-logo">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-[#1967d2] to-sky-400 shadow-md shadow-blue-500/20 transition-transform duration-300 group-hover:scale-105 group-hover:rotate-3 group-hover:shadow-blue-500/40">
                <svg class="h-6 w-6 text-white" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="32" height="32" rx="8" fill="transparent"/>
                    <path d="M16 7L6 15h3v10h6v-6h2v6h6V15h3L16 7z" fill="currentColor"/>
                </svg>
            </div>
            <span class="text-xl font-black tracking-tight text-slate-900 transition-colors group-hover:text-[#1967d2]">appkonkos</span>
        </a>

        {{-- Center nav links (Desktop) --}}
        <div class="hidden items-center gap-2 md:flex">
            <a href="{{ route('home') }}" class="rounded-full px-5 py-2.5 text-sm font-bold text-slate-600 transition-all hover:bg-blue-50 hover:text-[#1967d2]" id="nav-beranda">Beranda</a>

            {{-- Dropdown "Cari Apa?" --}}
            <div class="relative" x-data="{ open: false }" @click.outside="open = false" @mouseleave="open = false" @mouseenter="open = true">
                <button @click="open = !open" class="flex items-center gap-1.5 rounded-full px-5 py-2.5 text-sm font-bold text-slate-600 transition-all hover:bg-blue-50 hover:text-[#1967d2]" id="nav-cari-apa">
                    Cari Apa?
                    <svg class="h-4 w-4 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                </button>
                
                {{-- Dropdown Card --}}
                <div x-show="open" x-cloak 
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 translate-y-4 scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                     x-transition:leave="transition ease-in duration-150" 
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
                     x-transition:leave-end="opacity-0 translate-y-4 scale-95" 
                     class="absolute left-1/2 top-full mt-2 w-72 -translate-x-1/2 overflow-hidden rounded-2xl border border-slate-100 bg-white/95 p-3 shadow-2xl backdrop-blur-xl ring-1 ring-slate-900/5">
                    
                    <a href="{{ route('cari', ['tipe' => 'Kos']) }}" class="group flex items-center gap-4 rounded-xl p-3 transition-colors hover:bg-blue-50" id="nav-cari-kos">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-[#1967d2] transition-colors group-hover:bg-[#1967d2] group-hover:text-white">
                            <svg class="h-6 w-6 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-extrabold text-slate-900 group-hover:text-[#1967d2]">Cari Kos</p>
                            <p class="mt-0.5 text-xs font-medium text-slate-500">Temukan kamar kos impianmu</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('cari', ['tipe' => 'Kontrakan']) }}" class="group mt-1 flex items-center gap-4 rounded-xl p-3 transition-colors hover:bg-orange-50" id="nav-cari-kontrakan">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-orange-100 text-[#ff8a00] transition-colors group-hover:bg-[#ff8a00] group-hover:text-white">
                            <svg class="h-6 w-6 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-extrabold text-slate-900 group-hover:text-[#ff8a00]">Cari Kontrakan</p>
                            <p class="mt-0.5 text-xs font-medium text-slate-500">Sewa rumah untuk keluarga</p>
                        </div>
                    </a>
                </div>
            </div>

            <a href="{{ route('pusat-bantuan') }}" class="rounded-full px-5 py-2.5 text-sm font-bold text-slate-600 transition-all hover:bg-blue-50 hover:text-[#1967d2]" id="nav-bantuan">Pusat Bantuan</a>
        </div>

        {{-- Right: Login button (Desktop) + Mobile hamburger --}}
        <div class="flex items-center gap-4">
            @auth
                <a href="{{ Auth::user()->role === 'pencari' ? route('home') : (Auth::user()->role === 'pemilik' ? route('mitra.dashboard') : route('superadmin.dashboard')) }}" class="hidden items-center gap-2 rounded-full border-2 border-slate-200 bg-white px-6 py-2.5 text-sm font-bold text-slate-700 transition-all hover:border-[#1967d2] hover:text-[#1967d2] md:flex">
                    Dashboard Saya
                </a>
            @else
                <button type="button" @click="$dispatch('open-login-modal')" class="group hidden items-center gap-2 rounded-full bg-gradient-to-r from-[#1967d2] to-sky-500 px-8 py-2.5 text-sm font-bold text-white shadow-md shadow-blue-500/30 transition-all hover:-translate-y-0.5 hover:shadow-lg hover:shadow-blue-500/40 md:flex" id="nav-masuk">
                    Masuk
                    <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </button>
            @endauth

            <button @click="mobileOpen = !mobileOpen" class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-600 transition-colors hover:bg-slate-200 md:hidden" id="nav-mobile-toggle">
                <svg x-show="!mobileOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="mobileOpen" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div x-show="mobileOpen" x-cloak 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="absolute inset-x-0 top-full border-b border-slate-200 bg-white/95 px-4 pb-6 pt-2 shadow-xl backdrop-blur-xl md:hidden">
        
        <div class="space-y-2">
            <a href="{{ route('home') }}" class="block rounded-xl px-4 py-3 text-base font-bold text-slate-800 transition-colors hover:bg-blue-50 hover:text-[#1967d2]">Beranda</a>
            
            <div x-data="{ cariMobileOpen: false }" class="space-y-1">
                <button @click="cariMobileOpen = !cariMobileOpen" class="flex w-full items-center justify-between rounded-xl px-4 py-3 text-base font-bold text-slate-800 transition-colors hover:bg-blue-50 hover:text-[#1967d2]">
                    Cari Apa?
                    <svg class="h-5 w-5 transition-transform duration-300" :class="{ 'rotate-180': cariMobileOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="cariMobileOpen" x-collapse x-cloak class="pl-4 pr-2">
                    <a href="{{ route('cari', ['tipe' => 'Kos']) }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-bold text-slate-600 transition-colors hover:bg-blue-50 hover:text-[#1967d2]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
                        Cari Kos
                    </a>
                    <a href="{{ route('cari', ['tipe' => 'Kontrakan']) }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-bold text-slate-600 transition-colors hover:bg-orange-50 hover:text-[#ff8a00]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Cari Kontrakan
                    </a>
                </div>
            </div>
            
            <a href="{{ route('pusat-bantuan') }}" class="block rounded-xl px-4 py-3 text-base font-bold text-slate-800 transition-colors hover:bg-blue-50 hover:text-[#1967d2]">Pusat Bantuan</a>
        </div>

        <div class="mt-6 border-t border-slate-100 pt-6">
            @auth
                <a href="{{ Auth::user()->role === 'pencari' ? route('home') : (Auth::user()->role === 'pemilik' ? route('mitra.dashboard') : route('superadmin.dashboard')) }}" class="flex w-full items-center justify-center gap-2 rounded-full border-2 border-slate-200 bg-white px-6 py-3.5 text-base font-bold text-slate-700 transition-colors hover:border-[#1967d2] hover:text-[#1967d2]">
                    Dashboard Saya
                </a>
            @else
                <button type="button" @click="$dispatch('open-login-modal'); mobileOpen = false" class="group flex w-full items-center justify-center gap-2 rounded-full bg-gradient-to-r from-[#1967d2] to-sky-500 px-6 py-3.5 text-base font-bold text-white shadow-md shadow-blue-500/30 transition-all hover:-translate-y-0.5 hover:from-[#0f4fb5] hover:to-sky-600">
                    Masuk Sekarang
                    <svg class="h-5 w-5 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </button>
            @endauth
        </div>
    </div>
</nav>

@include('public.partials.login-modal')
