<?php $__env->startSection('mitra-title', 'Manajemen Saldo & Pencairan'); ?>
<?php $__env->startSection('mitra-subtitle', 'Pantau pendapatan bersih, saldo tersedia, dan ajukan pencairan dana dengan data rekening yang aman.'); ?>

<div class="flex-1 space-y-8 p-6 md:p-8">
    <section class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1.3fr)_minmax(0,1fr)]">
        <div class="relative overflow-hidden rounded-[28px] border border-slate-200 bg-white px-7 py-8 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="absolute -right-10 -top-10 h-44 w-44 rounded-full bg-blue-50 dark:bg-blue-950/30"></div>
            <div class="absolute bottom-0 right-20 h-24 w-24 rounded-full bg-emerald-50 dark:bg-emerald-950/20"></div>

            <div class="relative z-10">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#0F4C81] dark:text-blue-300">Saldo Tersedia</p>
                <h3 class="mt-4 text-[40px] font-black leading-none text-slate-900 dark:text-white">
                    <?php echo e($this->formatRupiah($saldoTersedia)); ?>

                </h3>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-500 dark:text-slate-400">
                    Saldo ini berasal dari total transaksi settlement milik properti Anda yang sudah dipotong komisi platform sebesar <?php echo e(rtrim(rtrim(number_format($komisiPlatformPersen, 2, ',', '.'), '0'), ',')); ?>%, lalu dikurangi total pencairan yang sudah sukses.
                </p>

                <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 dark:border-slate-700 dark:bg-slate-800/70">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Pendapatan Bersih</p>
                        <p class="mt-2 text-lg font-bold text-slate-900 dark:text-white"><?php echo e($this->formatRupiah($totalPendapatanBersih)); ?></p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 dark:border-slate-700 dark:bg-slate-800/70">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Dana Ditarik</p>
                        <p class="mt-2 text-lg font-bold text-slate-900 dark:text-white"><?php echo e($this->formatRupiah($totalDanaDitarik)); ?></p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 dark:border-slate-700 dark:bg-slate-800/70">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Dalam Proses</p>
                        <p class="mt-2 text-lg font-bold text-slate-900 dark:text-white"><?php echo e($this->formatRupiah($dalamProses)); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#0F4C81] dark:text-blue-300">Status Pengajuan</p>
                    <h3 class="mt-2 text-xl font-bold text-slate-900 dark:text-white">Pencairan Dana</h3>
                </div>
                <span class="material-symbols-outlined rounded-2xl bg-blue-50 p-3 text-[#0F4C81] dark:bg-blue-950/30 dark:text-blue-300">account_balance</span>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pencairanAktif !== null): ?>
                <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-4 text-sm text-amber-800 dark:border-amber-900/40 dark:bg-amber-950/20 dark:text-amber-200">
                    <p class="font-bold">Ada pengajuan yang masih berjalan</p>
                    <p class="mt-2 leading-6">
                        Pengajuan sebesar <span class="font-bold"><?php echo e($this->formatRupiah((int) $pencairanAktif->nominal)); ?></span> sedang berstatus
                        <span class="font-bold"><?php echo e($this->getStatusLabel((string) $pencairanAktif->status)); ?></span>.
                    </p>
                </div>
            <?php else: ?>
                <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-4 text-sm text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-950/20 dark:text-emerald-300">
                    Tidak ada pengajuan aktif. Anda dapat mengajukan pencairan baru jika saldo tersedia mencukupi.
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="mt-6 space-y-4 text-sm text-slate-500 dark:text-slate-400">
                <div class="flex items-start gap-3">
                    <span class="mt-1 h-2.5 w-2.5 rounded-full bg-[#0F4C81] dark:bg-blue-400"></span>
                    <p>Validasi saldo dilakukan otomatis agar nominal tidak melebihi hak pencairan Anda.</p>
                </div>
                <div class="flex items-start gap-3">
                    <span class="mt-1 h-2.5 w-2.5 rounded-full bg-rose-500"></span>
                    <p>Jika pengajuan ditolak, alasan penolakan akan tampil pada riwayat di bawah agar bisa segera diperbaiki.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="border-b border-slate-200 px-6 py-5 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Ajukan Pencairan Dana</h3>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Isi data rekening tujuan yang benar. Pengajuan akan ditinjau terlebih dahulu oleh Super Admin.</p>
        </div>

        <div class="grid gap-6 px-6 py-6 xl:grid-cols-[minmax(0,1fr)_320px]">
            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label for="nama-bank" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Nama Bank</label>
                    <input
                        id="nama-bank"
                        type="text"
                        wire:model.defer="namaBank"
                        placeholder="Contoh: BCA"
                        class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                    >
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['namaBank'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-rose-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div>
                    <label for="nomor-rekening" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Nomor Rekening</label>
                    <input
                        id="nomor-rekening"
                        type="text"
                        wire:model.defer="nomorRekening"
                        placeholder="Masukkan nomor rekening aktif"
                        class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                    >
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nomorRekening'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-rose-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div>
                    <label for="atas-nama" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Atas Nama</label>
                    <input
                        id="atas-nama"
                        type="text"
                        wire:model.defer="atasNama"
                        placeholder="Nama pemilik rekening"
                        class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                    >
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['atasNama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-rose-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div>
                    <label for="nominal-pencairan" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Nominal Pencairan</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-sm font-bold text-slate-400">Rp</span>
                        <input
                            id="nominal-pencairan"
                            type="number"
                            min="50000"
                            wire:model.defer="nominalPencairan"
                            placeholder="0"
                            class="w-full rounded-xl border-slate-200 bg-white py-3 pl-12 pr-4 text-sm font-semibold text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                        >
                    </div>
                    <p class="mt-2 text-xs text-slate-400 dark:text-slate-500">Minimal pencairan Rp 50.000 dan tidak boleh melebihi saldo tersedia Anda.</p>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nominalPencairan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-rose-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <div class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-800/60">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#0F4C81] dark:text-blue-300">Ringkasan</p>
                    <div class="mt-4 space-y-3 text-sm">
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-slate-500 dark:text-slate-400">Saldo Tersedia</span>
                            <span class="font-bold text-slate-900 dark:text-white"><?php echo e($this->formatRupiah($saldoTersedia)); ?></span>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-slate-500 dark:text-slate-400">Nominal Diajukan</span>
                            <span class="font-bold text-slate-900 dark:text-white"><?php echo e($this->formatRupiah((int) ($nominalPencairan ?: 0))); ?></span>
                        </div>
                    </div>

                    <div class="mt-5 rounded-2xl border border-blue-100 bg-blue-50 px-4 py-4 text-sm leading-6 text-blue-800 dark:border-blue-900/40 dark:bg-blue-950/20 dark:text-blue-200">
                        Pastikan data rekening sudah benar. Setelah dikirim, pengajuan akan ditinjau oleh Super Admin sebelum diproses lebih lanjut.
                    </div>
                </div>

                <div class="mt-6 flex flex-col gap-3">
                    <button
                        type="button"
                        wire:click="konfirmasiPencairan"
                        wire:loading.attr="disabled"
                        wire:target="konfirmasiPencairan,mintaPencairan"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0F4C81] px-5 py-3 text-sm font-bold text-white shadow-md transition hover:bg-[#0c3d68] disabled:cursor-not-allowed disabled:opacity-70"
                    >
                        <span class="material-symbols-outlined text-[18px]">send</span>
                        Ajukan Pencairan
                    </button>

                    <button
                        type="button"
                        wire:click="resetForm"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-slate-700 dark:text-slate-200 dark:hover:border-blue-400 dark:hover:text-blue-300"
                    >
                        Bersihkan Form
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex flex-col gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Riwayat Pencairan</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Menampilkan seluruh pengajuan pencairan milik akun Mitra yang sedang login.</p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center lg:justify-end">
                <div class="relative min-w-[220px]">
                    <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400 dark:text-slate-500">
                        <span class="material-symbols-outlined text-[18px]">search</span>
                    </span>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="searchRiwayat"
                        placeholder="Cari ID, bank, rekening"
                        class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 pl-12 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                    >
                </div>

                <div x-data="{ open: false }" class="relative">
                    <button
                        type="button"
                        @click="open = ! open"
                        @click.outside="open = false"
                        class="flex h-11 min-w-[190px] items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-blue-400 dark:hover:text-blue-300"
                    >
                        <span class="flex min-w-0 items-center gap-2">
                            <span class="material-symbols-outlined shrink-0 text-[18px]">filter_list</span>
                            <span class="truncate"><?php echo e($this->getRiwayatFilterLabel()); ?></span>
                        </span>
                        <span class="material-symbols-outlined shrink-0 text-[18px] text-slate-400 dark:text-slate-500">expand_more</span>
                    </button>

                    <div
                        x-cloak
                        x-show="open"
                        x-transition.origin.top.right
                        class="absolute right-0 z-20 mt-2 w-[220px] overflow-hidden rounded-2xl border border-slate-200 bg-white p-1 shadow-xl dark:border-slate-700 dark:bg-slate-800"
                    >
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $riwayatStatusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button
                                type="button"
                                wire:click="$set('filterRiwayatStatus', '<?php echo e($value); ?>')"
                                @click="open = false"
                                class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'flex w-full items-center justify-between rounded-xl px-4 py-3 text-left text-sm transition',
                                    'bg-blue-50 font-semibold text-[#0F4C81] dark:bg-blue-500/10 dark:text-blue-300' => $filterRiwayatStatus === $value,
                                    'text-slate-600 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-700/70' => $filterRiwayatStatus !== $value,
                                ]); ?>"
                            >
                                <span><?php echo e($label); ?></span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($filterRiwayatStatus === $value): ?>
                                    <span class="material-symbols-outlined text-[18px]">check</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-[0.08em] text-slate-500 dark:bg-slate-800 dark:text-slate-300">
                    <tr>
                        <th class="px-6 py-4 font-semibold">ID</th>
                        <th class="px-6 py-4 font-semibold">Tanggal</th>
                        <th class="px-6 py-4 font-semibold">Nominal</th>
                        <th class="px-6 py-4 font-semibold">Rekening Tujuan</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $riwayatPenarikan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pencairan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="align-top transition hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                            <td class="px-6 py-5 font-semibold text-[#0F4C81] dark:text-blue-400"><?php echo e($this->getWithdrawalDisplayId($pencairan)); ?></td>
                            <td class="px-6 py-5 text-slate-600 dark:text-slate-300">
                                <?php echo e($pencairan->created_at?->locale('id')->translatedFormat('d M Y, H:i') ?? '-'); ?>

                            </td>
                            <td class="px-6 py-5 font-semibold text-slate-900 dark:text-white">
                                <?php echo e($this->formatRupiah((int) $pencairan->nominal)); ?>

                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="font-semibold text-slate-800 dark:text-slate-100"><?php echo e($this->getWithdrawalBankName($pencairan)); ?></span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400"><?php echo e($this->getWithdrawalBankMeta($pencairan)); ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="<?php echo e($this->getStatusBadgeClasses((string) $pencairan->status)); ?> inline-flex rounded-full px-3 py-1 text-[11px] font-bold">
                                    <?php echo e($this->getStatusLabel((string) $pencairan->status)); ?>

                                </span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pencairan->status === 'ditolak'): ?>
                                    <p class="mt-2 max-w-[320px] text-sm font-semibold text-rose-600 dark:text-rose-300">
                                        Alasan Penolakan: <?php echo e($pencairan->alasan_penolakan ?? 'Pengajuan pencairan ditolak oleh Super Admin.'); ?>

                                    </p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-14 text-center text-sm text-slate-500 dark:text-slate-400">
                                Belum ada riwayat pencairan dana.
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?php /**PATH C:\xampp\htdocs\appkonkos_2\resources\views/livewire/mitra/keuangan.blade.php ENDPATH**/ ?>