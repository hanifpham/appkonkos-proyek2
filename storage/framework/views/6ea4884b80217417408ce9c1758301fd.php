<?php $__env->startSection('mitra-title', 'Transaksi Midtrans'); ?>
<?php $__env->startSection('mitra-subtitle', 'Pantau transaksi pembayaran penyewa, metode bayar, dan sinkron status Midtrans.'); ?>

<?php
    $filterMetodeOptions = [
        '' => 'Semua Metode',
        'gopay' => 'GoPay',
        'qris' => 'QRIS',
        'bank_transfer' => 'Virtual Account',
    ];
    $filterStatusOptions = [
        '' => 'Semua Status',
        'settlement' => 'Lunas',
        'pending' => 'Pending',
        'expire' => 'Gagal',
    ];
?>

<div class="flex-1 space-y-8 overflow-y-auto p-8">
    <section class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Pendapatan Platform</p>
                <span class="material-symbols-outlined text-2xl text-emerald-600 dark:text-emerald-400">trending_up</span>
            </div>
            <h3 class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">Rp <?php echo e(number_format($totalPendapatan, 0, ',', '.')); ?></h3>
            <p class="mt-2 text-[10px] font-medium uppercase tracking-tighter text-gray-400">Komisi Aplikasi Terkumpul</p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Transaksi Sukses</p>
                <span class="material-symbols-outlined text-2xl text-[#0F4C81] dark:text-blue-400">check_circle</span>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 dark:text-white"><?php echo e(number_format($totalSukses, 0, ',', '.')); ?></h3>
            <p class="mt-2 text-[10px] font-medium uppercase tracking-tighter text-gray-400">Pembayaran Berhasil</p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Mitra Pemilik Aktif</p>
                <span class="material-symbols-outlined text-2xl text-[#0F4C81] dark:text-blue-400">person</span>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 dark:text-white"><?php echo e(number_format($mitraAktif, 0, ',', '.')); ?></h3>
            <p class="mt-2 text-[10px] font-medium uppercase tracking-tighter text-gray-400">Mitra Terverifikasi</p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Pencairan Tertunda</p>
                <span class="material-symbols-outlined text-2xl text-orange-600 dark:text-orange-400">hourglass_top</span>
            </div>
            <h3 class="text-2xl font-bold text-orange-600 dark:text-orange-400"><?php echo e(number_format($pencairanTertunda, 0, ',', '.')); ?> Pengajuan</h3>
            <p class="mt-2 text-[10px] font-medium uppercase tracking-tighter text-gray-400">Perlu Persetujuan Segera</p>
        </div>
    </section>

    <div class="flex justify-end">
        <button
            type="button"
            wire:click="exportData"
            class="inline-flex items-center gap-2 rounded-xl bg-[#113C7A] px-4 py-3 text-sm font-bold text-white shadow-md transition-colors hover:bg-[#0d2f60]"
        >
            <span class="material-symbols-outlined text-[18px]">download</span>
            Ekspor Excel
        </button>
    </div>

    <section class="overflow-visible rounded-xl border border-gray-100 bg-white shadow-md dark:border-gray-700 dark:bg-slate-900">
        <div class="relative z-10 flex flex-col gap-4 border-b border-gray-100 bg-slate-50/50 p-6 dark:border-gray-700 dark:bg-slate-800/20 xl:flex-row xl:items-center xl:justify-between">
            <div class="flex items-center gap-4">
                <h3 class="flex items-center gap-2 text-lg font-bold text-gray-800 dark:text-white">
                    <span class="material-symbols-outlined text-[#0F4C81] dark:text-blue-400">receipt_long</span>
                    Live Transaksi Midtrans
                </h3>
                <div class="flex items-center gap-2 rounded-full border border-green-100 bg-green-50 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-green-600 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
                    <span class="h-2 w-2 animate-pulse rounded-full bg-green-500"></span>
                    Live Updates
                </div>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center xl:mr-5 xl:flex-nowrap xl:justify-end">
                <div x-data="{ open: false }" class="relative w-full sm:w-[190px]">
                    <button type="button" @click="open = ! open" @click.outside="open = false" class="flex h-11 w-full items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-blue-400 dark:hover:text-blue-300">
                        <span class="flex min-w-0 items-center gap-2">
                            <span class="material-symbols-outlined shrink-0 text-[18px]">payments</span>
                            <span class="truncate"><?php echo e($filterMetodeOptions[$filterMetode] ?? 'Semua Metode'); ?></span>
                        </span>
                        <span class="material-symbols-outlined shrink-0 text-[18px] text-slate-400 dark:text-slate-500">expand_more</span>
                    </button>
                    <div x-cloak x-show="open" x-transition.origin.top.right class="absolute right-0 top-[calc(100%+8px)] z-20 w-full min-w-[220px] overflow-hidden rounded-2xl border border-slate-200 bg-white p-1 shadow-xl dark:border-slate-700 dark:bg-slate-800">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $filterMetodeOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button type="button" wire:click="$set('filterMetode', '<?php echo e($value); ?>')" @click="open = false" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['flex w-full items-center justify-between rounded-xl px-4 py-3 text-left text-sm transition','bg-blue-50 font-semibold text-[#0F4C81] dark:bg-blue-500/10 dark:text-blue-300' => $filterMetode === $value,'text-slate-600 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-700/70' => $filterMetode !== $value]); ?>">
                                <span><?php echo e($label); ?></span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($filterMetode === $value): ?>
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
                <thead class="bg-gray-50 text-[11px] font-bold uppercase tracking-widest text-gray-600 dark:bg-slate-800 dark:text-gray-300">
                    <tr>
                        <th class="border-b border-gray-100 px-6 py-5 dark:border-gray-700">Order ID</th>
                        <th class="border-b border-gray-100 px-6 py-5 dark:border-gray-700">Penyewa</th>
                        <th class="border-b border-gray-100 px-6 py-5 dark:border-gray-700">Properti</th>
                        <th class="border-b border-gray-100 px-6 py-5 dark:border-gray-700">Pemilik Kos</th>
                        <th class="border-b border-gray-100 px-6 py-5 dark:border-gray-700">Nominal</th>
                        <th class="border-b border-gray-100 px-6 py-5 dark:border-gray-700">Metode Bayar</th>
                        <th class="border-b border-gray-100 px-6 py-5 dark:border-gray-700">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $transaksi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $metodeIcon = $this->getMetodeIcon($item);
                        ?>

                        <tr wire:key="midtrans-row-<?php echo e($item->id); ?>" class="transition hover:bg-blue-50/30 dark:hover:bg-slate-800/50">
                            <td class="whitespace-nowrap px-6 py-4 font-bold text-gray-800 dark:text-white">
                                <?php echo e($this->getOrderId($item)); ?>

                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-700 dark:text-gray-300"><?php echo e($this->getPenyewaName($item)); ?></span>
                            </td>
                            <td class="px-6 py-4 italic text-gray-500 dark:text-gray-400"><?php echo e($this->getNamaProperti($item)); ?></td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300"><?php echo e($this->getNamaPemilik($item)); ?></td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="text-lg font-bold text-emerald-600 dark:text-emerald-400">Rp <?php echo e(number_format((int) $item->nominal_bayar, 0, ',', '.')); ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="<?php echo e($metodeIcon['wrapper']); ?> flex h-7 w-7 items-center justify-center rounded-md">
                                        <span class="material-symbols-outlined <?php echo e($metodeIcon['iconClass']); ?> text-[16px]"><?php echo e($metodeIcon['icon']); ?></span>
                                    </div>
                                    <span class="text-sm font-bold text-gray-700 dark:text-gray-300"><?php echo e($this->getMetodeLabel($item)); ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="<?php echo e($this->getStatusBadgeClasses($item)); ?> rounded-full px-3 py-1 text-[10px] font-bold uppercase">
                                        <?php echo e($this->getStatusLabel($item)); ?>

                                    </span>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->canSync($item)): ?>
                                        <button
                                            type="button"
                                            wire:click="syncMidtrans('<?php echo e($item->midtrans_order_id); ?>')"
                                            wire:loading.attr="disabled"
                                            wire:target="syncMidtrans"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-amber-200 bg-amber-50 text-amber-700 transition hover:bg-amber-100 disabled:cursor-not-allowed disabled:opacity-60 dark:border-amber-800 dark:bg-amber-900/30 dark:text-amber-300"
                                            title="Sinkronisasi status dari Midtrans"
                                        >
                                            <span
                                                class="material-symbols-outlined text-[18px]"
                                                wire:loading.class="animate-spin"
                                                wire:target="syncMidtrans"
                                            >
                                                sync
                                            </span>
                                        </button>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                Belum ada transaksi Midtrans yang sesuai dengan filter.
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 bg-slate-50 p-6 dark:border-gray-700 dark:bg-slate-800/40">
            <?php echo e($transaksi->links()); ?>

        </div>
    </section>
</div>
<?php /**PATH C:\xampp\htdocs\appkonkos-proyek2\resources\views/livewire/superadmin/transaksi-midtrans.blade.php ENDPATH**/ ?>