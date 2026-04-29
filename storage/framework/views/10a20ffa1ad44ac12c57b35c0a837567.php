
<div x-data="{ loginModalOpen: window.location.hash === '#login' }"
     @hashchange.window="loginModalOpen = window.location.hash === '#login'"
     @open-login-modal.window="loginModalOpen = true; window.history.pushState(null, null, '#login')"
     x-effect="if(!loginModalOpen && window.location.hash === '#login') { window.history.replaceState(null, null, ' '); }"
     x-show="loginModalOpen"
     x-cloak
     class="relative z-[100]"
     aria-labelledby="modal-title" role="dialog" aria-modal="true">
     
    
    <div x-show="loginModalOpen" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-slate-900/30 backdrop-blur-md"></div>

    <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-12 sm:p-6"
         x-show="loginModalOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
         
        <section @click.outside="loginModalOpen = false" class="w-full max-w-[580px] rounded-[34px] bg-white/40 backdrop-blur-2xl border border-white/60 px-7 py-8 shadow-[0_34px_80px_rgba(15,23,42,0.2)] sm:px-10 sm:py-10">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-extrabold uppercase tracking-[0.24em] text-[#1967d2]">Masuk ke Appkonkos</p>
                    <h2 class="mt-7 text-[28px] font-extrabold leading-tight text-[#090a0b] sm:text-[34px]">
                        Pilih jenis akun untuk melanjutkan
                    </h2>
                </div>

                <button
                    type="button"
                    @click="loginModalOpen = false"
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-white/50 text-slate-900 transition hover:bg-white/80"
                    aria-label="Tutup"
                >
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="mt-9 space-y-5">
                <a
                    href="<?php echo e(route('auth.portal-login', 'pencari')); ?>"
                    class="group flex items-center gap-5 rounded-[26px] border border-white/50 bg-white/60 px-6 py-7 transition hover:border-[#1967d2]/35 hover:bg-white/90"
                >
                    <div class="flex h-[66px] w-[66px] shrink-0 items-center justify-center rounded-[20px] bg-[#e7f0ff] text-[#1967d2]">
                        <svg class="h-9 w-9" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>

                    <div class="min-w-0 flex-1">
                        <h3 class="text-[20px] font-extrabold leading-tight text-[#090a0b] sm:text-[21px]">Pencari Kos &amp; Kontrakan</h3>
                        <p class="mt-3 text-[15px] leading-7 text-[#6b7280] sm:text-[16px]">
                            Masuk untuk menemukan kos &amp; kontrakan
                        </p>
                    </div>
                </a>

                <a
                    href="<?php echo e(route('auth.portal-login', 'pemilik')); ?>"
                    class="group flex items-center gap-5 rounded-[26px] border border-white/50 bg-white/60 px-6 py-7 transition hover:border-[#f59e0b]/35 hover:bg-white/90"
                >
                    <div class="flex h-[66px] w-[66px] shrink-0 items-center justify-center rounded-[20px] bg-[#fff3df] text-[#ff8a00]">
                        <svg class="h-9 w-9" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                        </svg>
                    </div>

                    <div class="min-w-0 flex-1">
                        <h3 class="text-[20px] font-extrabold leading-tight text-[#090a0b] sm:text-[21px]">Admin Kos &amp; Kontrakan</h3>
                        <p class="mt-3 text-[15px] leading-7 text-[#6b7280] sm:text-[16px]">
                            Kelola listing kosan &amp; kontrakan
                        </p>
                    </div>
                </a>
            </div>

            <div class="mt-7 flex items-center justify-between gap-4">
                <p class="text-xs leading-6 text-slate-400">
                    Role superadmin tetap disembunyikan dari tampilan umum.
                </p>

                <a
                    href="<?php echo e(route('auth.portal-login', 'superadmin')); ?>"
                    class="rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-300 opacity-15 transition hover:opacity-100 focus:opacity-100"
                >
                    Superadmin
                </a>
            </div>
        </section>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\appkonkos_2\resources\views/public/partials/login-modal.blade.php ENDPATH**/ ?>