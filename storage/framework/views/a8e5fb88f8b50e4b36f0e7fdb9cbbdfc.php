<?php $__env->startSection('mitra-title', 'Moderasi Properti'); ?>
<?php $__env->startSection('mitra-subtitle', 'Tinjau, verifikasi, dan tolak listing kosan maupun kontrakan dari seluruh mitra.'); ?>

<?php
    $filterTipeOptions = [
        '' => 'Semua Tipe',
        'kosan' => 'Kosan',
        'kontrakan' => 'Kontrakan',
    ];
    $filterStatusOptions = [
        '' => 'Semua Status',
        'pending' => 'Menunggu',
        'aktif' => 'Aktif',
        'ditolak' => 'Ditolak',
    ];
?>

<div class="flex-1 space-y-8 overflow-y-auto p-8">
    <section class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
        <div
            class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:shadow-md dark:border-gray-700 dark:bg-slate-900">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">Menunggu
                    Verifikasi</p>
                <span class="material-symbols-outlined text-[22px] leading-none text-orange-500">pending_actions</span>
            </div>
            <h3 class="text-3xl font-extrabold text-orange-500 dark:text-white"><?php echo e(number_format($menungguVerifikasi, 0, ',', '.')); ?></h3>
            <p class="mt-2 text-[10px] font-medium uppercase tracking-tighter text-gray-400">Butuh Review Segera</p>
        </div>

        <div
            class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:shadow-md dark:border-gray-700 dark:bg-slate-900">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">Disetujui Hari
                    Ini</p>
                <span class="material-symbols-outlined text-[22px] leading-none text-emerald-500">verified</span>
            </div>
            <h3 class="text-3xl font-extrabold text-emerald-500 dark:text-white"><?php echo e(number_format($disetujuiHariIni, 0, ',', '.')); ?></h3>
            <p class="mt-2 text-[10px] font-medium uppercase tracking-tighter text-gray-400">Properti Live di App</p>
        </div>

        <div
            class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:shadow-md dark:border-gray-700 dark:bg-slate-900">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">Total Properti
                    Aktif</p>
                <span class="material-symbols-outlined text-[22px] leading-none text-[#113C7A] dark:text-blue-400">domain</span>
            </div>
            <h3 class="text-3xl font-extrabold text-black dark:text-white"><?php echo e(number_format($totalAktif, 0, ',', '.')); ?></h3>
            <p class="mt-2 text-[10px] font-medium uppercase tracking-tighter text-gray-400">Listing Terverifikasi</p>
        </div>

        <div
            class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:shadow-md dark:border-gray-700 dark:bg-slate-900">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">Properti Ditolak
                </p>
                <span class="material-symbols-outlined text-[22px] leading-none text-red-500">block</span>
            </div>
            <h3 class="text-3xl font-extrabold text-red-500 dark:text-white"><?php echo e(number_format($totalDitolak, 0, ',', '.')); ?></h3>
            <p class="mt-2 text-[10px] font-medium uppercase tracking-tighter text-gray-400">Perlu Tindak Lanjut</p>
        </div>
    </section>

    <div class="flex justify-end">
        <button
            type="button"
            wire:click="exportData"
            class="inline-flex items-center gap-2 rounded-xl bg-[#113C7A] px-4 py-3 text-sm font-bold text-white shadow-md transition-colors hover:bg-[#0d2f60]"
        >
            <span class="material-symbols-outlined text-[18px]">file_download</span>
            Ekspor Excel
        </button>
    </div>

    <section
        class="overflow-visible rounded-xl border border-gray-100 bg-white shadow-md dark:border-gray-700 dark:bg-slate-900">
        <div class="relative z-10 flex flex-col gap-4 border-b border-gray-200 p-6 dark:border-gray-700 xl:flex-row xl:items-center xl:justify-between">
            <h3 class="flex items-center gap-2 text-lg font-bold text-gray-800 dark:text-white">
                <span class="material-symbols-outlined text-[#113C7A] dark:text-blue-400">gavel</span>
                Antrean Moderasi Properti
            </h3>

            <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center xl:mr-5 xl:flex-nowrap xl:justify-end">
                <div class="relative w-full sm:w-[250px] xl:w-[220px]">
                    <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400 dark:text-slate-500">
                        <span class="material-symbols-outlined text-[18px]">search</span>
                    </span>
                    <input type="text" wire:model.live.debounce.300ms="search"
                        class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 py-2 pl-12 pr-4 text-sm text-slate-700 placeholder:text-slate-400 transition-all focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                        placeholder="Cari ID, Nama, Pemilik..." />
                </div>

                <div x-data="{ open: false }" class="relative w-full sm:w-[190px]">
                    <button type="button" @click="open = ! open" @click.outside="open = false" class="flex h-11 w-full items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-blue-400 dark:hover:text-blue-300">
                        <span class="flex min-w-0 items-center gap-2">
                            <span class="material-symbols-outlined shrink-0 text-[18px]">home_work</span>
                            <span class="truncate"><?php echo e($filterTipeOptions[$filterTipe] ?? 'Semua Tipe'); ?></span>
                        </span>
                        <span class="material-symbols-outlined shrink-0 text-[18px] text-slate-400 dark:text-slate-500">expand_more</span>
                    </button>
                    <div x-cloak x-show="open" x-transition.origin.top.right class="absolute right-0 top-[calc(100%+8px)] z-20 w-full min-w-[220px] overflow-hidden rounded-2xl border border-slate-200 bg-white p-1 shadow-xl dark:border-slate-700 dark:bg-slate-800">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $filterTipeOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button type="button" wire:click="$set('filterTipe', '<?php echo e($value); ?>')" @click="open = false" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['flex w-full items-center justify-between rounded-xl px-4 py-3 text-left text-sm transition','bg-blue-50 font-semibold text-[#0F4C81] dark:bg-blue-500/10 dark:text-blue-300' => $filterTipe === $value,'text-slate-600 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-700/70' => $filterTipe !== $value]); ?>">
                                <span><?php echo e($label); ?></span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($filterTipe === $value): ?>
                                    <span class="material-symbols-outlined text-[18px]">check</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <div x-data="{ open: false }" class="relative w-full sm:w-[190px]">
                    <button type="button" @click="open = ! open" @click.outside="open = false" class="flex h-11 w-full items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-blue-400 dark:hover:text-blue-300">
                        <span class="flex min-w-0 items-center gap-2">
                            <span class="material-symbols-outlined shrink-0 text-[18px]">filter_list</span>
                            <span class="truncate"><?php echo e($filterStatusOptions[$filterStatus] ?? 'Semua Status'); ?></span>
                        </span>
                        <span class="material-symbols-outlined shrink-0 text-[18px] text-slate-400 dark:text-slate-500">expand_more</span>
                    </button>
                    <div x-cloak x-show="open" x-transition.origin.top.right class="absolute right-0 top-[calc(100%+8px)] z-20 w-full min-w-[220px] overflow-hidden rounded-2xl border border-slate-200 bg-white p-1 shadow-xl dark:border-slate-700 dark:bg-slate-800">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $filterStatusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button type="button" wire:click="$set('filterStatus', '<?php echo e($value); ?>')" @click="open = false" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['flex w-full items-center justify-between rounded-xl px-4 py-3 text-left text-sm transition','bg-blue-50 font-semibold text-[#0F4C81] dark:bg-blue-500/10 dark:text-blue-300' => $filterStatus === $value,'text-slate-600 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-700/70' => $filterStatus !== $value]); ?>">
                                <span><?php echo e($label); ?></span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($filterStatus === $value): ?>
                                    <span class="material-symbols-outlined text-[18px]">check</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead
                    class="border-b border-gray-100 bg-gray-50/80 text-[10px] font-extrabold uppercase tracking-widest text-gray-500 dark:border-gray-700 dark:bg-slate-800/80 dark:text-gray-400">
                    <tr>
                        <th class="w-32 whitespace-nowrap border-b border-gray-100 px-4 py-4 dark:border-gray-700">ID Properti</th>
                        <th class="border-b border-gray-100 px-6 py-4 dark:border-gray-700">Detail Properti</th>
                        <th class="border-b border-gray-100 px-6 py-4 dark:border-gray-700">Pemilik (Mitra)</th>
                        <th class="whitespace-nowrap border-b border-gray-100 px-6 py-4 dark:border-gray-700">Kategori</th>
                        <th class="whitespace-nowrap border-b border-gray-100 px-6 py-4 text-right dark:border-gray-700">Harga Sewa</th>
                        <th class="border-b border-gray-100 px-6 py-4 text-center dark:border-gray-700">Status</th>
                        <th class="border-b border-gray-100 px-6 py-4 text-center dark:border-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($listProperti->count()): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $listProperti; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $properti): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="transition-colors hover:bg-blue-50/40 dark:hover:bg-slate-800/60" wire:key="properti-<?php echo e($properti->tipe); ?>-<?php echo e($properti->id); ?>">
                                <td class="whitespace-nowrap px-4 py-5 font-bold text-gray-900 dark:text-gray-100">
                                    PROP-<?php echo e(str_pad((string) $properti->id, 4, '0', STR_PAD_LEFT)); ?>

                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-[#113C7A] dark:text-blue-300">
                                            <?php echo e($properti->nama); ?>

                                        </span>
                                        <span
                                            class="mt-0.5 flex items-center gap-1 text-[11px] text-gray-500 dark:text-gray-400">
                                            <span class="material-symbols-outlined text-[13px]">location_on</span>
                                            <?php echo e($properti->alamat); ?>

                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-8 w-8 overflow-hidden rounded-full bg-blue-100 text-[10px] font-bold text-[#113C7A]">
                                            <img src="<?php echo e($this->getPemilikPhotoUrl($properti)); ?>" alt="<?php echo e($properti->nama_pemilik ?: 'User'); ?>" class="h-full w-full object-cover">
                                        </div>
                                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">
                                            <?php echo e($properti->nama_pemilik ?: '-'); ?>

                                        </span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-5">
                                    <span
                                        class="<?php echo e($this->getKategoriBadgeClasses($properti)); ?> rounded-md px-2 py-1 text-[10px] font-bold uppercase">
                                        <?php echo e($this->getKategoriLabel($properti)); ?>

                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-5 text-right">
                                    <div class="flex flex-col items-end">
                                        <span class="font-bold text-gray-900 dark:text-white">
                                            Rp <?php echo e(number_format((int) ($properti->harga ?? 0), 0, ',', '.')); ?>

                                        </span>
                                        <span class="text-[10px] text-gray-500"><?php echo e($this->getHargaSuffix($properti)); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span
                                        class="<?php echo e($this->getStatusBadgeClasses($properti->status)); ?> rounded-full px-3 py-1 text-[10px] font-bold uppercase">
                                        <?php echo e($this->getStatusLabel($properti->status)); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="<?php echo e(route('superadmin.moderasi.detail', ['tipe' => $properti->tipe, 'id' => $properti->id])); ?>"
                                            class="px-3 py-2 text-xs font-bold text-[#0F4C81] transition-colors hover:underline dark:text-blue-400">
                                            Detail
                                        </a>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($properti->status === 'aktif'): ?>
                                            <button type="button"
                                                wire:click="konfirmasiTakedown('<?php echo e($properti->tipe); ?>', <?php echo e($properti->id); ?>)"
                                                wire:loading.attr="disabled"
                                                class="text-red-500 hover:text-red-700 font-bold text-xs">
                                                Takedown
                                            </button>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->isPendingStatus($properti->status)): ?>
                                            <button type="button"
                                                wire:click="verifikasiProperti('<?php echo e($properti->tipe); ?>', <?php echo e($properti->id); ?>)"
                                                wire:loading.attr="disabled"
                                                class="rounded-lg bg-[#0F4C81] px-4 py-2 text-xs font-bold text-white shadow-sm transition-all hover:bg-[#0d3f6d]">
                                                Verifikasi
                                            </button>
                                            <button type="button"
                                                wire:click="konfirmasiTolak('<?php echo e($properti->tipe); ?>', <?php echo e($properti->id); ?>)"
                                                wire:loading.attr="disabled"
                                                class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-xs font-bold text-red-600 transition-colors hover:bg-red-600 hover:text-white">
                                                Tolak
                                            </button>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                Belum ada properti yang sesuai dengan pencarian.
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <div
            class="flex items-center justify-between border-t border-gray-100 bg-gray-50 p-4 dark:border-gray-700 dark:bg-slate-900">
            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">
                Menampilkan <?php echo e($listProperti->firstItem() ?? 0); ?> sampai <?php echo e($listProperti->lastItem() ?? 0); ?> dari
                <?php echo e(number_format($listProperti->total(), 0, ',', '.')); ?> properti
            </span>
            <div>
                <?php echo e($listProperti->links()); ?>

            </div>
        </div>
    </section>
</div>
<?php /**PATH C:\xampp\htdocs\appkonkos_2\resources\views/livewire/superadmin/moderasi-properti.blade.php ENDPATH**/ ?>