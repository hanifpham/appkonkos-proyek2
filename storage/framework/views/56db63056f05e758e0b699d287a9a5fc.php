<?php $__env->startSection('mitra-title', 'Detail Properti'); ?>
<?php $__env->startSection('mitra-subtitle', 'Lihat data properti dan pemilik sebelum mengambil keputusan moderasi.'); ?>

<?php
    $fotoProperti = $this->getPropertyPhotoUrl();
    $pemilik = $properti->pemilikProperti;
    $pemilikUser = $pemilik?->user;
?>

<div class="flex-1 space-y-6 overflow-y-auto p-8">
    <div class="flex flex-col gap-4 rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-[10px] font-extrabold uppercase tracking-widest text-gray-500 dark:text-gray-400">ID Properti <?php echo e($this->getPropertyDisplayId()); ?></p>
            <h3 class="mt-2 text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($properti->nama_properti); ?></h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400"><?php echo e($this->getKategoriLabel()); ?> · <?php echo e($this->getStatusLabel()); ?></p>
        </div>

        <a href="<?php echo e(route('superadmin.properti')); ?>" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-gray-700 dark:text-gray-200 dark:hover:border-blue-400 dark:hover:text-blue-300">
            <span class="material-symbols-outlined text-[18px] leading-none">arrow_back</span>
            Kembali
        </a>
    </div>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_380px]">
        <div class="space-y-6">
            <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-gray-700 dark:bg-slate-900">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fotoProperti !== ''): ?>
                    <img src="<?php echo e($fotoProperti); ?>" alt="<?php echo e($properti->nama_properti); ?>" class="h-80 w-full object-cover">
                <?php else: ?>
                    <div class="flex h-80 w-full items-center justify-center bg-slate-100 text-sm font-semibold text-slate-500 dark:bg-slate-950 dark:text-slate-400">
                        Foto properti belum tersedia
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
                <p class="text-[10px] font-extrabold uppercase tracking-widest text-gray-500 dark:text-gray-400">Data Properti</p>

                <dl class="mt-5 grid gap-4 md:grid-cols-2">
                    <div class="rounded-lg border border-gray-100 bg-gray-50 p-4 dark:border-gray-700 dark:bg-slate-950">
                        <dt class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Nama</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white"><?php echo e($properti->nama_properti); ?></dd>
                    </div>

                    <div class="rounded-lg border border-gray-100 bg-gray-50 p-4 dark:border-gray-700 dark:bg-slate-950">
                        <dt class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Kategori</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white"><?php echo e($this->getKategoriLabel()); ?></dd>
                    </div>

                    <div class="rounded-lg border border-gray-100 bg-gray-50 p-4 dark:border-gray-700 dark:bg-slate-950">
                        <dt class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Harga</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white"><?php echo e($this->getHargaLabel()); ?></dd>
                    </div>

                    <div class="rounded-lg border border-gray-100 bg-gray-50 p-4 dark:border-gray-700 dark:bg-slate-950">
                        <dt class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Status</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white"><?php echo e($this->getStatusLabel()); ?></dd>
                    </div>

                    <div class="rounded-lg border border-gray-100 bg-gray-50 p-4 dark:border-gray-700 dark:bg-slate-950">
                        <dt class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Koordinat</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white"><?php echo e($this->getCoordinateLabel()); ?></dd>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tipe === 'kosan'): ?>
                        <div class="rounded-lg border border-gray-100 bg-gray-50 p-4 dark:border-gray-700 dark:bg-slate-950">
                            <dt class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Kamar</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white"><?php echo e($this->getKamarSummary()); ?></dd>
                        </div>
                    <?php else: ?>
                        <div class="rounded-lg border border-gray-100 bg-gray-50 p-4 dark:border-gray-700 dark:bg-slate-950">
                            <dt class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Unit Tersedia</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white"><?php echo e(number_format((int) $properti->sisa_kamar, 0, ',', '.')); ?> Unit</dd>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </dl>

                <div class="mt-4 rounded-lg border border-gray-100 bg-gray-50 p-4 dark:border-gray-700 dark:bg-slate-950">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Alamat Lengkap</p>
                    <p class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-200"><?php echo e($properti->alamat_lengkap); ?></p>
                </div>
            </section>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tipe === 'kosan'): ?>
                <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-gray-500 dark:text-gray-400">Peraturan Kos</p>
                    <p class="mt-3 whitespace-pre-line text-sm leading-6 text-gray-700 dark:text-gray-200"><?php echo e($properti->peraturan_kos ?: '-'); ?></p>
                </section>

                <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-[10px] font-extrabold uppercase tracking-widest text-gray-500 dark:text-gray-400">Tipe & Kamar</p>
                            <h4 class="mt-2 text-lg font-bold text-gray-900 dark:text-white">Daftar Tipe Kamar</h4>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500 dark:bg-slate-800 dark:text-slate-300"><?php echo e($properti->tipeKamar->count()); ?> Tipe</span>
                    </div>

                    <div class="mt-5 space-y-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $properti->tipeKamar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipeKamar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $fotoInterior = $this->getInteriorPhotoUrl($tipeKamar);
                            ?>

                            <article class="overflow-hidden rounded-xl border border-gray-100 bg-gray-50 dark:border-gray-700 dark:bg-slate-950">
                                <div class="grid gap-0 md:grid-cols-[220px_minmax(0,1fr)]">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fotoInterior !== ''): ?>
                                        <img src="<?php echo e($fotoInterior); ?>" alt="<?php echo e($tipeKamar->nama_tipe); ?>" class="h-48 w-full object-cover md:h-full">
                                    <?php else: ?>
                                        <div class="flex h-48 w-full items-center justify-center bg-slate-100 text-xs font-semibold text-slate-400 dark:bg-slate-900 dark:text-slate-500 md:h-full">
                                            Foto interior belum tersedia
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <div class="p-4">
                                        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                            <div>
                                                <h5 class="text-base font-bold text-gray-900 dark:text-white"><?php echo e($tipeKamar->nama_tipe); ?></h5>
                                                <p class="mt-1 text-sm font-semibold text-[#0F4C81] dark:text-blue-300">Rp <?php echo e(number_format((int) $tipeKamar->harga_per_bulan, 0, ',', '.')); ?> / bulan</p>
                                            </div>
                                            <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-500 dark:bg-slate-800 dark:text-slate-300"><?php echo e($tipeKamar->kamar->count()); ?> Kamar</span>
                                        </div>

                                        <div class="mt-4">
                                            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Fasilitas Tipe</p>
                                            <p class="mt-1 whitespace-pre-line text-sm leading-6 text-gray-700 dark:text-gray-200"><?php echo e($tipeKamar->fasilitas_tipe ?: '-'); ?></p>
                                        </div>

                                        <div class="mt-4">
                                            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Nomor Kamar</p>
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_2 = true; $__currentLoopData = $tipeKamar->kamar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kamar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                    <span class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                                                        <?php echo e($kamar->nomor_kamar); ?> · <?php echo e(ucfirst((string) $kamar->status_kamar)); ?>

                                                    </span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Belum ada nomor kamar.</span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="rounded-xl border border-dashed border-gray-200 px-5 py-10 text-center text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400">
                                Belum ada tipe kamar yang didaftarkan.
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </section>
            <?php else: ?>
                <section class="grid gap-6 md:grid-cols-2">
                    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
                        <p class="text-[10px] font-extrabold uppercase tracking-widest text-gray-500 dark:text-gray-400">Fasilitas Kontrakan</p>
                        <p class="mt-3 whitespace-pre-line text-sm leading-6 text-gray-700 dark:text-gray-200"><?php echo e($properti->fasilitas ?: '-'); ?></p>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
                        <p class="text-[10px] font-extrabold uppercase tracking-widest text-gray-500 dark:text-gray-400">Peraturan Kontrakan</p>
                        <p class="mt-3 whitespace-pre-line text-sm leading-6 text-gray-700 dark:text-gray-200"><?php echo e($properti->peraturan_kontrakan ?: '-'); ?></p>
                    </div>
                </section>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <aside class="space-y-6">
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
                <p class="text-[10px] font-extrabold uppercase tracking-widest text-gray-500 dark:text-gray-400">Pemilik</p>

                <div class="mt-5 flex items-center gap-3">
                    <div class="h-12 w-12 overflow-hidden rounded-full bg-blue-100">
                        <img src="<?php echo e($this->getPemilikPhotoUrl()); ?>" alt="<?php echo e($pemilikUser?->name ?? 'User'); ?>" class="h-full w-full object-cover">
                    </div>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-bold text-gray-900 dark:text-white"><?php echo e($pemilikUser?->name ?? '-'); ?></p>
                        <p class="truncate text-xs text-gray-500 dark:text-gray-400"><?php echo e($pemilikUser?->email ?? '-'); ?></p>
                    </div>
                </div>

                <dl class="mt-5 space-y-3">
                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Telepon</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white"><?php echo e($pemilikUser?->no_telepon ?? '-'); ?></dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Alamat Domisili</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-200"><?php echo e($pemilik?->alamat_domisili ?? '-'); ?></dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Bank</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white"><?php echo e($pemilik?->nama_bank ?? '-'); ?></dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-widest text-gray-400">No. Rekening</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white"><?php echo e($pemilik?->nomor_rekening ?? '-'); ?></dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Status Verifikasi</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white"><?php echo e(strtoupper((string) ($pemilik?->status_verifikasi ?? '-'))); ?></dd>
                    </div>
                </dl>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
                <p class="text-[10px] font-extrabold uppercase tracking-widest text-gray-500 dark:text-gray-400">Waktu Data</p>
                <dl class="mt-4 space-y-3">
                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Dibuat</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white"><?php echo e($properti->created_at?->locale('id')->translatedFormat('d M Y, H:i') ?? '-'); ?></dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Diperbarui</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white"><?php echo e($properti->updated_at?->locale('id')->translatedFormat('d M Y, H:i') ?? '-'); ?></dd>
                    </div>
                </dl>
            </section>
        </aside>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\appkonkos_2\resources\views/livewire/superadmin/detail-moderasi-properti.blade.php ENDPATH**/ ?>