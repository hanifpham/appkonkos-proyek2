<?php $__env->startSection('mitra-title', 'Ulasan Penyewa'); ?>
<?php $__env->startSection('mitra-subtitle', 'Pantau masukan penyewa dan kirim tanggapan langsung dari dashboard mitra.'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        .material-symbols-outlined.fill-1 {
            font-variation-settings: 'FILL' 1, 'wght' 300, 'GRAD' 0, 'opsz' 24;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php
    $fullAverageStars = (int) floor($rataRataRating);
    $hasHalfAverageStar = ($rataRataRating - $fullAverageStars) >= 0.5;
?>

<div class="flex-1 space-y-8 p-6 md:p-8">
    <section class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-700 dark:bg-slate-900">
        <div class="flex flex-col items-center gap-10 md:flex-row">
            <div class="flex min-w-[240px] flex-col items-center justify-center rounded-2xl border border-blue-100 bg-blue-50 p-6 dark:border-blue-800/30 dark:bg-blue-900/20">
                <span class="mb-1 text-xs font-bold uppercase tracking-widest text-[#0F4C81] dark:text-blue-400">Rata-rata Rating</span>
                <div class="flex items-baseline gap-1">
                    <span class="text-5xl font-bold text-slate-800 dark:text-white"><?php echo e(number_format($rataRataRating, 1)); ?></span>
                    <span class="text-xl font-medium text-gray-400">/ 5.0</span>
                </div>
                <div class="my-3 flex text-yellow-400">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 5; $i++): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($i <= $fullAverageStars): ?>
                            <span class="material-symbols-outlined fill-1 text-2xl">star</span>
                        <?php elseif($i === $fullAverageStars + 1 && $hasHalfAverageStar): ?>
                            <span class="material-symbols-outlined fill-1 text-2xl">star_half</span>
                        <?php else: ?>
                            <span class="material-symbols-outlined text-2xl">star</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Berdasarkan <?php echo e(number_format($totalUlasan, 0, ',', '.')); ?> ulasan
                </span>
            </div>

            <div class="grid h-full w-full flex-1 grid-cols-1 gap-8 sm:grid-cols-3">
                <div class="flex flex-col justify-center rounded-xl border border-gray-100 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-slate-800/40">
                    <span class="mb-1 text-sm text-gray-500 dark:text-gray-400">Total Ulasan</span>
                    <span class="text-3xl font-bold text-slate-800 dark:text-white"><?php echo e(number_format($totalUlasan, 0, ',', '.')); ?></span>
                    <div class="mt-2 flex items-center gap-1 text-xs font-medium text-green-600 dark:text-green-400">
                        <span class="material-symbols-outlined text-[14px]">rate_review</span>
                        <span>Semua ulasan properti Anda</span>
                    </div>
                </div>

                <div class="flex flex-col justify-center rounded-xl border border-gray-100 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-slate-800/40">
                    <span class="mb-1 text-sm text-gray-500 dark:text-gray-400">Belum Dibalas</span>
                    <span class="text-3xl font-bold text-red-600 dark:text-red-400"><?php echo e(number_format($belumDibalas, 0, ',', '.')); ?></span>
                    <div class="mt-2 flex items-center gap-1 text-xs font-medium text-red-600 dark:text-red-400">
                        <span class="material-symbols-outlined text-[14px]">priority_high</span>
                        <span>Butuh respon segera</span>
                    </div>
                </div>

                <div class="flex flex-col justify-center rounded-xl border border-gray-100 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-slate-800/40">
                    <span class="mb-1 text-sm text-gray-500 dark:text-gray-400">Kepuasan Penyewa</span>
                    <span class="text-3xl font-bold text-green-600 dark:text-green-400"><?php echo e($kepuasanPenyewa); ?>%</span>
                    <div class="mt-3 h-2 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                        <div class="h-full bg-green-600 dark:bg-green-400" style="width: <?php echo e($kepuasanPenyewa); ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
        <h3 class="text-xl font-bold text-slate-800 dark:text-white">Semua Ulasan</h3>
        <div class="flex w-full gap-3 sm:w-auto">
            <div class="relative flex-1 sm:w-64">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-gray-400">search</span>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    class="w-full rounded-lg border border-gray-200 bg-white py-2 pl-10 pr-4 text-sm text-slate-700 outline-none transition-all focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-gray-700 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                    placeholder="Cari ulasan atau properti..."
                >
            </div>

            <select
                wire:model.live="filter"
                class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-gray-600 focus:ring-[#0F4C81] dark:border-gray-700 dark:bg-slate-900 dark:text-gray-300 dark:focus:ring-blue-400"
            >
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $filterOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($option); ?>"><?php echo e($option); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </select>
        </div>
    </div>

    <div class="flex flex-col gap-6">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $ulasanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ulasan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $penyewa = $ulasan->booking?->pencariKos?->user;
            ?>

            <div
                wire:key="ulasan-<?php echo e($ulasan->id); ?>"
                class="group flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition-all hover:border-[#0F4C81]/30 hover:shadow-md dark:border-gray-700 dark:bg-slate-900"
            >
                <div class="flex-1 p-6">
                    <div class="mb-4 flex items-start justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full border text-lg font-bold <?php echo e($this->getAvatarClasses($ulasan)); ?>">
                                <?php echo e($this->getPenyewaInitials($ulasan)); ?>

                            </div>
                            <div class="flex flex-col">
                                <h4 class="text-base font-bold leading-tight text-slate-800 dark:text-white">
                                    <?php echo e($penyewa?->name ?? 'Penyewa'); ?>

                                </h4>
                                <div class="mt-0.5 flex origin-left scale-75 text-yellow-400">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 5; $i++): ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($i <= $ulasan->rating): ?>
                                            <span class="material-symbols-outlined fill-1">star</span>
                                        <?php else: ?>
                                            <span class="material-symbols-outlined">star</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <span class="rounded bg-gray-100 px-2 py-1 text-[11px] font-medium text-gray-400 dark:bg-gray-800">
                            <?php echo e($ulasan->created_at?->locale('id')->translatedFormat('d M Y') ?? '-'); ?>

                        </span>
                    </div>

                    <div class="mb-3">
                        <div class="flex items-center gap-1.5 text-xs font-semibold text-[#0F4C81] dark:text-blue-300">
                            <span class="material-symbols-outlined text-[16px]"><?php echo e($this->getIkonProperti($ulasan)); ?></span>
                            <?php echo e($this->getNamaProperti($ulasan)); ?>

                        </div>
                    </div>

                    <p class="mb-4 text-sm leading-relaxed text-gray-600 italic dark:text-gray-400">
                        "<?php echo e($ulasan->komentar); ?>"
                    </p>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ulasan->balasan_pemilik !== null): ?>
                        <div class="mt-2 rounded-xl border-l-4 border-[#0F4C81] bg-blue-50 p-4 dark:bg-blue-900/20">
                            <div class="mb-2 flex items-center justify-between">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-[#0F4C81] dark:text-blue-400">
                                    Tanggapan Anda:
                                </span>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">"<?php echo e($ulasan->balasan_pemilik); ?>"</p>
                        </div>
                    <?php elseif($replyingTo === $ulasan->id): ?>
                        <div class="mt-4 rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-slate-800/60">
                            <label for="balasan-<?php echo e($ulasan->id); ?>" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                Tulis Balasan
                            </label>
                            <textarea
                                id="balasan-<?php echo e($ulasan->id); ?>"
                                wire:model="balasanText"
                                rows="4"
                                class="w-full rounded-xl border border-gray-200 bg-white text-sm text-slate-700 focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-gray-700 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                placeholder="Tulis tanggapan Anda untuk ulasan ini..."
                            ></textarea>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['balasanText'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="flex justify-end border-t border-gray-100 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-slate-800/50">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ulasan->balasan_pemilik !== null): ?>
                        <span class="inline-flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 px-4 py-2 text-sm font-semibold text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-300">
                            <span class="material-symbols-outlined text-[18px]">check_circle</span>
                            Sudah Dibalas
                        </span>
                    <?php elseif($replyingTo === $ulasan->id): ?>
                        <button
                            type="button"
                            wire:click="simpanBalasan"
                            wire:loading.attr="disabled"
                            wire:target="simpanBalasan"
                            class="flex items-center gap-2 rounded-lg bg-[#0F4C81] px-5 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-[#0c3c66] active:scale-95 disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            <span class="material-symbols-outlined text-[18px]">send</span>
                            Kirim Balasan
                        </button>
                    <?php else: ?>
                        <button
                            type="button"
                            wire:click="bukaFormBalas(<?php echo e($ulasan->id); ?>)"
                            wire:loading.attr="disabled"
                            wire:target="bukaFormBalas"
                            class="flex items-center gap-2 rounded-lg bg-[#0F4C81] px-5 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-[#0c3c66] active:scale-95 disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            <span class="material-symbols-outlined text-[18px]">reply</span>
                            Balas Ulasan
                        </button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="rounded-2xl border border-dashed border-gray-300 bg-white px-6 py-14 text-center text-sm text-gray-500 dark:border-gray-700 dark:bg-slate-900 dark:text-slate-400">
                Belum ada ulasan yang sesuai dengan pencarian atau filter saat ini.
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div class="flex justify-center pb-12 pt-4">
        <?php echo e($ulasanList->links()); ?>

    </div>
</div>
<?php /**PATH C:\xampp\htdocs\appkonkos_2\resources\views/livewire/mitra/ulasan-penyewa.blade.php ENDPATH**/ ?>