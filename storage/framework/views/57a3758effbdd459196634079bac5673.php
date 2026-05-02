<?php $__env->startSection('mitra-title', 'Kelola Unit Kos'); ?>
<?php $__env->startSection('mitra-subtitle', $kosan->nama_properti.' - atur tipe kamar, foto interior, dan nomor kamar spesifik.'); ?>

<?php
    $fotoProperti = $kosan->getMediaDisplayUrl('foto_properti');
    $totalTipe = $kosan->tipeKamar->count();
    $totalKamar = $kosan->tipeKamar->sum(fn ($tipe) => $tipe->kamar->count());
    $kamarTersedia = $kosan->tipeKamar->sum(fn ($tipe) => $tipe->kamar->where('status_kamar', 'tersedia')->count());
    $kamarDihuni = $kosan->tipeKamar->sum(fn ($tipe) => $tipe->kamar->where('status_kamar', 'dihuni')->count());
    $peraturanKos = collect(preg_split('/\r\n|\r|\n/', $kosan->peraturan_kos ?? '') ?: [])
        ->map(static fn (string $item): string => trim($item))
        ->filter()
        ->values();

    if ($peraturanKos->isEmpty() && trim((string) $kosan->peraturan_kos) !== '') {
        $peraturanKos = collect(preg_split('/(?<=[.;])\s+/', trim((string) $kosan->peraturan_kos)) ?: [])
            ->map(static fn (string $item): string => trim($item))
            ->filter()
            ->values();
    }
?>

