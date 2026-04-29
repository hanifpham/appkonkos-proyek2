{{-- Hero Section --}}
<section class="relative flex min-h-[560px] items-center justify-center overflow-hidden pt-16" id="hero-section">
    {{-- Background image --}}
    <div class="absolute inset-0">
        <img src="{{ asset('images/hero-bg.png') }}" alt="Interior hunian modern" class="h-full w-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900/60 via-slate-900/40 to-[#0EA5E9]/20"></div>
    </div>

    {{-- Particle-like floating shapes --}}
    <div class="absolute left-10 top-32 h-20 w-20 animate-pulse rounded-full bg-[#0EA5E9]/20 blur-2xl"></div>
    <div class="absolute bottom-40 right-20 h-32 w-32 animate-pulse rounded-full bg-[#3B82F6]/15 blur-3xl" style="animation-delay:.5s"></div>

    {{-- Hero content --}}
    <div class="relative z-10 mx-auto w-full max-w-7xl px-4 py-20 text-center sm:px-6 lg:px-8">
        <p class="mx-auto inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-white/90 backdrop-blur-sm">
            <svg class="h-4 w-4 text-[#0EA5E9]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            Platform Hunian #1 di Indonesia
        </p>

        <h1 class="mx-auto mt-6 max-w-4xl text-3xl font-extrabold leading-tight tracking-tight text-white sm:text-4xl md:text-5xl lg:text-6xl">
            Temukan <span class="gradient-text">Hunian Nyaman</span>,
            <br class="hidden sm:block">
            Transaksi <span class="bg-gradient-to-r from-cyan-300 to-sky-400 bg-clip-text text-transparent">Aman & Mudah</span>
        </h1>

        <p class="mx-auto mt-5 max-w-xl text-base leading-relaxed text-white/75 sm:text-lg">
            Cari kos dan kontrakan terbaik di seluruh Indonesia dengan mudah, cepat, dan terpercaya.
        </p>

        {{-- Search & Filter Box --}}
        <div class="animate-float-up relative mx-auto mt-10 max-w-4xl">
            <form action="{{ route('cari') }}" method="GET" class="rounded-full bg-white p-2 shadow-2xl shadow-black/10 md:p-2.5">
                <div class="flex flex-col gap-2 md:flex-row md:items-center">

                    {{-- Dropdown Jenis Properti --}}
                    <div class="relative md:w-44" x-data="{ open: false, selected: 'Semua Tipe', icon: 'all' }" @click.outside="open = false">
                        <input type="hidden" name="tipe" x-model="selected">
                        <button type="button" @click="open = !open" class="flex w-full items-center gap-2 rounded-full bg-slate-50 px-4 py-3 text-sm text-slate-600 transition hover:bg-slate-100 md:py-3.5" id="hero-dropdown-tipe">
                            <svg class="h-4 w-4 flex-shrink-0 text-[#0EA5E9]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span x-text="selected" class="flex-1 text-left"></span>
                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-cloak x-transition class="absolute left-0 top-full z-20 mt-2 w-full overflow-hidden rounded-xl border border-gray-100 bg-white py-1 shadow-lg">
                            <button type="button" @click="selected = 'Semua Tipe'; open = false" class="flex w-full items-center gap-2 px-4 py-2.5 text-left text-sm text-slate-600 hover:bg-sky-50 hover:text-[#0EA5E9]">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                Semua Tipe
                            </button>
                            <button type="button" @click="selected = 'Kos'; open = false" class="flex w-full items-center gap-2 px-4 py-2.5 text-left text-sm text-slate-600 hover:bg-sky-50 hover:text-[#0EA5E9]">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
                                Kos
                            </button>
                            <button type="button" @click="selected = 'Kontrakan'; open = false" class="flex w-full items-center gap-2 px-4 py-2.5 text-left text-sm text-slate-600 hover:bg-sky-50 hover:text-[#0EA5E9]">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                Kontrakan
                            </button>
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div class="hidden h-8 w-px bg-slate-200 md:block"></div>

                    {{-- Input Lokasi --}}
                    <div class="relative flex-1">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <svg class="h-4 w-4 text-[#0EA5E9]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <input type="text" name="lokasi" placeholder="Cari lokasi atau nama properti..." class="w-full rounded-full border-0 bg-transparent py-3 pl-10 pr-4 text-sm text-slate-700 outline-none ring-0 placeholder:text-slate-400 focus:ring-0 md:py-3.5" id="hero-input-lokasi">
                    </div>

                    {{-- Divider --}}
                    <div class="hidden h-8 w-px bg-slate-200 md:block"></div>

                    {{-- Dropdown Harga --}}
                    <div class="relative md:w-44" x-data="{ open: false, selected: 'Rentang Harga' }" @click.outside="open = false">
                        <input type="hidden" name="harga" x-model="selected">
                        <button type="button" @click="open = !open" class="flex w-full items-center gap-2 rounded-full bg-slate-50 px-4 py-3 text-sm text-slate-500 transition hover:bg-slate-100 md:bg-transparent md:py-3.5" id="hero-dropdown-harga">
                            <svg class="h-4 w-4 flex-shrink-0 text-[#0EA5E9]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span x-text="selected" class="flex-1 text-left"></span>
                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-cloak x-transition class="absolute left-0 top-full z-20 mt-2 w-full overflow-hidden rounded-xl border border-gray-100 bg-white py-1 shadow-lg">
                            <button type="button" @click="selected = '< Rp 1 Juta'; open = false" class="block w-full px-4 py-2.5 text-left text-sm text-slate-600 hover:bg-sky-50 hover:text-[#0EA5E9]">&lt; Rp 1 Juta</button>
                            <button type="button" @click="selected = 'Rp 1 - 2 Juta'; open = false" class="block w-full px-4 py-2.5 text-left text-sm text-slate-600 hover:bg-sky-50 hover:text-[#0EA5E9]">Rp 1 - 2 Juta</button>
                            <button type="button" @click="selected = 'Rp 2 - 3 Juta'; open = false" class="block w-full px-4 py-2.5 text-left text-sm text-slate-600 hover:bg-sky-50 hover:text-[#0EA5E9]">Rp 2 - 3 Juta</button>
                            <button type="button" @click="selected = '> Rp 3 Juta'; open = false" class="block w-full px-4 py-2.5 text-left text-sm text-slate-600 hover:bg-sky-50 hover:text-[#0EA5E9]">&gt; Rp 3 Juta</button>
                        </div>
                    </div>

                    {{-- Tombol Cari --}}
                    <button type="submit" class="animate-pulse-glow flex items-center justify-center gap-2 rounded-full bg-[#0EA5E9] px-7 py-3 text-sm font-semibold text-white transition-all hover:bg-[#3B82F6] hover:shadow-lg md:py-3.5" id="hero-btn-cari">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Cari
                    </button>
                </div>
            </form>
        </div>

        {{-- Trust badges --}}
        <div class="mt-8 flex flex-wrap items-center justify-center gap-6 text-xs text-white/60">
            <span class="flex items-center gap-1.5">
                <svg class="h-4 w-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                Gratis & Tanpa Biaya Admin
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="h-4 w-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                Properti Terverifikasi
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="h-4 w-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                Pembayaran Aman
            </span>
        </div>
    </div>
</section>
