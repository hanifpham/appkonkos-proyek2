{{-- Login Modal --}}
<div x-data="{ loginModalOpen: window.location.hash === '#login' }"
     @hashchange.window="loginModalOpen = window.location.hash === '#login'"
     @open-login-modal.window="loginModalOpen = true; window.history.pushState(null, null, '#login')"
     x-effect="if(!loginModalOpen && window.location.hash === '#login') { window.history.replaceState(null, null, ' '); }"
     x-show="loginModalOpen"
     x-cloak
     class="relative z-[100]"
     aria-labelledby="modal-title" role="dialog" aria-modal="true">
     
    {{-- Background overlay with animated blur --}}
    <div x-show="loginModalOpen" 
         x-transition:enter="ease-out duration-500"
         x-transition:enter-start="opacity-0 backdrop-blur-none"
         x-transition:enter-end="opacity-100 backdrop-blur-xl"
         x-transition:leave="ease-in duration-300"
         x-transition:leave-start="opacity-100 backdrop-blur-xl"
         x-transition:leave-end="opacity-0 backdrop-blur-none"
         class="fixed inset-0 z-40 bg-slate-900/40"></div>

    <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-12 sm:p-6"
         x-show="loginModalOpen"
         x-transition:enter="ease-out duration-500"
         x-transition:enter-start="opacity-0 translate-y-8 scale-90"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="ease-in duration-300"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95">
         
        <section @click.outside="loginModalOpen = false" class="relative w-full max-w-[640px] overflow-hidden rounded-[2.5rem] bg-white shadow-[0_20px_80px_rgba(0,0,0,0.2)]">
            
            {{-- Decorative Background Elements --}}
            <div class="pointer-events-none absolute -left-24 -top-24 h-64 w-64 rounded-full bg-gradient-to-br from-blue-400/20 to-sky-300/20 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 -right-24 h-64 w-64 rounded-full bg-gradient-to-br from-orange-400/20 to-amber-300/20 blur-3xl"></div>

            <div class="relative px-6 py-10 sm:px-12 sm:py-14">
                
                {{-- Close Button --}}
                <button
                    type="button"
                    @click="loginModalOpen = false"
                    class="absolute right-6 top-6 flex h-10 w-10 items-center justify-center rounded-full bg-slate-100/80 text-slate-500 transition-all hover:rotate-90 hover:bg-slate-200 hover:text-slate-800"
                    aria-label="Tutup"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>

                {{-- Header --}}
                <div class="text-center">
                    <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-br from-[#1967d2] to-sky-400 shadow-lg shadow-blue-500/30">
                        <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                    </div>
                    <h2 class="text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">
                        Selamat Datang!
                    </h2>
                    <p class="mt-3 text-base text-slate-500">
                        Pilih jenis akun untuk melanjutkan ke Appkonkos.
                    </p>
                </div>

                {{-- Cards Container --}}
                <div class="mt-10 grid gap-5 sm:grid-cols-2">
                    
                    {{-- Card Pencari --}}
                    <a
                        href="{{ route('auth.portal-login', 'pencari') }}"
                        class="group relative flex flex-col items-center rounded-3xl border border-slate-100 bg-white/80 backdrop-blur-sm p-6 text-center shadow-sm transition-all duration-300 hover:-translate-y-2 hover:border-blue-100 hover:bg-blue-50/50 hover:shadow-[0_20px_40px_-15px_rgba(25,103,210,0.15)]"
                    >
                        <div class="mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-100 text-[#1967d2] transition-colors duration-300 group-hover:bg-[#1967d2] group-hover:text-white">
                            <svg class="h-8 w-8 transition-transform duration-300 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 transition-colors group-hover:text-[#1967d2]">Pencari Kos</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-500">
                            Cari dan temukan hunian idamanmu dengan mudah.
                        </p>
                        
                        <div class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl bg-slate-50 py-2.5 text-sm font-semibold text-slate-600 transition-colors group-hover:bg-[#1967d2] group-hover:text-white">
                            Masuk <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                        </div>
                    </a>

                    {{-- Card Pemilik --}}
                    <a
                        href="{{ route('auth.portal-login', 'pemilik') }}"
                        class="group relative flex flex-col items-center rounded-3xl border border-slate-100 bg-white/80 backdrop-blur-sm p-6 text-center shadow-sm transition-all duration-300 hover:-translate-y-2 hover:border-orange-100 hover:bg-orange-50/50 hover:shadow-[0_20px_40px_-15px_rgba(255,138,0,0.15)]"
                    >
                        <div class="mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-orange-100 text-[#ff8a00] transition-colors duration-300 group-hover:bg-[#ff8a00] group-hover:text-white">
                            <svg class="h-8 w-8 transition-transform duration-300 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 transition-colors group-hover:text-[#ff8a00]">Pemilik / Admin</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-500">
                            Kelola properti dan penyewa dalam satu dashboard.
                        </p>

                        <div class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl bg-slate-50 py-2.5 text-sm font-semibold text-slate-600 transition-colors group-hover:bg-[#ff8a00] group-hover:text-white">
                            Masuk <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                        </div>
                    </a>
                </div>

                <div class="mt-8 text-center">
                    <a
                        href="{{ route('auth.portal-login', 'superadmin') }}"
                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400 transition hover:text-slate-700"
                    >
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                        Login Superadmin
                    </a>
                </div>
            </div>
        </section>
    </div>
</div>