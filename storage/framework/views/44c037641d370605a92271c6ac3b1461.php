
<section class="bg-[#F8FAFC] py-16" id="area-populer">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-2xl font-bold text-slate-900 sm:text-3xl">Area Kos & Kontrakan Terpopuler</h2>
            <p class="mt-2 text-sm text-slate-500">Temukan hunian di kota dan area favorit pengguna kami</p>
        </div>

        <div class="mt-10 flex flex-wrap justify-center gap-3">
            <?php
                $areas = [
                    'Jakarta Selatan', 'Jakarta Barat', 'Depok', 'Bogor', 'Tangerang Selatan',
                    'Bandung', 'Malang', 'Yogyakarta', 'Surabaya', 'Semarang',
                    'Solo', 'Bekasi', 'Makassar', 'Medan', 'Denpasar',
                ];
            ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('cari', ['lokasi' => $area])); ?>" class="rounded-full border border-gray-200 bg-white px-6 py-3 text-sm font-medium text-slate-700 shadow-sm transition-all duration-200 hover:border-[#0EA5E9] hover:text-[#0EA5E9] hover:shadow-md" id="area-<?php echo e(Str::slug($area)); ?>">
                    <?php echo e($area); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</section>
<?php /**PATH C:\xampp\htdocs\appkonkos-proyek2\resources\views/public/partials/area.blade.php ENDPATH**/ ?>