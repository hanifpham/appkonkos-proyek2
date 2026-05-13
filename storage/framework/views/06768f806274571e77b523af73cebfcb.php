
<footer class="relative mt-20 overflow-hidden border-t border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-950" id="footer">
    
    <div class="pointer-events-none absolute -right-24 -top-24 h-96 w-96 rounded-full bg-blue-50/50 blur-3xl dark:bg-blue-900/10"></div>
    <div class="pointer-events-none absolute -bottom-24 -left-24 h-96 w-96 rounded-full bg-indigo-50/50 blur-3xl dark:bg-indigo-900/10"></div>

    <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-12 sm:grid-cols-2 lg:grid-cols-4">
            
            <div class="space-y-6">
                <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-2.5">
                    <div class="flex h-11 w-11 items-center justify-center overflow-hidden rounded-full bg-white shadow-md shadow-blue-500/15 ring-2 ring-[#1967d2]/20 transition-all duration-300 group-hover:scale-105 group-hover:shadow-blue-500/30 group-hover:ring-[#1967d2]/40">
                        <img src="<?php echo e(asset('images/appkonkos.png')); ?>" alt="<?php echo e(config('app.name', 'APPKONKOS')); ?>" class="h-9 w-9 object-contain">
                    </div>
                    <span class="text-xl font-black tracking-tight text-slate-900 dark:text-white">appkonkos</span>
                </a>
                <p class="text-sm font-medium leading-relaxed text-slate-500 dark:text-slate-400">
                    Platform pencari kos dan kontrakan terpercaya yang menghubungkan ribuan penyewa dengan hunian impian mereka di seluruh Indonesia dengan mudah dan aman.
                </p>
                <div class="flex gap-3">
                    <?php
                    $socials = [
                    ['icon' => 'facebook', 'color' => '#1877F2', 'url' => '#'],
                    ['icon' => 'instagram', 'color' => '#E4405F', 'url' => '#'],
                    ['icon' => 'twitter', 'color' => '#1DA1F2', 'url' => '#'],
                    ['icon' => 'youtube', 'color' => '#FF0000', 'url' => '#'],
                    ];
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $socials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $social): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e($social['url']); ?>" class="group relative flex h-10 w-10 items-center justify-center rounded-full border border-slate-100 bg-white shadow-sm transition-all hover:-translate-y-1 hover:border-transparent hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900" aria-label="<?php echo e($social['icon']); ?>">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($social['icon'] === 'facebook'): ?>
                        <svg class="h-5 w-5 text-slate-400 transition-colors group-hover:text-[#1877F2]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                        <?php elseif($social['icon'] === 'instagram'): ?>
                        <svg class="h-5 w-5 text-slate-400 transition-colors group-hover:text-[#E4405F]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                        </svg>
                        <?php elseif($social['icon'] === 'twitter'): ?>
                        <svg class="h-4 w-4 text-slate-400 transition-colors group-hover:text-black dark:group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                        </svg>
                        <?php elseif($social['icon'] === 'youtube'): ?>
                        <svg class="h-5 w-5 text-slate-400 transition-colors group-hover:text-[#FF0000]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                        </svg>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            
            <div>
                <h4 class="text-xs font-black uppercase tracking-[0.2em] text-slate-900 dark:text-white">Fitur Sistem</h4>
                <ul class="mt-6 space-y-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['Cari Kos & Kontrakan', 'Manajemen Sewa', 'Sistem Booking Online', 'Ulasan & Rating']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <a href="#" class="group flex items-center text-sm font-bold text-slate-500 transition hover:text-[#1967d2] dark:text-slate-400 dark:hover:text-blue-400">
                            <span class="h-1.5 w-0 rounded-full bg-[#1967d2] transition-all group-hover:mr-2 group-hover:w-1.5"></span>
                            <?php echo e($item); ?>

                        </a>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </ul>
            </div>

            
            <div>
                <h4 class="text-xs font-black uppercase tracking-[0.2em] text-slate-900 dark:text-white">Bantuan & Kebijakan</h4>
                <ul class="mt-6 space-y-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                    ['label' => 'Pusat Bantuan', 'url' => route('pusat-bantuan')],
                    ['label' => 'Kebijakan Privasi', 'url' => '#'],
                    ['label' => 'Syarat & Ketentuan', 'url' => '#'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <a href="<?php echo e($item['url']); ?>" class="group flex items-center text-sm font-bold text-slate-500 transition hover:text-[#1967d2] dark:text-slate-400 dark:hover:text-blue-400">
                            <span class="h-1.5 w-0 rounded-full bg-[#1967d2] transition-all group-hover:mr-2 group-hover:w-1.5"></span>
                            <?php echo e($item['label']); ?>

                        </a>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </ul>
            </div>

            
            <div>
                <h4 class="text-xs font-black uppercase tracking-[0.2em] text-slate-900 dark:text-white">Hubungi Kami</h4>
                <div class="mt-6 space-y-5">
                    <a href="mailto:support@appkonkos.com" class="flex items-start gap-3 group">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-50 text-[#1967d2] transition-colors group-hover:bg-[#1967d2] group-hover:text-white dark:bg-blue-900/30">
                            <span class="material-symbols-outlined text-lg">mail</span>
                        </div>
                        <div class="space-y-0.5">
                            <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Email Support</p>
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200">support@appkonkos.com</p>
                        </div>
                    </a>
                    <a href="https://wa.me/628120000000" class="flex items-start gap-3 group">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-green-50 text-green-600 transition-colors group-hover:bg-green-600 group-hover:text-white dark:bg-green-900/30">
                            <span class="material-symbols-outlined text-lg">call</span>
                        </div>
                        <div class="space-y-0.5">
                            <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">WhatsApp Center</p>
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200">+62 812-XXXX-XXXX</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    
    <div class="border-t border-slate-100 bg-slate-50 py-8 dark:border-slate-800 dark:bg-slate-900/50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-6 md:flex-row">
                <p class="text-sm font-bold text-slate-500 dark:text-slate-400">
                    &copy; <?php echo e(date('Y')); ?> <span class="text-[#1967d2]">APPKONKOS</span>. All rights reserved.
                </p>
                <div class="flex flex-wrap justify-center gap-x-8 gap-y-2">
                    <a href="#" class="text-xs font-bold text-slate-400 transition hover:text-[#1967d2]">Indonesian</a>
                    <span class="h-4 w-px bg-slate-200 dark:bg-slate-700"></span>
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                        <span class="text-xs font-bold text-slate-400">System Online</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer><?php /**PATH C:\xampp\htdocs\appkonkos-proyek2\resources\views/public/partials/footer.blade.php ENDPATH**/ ?>