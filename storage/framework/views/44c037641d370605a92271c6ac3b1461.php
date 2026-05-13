
<section class="bg-[#F8FAFC] py-16" id="area-populer">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-2xl font-bold text-[#090a0b] sm:text-3xl">Area Kos & Kontrakan Terpopuler</h2>
            <p class="mt-2 text-sm text-[#6b7280]">Temukan hunian di kota dan area favorit pengguna kami</p>
        </div>

        <div class="mt-10 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            <?php
                $areas = [
                    ['nama' => 'Yogyakarta', 'label' => 'Kos Yogyakarta', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9e/Yogyakarta_Indonesia_Kraton-the-Sultans-Palace-02.jpg/330px-Yogyakarta_Indonesia_Kraton-the-Sultans-Palace-02.jpg'],
                    ['nama' => 'Jakarta', 'label' => 'Kos Jakarta', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/bf/Busway_in_Bundaran_HI.jpg/330px-Busway_in_Bundaran_HI.jpg'],
                    ['nama' => 'Bandung', 'label' => 'Kos Bandung', 'image' => 'https://upload.wikimedia.org/wikipedia/id/thumb/8/80/Jembatan_layang_pasupati.jpeg/330px-Jembatan_layang_pasupati.jpeg'],
                    ['nama' => 'Surabaya', 'label' => 'Kos Surabaya', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/da/Jembatan_suroboyo_dusk.jpg/330px-Jembatan_suroboyo_dusk.jpg'],
                    ['nama' => 'Malang', 'label' => 'Kos Malang', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/08/Gajayana_Stadium_4.jpg/330px-Gajayana_Stadium_4.jpg'],
                    ['nama' => 'Semarang', 'label' => 'Kos Semarang', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7e/Lawang_Sewu_in_Semarang_City.jpg/330px-Lawang_Sewu_in_Semarang_City.jpg'],
                    ['nama' => 'Medan', 'label' => 'Kos Medan', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/2d/Great_mosque_in_Medan_cropped.jpg/330px-Great_mosque_in_Medan_cropped.jpg'],
                    ['nama' => 'Cirebon', 'label' => 'Kos Cirebon', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/68/Balaikota_Cirebon_%281%29.jpg/330px-Balaikota_Cirebon_%281%29.jpg'],
                    ['nama' => 'Indramayu', 'label' => 'Kos Indramayu', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/48/Karangsong_beach_in_Indramayu%2C_West_Java.jpg/330px-Karangsong_beach_in_Indramayu%2C_West_Java.jpg'],
                    ['nama' => 'Depok', 'label' => 'Kos Depok', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/86/No_18_Rektorat_Universitas_Indonesia.jpg/330px-No_18_Rektorat_Universitas_Indonesia.jpg'],
                    ['nama' => 'Bogor', 'label' => 'Kos Bogor', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/40/Naruhito_and_Masako_visit_Bogor_Palace_56.jpg/330px-Naruhito_and_Masako_visit_Bogor_Palace_56.jpg'],
                ];
            ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('cari', ['lokasi' => $area['nama']])); ?>" class="group relative overflow-hidden rounded-2xl bg-slate-200 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md h-32 sm:h-40" id="area-<?php echo e(Str::slug($area['nama'])); ?>">
                    <img src="<?php echo e($area['image']); ?>" alt="<?php echo e($area['label']); ?>" class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#090a0b]/80 via-[#090a0b]/20 to-transparent"></div>
                    <div class="absolute bottom-4 left-0 right-0 px-4 text-center">
                        <span class="text-base font-bold text-white sm:text-lg"><?php echo e($area['label']); ?></span>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <a href="<?php echo e(route('cari')); ?>" class="group flex items-center justify-center rounded-2xl bg-white shadow-sm ring-1 ring-[#e5e7eb] transition-all duration-300 hover:ring-2 hover:ring-[#1967d2] hover:shadow-md h-32 sm:h-40" id="area-lihat-semua">
                <span class="text-base font-bold text-[#090a0b] transition group-hover:text-[#1967d2]">Lihat semua &rarr;</span>
            </a>
        </div>
    </div>
</section>
<?php /**PATH C:\xampp\htdocs\appkonkos-proyek2\resources\views/public/partials/area.blade.php ENDPATH**/ ?>