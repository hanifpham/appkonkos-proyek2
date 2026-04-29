<div class="py-10">
    <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
        <section class="overflow-hidden rounded-[28px] border border-sky-100 bg-[radial-gradient(circle_at_top_left,_rgba(14,116,144,0.12),_transparent_42%),linear-gradient(135deg,_#ffffff_0%,_#f0f9ff_55%,_#ecfeff_100%)] p-8 shadow-sm">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-sky-700">Appkonkos</p>
            <div class="mt-4 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <h1 class="text-3xl font-bold tracking-tight text-slate-900">Dashboard pembayaran penyewa</h1>
                    <p class="mt-3 text-sm leading-6 text-slate-600">
                        Pantau booking Anda, lanjutkan pembayaran Midtrans Sandbox, dan cek status transaksi tanpa keluar dari aplikasi.
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                    <div class="rounded-2xl border border-white/70 bg-white/80 px-4 py-3">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">Total Booking</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900"><?php echo e($bookings->count()); ?></p>
                    </div>
                    <div class="rounded-2xl border border-white/70 bg-white/80 px-4 py-3">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">Belum Lunas</p>
                        <p class="mt-2 text-2xl font-bold text-amber-600"><?php echo e($bookings->filter(fn ($item) => ! ($item->pembayaran?->isSuccessful() ?? false))->count()); ?></p>
                    </div>
                    <div class="rounded-2xl border border-white/70 bg-white/80 px-4 py-3 sm:col-span-1 col-span-2">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">Sudah Lunas</p>
                        <p class="mt-2 text-2xl font-bold text-emerald-600"><?php echo e($bookings->filter(fn ($item) => $item->pembayaran?->isSuccessful() ?? false)->count()); ?></p>
                    </div>
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Booking & Pembayaran</h2>
                    <p class="mt-1 text-sm text-slate-500">Jika pembayaran belum lunas, klik tombol bayar untuk membuka Snap Midtrans.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-50 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">
                        <tr>
                            <th class="px-6 py-4">Booking</th>
                            <th class="px-6 py-4">Properti</th>
                            <th class="px-6 py-4">Pemilik</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Status Bayar</th>
                            <th class="px-6 py-4">Status Booking</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr wire:key="pencari-booking-<?php echo e($booking->id); ?>" class="align-top transition hover:bg-slate-50/70">
                                <td class="px-6 py-5">
                                    <p class="font-semibold text-slate-900">#<?php echo e(strtoupper(substr($booking->id, 0, 8))); ?></p>
                                    <p class="mt-1 text-xs text-slate-500">
                                        <?php echo e($booking->tgl_mulai_sewa?->format('d M Y')); ?> - <?php echo e($booking->tgl_selesai_sewa?->format('d M Y')); ?>

                                    </p>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="font-medium text-slate-800"><?php echo e($this->getPropertyName($booking)); ?></p>
                                </td>
                                <td class="px-6 py-5 text-slate-600"><?php echo e($this->getOwnerName($booking)); ?></td>
                                <td class="px-6 py-5 font-semibold text-slate-900">Rp <?php echo e(number_format((int) $booking->total_biaya, 0, ',', '.')); ?></td>
                                <td class="px-6 py-5">
                                    <span class="<?php echo e($this->getPaymentStatusClasses($booking)); ?> rounded-full px-3 py-1 text-xs font-semibold">
                                        <?php echo e($this->getPaymentStatusLabel($booking)); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-5 text-slate-600"><?php echo e($this->getBookingStatusLabel($booking)); ?></td>
                                <td class="px-6 py-5 text-right">
                                    <a
                                        href="<?php echo e(route('pencari.pembayaran.show', $booking)); ?>"
                                        class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-semibold <?php echo e($this->canPay($booking) ? 'bg-[#0F4C81] text-white shadow-sm hover:bg-[#0c3d67]' : 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50'); ?>"
                                    >
                                        <span class="material-symbols-outlined text-base"><?php echo e($this->canPay($booking) ? 'payments' : 'receipt_long'); ?></span>
                                        <?php echo e($this->canPay($booking) ? 'Bayar Sekarang' : 'Lihat Detail'); ?>

                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center text-sm text-slate-500">
                                    Belum ada booking yang dapat dibayar.
                                </td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\appkonkos_2\resources\views/livewire/pencari/dashboard.blade.php ENDPATH**/ ?>