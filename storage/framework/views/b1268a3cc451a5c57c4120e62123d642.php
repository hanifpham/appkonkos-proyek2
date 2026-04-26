<?php $__env->startSection('mitra-title', 'Manajemen Pengguna'); ?>
<?php $__env->startSection('mitra-subtitle', 'Kelola akun pencari dan pemilik kos, termasuk validasi, blokir, dan pemantauan status.'); ?>

<?php
    $filterPeranOptions = [
        '' => 'Semua Peran',
        'pemilik' => 'Pemilik Kos',
        'pencari' => 'Pencari Kos',
    ];
    $filterStatusOptions = [
        '' => 'Semua Status',
        'pending' => 'Perlu Validasi',
        'aktif' => 'Aktif',
        'diblokir' => 'Diblokir',
    ];
?>

<div class="flex-1 space-y-8 overflow-y-auto p-8">
    <section class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Pengguna
                </p>
                <span class="material-symbols-outlined text-3xl text-[#113C7A]">group</span>
            </div>
            <h3 class="text-3xl font-bold text-black dark:text-white"><?php echo e(number_format($totalPengguna, 0, ',', '.')); ?>

            </h3>
            <p class="mt-2 text-[10px] font-medium uppercase tracking-tighter text-gray-400">Database Terpadu</p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Aktif</p>
                <span class="material-symbols-outlined text-3xl text-emerald-600">check_circle</span>
            </div>
            <h3 class="text-3xl font-bold text-emerald-600"><?php echo e(number_format($totalAktif, 0, ',', '.')); ?></h3>
            <p class="mt-2 text-[10px] font-medium uppercase tracking-tighter text-gray-400">Status Terverifikasi</p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Perlu Validasi
                </p>
                <span class="material-symbols-outlined text-3xl text-amber-600">verified_user</span>
            </div>
            <h3 class="text-3xl font-bold text-amber-600"><?php echo e(number_format($totalPending, 0, ',', '.')); ?></h3>
            <p class="mt-2 text-[10px] font-medium uppercase tracking-tighter text-gray-400">Antrean Persetujuan</p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Terblokir</p>
                <span class="material-symbols-outlined text-3xl text-red-600">block</span>
            </div>
            <h3 class="text-3xl font-bold text-red-600"><?php echo e(number_format($totalTerblokir, 0, ',', '.')); ?></h3>
            <p class="mt-2 text-[10px] font-medium uppercase tracking-tighter text-gray-400">Tindakan Pelanggaran</p>
        </div>
    </section>

    <div class="flex justify-end">
        <button
            type="button"
            wire:click="eksporData"
            class="inline-flex items-center gap-2 rounded-xl bg-[#113C7A] px-4 py-3 text-sm font-bold text-white shadow-md transition-colors hover:bg-[#0d2f60]"
        >
            <span class="material-symbols-outlined text-[18px]">file_download</span>
            Ekspor Excel
        </button>
    </div>

    <section class="overflow-visible rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-slate-900">
        <div class="relative z-10 border-b border-gray-100 bg-slate-50/30 p-6 dark:border-gray-700 dark:bg-slate-800/20">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                <h3 class="flex items-center gap-2 text-lg font-bold text-gray-800 dark:text-white">
                    <span class="material-symbols-outlined text-[#113C7A] dark:text-blue-400">group</span>
                    Database Pengguna Terpadu
                </h3>

                <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center xl:mr-5 xl:flex-nowrap xl:justify-end">
                    <div class="relative w-full sm:w-[250px] xl:w-[220px]">
                        <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400 dark:text-slate-500">
                            <span class="material-symbols-outlined text-[18px]">search</span>
                        </span>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 pl-12 pr-4 text-sm text-slate-700 placeholder:text-slate-400 shadow-sm transition-all focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            placeholder="Cari nama, email, HP, atau ID..."
                        />
                    </div>

                    <div x-data="{ open: false }" class="relative w-full sm:w-[190px]">
                        <button type="button" @click="open = ! open" @click.outside="open = false" class="flex h-11 w-full items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-blue-400 dark:hover:text-blue-300">
                            <span class="flex min-w-0 items-center gap-2">
                                <span class="material-symbols-outlined shrink-0 text-[18px]">manage_accounts</span>
                                <span class="truncate"><?php echo e($filterPeranOptions[$filterPeran] ?? 'Semua Peran'); ?></span>
                            </span>
                            <span class="material-symbols-outlined shrink-0 text-[18px] text-slate-400 dark:text-slate-500">expand_more</span>
                        </button>
                        <div x-cloak x-show="open" x-transition.origin.top.right class="absolute right-0 top-[calc(100%+8px)] z-20 w-full min-w-[220px] overflow-hidden rounded-2xl border border-slate-200 bg-white p-1 shadow-xl dark:border-slate-700 dark:bg-slate-800">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $filterPeranOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button type="button" wire:click="$set('filterPeran', '<?php echo e($value); ?>')" @click="open = false" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['flex w-full items-center justify-between rounded-xl px-4 py-3 text-left text-sm transition','bg-blue-50 font-semibold text-[#0F4C81] dark:bg-blue-500/10 dark:text-blue-300' => $filterPeran === $value,'text-slate-600 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-700/70' => $filterPeran !== $value]); ?>">
                                    <span><?php echo e($label); ?></span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($filterPeran === $value): ?>
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
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead
                    class="bg-gray-50 text-[11px] font-bold uppercase tracking-widest text-gray-600 dark:bg-slate-800 dark:text-gray-300">
                    <tr>
                        <th class="px-8 py-5">Informasi Pengguna</th>
                        <th class="px-6 py-5">Peran</th>
                        <th class="px-6 py-5">Kontak</th>
                        <th class="px-6 py-5">Status Akun</th>
                        <th class="px-8 py-5 text-center">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr wire:key="user-<?php echo e($user->id); ?>">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="<?php echo e($this->getAvatarClasses($user)); ?> flex h-11 w-11 items-center justify-center rounded-xl font-bold">
                                        <?php echo e(strtoupper(substr($user->name, 0, 2))); ?>

                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="text-base font-bold text-gray-900 dark:text-gray-100"><?php echo e($user->name); ?></span>
                                        <span class="text-xs font-medium text-gray-400">ID: <?php echo e($user->id); ?> |
                                            <?php echo e($user->created_at?->format('d M Y')); ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                <span
                                    class="<?php echo e($this->getRoleBadgeClasses($user)); ?> rounded-lg px-3 py-1 text-[10px] font-bold uppercase tracking-wide">
                                    <?php echo e($this->getRoleLabel($user)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-6 text-gray-500">
                                <div class="text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e($user->email); ?></div>
                                <div class="text-xs"><?php echo e($user->no_telepon); ?></div>
                            </td>
                            <td class="px-6 py-6">
                                <span
                                    class="<?php echo e($this->getStatusBadgeClasses($user)); ?> inline-flex items-center rounded-full px-4 py-1.5 text-[10px] font-black uppercase">
                                    <?php echo e($this->getStatusLabel($user)); ?>

                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center justify-center gap-2">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->status === 'diblokir'): ?>
                                        <button type="button" wire:click="bukaBlokir(<?php echo e($user->id); ?>)"
                                            class="flex h-10 w-[148px] items-center justify-center gap-2 rounded-lg bg-red-600 px-3 text-xs font-bold text-white transition-all hover:bg-red-700">
                                            <span class="material-symbols-outlined text-[18px]">lock_open</span>
                                            Buka Blokir
                                        </button>
                                    <?php elseif($user->role === 'pemilik' && $user->pemilikProperti?->status_verifikasi === 'pending'): ?>
                                        <button type="button" wire:click="validasiAkun(<?php echo e($user->id); ?>)"
                                            class="flex h-10 w-[148px] items-center justify-center gap-2 rounded-lg bg-[#0F4C81] px-3 text-xs font-bold text-white transition-all hover:bg-[#0d3f6d]">
                                            <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
                                            Validasi Akun
                                        </button>
                                    <?php else: ?>
                                        <button type="button" wire:click="blokirAkun(<?php echo e($user->id); ?>)"
                                            class="flex h-10 w-[148px] items-center justify-center gap-2 rounded-lg border border-red-200 bg-red-50 px-3 text-xs font-bold text-red-600 transition-all hover:bg-red-100">
                                            <span class="material-symbols-outlined text-[18px]">block</span>
                                            Blokir Akun
                                        </button>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <button type="button" wire:click="bukaDetail(<?php echo e($user->id); ?>)"
                                        class="flex h-10 w-[148px] items-center justify-center rounded-lg border border-gray-300 bg-white px-3 text-xs font-bold text-gray-700 transition-all hover:bg-gray-50">
                                        Detail
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-8 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                Belum ada pengguna yang sesuai dengan filter.
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between border-t border-gray-100 bg-gray-50/30 p-6 dark:border-gray-700 dark:bg-slate-800/20">
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                Menampilkan <span class="font-bold text-gray-900 dark:text-white"><?php echo e($users->firstItem() ?? 0); ?> -
                    <?php echo e($users->lastItem() ?? 0); ?></span>
                dari <span
                    class="font-bold text-gray-900 dark:text-white"><?php echo e(number_format($users->total(), 0, ',', '.')); ?></span>
                pengguna terdaftar
            </span>
            <div>
                <?php echo e($users->links()); ?>

            </div>
        </div>
    </section>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showModalDetail && $detailUser): ?>
        <div class="fixed inset-0 z-[999] bg-black/75 transition-opacity" wire:click="tutupDetail"></div>

        <div class="fixed inset-0 z-[1000] flex items-center justify-center p-4 sm:p-6 pointer-events-none">
            <div class="flex max-h-[90vh] w-full max-w-3xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-slate-900 pointer-events-auto"
                wire:click.stop>

                <div
                    class="flex items-center justify-between border-b border-gray-100 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-slate-800/50">
                    <h3 class="flex items-center gap-2 text-lg font-bold text-gray-900 dark:text-white">
                        <span class="material-symbols-outlined text-[#113C7A] dark:text-blue-400">badge</span>
                        Detail Verifikasi Mitra
                    </h3>
                    <button wire:click="tutupDetail" class="text-gray-400 transition-colors hover:text-red-500">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <div class="flex-1 space-y-6 overflow-y-auto p-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                        <div class="space-y-4">
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Nama Lengkap</span>
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200"><?php echo e($detailUser->name); ?>

                                </p>
                            </div>
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Kontak</span>
                                <p class="text-sm text-gray-800 dark:text-gray-200"><?php echo e($detailUser->email); ?> |
                                    <?php echo e($detailUser->no_telepon); ?></p>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($detailUser->role === 'pemilik' && $detailUser->pemilikProperti): ?>
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Alamat
                                        Domisili</span>
                                    <p class="text-sm text-gray-800 dark:text-gray-200">
                                        <?php echo e($detailUser->pemilikProperti->alamat_domisili ?? '-'); ?></p>
                                </div>
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Informasi Bank</span>
                                    <p class="text-sm font-medium text-[#113C7A] dark:text-blue-400">
                                        <?php echo e($detailUser->pemilikProperti->nama_bank); ?> -
                                        <?php echo e($detailUser->pemilikProperti->nomor_rekening); ?></p>
                                    <p class="text-xs text-gray-500">a.n
                                        <?php echo e($detailUser->pemilikProperti->nama_pemilik_rekening); ?></p>
                                </div>
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Nomor NIK KTP</span>
                                    <p
                                        class="mt-1 inline-block rounded bg-gray-100 px-3 py-1 font-mono text-base font-bold text-gray-900 dark:bg-slate-800 dark:text-white">
                                        <?php echo e($detailUser->pemilikProperti->nik_ktp ?? 'Belum diisi'); ?>

                                    </p>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($detailUser->role === 'pemilik' && $detailUser->pemilikProperti): ?>
                            <div class="space-y-4">
                                <div>
                                    <span class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-400">Foto
                                        KTP</span>
                                    <div
                                        class="flex h-48 items-center justify-center overflow-hidden rounded-xl border border-gray-200 bg-gray-200 dark:border-gray-700 dark:bg-slate-900">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($detailUser->pemilikProperti->getMediaDisplayUrl('foto_ktp') !== ''): ?>
                                            <img src="<?php echo e($detailUser->pemilikProperti->getMediaDisplayUrl('foto_ktp')); ?>"
                                                class="h-full w-full object-contain p-2 cursor-pointer transition-transform hover:scale-105"
                                                alt="KTP">
                                        <?php else: ?>
                                            <span class="text-sm text-gray-400">Tidak ada dokumen</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>

                                <div>
                                    <span class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-400">Foto
                                        Selfie KTP</span>
                                    <div
                                        class="flex h-48 items-center justify-center overflow-hidden rounded-xl border border-gray-200 bg-gray-200 dark:border-gray-700 dark:bg-slate-900">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($detailUser->pemilikProperti->getMediaDisplayUrl('foto_selfie') !== ''): ?>
                                            <img src="<?php echo e($detailUser->pemilikProperti->getMediaDisplayUrl('foto_selfie')); ?>"
                                                class="h-full w-full object-contain p-2 cursor-pointer transition-transform hover:scale-105"
                                                alt="Selfie KTP">
                                        <?php else: ?>
                                            <span class="text-sm text-gray-400">Tidak ada dokumen</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <div
                    class="flex justify-end gap-3 border-t border-gray-100 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-slate-800/50">
                    <button wire:click="tutupDetail"
                        class="rounded-lg border border-gray-300 bg-white px-5 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">Tutup</button>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($detailUser->role === 'pemilik' && $detailUser->pemilikProperti?->status_verifikasi === 'pending'): ?>
                        <button wire:click="validasiAkun(<?php echo e($detailUser->id); ?>)"
                            class="flex items-center gap-2 rounded-lg bg-[#0F4C81] px-5 py-2 text-sm font-bold text-white hover:bg-[#0d3f6d]">
                            <span class="material-symbols-outlined text-[18px]">how_to_reg</span> Setujui Verifikasi
                        </button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\appkonkos_2\resources\views/livewire/superadmin/manajemen-pengguna.blade.php ENDPATH**/ ?>