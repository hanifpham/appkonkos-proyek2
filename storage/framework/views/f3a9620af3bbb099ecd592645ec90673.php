
<nav x-data="{ mobileOpen: false, cariOpen: false }" class="fixed inset-x-0 top-0 z-50 border-b border-gray-100 bg-white/90 backdrop-blur-md">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">

        
        <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-2" id="nav-logo">
            <svg class="h-7 w-7 text-[#0EA5E9]" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="32" height="32" rx="8" fill="currentColor"/>
                <path d="M16 7L6 15h3v10h6v-6h2v6h6V15h3L16 7z" fill="white"/>
            </svg>
            <span class="text-sm font-bold tracking-tight text-slate-800">appkonkos</span>
        </a>

        
        <div class="hidden items-center gap-8 md:flex">
            <a href="<?php echo e(route('home')); ?>" class="text-sm font-semibold text-slate-800 transition hover:text-[#0EA5E9]" id="nav-beranda">Beranda</a>

            
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open" class="flex items-center gap-1 text-sm font-semibold text-slate-600 transition hover:text-[#0EA5E9]" id="nav-cari-apa">
                    Cari Apa?
                    <svg class="h-4 w-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1" class="absolute left-1/2 top-full mt-2 w-48 -translate-x-1/2 overflow-hidden rounded-xl border border-gray-100 bg-white py-1 shadow-lg">
                    <a href="<?php echo e(route('cari', ['tipe' => 'Kos'])); ?>" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 transition hover:bg-sky-50 hover:text-[#0EA5E9]" id="nav-cari-kos">
                        <svg class="h-5 w-5 text-[#0EA5E9]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
                        Cari Kos
                    </a>
                    <a href="<?php echo e(route('cari', ['tipe' => 'Kontrakan'])); ?>" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 transition hover:bg-sky-50 hover:text-[#0EA5E9]" id="nav-cari-kontrakan">
                        <svg class="h-5 w-5 text-[#0EA5E9]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Cari Kontrakan
                    </a>
                </div>
            </div>

            <a href="#" class="text-sm font-semibold text-slate-600 transition hover:text-[#0EA5E9]" id="nav-bantuan">Pusat Bantuan</a>
        </div>

        
        <div class="flex items-center gap-3">
            <button type="button" @click="$dispatch('open-login-modal')" class="hidden rounded-full bg-[#0EA5E9] px-6 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-[#3B82F6] hover:shadow-md md:inline-flex" id="nav-masuk">
                Masuk
            </button>
            <button @click="mobileOpen = !mobileOpen" class="rounded-lg p-2 text-slate-600 transition hover:bg-slate-100 md:hidden" id="nav-mobile-toggle">
                <svg x-show="!mobileOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="mobileOpen" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    
    <div x-show="mobileOpen" x-cloak x-transition class="border-t border-gray-100 bg-white px-4 pb-4 pt-2 md:hidden">
        <a href="<?php echo e(route('home')); ?>" class="block rounded-lg px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-sky-50">Beranda</a>
        <button @click="cariOpen = !cariOpen" class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-sky-50">
            Cari Apa?
            <svg class="h-4 w-4 transition-transform" :class="{ 'rotate-180': cariOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div x-show="cariOpen" x-cloak class="ml-4 space-y-1">
            <a href="<?php echo e(route('cari', ['tipe' => 'Kos'])); ?>" class="block rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-sky-50 hover:text-[#0EA5E9]">Cari Kos</a>
            <a href="<?php echo e(route('cari', ['tipe' => 'Kontrakan'])); ?>" class="block rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-sky-50 hover:text-[#0EA5E9]">Cari Kontrakan</a>
        </div>
        <a href="#" class="block rounded-lg px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-sky-50">Pusat Bantuan</a>
        <button type="button" @click="$dispatch('open-login-modal')" class="w-full mt-2 block rounded-full bg-[#0EA5E9] px-6 py-2.5 text-center text-sm font-semibold text-white transition-all hover:bg-[#3B82F6]">Masuk</button>
    </div>
</nav>

<?php echo $__env->make('public.partials.login-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH C:\xampp\htdocs\appkonkos_2\resources\views/public/partials/navbar.blade.php ENDPATH**/ ?>