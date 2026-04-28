<?php $__env->startSection('mitra-title', 'Dashboard Utama'); ?>
<?php $__env->startSection('mitra-subtitle', 'Pantau pendapatan platform, transaksi Midtrans, mitra aktif, dan pencairan dana.'); ?>

<div class="flex-1 px-4 py-5 sm:px-6 sm:py-7 xl:px-8">
    <div class="mx-auto flex w-full max-w-[1220px] flex-col gap-8">
    <section class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">
                    Total Pendapatan Platform
                </p>
                <span class="material-symbols-outlined text-2xl text-emerald-600 dark:text-emerald-400">trending_up</span>
            </div>
            <h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-400">
                Rp <?php echo e(number_format($totalPendapatan, 0, ',', '.')); ?>

            </h3>
            <p class="mt-2 text-[10px] font-medium text-gray-400">
                Komisi <?php echo e(rtrim(rtrim(number_format($komisiPlatformPersen, 2, ',', '.'), '0'), ',')); ?>% dari settlement Rp <?php echo e(number_format($totalNominalSettlement, 0, ',', '.')); ?>

            </p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">
                    Total Transaksi Sukses
                </p>
                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        wire:click="syncMassalPending"
                        wire:loading.attr="disabled"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-blue-100 bg-blue-50 text-blue-600 transition hover:bg-blue-100 disabled:cursor-not-allowed disabled:opacity-60 dark:border-blue-900/50 dark:bg-blue-900/20 dark:text-blue-300"
                        title="Sinkronkan maksimal 50 transaksi pending terbaru"
                    >
                        <span
                            class="material-symbols-outlined text-[20px]"
                            wire:loading.class="animate-spin"
                            wire:target="syncMassalPending"
                        >
                            sync
                        </span>
                    </button>
                    <span class="material-symbols-outlined text-2xl text-blue-600 dark:text-blue-400">check_circle</span>
                </div>
            </div>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                <?php echo e(number_format($totalTransaksiSukses, 0, ',', '.')); ?>

            </h3>
            <p class="mt-2 text-[10px] font-medium text-gray-400">
                Pembayaran Berhasil
            </p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">
                    Mitra Pemilik Aktif
                </p>
                <span class="material-symbols-outlined text-2xl text-blue-600 dark:text-blue-400">person_pin_circle</span>
            </div>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                <?php echo e(number_format($mitraAktif, 0, ',', '.')); ?>

            </h3>
            <p class="mt-2 text-[10px] font-medium text-gray-400">
                Mitra Terverifikasi
            </p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">
                    Pencairan Tertunda
                </p>
                <span class="material-symbols-outlined text-2xl text-amber-600 dark:text-amber-400">hourglass_empty</span>
            </div>
            <h3 class="text-2xl font-black text-orange-600 dark:text-orange-400">
                <?php echo e(number_format($pencairanTertunda, 0, ',', '.')); ?> Pengajuan
            </h3>
            <p class="mt-2 text-[10px] font-medium text-gray-400">
                Perlu Persetujuan Segera
            </p>
        </div>
    </section>

    <section class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-slate-900">
        <div class="flex items-center justify-between border-b border-gray-100 p-6 dark:border-gray-700">
            <h3 class="flex items-center gap-2 text-lg font-bold text-gray-800 dark:text-white">
                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">account_balance_wallet</span>
                Permintaan Pencairan Dana Mitra
            </h3>
            <span class="rounded-lg border border-red-100 bg-red-50 px-3 py-1 text-[10px] font-extrabold uppercase tracking-wider text-red-600">
                <?php echo e(number_format($pencairanTertunda, 0, ',', '.')); ?> Perlu Tindakan
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead class="bg-gray-50/80 text-[11px] font-extrabold uppercase tracking-widest text-gray-500 dark:bg-slate-800/80 dark:text-gray-400">
                    <tr>
                        <th class="border-b border-gray-100 px-6 py-4 dark:border-gray-700">ID Penarikan</th>
                        <th class="border-b border-gray-100 px-6 py-4 dark:border-gray-700">Nama Mitra</th>
                        <th class="border-b border-gray-100 px-6 py-4 dark:border-gray-700">Bank Tujuan</th>
                        <th class="border-b border-gray-100 px-6 py-4 dark:border-gray-700">Nominal</th>
                        <th class="border-b border-gray-100 px-6 py-4 dark:border-gray-700">Waktu</th>
                        <th class="border-b border-gray-100 px-6 py-4 dark:border-gray-700">Status</th>
                        <th class="border-b border-gray-100 px-6 py-4 text-center dark:border-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $listPencairan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pencairan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr wire:key="pencairan-<?php echo e($pencairan->id); ?>">
                            <td class="px-6 py-5 font-bold text-blue-600 dark:text-blue-400">
                                <?php echo e($this->getWithdrawalDisplayId($pencairan)); ?>

                            </td>
                            <td class="px-6 py-5 font-semibold text-gray-800 dark:text-gray-200">
                                <?php echo e($pencairan->pemilikProperti?->user?->name ?? '-'); ?>

                            </td>
                            <td class="px-6 py-5 text-gray-600 dark:text-gray-400">
                                <?php echo e($pencairan->pemilikProperti?->nama_bank ?? '-'); ?> - <?php echo e($pencairan->pemilikProperti?->nomor_rekening ?? '-'); ?>

                            </td>
                            <td class="px-6 py-5 font-black text-slate-900 dark:text-white">
                                Rp <?php echo e(number_format($pencairan->nominal, 0, ',', '.')); ?>

                            </td>
                            <td class="px-6 py-5 text-xs text-gray-400">
                                <?php echo e($pencairan->created_at?->diffForHumans() ?? '-'); ?>

                            </td>
                            <td class="px-6 py-5">
                                <span class="<?php echo e($this->getWithdrawalStatusClasses($pencairan->status)); ?> rounded-lg px-2.5 py-1 text-[10px] font-black">
                                    <?php echo e($this->getWithdrawalStatusLabel($pencairan->status)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-2">
                                    <button
                                        type="button"
                                        wire:click="prosesCairkan(<?php echo e($pencairan->id); ?>)"
                                        wire:loading.attr="disabled"
                                        class="rounded-lg bg-blue-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition-all hover:bg-blue-700 active:scale-95 disabled:cursor-not-allowed disabled:opacity-60"
                                    >
                                        Cairkan via Midtrans
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="tolakCairan(<?php echo e($pencairan->id); ?>)"
                                        wire:loading.attr="disabled"
                                        class="rounded-lg bg-red-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition-all hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-60"
                                    >
                                        Tolak
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                Belum ada permintaan pencairan dana yang menunggu tindakan.
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-slate-900">
        <div class="flex items-center justify-between border-b border-gray-100 p-6 dark:border-gray-700">
            <h3 class="flex items-center gap-2 text-lg font-bold text-gray-800 dark:text-white">
                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">receipt_long</span>
                Live Transaksi Midtrans
            </h3>
            <div class="flex items-center gap-2">
                <button
                    type="button"
                    wire:click="syncMassalPending"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 rounded-lg border border-blue-100 bg-blue-50 px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-blue-600 transition hover:bg-blue-100 disabled:cursor-not-allowed disabled:opacity-60 dark:border-blue-900/50 dark:bg-blue-900/20 dark:text-blue-300"
                >
                    <span
                        class="material-symbols-outlined text-sm"
                        wire:loading.class="animate-spin"
                        wire:target="syncMassalPending"
                    >
                        sync
                    </span>
                    Sinkronkan Pending
                </button>
                <div class="flex items-center gap-2 rounded-lg border border-green-100 bg-green-50 px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-green-600 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
                    <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-green-500"></span>
                    Live Updates
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead class="bg-gray-50/80 text-[11px] font-extrabold uppercase tracking-widest text-gray-500 dark:bg-slate-800/80 dark:text-gray-400">
                    <tr>
                        <th class="border-b border-gray-100 px-6 py-4 dark:border-gray-700">Order ID</th>
                        <th class="border-b border-gray-100 px-6 py-4 dark:border-gray-700">Penyewa</th>
                        <th class="border-b border-gray-100 px-6 py-4 dark:border-gray-700">Properti</th>
                        <th class="border-b border-gray-100 px-6 py-4 dark:border-gray-700">Pemilik Kos</th>
                        <th class="border-b border-gray-100 px-6 py-4 dark:border-gray-700">Nominal</th>
                        <th class="border-b border-gray-100 px-6 py-4 dark:border-gray-700">Metode</th>
                        <th class="border-b border-gray-100 px-6 py-4 dark:border-gray-700">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $listTransaksi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaksi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr wire:key="transaksi-<?php echo e($transaksi->id); ?>">
                            <td class="px-6 py-4 font-black text-gray-800 dark:text-white">
                                <?php echo e($this->getOrderDisplayId($transaksi)); ?>

                            </td>
                            <td class="px-6 py-4 font-medium text-gray-700 dark:text-gray-300">
                                <?php echo e($transaksi->pencariKos?->user?->name ?? '-'); ?>

                            </td>
                            <td class="px-6 py-4 text-gray-400 italic">
                                <?php echo e($this->getNamaProperti($transaksi)); ?>

                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                <?php echo e($this->getNamaPemilik($transaksi)); ?>

                            </td>
                            <td class="px-6 py-4 font-black text-slate-900 dark:text-white">
                                Rp <?php echo e(number_format($this->getNominalTransaksi($transaksi), 0, ',', '.')); ?>

                            </td>
                            <td class="px-6 py-4">
                                <span class="rounded border border-gray-200 bg-gray-100 px-2 py-0.5 text-[9px] font-extrabold text-gray-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700">
                                    <?php echo e($this->getMetodePembayaran($transaksi)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="<?php echo e($this->getStatusTransaksiClasses($transaksi)); ?> rounded-lg px-2.5 py-1 text-[10px] font-black">
                                    <?php echo e($this->getStatusTransaksiLabel($transaksi)); ?>

                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                Belum ada transaksi Midtrans yang dapat ditampilkan.
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

        <div class="h-12"></div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\appkonkos-proyek2\resources\views/livewire/superadmin/dashboard-utama.blade.php ENDPATH**/ ?>