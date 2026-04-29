{{-- Promo & Tawaran Carousel - Auto-play with zoom --}}
<section class="relative z-10 -mt-4 px-4 sm:px-6 lg:px-8" id="promo-section"
    x-data="{
        current: 0,
        total: 3,
        autoplayInterval: null,
        startAutoplay() {
            this.autoplayInterval = setInterval(() => { this.next() }, 4000);
        },
        stopAutoplay() {
            clearInterval(this.autoplayInterval);
        },
        next() {
            this.current = (this.current + 1) % this.total;
        },
        prev() {
            this.current = (this.current - 1 + this.total) % this.total;
        }
    }"
    x-init="startAutoplay()"
    @mouseenter="stopAutoplay()"
    @mouseleave="startAutoplay()"
>
    <div class="mx-auto max-w-5xl">
        {{-- Carousel container --}}
        <div class="relative overflow-hidden rounded-2xl">
            <div class="flex transition-transform duration-700 ease-in-out" :style="'transform: translateX(-' + (current * 100) + '%)'">

                {{-- Banner 1: Daftarkan Properti --}}
                <div class="w-full flex-shrink-0 px-1">
                    <div class="relative h-44 overflow-hidden rounded-2xl bg-gradient-to-r from-[#0EA5E9] to-cyan-400 p-6 transition-transform duration-700 sm:h-52 sm:p-8"
                         :class="current === 0 ? 'scale-100' : 'scale-[0.92] opacity-80'">
                        <div class="absolute -right-8 -top-8 h-40 w-40 rounded-full bg-white/10"></div>
                        <div class="absolute -bottom-6 right-12 h-28 w-28 rounded-full bg-white/5"></div>
                        <div class="relative z-10 flex h-full flex-col justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-white/80">Untuk Pemilik Properti</p>
                                <h3 class="mt-2 text-xl font-bold text-white sm:text-2xl">Daftarkan Properti Anda</h3>
                                <p class="mt-1 max-w-xs text-sm text-white/80">Jangkau ribuan pencari kos dan kontrakan di seluruh Indonesia.</p>
                            </div>
                            <a href="#" class="inline-flex w-fit items-center gap-2 rounded-full bg-white px-5 py-2 text-sm font-semibold text-[#0EA5E9] shadow-sm transition hover:shadow-md" id="promo-btn-daftar">
                                Daftar Sekarang
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Banner 2: Promo Cashback --}}
                <div class="w-full flex-shrink-0 px-1">
                    <div class="relative h-44 overflow-hidden rounded-2xl bg-gradient-to-r from-[#3B82F6] to-indigo-500 p-6 transition-transform duration-700 sm:h-52 sm:p-8"
                         :class="current === 1 ? 'scale-100' : 'scale-[0.92] opacity-80'">
                        <div class="absolute -right-6 top-6 h-36 w-36 rounded-full bg-white/10"></div>
                        <div class="absolute -bottom-4 right-20 h-20 w-20 rounded-full bg-white/5"></div>
                        <div class="relative z-10 flex h-full flex-col justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-white/80">Promo Spesial</p>
                                <h3 class="mt-2 text-xl font-bold text-white sm:text-2xl">Cashback hingga Rp 500.000!</h3>
                                <p class="mt-1 max-w-xs text-sm text-white/80">Untuk booking pertama kamu di appkonkos. Syarat & ketentuan berlaku.</p>
                            </div>
                            <a href="#" class="inline-flex w-fit items-center gap-2 rounded-full bg-white px-5 py-2 text-sm font-semibold text-[#3B82F6] shadow-sm transition hover:shadow-md" id="promo-btn-cashback">
                                Klaim Sekarang
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Banner 3: Gratis Konsultasi --}}
                <div class="w-full flex-shrink-0 px-1">
                    <div class="relative h-44 overflow-hidden rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-400 p-6 transition-transform duration-700 sm:h-52 sm:p-8"
                         :class="current === 2 ? 'scale-100' : 'scale-[0.92] opacity-80'">
                        <div class="absolute -right-10 -top-10 h-44 w-44 rounded-full bg-white/10"></div>
                        <div class="relative z-10 flex h-full flex-col justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-white/80">Layanan Baru</p>
                                <h3 class="mt-2 text-xl font-bold text-white sm:text-2xl">Gratis Konsultasi Hunian</h3>
                                <p class="mt-1 max-w-xs text-sm text-white/80">Tim kami siap bantu kamu menemukan hunian ideal sesuai budget.</p>
                            </div>
                            <a href="#" class="inline-flex w-fit items-center gap-2 rounded-full bg-white px-5 py-2 text-sm font-semibold text-emerald-600 shadow-sm transition hover:shadow-md" id="promo-btn-konsultasi">
                                Hubungi Kami
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Navigation arrows --}}
            <button @click="prev()" class="absolute left-3 top-1/2 z-10 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-slate-700 shadow-md backdrop-blur-sm transition hover:bg-white hover:scale-110">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button @click="next()" class="absolute right-3 top-1/2 z-10 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-slate-700 shadow-md backdrop-blur-sm transition hover:bg-white hover:scale-110">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>

        {{-- Dot indicators --}}
        <div class="mt-4 flex items-center justify-center gap-2">
            <template x-for="i in total" :key="i">
                <button @click="current = i - 1" class="h-2 rounded-full transition-all duration-300" :class="current === i - 1 ? 'w-6 bg-[#0EA5E9]' : 'w-2 bg-slate-300 hover:bg-slate-400'"></button>
            </template>
        </div>
    </div>
</section>