<div class="px-4 py-8 sm:px-6 xl:px-8">
    <div class="mx-auto w-full max-w-6xl space-y-6 pb-16">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('success')): ?>
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-950/20 dark:text-emerald-300">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['general'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700 dark:border-rose-900/40 dark:bg-rose-950/20 dark:text-rose-300">
                <?php echo e($message); ?>

            </div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="space-y-4">
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="grid gap-0 lg:grid-cols-[280px_minmax(0,1fr)]">
                    <div class="relative min-h-[250px] bg-slate-100 dark:bg-slate-950">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fotoProperti !== ''): ?>
                            <img src="<?php echo e($fotoProperti); ?>" alt="<?php echo e($kosan->nama_properti); ?>" class="h-full w-full object-cover">
                        <?php else: ?>
                            <div class="flex h-full min-h-[250px] items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 text-sm font-semibold uppercase tracking-[0.22em] text-slate-500 dark:from-slate-900 dark:to-slate-800 dark:text-slate-400">
                                Foto Properti
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div class="absolute left-4 top-4 inline-flex items-center gap-2 rounded-full bg-white/90 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-[#0F4C81] shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">meeting_room</span>
                            Kelola Unit
                        </div>
                    </div>

                    <div class="flex flex-col justify-between p-6">
                        <div>
                            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white"><?php echo e($kosan->nama_properti); ?></h3>
                                    <div class="mt-2 flex items-start gap-2 text-sm text-slate-500 dark:text-slate-400">
                                        <span class="material-symbols-outlined mt-0.5 text-[18px] text-[#0F4C81] dark:text-blue-300">location_on</span>
                                        <span><?php echo e($kosan->alamat_lengkap); ?></span>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-2 sm:flex-row lg:flex-col lg:items-stretch">
                                    <a
                                        href="<?php echo e(route('mitra.properti.tambah-kosan', ['edit' => $kosan->id])); ?>"
                                        class="inline-flex min-w-[170px] items-center justify-center gap-2 whitespace-nowrap rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-slate-700 dark:text-slate-200 dark:hover:border-blue-400 dark:hover:text-blue-300"
                                    >
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                        Edit Profil
                                    </a>

                                    <button
                                        type="button"
                                        wire:click="selesai"
                                        class="inline-flex min-w-[170px] items-center justify-center gap-2 whitespace-nowrap rounded-xl bg-[#0F4C81] px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-[#0F4C81]/20 transition hover:bg-[#0c3d68]"
                                    >
                                        <span class="material-symbols-outlined text-[18px]">task_alt</span>
                                        Simpan & Selesai
                                    </button>
                                </div>
                            </div>

                            <div class="mt-5 rounded-2xl border border-slate-100 bg-slate-50 px-4 py-4 dark:border-slate-800 dark:bg-slate-950">
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400 dark:text-slate-500">Peraturan Kos</p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($peraturanKos->isNotEmpty()): ?>
                                    <div class="mt-3 space-y-2.5">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $peraturanKos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aturan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="flex items-start gap-3 text-sm leading-6 text-slate-600 dark:text-slate-300">
                                                <span class="mt-2 h-1.5 w-1.5 rounded-full bg-[#0F4C81] dark:bg-blue-300"></span>
                                                <span><?php echo e($aturan); ?></span>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">Belum ada peraturan kos yang ditambahkan.</p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-[minmax(0,0.9fr)_minmax(0,1fr)_minmax(0,1fr)_minmax(0,1.35fr)]">
                <div class="min-w-0 rounded-2xl border border-slate-200 bg-white px-4 py-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[#0F4C81] dark:text-blue-300">Tipe Kamar</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900 dark:text-white"><?php echo e($totalTipe); ?></p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Kategori kamar aktif</p>
                </div>

                <div class="min-w-0 rounded-2xl border border-slate-200 bg-white px-4 py-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-emerald-600 dark:text-emerald-300">Kamar Tersedia</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900 dark:text-white"><?php echo e($kamarTersedia); ?></p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Dari total <?php echo e($totalKamar); ?> kamar</p>
                </div>

                <div class="min-w-0 rounded-2xl border border-slate-200 bg-white px-4 py-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-amber-600 dark:text-amber-300">Kamar Dihuni</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900 dark:text-white"><?php echo e($kamarDihuni); ?></p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Booking aktif berjalan</p>
                </div>

                <div class="min-w-0 rounded-2xl border border-slate-200 bg-white px-4 py-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[#0F4C81] dark:text-blue-300">Harga Listing</p>
                    <p class="mt-2 truncate text-[1.35rem] font-bold leading-tight text-slate-900 dark:text-white" title="<?php echo e($kosan->harga_range ?? 'Belum diatur'); ?>"><?php echo e($kosan->harga_range ?? 'Belum diatur'); ?></p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Rentang harga yang tampil</p>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[#0F4C81] dark:text-blue-300">Panduan Pengelolaan</p>
                <h3 class="mt-1 text-sm font-bold text-slate-900 dark:text-white">Urutan Input</h3>

                <div class="mt-4 grid gap-2.5 md:grid-cols-3">
                    <div class="rounded-xl border border-slate-100 bg-slate-50 px-3 py-3 dark:border-slate-800 dark:bg-slate-950">
                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-6 w-6 items-center justify-center rounded-full bg-[#0F4C81] text-xs font-bold text-white">1</span>
                            <div>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">Buat tipe kamar</p>
                                <p class="mt-0.5 text-xs leading-5 text-slate-500 dark:text-slate-400">Pisahkan berdasarkan harga, fasilitas, dan interior.</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-100 bg-slate-50 px-3 py-3 dark:border-slate-800 dark:bg-slate-950">
                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500 text-xs font-bold text-white">2</span>
                            <div>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">Isi nomor kamar spesifik</p>
                                <p class="mt-0.5 text-xs leading-5 text-slate-500 dark:text-slate-400">Gunakan format seperti A1, A2, atau Lantai 2 - 01.</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-100 bg-slate-50 px-3 py-3 dark:border-slate-800 dark:bg-slate-950">
                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-6 w-6 items-center justify-center rounded-full bg-amber-500 text-xs font-bold text-white">3</span>
                            <div>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">Simpan sebelum selesai</p>
                                <p class="mt-0.5 text-xs leading-5 text-slate-500 dark:text-slate-400">Pastikan tidak ada draft tipe atau kamar yang tertinggal.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="border-b border-slate-100 px-6 py-5 dark:border-slate-800">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[#0F4C81] dark:text-blue-300">Tambah Tipe Kamar</p>
                    <h3 class="mt-2 text-lg font-bold text-slate-900 dark:text-white">Buat Kategori Unit Baru</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Pisahkan kamar berdasarkan harga, fasilitas, dan foto interior.</p>
                </div>

                <form wire:submit.prevent="tambahTipeKamar" class="space-y-5 px-6 py-6">
                    <div class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_320px] lg:items-start">
                        <div class="space-y-5">
                            <div>
                                <label for="nama_tipe" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Nama Tipe <span class="text-rose-600">*</span></label>
                                <input
                                    id="nama_tipe"
                                    type="text"
                                    wire:model.defer="nama_tipe"
                                    class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                    placeholder="Contoh: Tipe AC"
                                >
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nama_tipe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-rose-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div>
                                <label for="harga_per_bulan" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Harga per Bulan <span class="text-rose-600">*</span></label>
                                <input
                                    id="harga_per_bulan"
                                    type="number"
                                    min="0"
                                    wire:model.defer="harga_per_bulan"
                                    class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                    placeholder="850000"
                                >
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['harga_per_bulan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-rose-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div>
                                <label for="fasilitas_tipe" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Fasilitas Tipe <span class="text-rose-600">*</span></label>
                                <textarea
                                    id="fasilitas_tipe"
                                    rows="5"
                                    wire:model.defer="fasilitas_tipe"
                                    class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                    placeholder="Contoh: AC, kasur, lemari, meja belajar, kamar mandi dalam"
                                ></textarea>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['fasilitas_tipe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-rose-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                        <div>
                            <label for="foto_interior" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Foto Interior</label>
                            <input
                                id="foto_interior"
                                type="file"
                                wire:model="foto_interior"
                                accept=".jpg,.jpeg,.png,.webp"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm file:mr-4 file:rounded-lg file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:font-semibold file:text-[#0F4C81] hover:file:bg-blue-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200 dark:file:bg-slate-800 dark:file:text-blue-300"
                            >
                            <p class="mt-2 text-xs leading-5 text-slate-500 dark:text-slate-400">Opsional. Jika diunggah, foto ini akan menjadi foto interior utama untuk tipe kamar.</p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['foto_interior'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-rose-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($foto_interior): ?>
                                <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700">
                                    <img src="<?php echo e($foto_interior->temporaryUrl()); ?>" alt="Preview foto interior" class="aspect-[4/3] w-full object-cover">
                                </div>
                            <?php else: ?>
                                <div class="mt-4 flex aspect-[4/3] items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-slate-50 text-center text-sm font-medium text-slate-400 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-500">
                                    <div class="space-y-2">
                                        <span class="material-symbols-outlined text-[32px]">imagesmode</span>
                                        <p>Preview foto interior akan tampil di sini</p>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:target="tambahTipeKamar,foto_interior"
                            class="inline-flex w-full items-center justify-center gap-2 whitespace-nowrap rounded-xl bg-[#0F4C81] px-5 py-3 text-sm font-semibold text-white shadow-md shadow-[#0F4C81]/20 transition hover:bg-[#0c3d68] disabled:cursor-not-allowed disabled:opacity-70 sm:w-auto"
                        >
                            <svg wire:loading wire:target="tambahTipeKamar,foto_interior" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4Z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="tambahTipeKamar,foto_interior">Simpan Tipe Kamar</span>
                            <span wire:loading wire:target="tambahTipeKamar,foto_interior">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </section>
        </div>

        <section class="space-y-4">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[#0F4C81] dark:text-blue-300">Daftar Tipe Kamar</p>
                    <h3 class="mt-2 text-xl font-bold text-slate-900 dark:text-white">Kelola Tipe dan Nomor Kamar</h3>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400"><?php echo e($totalKamar); ?> kamar tercatat, <?php echo e($kamarTersedia); ?> masih tersedia.</p>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kosan->tipeKamar->isEmpty()): ?>
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Belum ada tipe kamar</h3>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Tambahkan tipe kamar pertama agar nomor unit bisa mulai dikelola.</p>
                </div>
            <?php else: ?>
                <div class="space-y-6">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $kosan->tipeKamar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipeKamar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $fotoInterior = $tipeKamar->getMediaDisplayUrl('foto_interior');
                            $tersediaTipe = $tipeKamar->kamar->where('status_kamar', 'tersedia')->count();
                            $dihuniTipe = $tipeKamar->kamar->where('status_kamar', 'dihuni')->count();
                            $perbaikanTipe = $tipeKamar->kamar->where('status_kamar', 'perbaikan')->count();
                        ?>

                        <article wire:key="tipe-kamar-<?php echo e($tipeKamar->id); ?>" class="overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm transition-shadow hover:shadow-md dark:border-slate-800 dark:bg-slate-900">
                            <div class="grid gap-0 xl:grid-cols-[300px_minmax(0,1fr)]">
                                <div class="border-b border-slate-200 bg-slate-100 p-4 dark:border-slate-800 dark:bg-slate-950 xl:border-b-0 xl:border-r">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fotoInterior !== ''): ?>
                                        <img src="<?php echo e($fotoInterior); ?>" alt="<?php echo e($tipeKamar->nama_tipe); ?>" class="aspect-[4/3] h-full w-full rounded-2xl border border-slate-200 bg-white object-contain p-2 dark:border-slate-800 dark:bg-slate-900">
                                    <?php else: ?>
                                        <div class="flex aspect-[4/3] h-full min-h-[220px] flex-col items-center justify-center gap-3 rounded-2xl border border-dashed border-slate-300 bg-gradient-to-br from-slate-100 to-slate-200 text-sm font-semibold text-slate-500 dark:border-slate-700 dark:from-slate-900 dark:to-slate-800 dark:text-slate-400">
                                            <span class="material-symbols-outlined text-[34px]">imagesmode</span>
                                            <span>Foto interior belum ada</span>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="p-5 sm:p-6">
                                    <div class="flex flex-col gap-4 border-b border-slate-100 pb-5 dark:border-slate-800 lg:flex-row lg:items-start lg:justify-between">
                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <h4 class="text-xl font-bold text-slate-900 dark:text-white"><?php echo e($tipeKamar->nama_tipe); ?></h4>
                                                <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-[#0F4C81] dark:bg-blue-950/30 dark:text-blue-300">
                                                    Rp <?php echo e(number_format($tipeKamar->harga_per_bulan, 0, ',', '.')); ?> / bulan
                                                </span>
                                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300">
                                                    <?php echo e($tersediaTipe); ?> tersedia
                                                </span>
                                            </div>
                                            <p class="mt-3 text-sm leading-6 text-slate-500 dark:text-slate-400"><?php echo e($tipeKamar->fasilitas_tipe); ?></p>
                                        </div>

                                        <button
                                            type="button"
                                            wire:click="hapusTipeKamar(<?php echo e($tipeKamar->id); ?>)"
                                            wire:confirm="Hapus tipe kamar ini beserta daftar kamar di dalamnya?"
                                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl border border-rose-200 bg-rose-50 px-3.5 py-2 text-sm font-semibold text-rose-700 transition hover:bg-rose-100 dark:border-rose-900/40 dark:bg-rose-950/20 dark:text-rose-300"
                                        >
                                            <span class="material-symbols-outlined text-[17px]">delete</span>
                                            Hapus Tipe
                                        </button>
                                    </div>

                                    <div class="mt-5 grid gap-3 sm:grid-cols-3">
                                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-950">
                                            <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">Total Kamar</p>
                                            <p class="mt-2 text-lg font-bold text-slate-900 dark:text-white"><?php echo e($tipeKamar->kamar->count()); ?></p>
                                        </div>
                                        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 dark:border-emerald-900/40 dark:bg-emerald-950/20">
                                            <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-emerald-600 dark:text-emerald-300">Tersedia</p>
                                            <p class="mt-2 text-lg font-bold text-emerald-700 dark:text-emerald-300"><?php echo e($tersediaTipe); ?></p>
                                        </div>
                                        <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 dark:border-amber-900/40 dark:bg-amber-950/20">
                                            <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-amber-600 dark:text-amber-300">Dihuni / Perbaikan</p>
                                            <p class="mt-2 text-lg font-bold text-amber-700 dark:text-amber-300"><?php echo e($dihuniTipe + $perbaikanTipe); ?></p>
                                        </div>
                                    </div>

                                    <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950">
                                        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                                            <div>
                                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[#0F4C81] dark:text-blue-300">Nomor Kamar</p>
                                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Tambahkan nomor unit lalu simpan.</p>
                                            </div>

                                            <div class="flex flex-wrap gap-2">
                                                <button
                                                    type="button"
                                                    wire:click="tambahInputKamar(<?php echo e($tipeKamar->id); ?>)"
                                                    class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 transition hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-blue-400 dark:hover:text-blue-300"
                                                >
                                                    <span class="material-symbols-outlined text-[17px]">add</span>
                                                    Tambah Input
                                                </button>

                                                <button
                                                    type="button"
                                                    wire:click="simpanKamar(<?php echo e($tipeKamar->id); ?>)"
                                                    class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl bg-[#0F4C81] px-3.5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0c3d68]"
                                                >
                                                    <span class="material-symbols-outlined text-[17px]">save</span>
                                                    Simpan
                                                </button>
                                            </div>
                                        </div>

                                        <div class="mt-4 space-y-2.5">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $roomInputs[$tipeKamar->id] ?? ['']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div wire:key="input-kamar-<?php echo e($tipeKamar->id); ?>-<?php echo e($index); ?>" class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                                    <input
                                                        type="text"
                                                        wire:model.defer="roomInputs.<?php echo e($tipeKamar->id); ?>.<?php echo e($index); ?>"
                                                        class="w-full rounded-xl border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                                        placeholder="Contoh: A1 atau Lantai 2 - 01"
                                                    >
                                                    <button
                                                        type="button"
                                                        wire:click="hapusInputKamar(<?php echo e($tipeKamar->id); ?>, <?php echo e($index); ?>)"
                                                        class="inline-flex items-center justify-center gap-1 whitespace-nowrap rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-white dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900"
                                                    >
                                                        <span class="material-symbols-outlined text-[17px]">remove</span>
                                                        Hapus
                                                    </button>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['roomInputs.'.$tipeKamar->id];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-3 text-sm text-rose-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>

                                    <div class="mt-5">
                                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Kamar Terdaftar</p>
                                            <p class="text-sm text-slate-500 dark:text-slate-400"><?php echo e($tersediaTipe); ?> tersedia, <?php echo e($dihuniTipe); ?> dihuni, <?php echo e($perbaikanTipe); ?> perbaikan</p>
                                        </div>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tipeKamar->kamar->isEmpty()): ?>
                                            <div class="mt-3 rounded-2xl border border-dashed border-slate-300 px-4 py-8 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
                                                Belum ada nomor kamar untuk tipe ini.
                                            </div>
                                        <?php else: ?>
                                            <div class="mt-3 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $tipeKamar->kamar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kamar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div wire:key="kamar-<?php echo e($kamar->id); ?>" class="rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                                                        <div class="flex items-center justify-between gap-3">
                                                            <div>
                                                                <p class="text-base font-bold text-slate-900 dark:text-white"><?php echo e($kamar->nomor_kamar); ?></p>
                                                                <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                                    'mt-2 inline-flex rounded-full px-3 py-1 text-xs font-semibold',
                                                                    'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300' => $kamar->status_kamar === 'tersedia',
                                                                    'bg-amber-100 text-amber-700 dark:bg-amber-950/30 dark:text-amber-300' => $kamar->status_kamar === 'dihuni',
                                                                    'bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300' => $kamar->status_kamar === 'perbaikan',
                                                                ]); ?>">
                                                                    <?php echo e(ucfirst($kamar->status_kamar)); ?>

                                                                </span>
                                                            </div>

                                                            <button
                                                                type="button"
                                                                wire:click="hapusKamar(<?php echo e($kamar->id); ?>)"
                                                                wire:confirm="Hapus kamar <?php echo e($kamar->nomor_kamar); ?>?"
                                                                class="inline-flex items-center justify-center gap-1 whitespace-nowrap rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 transition hover:bg-rose-100 dark:border-rose-900/40 dark:bg-rose-950/20 dark:text-rose-300"
                                                            >
                                                                <span class="material-symbols-outlined text-[15px]">delete</span>
                                                                Hapus
                                                            </button>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </section>

        <div class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Selesai mengatur unit kos?</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Gunakan tombol ini setelah semua tipe kamar dan nomor kamar sudah tersimpan tanpa draft tertinggal.</p>
            </div>

            <button
                type="button"
                wire:click="selesai"
                class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl bg-[#0F4C81] px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-[#0F4C81]/20 transition hover:bg-[#0c3d68]"
            >
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                Simpan & Kembali ke Properti Saya
            </button>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\appkonkos-proyek2\resources\views/livewire/mitra/properti/kelola-kamar.blade.php ENDPATH**/ ?>