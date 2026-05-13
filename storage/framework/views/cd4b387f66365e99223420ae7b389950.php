
<style>
@keyframes float-hero {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-15px); }
}
.animate-float-hero {
    animation: float-hero 6s ease-in-out infinite;
}
.animate-float-hero-delayed {
    animation: float-hero 8s ease-in-out infinite reverse;
}
.animate-float-hero-slow {
    animation: float-hero 10s ease-in-out infinite;
}
</style>

<section class="relative flex min-h-[600px] flex-col items-center justify-center overflow-visible bg-gradient-to-b from-[#f4f7fb] to-white pt-24 pb-20" id="hero-section">
    
    
    <div class="pointer-events-none absolute inset-0 overflow-hidden">
        
        <svg class="absolute -left-10 top-20 w-48 text-blue-200/40 animate-float-hero" fill="currentColor" viewBox="0 0 24 24"><path d="M17.5 19c2.485 0 4.5-2.015 4.5-4.5S19.985 10 17.5 10c-.17 0-.337.013-.5.038C16.402 7.086 13.916 5 11 5 7.686 5 5 7.686 5 11c0 .248.015.492.044.73C2.793 12.186 1 14.153 1 16.5 1 19.538 3.462 22 6.5 22h11z"/></svg>
        
        
        <svg class="absolute right-10 top-32 w-32 text-blue-200/50 animate-float-hero-delayed" fill="currentColor" viewBox="0 0 24 24"><path d="M17.5 19c2.485 0 4.5-2.015 4.5-4.5S19.985 10 17.5 10c-.17 0-.337.013-.5.038C16.402 7.086 13.916 5 11 5 7.686 5 5 7.686 5 11c0 .248.015.492.044.73C2.793 12.186 1 14.153 1 16.5 1 19.538 3.462 22 6.5 22h11z"/></svg>

        
        <svg class="absolute left-32 bottom-40 w-24 text-blue-200/30 animate-float-hero-slow" fill="currentColor" viewBox="0 0 24 24"><path d="M17.5 19c2.485 0 4.5-2.015 4.5-4.5S19.985 10 17.5 10c-.17 0-.337.013-.5.038C16.402 7.086 13.916 5 11 5 7.686 5 5 7.686 5 11c0 .248.015.492.044.73C2.793 12.186 1 14.153 1 16.5 1 19.538 3.462 22 6.5 22h11z"/></svg>

        
        <div class="absolute bottom-0 left-0 lg:left-10 hidden md:flex items-end opacity-90">
            <div class="h-32 w-20 rounded-tr-xl bg-gradient-to-t from-blue-100 to-blue-50 border-r border-t border-white shadow-[inset_0_2px_4px_rgba(255,255,255,0.8)] z-0"></div>
            <div class="h-48 w-28 rounded-t-xl bg-gradient-to-t from-blue-200 to-blue-100 border-r border-l border-t border-white shadow-[inset_0_2px_4px_rgba(255,255,255,0.8)] -ml-4 z-10">
                <div class="grid grid-cols-3 gap-2 p-4 pt-6">
                    <div class="h-6 w-full bg-white/60 rounded-sm"></div>
                    <div class="h-6 w-full bg-white/60 rounded-sm"></div>
                    <div class="h-6 w-full bg-white/60 rounded-sm"></div>
                    <div class="h-6 w-full bg-white/60 rounded-sm"></div>
                    <div class="h-6 w-full bg-white/60 rounded-sm"></div>
                    <div class="h-6 w-full bg-white/60 rounded-sm"></div>
                </div>
            </div>
            <div class="h-24 w-16 rounded-tl-xl bg-gradient-to-t from-slate-100 to-white border-l border-t border-white shadow-[inset_0_2px_4px_rgba(255,255,255,0.8)] -ml-4 z-20"></div>
        </div>

        
        <div class="absolute bottom-0 right-0 lg:right-10 hidden md:flex items-end opacity-90">
            <div class="h-40 w-24 rounded-tr-xl bg-gradient-to-t from-blue-100 to-white border-r border-t border-white shadow-[inset_0_2px_4px_rgba(255,255,255,0.8)] z-0"></div>
            <div class="h-32 w-20 rounded-t-xl bg-gradient-to-t from-blue-200 to-blue-50 border-r border-l border-t border-white shadow-[inset_0_2px_4px_rgba(255,255,255,0.8)] -ml-6 z-20">
                <div class="grid grid-cols-2 gap-3 p-4 pt-6">
                    <div class="h-6 w-full bg-white/60 rounded-sm"></div>
                    <div class="h-6 w-full bg-white/60 rounded-sm"></div>
                    <div class="h-6 w-full bg-white/60 rounded-sm"></div>
                    <div class="h-6 w-full bg-white/60 rounded-sm"></div>
                </div>
            </div>
            <div class="h-56 w-28 rounded-tl-xl bg-gradient-to-t from-[#1967d2]/10 to-blue-100 border-l border-t border-white shadow-[inset_0_2px_4px_rgba(255,255,255,0.8)] -ml-4 z-10">
                <div class="grid grid-cols-3 gap-2 p-5 pt-8">
                    <div class="h-5 w-full bg-white/50 rounded-sm"></div>
                    <div class="h-5 w-full bg-white/50 rounded-sm"></div>
                    <div class="h-5 w-full bg-white/50 rounded-sm"></div>
                    <div class="h-5 w-full bg-white/50 rounded-sm"></div>
                    <div class="h-5 w-full bg-white/50 rounded-sm"></div>
                    <div class="h-5 w-full bg-white/50 rounded-sm"></div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="relative z-30 mx-auto w-full max-w-7xl px-4 text-center sm:px-6 lg:px-8">
        
        <h1 class="mx-auto max-w-4xl text-3xl font-extrabold leading-tight tracking-tight text-slate-900 sm:text-4xl md:text-5xl lg:text-5xl">
            Temukan <span class="text-[#1967d2]">Hunian Nyaman</span>,
            <br class="hidden sm:block">
            Transaksi <span class="text-[#1967d2]">Aman & Mudah</span>
        </h1>

        
        <p class="mx-auto mt-4 max-w-2xl text-base leading-relaxed text-slate-600 sm:text-lg md:text-xl">
            Cari kos dan kontrakan terbaik di seluruh Indonesia dengan mudah, cepat, dan terpercaya.
        </p>

        
        <div class="relative mx-auto mt-10 max-w-4xl">
            <form action="<?php echo e(route('cari')); ?>" method="GET" class="hero-search-card rounded-full bg-white p-2 shadow-[0_20px_50px_rgba(15,79,181,0.12)] md:p-2.5">
                <div class="flex flex-col gap-2 md:flex-row md:items-center">

                    
                    <div class="relative md:w-auto md:min-w-[11rem]" x-data="{ open: false, selected: 'Semua Tipe', icon: 'all' }" @click.outside="open = false">
                        <input type="hidden" name="tipe" x-model="selected">
                        <button type="button" @click="open = !open" class="hero-search-trigger flex w-full items-center gap-2 rounded-full bg-slate-50 px-4 py-3 text-sm text-slate-600 transition hover:bg-slate-100 md:py-3.5" id="hero-dropdown-tipe">
                            <svg class="h-4 w-4 flex-shrink-0 text-[#1967d2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span x-text="selected" class="flex-1 whitespace-nowrap text-left"></span>
                            <svg class="h-3.5 w-3.5 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-cloak x-transition class="hero-search-menu absolute left-0 top-full z-50 mt-2 w-full overflow-hidden rounded-xl border border-gray-100 bg-white py-1 shadow-lg">
                            <button type="button" @click="selected = 'Semua Tipe'; open = false" class="hero-search-option flex w-full items-center gap-2 px-4 py-2.5 text-left text-sm text-[#090a0b] hover:bg-sky-50 hover:text-[#1967d2]">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                Semua Tipe
                            </button>
                            <button type="button" @click="selected = 'Kos'; open = false" class="hero-search-option flex w-full items-center gap-2 px-4 py-2.5 text-left text-sm text-[#090a0b] hover:bg-sky-50 hover:text-[#1967d2]">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
                                Kos
                            </button>
                            <button type="button" @click="selected = 'Kontrakan'; open = false" class="hero-search-option flex w-full items-center gap-2 px-4 py-2.5 text-left text-sm text-[#090a0b] hover:bg-sky-50 hover:text-[#1967d2]">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                Kontrakan
                            </button>
                        </div>
                    </div>

                    
                    <div class="hero-search-divider hidden h-8 w-px bg-slate-200 md:block"></div>

                    
                    <div class="relative flex-1">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <svg class="hero-search-icon h-4 w-4 text-[#1967d2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <input type="text" name="lokasi" placeholder="Cari lokasi atau nama properti..." class="w-full rounded-full border-0 bg-transparent py-3 pl-10 pr-4 text-sm text-slate-700 outline-none ring-0 placeholder:text-slate-400 focus:ring-0 md:py-3.5" id="hero-input-lokasi">
                    </div>

                    
                    <div class="hero-search-divider hidden h-8 w-px bg-slate-200 md:block"></div>

                    
                    <div class="relative md:w-auto md:min-w-[11rem]" x-data="{ open: false, selected: 'Rentang Harga' }" @click.outside="open = false">
                        <input type="hidden" name="harga" x-model="selected">
                        <button type="button" @click="open = !open" class="hero-search-trigger flex w-full items-center gap-2 rounded-full bg-slate-50 px-4 py-3 text-sm text-slate-500 transition hover:bg-slate-100 md:bg-transparent md:py-3.5" id="hero-dropdown-harga">
                            <svg class="h-4 w-4 flex-shrink-0 text-[#1967d2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span x-text="selected" class="flex-1 whitespace-nowrap text-left"></span>
                            <svg class="h-3.5 w-3.5 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-cloak x-transition class="hero-search-menu absolute left-0 top-full z-50 mt-2 w-full overflow-hidden rounded-xl border border-gray-100 bg-white py-1 shadow-lg">
                            <button type="button" @click="selected = 'Semua Harga'; open = false" class="hero-search-option block w-full px-4 py-2.5 text-left text-sm text-[#090a0b] hover:bg-sky-50 hover:text-[#1967d2]">Semua Harga</button>
                            <button type="button" @click="selected = '< Rp 1 Juta'; open = false" class="hero-search-option block w-full px-4 py-2.5 text-left text-sm text-[#090a0b] hover:bg-sky-50 hover:text-[#1967d2]">&lt; Rp 1 Juta</button>
                            <button type="button" @click="selected = 'Rp 1 - 3 Juta'; open = false" class="hero-search-option block w-full px-4 py-2.5 text-left text-sm text-[#090a0b] hover:bg-sky-50 hover:text-[#1967d2]">Rp 1 - 3 Juta</button>
                            <button type="button" @click="selected = 'Rp 3 - 5 Juta'; open = false" class="hero-search-option block w-full px-4 py-2.5 text-left text-sm text-[#090a0b] hover:bg-sky-50 hover:text-[#1967d2]">Rp 3 - 5 Juta</button>
                            <button type="button" @click="selected = 'Rp 5 - 10 Juta'; open = false" class="hero-search-option block w-full px-4 py-2.5 text-left text-sm text-[#090a0b] hover:bg-sky-50 hover:text-[#1967d2]">Rp 5 - 10 Juta</button>
                            <button type="button" @click="selected = 'Rp 10 - 20 Juta'; open = false" class="hero-search-option block w-full px-4 py-2.5 text-left text-sm text-[#090a0b] hover:bg-sky-50 hover:text-[#1967d2]">Rp 10 - 20 Juta</button>
                            <button type="button" @click="selected = '> Rp 20 Juta'; open = false" class="hero-search-option block w-full px-4 py-2.5 text-left text-sm text-[#090a0b] hover:bg-sky-50 hover:text-[#1967d2]">&gt; Rp 20 Juta</button>
                        </div>
                    </div>

                    
                    <button type="submit" class="flex items-center justify-center gap-2 rounded-full bg-[#1967d2] px-7 py-3 text-sm font-semibold text-white transition-all hover:bg-[#0f4fb5] hover:shadow-lg md:py-3.5" id="hero-btn-cari">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Cari
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
<?php /**PATH C:\xampp\htdocs\appkonkos-proyek2\resources\views/public/partials/hero.blade.php ENDPATH**/ ?>