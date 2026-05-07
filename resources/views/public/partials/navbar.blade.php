{{-- Navbar - Premium Glassmorphism --}}
<nav x-data="{
        mobileOpen: false,
        cariOpen: false,
        scrolled: false,
        darkMode: false,
        init() {
            this.darkMode = window.appkonkosTheme?.isDark() ?? document.documentElement.classList.contains('dark');
            window.addEventListener('appkonkos-theme-changed', (event) => {
                this.darkMode = event.detail.darkMode;
            });
        },
        toggleDarkMode() {
            this.darkMode = window.appkonkosTheme?.toggle() ?? !this.darkMode;
            document.documentElement.classList.toggle('dark', this.darkMode);
        },
    }"
    @scroll.window="scrolled = (window.pageYOffset > 10)"
    :class="scrolled ? 'bg-white/80 dark:bg-slate-900/80 backdrop-blur-2xl shadow-sm border-slate-200/50 dark:border-slate-800/50 dark:shadow-slate-900/50' : 'bg-white/95 dark:bg-slate-900/95 backdrop-blur-md shadow-none border-transparent'"
    class="fixed inset-x-0 top-0 z-50 border-b transition-all duration-300">
    <div class="mx-auto flex max-w-[1400px] items-center justify-between px-4 py-3 sm:px-6 lg:px-8">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="group flex items-center gap-3" id="nav-logo">
            <div class="flex h-11 w-11 items-center justify-center overflow-hidden rounded-full bg-white shadow-md shadow-blue-500/15 ring-2 ring-[#1967d2]/20 transition-all duration-300 group-hover:scale-105 group-hover:shadow-blue-500/30 group-hover:ring-[#1967d2]/40">
                <img src="{{ asset('images/appkonkos.png') }}" alt="{{ config('app.name', 'APPKONKOS') }}" class="h-9 w-9 object-contain">
            </div>
            <span class="text-xl font-black tracking-tight text-slate-900 transition-colors group-hover:text-[#1967d2] dark:text-white dark:group-hover:text-blue-400">appkonkos</span>
        </a>

        {{-- Center nav links (Desktop) --}}
        <div class="hidden items-center gap-2 md:flex">
            <a href="{{ route('home') }}" class="rounded-full px-5 py-2.5 text-sm font-bold text-slate-600 transition-all hover:bg-blue-50 hover:text-[#1967d2] dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-blue-300" id="nav-beranda">Beranda</a>

            {{-- Dropdown "Cari Apa?" --}}
            <div class="relative" x-data="{ open: false }" @click.outside="open = false" @mouseleave="open = false" @mouseenter="open = true">
                <button @click="open = !open" class="flex items-center gap-1.5 rounded-full px-5 py-2.5 text-sm font-bold text-slate-600 transition-all hover:bg-blue-50 hover:text-[#1967d2] dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-blue-300" id="nav-cari-apa">
                    Cari Apa?
                    <svg class="h-4 w-4 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                {{-- Dropdown Card --}}
                <div x-show="open" x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                    class="absolute left-1/2 top-full mt-2 w-72 -translate-x-1/2 overflow-hidden rounded-2xl border border-slate-100 bg-white/95 p-3 shadow-2xl backdrop-blur-xl ring-1 ring-slate-900/5 dark:border-slate-700 dark:bg-slate-900/95 dark:ring-white/10">

                    <a href="{{ route('cari', ['tipe' => 'Kos']) }}" class="group flex items-center gap-4 rounded-xl p-3 transition-colors hover:bg-blue-50 dark:hover:bg-slate-800" id="nav-cari-kos">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-100 text-[#1967d2] transition-colors group-hover:bg-[#1967d2] group-hover:text-white">
                            <svg class="h-6 w-6 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-extrabold text-slate-900 group-hover:text-[#1967d2] dark:text-slate-100 dark:group-hover:text-blue-300">Cari Kos</p>
                            <p class="mt-0.5 text-xs font-medium text-slate-500 dark:text-slate-400">Temukan kamar kos impianmu</p>
                        </div>
                    </a>

                    <a href="{{ route('cari', ['tipe' => 'Kontrakan']) }}" class="group mt-1 flex items-center gap-4 rounded-xl p-3 transition-colors hover:bg-orange-50 dark:hover:bg-slate-800" id="nav-cari-kontrakan">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-orange-100 text-[#ff8a00] transition-colors group-hover:bg-[#ff8a00] group-hover:text-white">
                            <svg class="h-6 w-6 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-extrabold text-slate-900 group-hover:text-[#ff8a00] dark:text-slate-100">Cari Kontrakan</p>
                            <p class="mt-0.5 text-xs font-medium text-slate-500 dark:text-slate-400">Sewa rumah untuk keluarga</p>
                        </div>
                    </a>
                </div>
            </div>

            <a href="{{ route('pusat-bantuan') }}" class="rounded-full px-5 py-2.5 text-sm font-bold text-slate-600 transition-all hover:bg-blue-50 hover:text-[#1967d2] dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-blue-300" id="nav-bantuan">Pusat Bantuan</a>
        </div>

        {{-- Right: Login button (Desktop) + Mobile hamburger --}}
        <div class="flex items-center gap-4">
            @auth
            @php
            $navUser = Auth::user();
            $navProfilePhotoPath = is_string($navUser?->profile_photo_path)
            ? ltrim($navUser->profile_photo_path, '/')
            : '';

            if ($navProfilePhotoPath !== '') {
            $navProfilePhotoPath = preg_replace('#^(storage/|public/storage/)#', '', $navProfilePhotoPath) ?? $navProfilePhotoPath;
            $sourceNavProfilePhoto = storage_path('app/public/'.$navProfilePhotoPath);
            $publicNavProfilePhoto = public_path('storage/'.$navProfilePhotoPath);

            if (file_exists($sourceNavProfilePhoto)) {
            if (! is_dir(dirname($publicNavProfilePhoto))) {
            mkdir(dirname($publicNavProfilePhoto), 0775, true);
            }

            if (! file_exists($publicNavProfilePhoto) || filesize($publicNavProfilePhoto) !== filesize($sourceNavProfilePhoto)) {
            copy($sourceNavProfilePhoto, $publicNavProfilePhoto);
            }
            }
            }

            $navProfilePhoto = $navProfilePhotoPath !== ''
            ? '/storage/'.$navProfilePhotoPath.'?v='.($navUser->updated_at?->timestamp ?? now()->timestamp)
            : 'https://ui-avatars.com/api/?name='.urlencode($navUser->name).'&color=113C7A&background=EBF4FF';
            $navProfileFallbackPhoto = 'https://ui-avatars.com/api/?name='.urlencode($navUser->name).'&color=113C7A&background=EBF4FF';
            @endphp
            <div class="hidden items-center gap-2 md:flex">
                {{-- A. Toggle Dark/Light Mode (synced with mitra dashboard) --}}
                <button type="button" @click="toggleDarkMode()" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200/80 text-slate-500 transition-all hover:bg-slate-100 hover:text-[#1967d2] dark:border-slate-700 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white" title="Mode Gelap/Terang" aria-label="Mode Gelap/Terang">
                    <svg x-show="!darkMode" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg x-show="darkMode" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>

                {{-- B. Notifikasi (Livewire Component - same as mitra dashboard) --}}
                <livewire:common.notification-bell />

                {{-- C. Dropdown Profil User (Premium Airbnb style) --}}
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="flex items-center gap-2 rounded-full border border-slate-200/80 bg-white py-1.5 pl-1.5 pr-2.5 shadow-sm transition-all hover:border-slate-300 hover:shadow-md dark:border-slate-700 dark:bg-slate-800 dark:hover:border-slate-600" aria-label="Menu profil">
                        <img src="{{ $navProfilePhoto }}" alt="{{ $navUser->name }}" class="h-9 w-9 rounded-full object-cover ring-2 ring-white dark:ring-slate-700" data-appkonkos-profile-photo onerror="this.onerror=null;this.src='{{ $navProfileFallbackPhoto }}';">
                        <svg class="h-4 w-4 text-slate-400 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    {{-- Dropdown Menu --}}
                    <div x-show="open" x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                        class="absolute right-0 mt-3 w-72 origin-top-right overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-2xl shadow-slate-900/10 dark:border-slate-700 dark:bg-slate-800 dark:shadow-black/30">

                        {{-- Profile Header Card --}}
                        <div class="bg-gradient-to-br from-[#1967d2] to-sky-500 px-5 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $navProfilePhoto }}" alt="{{ $navUser->name }}" class="h-12 w-12 rounded-full object-cover ring-2 ring-white/40 shadow-lg" data-appkonkos-profile-photo>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-bold text-white">{{ $navUser->name }}</p>
                                    <p class="truncate text-xs text-blue-100">{{ $navUser->email }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="py-2">
                            <a href="{{ route('pencari.profil') }}" wire:navigate class="flex items-center gap-3 px-5 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-blue-50 hover:text-[#1967d2] dark:text-slate-300 dark:hover:bg-slate-700/50 dark:hover:text-blue-400">
                                <svg class="h-[18px] w-[18px] text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Profil Saya
                            </a>
                            <a href="{{ route('pencari.favorit') }}" wire:navigate class="flex items-center gap-3 px-5 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-blue-50 hover:text-[#1967d2] dark:text-slate-300 dark:hover:bg-slate-700/50 dark:hover:text-blue-400">
                                <svg class="h-[18px] w-[18px] text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                Favorit Saya
                            </a>
                            <a href="{{ route('pencari.riwayat-pesanan') }}" wire:navigate class="flex items-center gap-3 px-5 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-blue-50 hover:text-[#1967d2] dark:text-slate-300 dark:hover:bg-slate-700/50 dark:hover:text-blue-400">
                                <svg class="h-[18px] w-[18px] text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Riwayat Pesanan
                            </a>
                            <a href="{{ route('pencari.ulasan-saya') }}" wire:navigate class="flex items-center gap-3 px-5 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-blue-50 hover:text-[#1967d2] dark:text-slate-300 dark:hover:bg-slate-700/50 dark:hover:text-blue-400">
                                <svg class="h-[18px] w-[18px] text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                                Ulasan Saya
                            </a>
                            <div class="mx-4 my-1.5 border-t border-slate-100 dark:border-slate-700"></div>
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-3 px-5 py-2.5 text-sm font-medium text-red-500 transition-colors hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10">
                                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="hidden items-center gap-3 md:flex">
                <button type="button" @click="toggleDarkMode()" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200/80 text-slate-500 transition-all hover:bg-slate-100 hover:text-[#1967d2] dark:border-slate-700 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white" title="Mode Gelap/Terang" aria-label="Mode Gelap/Terang">
                    <svg x-show="!darkMode" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg x-show="darkMode" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>
                <button type="button" @click="$dispatch('open-login-modal')" class="group flex items-center gap-2 rounded-full bg-gradient-to-r from-[#1967d2] to-sky-500 px-6 py-2.5 text-sm font-bold text-white shadow-md shadow-blue-500/30 transition-all hover:-translate-y-0.5 hover:shadow-lg hover:shadow-blue-500/40" id="nav-masuk">
                    Masuk
                    <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </button>
            </div>
            @endauth

            <button @click="mobileOpen = !mobileOpen" class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-600 transition-colors hover:bg-slate-200 md:hidden dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700" id="nav-mobile-toggle">
                <svg x-show="!mobileOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="mobileOpen" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
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
        class="absolute inset-x-0 top-full border-b border-slate-200 bg-white/95 px-4 pb-6 pt-2 shadow-xl backdrop-blur-xl md:hidden dark:border-slate-800 dark:bg-slate-900/95">

        <div class="space-y-2">
            <a href="{{ route('home') }}" class="block rounded-xl px-4 py-3 text-base font-bold text-slate-800 transition-colors hover:bg-blue-50 hover:text-[#1967d2] dark:text-slate-100 dark:hover:bg-slate-800 dark:hover:text-blue-300">Beranda</a>

            <div x-data="{ cariMobileOpen: false }" class="space-y-1">
                <button @click="cariMobileOpen = !cariMobileOpen" class="flex w-full items-center justify-between rounded-xl px-4 py-3 text-base font-bold text-slate-800 transition-colors hover:bg-blue-50 hover:text-[#1967d2] dark:text-slate-100 dark:hover:bg-slate-800 dark:hover:text-blue-300">
                    Cari Apa?
                    <svg class="h-5 w-5 transition-transform duration-300" :class="{ 'rotate-180': cariMobileOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="cariMobileOpen" x-cloak class="pl-4 pr-2">
                    <a href="{{ route('cari', ['tipe' => 'Kos']) }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-bold text-slate-600 transition-colors hover:bg-blue-50 hover:text-[#1967d2] dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-blue-300">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4" />
                        </svg>
                        Cari Kos
                    </a>
                    <a href="{{ route('cari', ['tipe' => 'Kontrakan']) }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-bold text-slate-600 transition-colors hover:bg-orange-50 hover:text-[#ff8a00] dark:text-slate-300 dark:hover:bg-slate-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Cari Kontrakan
                    </a>
                </div>
            </div>

            <a href="{{ route('pusat-bantuan') }}" class="block rounded-xl px-4 py-3 text-base font-bold text-slate-800 transition-colors hover:bg-blue-50 hover:text-[#1967d2] dark:text-slate-100 dark:hover:bg-slate-800 dark:hover:text-blue-300">Pusat Bantuan</a>

            <button type="button" @click="toggleDarkMode()" class="flex w-full items-center justify-between rounded-xl px-4 py-3 text-base font-bold text-slate-800 transition-colors hover:bg-blue-50 hover:text-[#1967d2] dark:text-slate-100 dark:hover:bg-slate-800 dark:hover:text-blue-300">
                <span>Mode Tampilan</span>
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-300">
                    <svg x-show="!darkMode" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg x-show="darkMode" x-cloak class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </span>
            </button>
        </div>

        <div class="mt-6 border-t border-slate-100 pt-6 dark:border-slate-800">
            @auth
            <div class="space-y-2">
                {{-- Profile Header --}}
                <div class="flex items-center gap-3 rounded-2xl bg-gradient-to-br from-[#1967d2] to-sky-500 px-4 py-3.5">
                    <img src="{{ $navProfilePhoto }}" alt="{{ $navUser->name }}" class="h-11 w-11 rounded-full object-cover ring-2 ring-white/40 shadow-lg" data-appkonkos-profile-photo>
                    <div class="min-w-0 flex-1">
                        <div class="truncate text-sm font-bold text-white">{{ $navUser->name }}</div>
                        <div class="truncate text-xs text-blue-100">{{ $navUser->email }}</div>
                    </div>
                </div>

                <a href="{{ route('pencari.profil') }}" wire:navigate class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-bold text-slate-600 transition-colors hover:bg-blue-50 hover:text-[#1967d2] dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-blue-400">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profil Saya
                </a>
                <a href="{{ route('pencari.favorit') }}" wire:navigate class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-bold text-slate-600 transition-colors hover:bg-blue-50 hover:text-[#1967d2] dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-blue-400">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    Favorit Saya
                </a>
                <a href="{{ route('pencari.riwayat-pesanan') }}" wire:navigate class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-bold text-slate-600 transition-colors hover:bg-blue-50 hover:text-[#1967d2] dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-blue-400">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Riwayat Pesanan
                </a>
                <a href="{{ route('pencari.ulasan-saya') }}" wire:navigate class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-bold text-slate-600 transition-colors hover:bg-blue-50 hover:text-[#1967d2] dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-blue-400">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    Ulasan Saya
                </a>

                <div class="my-2 border-t border-slate-100 dark:border-slate-800"></div>

                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm font-bold text-red-500 transition-colors hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
            @else
            <div class="space-y-3">
                <button type="button" @click="$dispatch('open-login-modal'); mobileOpen = false" class="group flex w-full items-center justify-center gap-2 rounded-full bg-gradient-to-r from-[#1967d2] to-sky-500 px-6 py-3.5 text-base font-bold text-white shadow-md shadow-blue-500/30 transition-all hover:-translate-y-0.5 hover:from-[#0f4fb5] hover:to-sky-600">
                    Masuk Sekarang
                    <svg class="h-5 w-5 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </button>
            </div>
            @endauth
        </div>
    </div>
</nav>

@include('public.partials.login-modal')