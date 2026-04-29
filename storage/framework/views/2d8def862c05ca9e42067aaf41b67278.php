<?php $__env->startSection('mitra-title', 'Dashboard Utama'); ?>
<?php $__env->startSection('mitra-subtitle', 'Selamat datang kembali, kelola pesanan dan properti Anda hari ini.'); ?>

<div class="flex-1 px-4 py-5 sm:px-6 sm:py-7 xl:px-8">
    <div class="mx-auto flex w-full max-w-[1220px] flex-col gap-8">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('dashboard_success')): ?>
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700 shadow-sm">
                <?php echo e(session('dashboard_success')); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('dashboard_error')): ?>
            <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700 shadow-sm">
                <?php echo e(session('dashboard_error')); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <section class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
            <div class="group relative overflow-hidden rounded-[18px] border border-[#edf0f4] bg-white p-6 shadow-[0_6px_18px_rgba(15,23,42,0.05)] transition-colors dark:border-slate-700 dark:bg-slate-900">
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-blue-50 dark:bg-blue-500/10"></div>
                <div class="relative z-10">
                    <p class="mb-2 text-[14px] font-medium text-slate-500 dark:text-slate-400">Pendapatan Bersih</p>
                    <h3 class="text-[22px] font-bold leading-tight text-slate-900 dark:text-white">Rp <?php echo e(number_format($pendapatanBersih, 0, ',', '.')); ?></h3>
                    <p class="mt-2 text-xs leading-5 text-slate-400 dark:text-slate-500">
                        Telah dipotong komisi platform aplikasi sebesar <?php echo e(rtrim(rtrim(number_format($komisiPlatformPersen, 2, ',', '.'), '0'), ',')); ?>%
                    </p>
                    <a href="<?php echo e(route('mitra.keuangan')); ?>" class="inline-flex items-center gap-2 rounded-[10px] bg-[#0F4C81] px-4 py-2.5 text-sm font-medium text-white shadow-[0_8px_16px_rgba(15,76,129,0.22)] transition-all duration-200 hover:bg-[#0d3f6d]">
                        <span class="material-symbols-outlined text-[18px]">payments</span>
                        Tarik Dana
                    </a>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-[18px] border border-[#edf0f4] bg-white p-6 shadow-[0_6px_18px_rgba(15,23,42,0.05)] transition-colors dark:border-slate-700 dark:bg-slate-900">
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-purple-50 dark:bg-purple-500/10"></div>
                <div class="relative z-10 flex h-full flex-col justify-between">
                    <div>
                        <p class="mb-2 text-[14px] font-medium text-slate-500 dark:text-slate-400">Properti Aktif</p>
                        <h3 class="text-[22px] font-bold leading-tight text-slate-900 dark:text-white"><?php echo e($totalPropertiAktif); ?> Properti</h3>
                    </div>
                    <div class="mt-6 flex items-center gap-2 text-[14px] text-slate-500 dark:text-slate-400">
                        <span class="material-symbols-outlined text-[15px]">apartment</span>
                        Sudah disetujui untuk tayang
                    </div>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-[18px] border border-[#edf0f4] bg-white p-6 shadow-[0_6px_18px_rgba(15,23,42,0.05)] transition-colors dark:border-slate-700 dark:bg-slate-900">
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-emerald-50 dark:bg-emerald-500/10"></div>
                <div class="relative z-10 flex h-full flex-col justify-between">
                    <div>
                        <p class="mb-2 text-[14px] font-medium text-slate-500 dark:text-slate-400">Booking Aktif Bulan Ini</p>
                        <h3 class="text-[22px] font-bold leading-tight text-slate-900 dark:text-white"><?php echo e($bookingAktif); ?> Booking</h3>
                    </div>
                    <div class="mt-6 flex items-center gap-2 text-[14px] text-slate-500 dark:text-slate-400">
                        <span class="material-symbols-outlined text-[15px] text-emerald-500">calendar_month</span>
                        Transaksi sukses pada bulan berjalan
                    </div>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-[18px] border border-[#edf0f4] bg-white p-6 shadow-[0_6px_18px_rgba(15,23,42,0.05)] transition-colors dark:border-slate-700 dark:bg-slate-900">
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-orange-50 dark:bg-orange-500/10"></div>
                <div class="relative z-10 flex h-full flex-col justify-between">
                    <div>
                        <p class="mb-2 text-[14px] font-medium text-slate-500 dark:text-slate-400">Menunggu Konfirmasi</p>
                        <h3 class="text-[22px] font-bold leading-tight text-slate-900 dark:text-white"><?php echo e($pesananMenunggu); ?> Pesanan</h3>
                    </div>
                    <div class="mt-6 inline-flex w-fit items-center gap-1.5 rounded-lg bg-orange-50 px-2.5 py-1 text-[11px] font-bold text-orange-600 dark:bg-orange-500/10 dark:text-orange-300">
                        <span class="material-symbols-outlined text-[15px]">pending_actions</span>
                        Perlu Tindakan
                    </div>
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-[20px] border border-[#edf0f4] bg-white shadow-[0_6px_18px_rgba(15,23,42,0.05)] dark:border-slate-700 dark:bg-slate-900">
            <div class="flex flex-col justify-between gap-4 border-b border-[#edf0f4] px-6 py-6 dark:border-slate-700 sm:flex-row sm:items-center">
                <h3 class="flex items-center gap-2 text-[18px] font-bold leading-tight text-slate-900 dark:text-white">
                    <span class="material-symbols-outlined text-[22px] text-[#0F4C81] dark:text-blue-400">receipt_long</span>
                    Pesanan Masuk Terbaru
                </h3>
                <a class="text-sm font-semibold text-[#0F4C81] transition hover:text-[#0c3c66] hover:underline dark:text-blue-400" href="<?php echo e(route('mitra.pesanan')); ?>">Lihat Semua</a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-[#f8f9fb] text-[11px] uppercase tracking-[0.04em] text-slate-500 dark:bg-slate-950/80 dark:text-slate-300">
                        <tr>
                            <th class="px-6 py-4 font-semibold">ID Booking</th>
                            <th class="px-6 py-4 font-semibold">Nama Penyewa</th>
                            <th class="px-6 py-4 font-semibold">Properti</th>
                            <th class="px-6 py-4 font-semibold">Tanggal Mulai</th>
                            <th class="px-6 py-4 font-semibold">Status Bayar</th>
                            <th class="px-6 py-4 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#edf0f4] dark:divide-slate-800">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $pesananTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pesanan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr wire:key="booking-row-<?php echo e($pesanan->id); ?>" class="transition hover:bg-[#fafbfc] dark:hover:bg-slate-800/30">
                                <td class="px-6 py-5 font-semibold text-slate-800 dark:text-white">#<?php echo e(strtoupper(substr($pesanan->id, 0, 8))); ?></td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="<?php echo e($this->getPenyewaInitialsClasses($pesanan)); ?> flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold">
                                            <?php echo e($this->getPenyewaInitials($pesanan)); ?>

                                        </div>
                                        <span class="font-medium text-slate-700 dark:text-slate-200"><?php echo e($pesanan->pencariKos?->user?->name ?? 'Penyewa'); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-slate-600 dark:text-slate-300"><?php echo e($this->getBookingPropertyLabel($pesanan)); ?></td>
                                <td class="px-6 py-5 text-slate-600 dark:text-slate-300"><?php echo e($pesanan->tgl_mulai_sewa?->locale('id')->translatedFormat('d M Y')); ?></td>
                                <td class="px-6 py-5">
                                    <span class="<?php echo e($this->getStatusBayarClasses($pesanan)); ?> rounded-full px-3 py-1.5 text-xs font-medium">
                                        <?php echo e($this->getStatusBayarLabel($pesanan)); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->canProcessBooking($pesanan)): ?>
                                        <div class="flex flex-wrap items-center justify-end gap-2">
                                            <button wire:click="konfirmasiBooking('<?php echo e($pesanan->id); ?>')" wire:loading.attr="disabled" wire:target="konfirmasiBooking,tolakBooking" type="button" class="rounded-[10px] bg-[#0F4C81] px-4 py-2 text-xs font-medium text-white transition hover:bg-[#0c3c66] disabled:cursor-not-allowed disabled:opacity-60">
                                                Konfirmasi
                                            </button>
                                            <button wire:click="tolakBooking('<?php echo e($pesanan->id); ?>')" wire:loading.attr="disabled" wire:target="konfirmasiBooking,tolakBooking" type="button" class="rounded-[10px] border border-red-200 bg-red-50 px-4 py-2 text-xs font-medium text-red-600 transition hover:bg-red-100 disabled:cursor-not-allowed disabled:opacity-60 dark:border-red-900/70 dark:bg-red-950/30 dark:text-red-300">
                                                Tolak
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-xs italic text-slate-400 dark:text-slate-500">
                                            <?php echo e($pesanan->status_booking === 'batal' ? 'Booking dibatalkan' : 'Menunggu pembayaran'); ?>

                                        </span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-14 text-center text-sm text-slate-500 dark:text-slate-400">
                                    Belum ada pesanan masuk terbaru.
                                </td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="space-y-5">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <h3 class="flex items-center gap-2 text-[18px] font-bold leading-tight text-slate-900 dark:text-white">
                        <span class="material-symbols-outlined text-[22px] text-[#0F4C81] dark:text-blue-400">home</span>
                        Kosan &amp; Kontrakan Saya
                    </h3>
                    <div class="flex flex-wrap items-center gap-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['semua' => 'Semua', 'kosan' => 'Kos', 'kontrakan' => 'Kontrakan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filter => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button
                                type="button"
                                wire:click="setFilterProperti('<?php echo e($filter); ?>')"
                                class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'rounded-[10px] border px-3.5 py-2 text-xs font-semibold transition',
                                    'border-[#0F4C81] bg-[#0F4C81] text-white shadow-[0_8px_16px_rgba(15,76,129,0.18)] dark:border-blue-500 dark:bg-blue-500 dark:text-white' => $filterProperti === $filter,
                                    'border-slate-200 bg-white text-slate-600 hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-300 dark:hover:border-blue-500 dark:hover:text-blue-300' => $filterProperti !== $filter,
                                ]); ?>"
                            >
                                <?php echo e($label); ?>

                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
                <a href="<?php echo e(route('mitra.properti')); ?>" class="inline-flex items-center justify-center gap-2 rounded-[12px] bg-[#0F4C81] px-4 py-2.5 text-sm font-medium text-white shadow-[0_8px_16px_rgba(15,76,129,0.18)] transition hover:bg-[#0c3c66]">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    Tambah Properti
                </a>
            </div>

            <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $propertiSaya; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $properti): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <article wire:key="dashboard-property-<?php echo e($properti['type']); ?>-<?php echo e($properti['id']); ?>" class="group flex flex-col gap-4 rounded-[18px] border border-[#edf0f4] bg-white p-4 shadow-[0_6px_18px_rgba(15,23,42,0.05)] dark:border-slate-700 dark:bg-slate-950/90 dark:shadow-[0_14px_32px_rgba(2,6,23,0.35)] sm:flex-row sm:items-center">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($properti['image_url'] !== ''): ?>
                            <img alt="<?php echo e($properti['nama_properti']); ?>" class="h-28 w-full rounded-[14px] object-cover sm:w-28" src="<?php echo e($properti['image_url']); ?>">
                        <?php else: ?>
                            <div class="flex h-28 w-full items-center justify-center rounded-[14px] bg-gradient-to-br from-slate-100 to-slate-200 text-sm font-semibold text-slate-500 sm:w-28 dark:from-slate-800 dark:to-slate-700 dark:text-slate-300">
                                APPKONKOS
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div class="flex min-w-0 flex-1 flex-col">
                            <div class="mb-2 flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <h4 class="truncate text-[16px] font-bold leading-tight text-slate-900 dark:text-white"><?php echo e($properti['nama_properti']); ?></h4>
                                    <div class="mt-1 flex flex-wrap items-center gap-x-2 gap-y-1 text-[13px] text-slate-500 dark:text-slate-400">
                                        <span class="truncate"><?php echo e($properti['lokasi_label']); ?></span>
                                        <span class="rounded-md border border-slate-200 bg-slate-50 px-2 py-0.5 text-[10px] font-bold text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                                            ID <?php echo e($properti['display_id']); ?>

                                        </span>
                                    </div>
                                </div>
                                <a href="<?php echo e($properti['edit_url']); ?>" class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-50 text-slate-400 transition hover:bg-slate-100 hover:text-[#0F4C81] dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </a>
                            </div>

                            <div class="mb-4 flex flex-wrap gap-2">
                                <span class="<?php echo e($properti['badge_classes']); ?> rounded-lg px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.08em]">
                                    <?php echo e($properti['badge_label']); ?>

                                </span>
                                <span class="<?php echo e($properti['availability_classes']); ?> rounded-lg px-2.5 py-1 text-[10px] font-bold">
                                    <?php echo e($properti['availability_label']); ?>

                                </span>
                            </div>

                            <div class="mt-auto flex items-end justify-between gap-4">
                                <span class="text-[16px] font-bold leading-none text-[#0F4C81] dark:text-blue-400">
                                    <?php echo e($properti['price_label']); ?>

                                    <span class="ml-1 text-[12px] font-normal text-slate-500 dark:text-slate-400"><?php echo e($properti['price_suffix']); ?></span>
                                </span>
                                <a class="inline-flex items-center gap-1 text-[13px] font-medium text-slate-500 transition hover:text-[#0F4C81] dark:text-slate-400 dark:hover:text-blue-400" href="<?php echo e($properti['detail_url']); ?>">
                                    Detail
                                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="xl:col-span-2">
                        <div class="rounded-[18px] border border-dashed border-slate-300 bg-white px-6 py-14 text-center text-sm text-slate-500 shadow-sm dark:border-slate-700 dark:bg-slate-950/90 dark:text-slate-400">
                            Belum ada properti yang cocok dengan filter ini.
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </section>

    </div>
</div>
<?php /**PATH C:\xampp\htdocs\appkonkos_2\resources\views/livewire/mitra/dashboard-utama.blade.php ENDPATH**/ ?>