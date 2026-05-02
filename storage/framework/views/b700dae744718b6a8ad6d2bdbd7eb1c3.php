<?php $__env->startSection('mitra-title', 'Daftar Properti Saya'); ?>
<?php $__env->startSection('mitra-subtitle', 'Total '.$totalProperti.' properti terdaftar saat ini'); ?>

<div class="px-4 py-8 sm:px-6 xl:px-8">
    <div class="mx-auto w-full max-w-6xl space-y-8 pb-16">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('success')): ?>
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div class="flex flex-wrap items-center gap-3">
                <button
                    type="button"
                    wire:click="setFilter('semua')"
                    class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                        'rounded-full border px-6 py-2 text-sm font-semibold transition-all',
                        'border-[#0F4C81] bg-[#0F4C81] text-white shadow-md' => $filterTab === 'semua',
                        'border-gray-200 bg-white text-gray-600 hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-gray-700 dark:bg-slate-900 dark:text-gray-300' => $filterTab !== 'semua',
                    ]); ?>"
                >
                    Semua
                </button>
                <button
                    type="button"
                    wire:click="setFilter('kosan')"
                    class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                        'rounded-full border px-6 py-2 text-sm font-semibold transition-all',
                        'border-[#0F4C81] bg-[#0F4C81] text-white shadow-md' => $filterTab === 'kosan',
                        'border-gray-200 bg-white text-gray-600 hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-gray-700 dark:bg-slate-900 dark:text-gray-300' => $filterTab !== 'kosan',
                    ]); ?>"
                >
                    Kos
                </button>
                <button
                    type="button"
                    wire:click="setFilter('kontrakan')"
                    class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                        'rounded-full border px-6 py-2 text-sm font-semibold transition-all',
                        'border-[#0F4C81] bg-[#0F4C81] text-white shadow-md' => $filterTab === 'kontrakan',
                        'border-gray-200 bg-white text-gray-600 hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-gray-700 dark:bg-slate-900 dark:text-gray-300' => $filterTab !== 'kontrakan',
                    ]); ?>"
                >
                    Kontrakan
                </button>
            </div>

            <div x-data="{ open: false }" class="relative">
                <button
                    type="button"
                    @click="open = ! open"
                    @click.outside="open = false"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0F4C81] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-[#0F4C81]/20 transition-all hover:bg-[#0c3d68]"
                >
                    <span class="material-symbols-outlined text-[20px]">add_circle</span>
                    Tambah Properti
                </button>

                <div
                    x-cloak
                    x-show="open"
                    x-transition.origin.top.right
                    class="absolute right-0 z-20 mt-3 w-[220px] overflow-hidden rounded-2xl border border-slate-200 bg-white p-1 shadow-xl dark:border-slate-700 dark:bg-slate-800"
                >
                    <a
                        href="<?php echo e(route('mitra.properti.tambah-kosan')); ?>"
                        class="flex items-center justify-between rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 hover:text-[#0F4C81] dark:text-slate-200 dark:hover:bg-slate-700/70 dark:hover:text-white"
                    >
                        Tambah Kos
                        <span class="material-symbols-outlined text-[18px] text-slate-400 dark:text-slate-500">arrow_forward</span>
                    </a>
                    <a
                        href="<?php echo e(route('mitra.properti.tambah-kontrakan')); ?>"
                        class="flex items-center justify-between rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 hover:text-[#0F4C81] dark:text-slate-200 dark:hover:bg-slate-700/70 dark:hover:text-white"
                    >
                        Tambah Kontrakan
                        <span class="material-symbols-outlined text-[18px] text-slate-400 dark:text-slate-500">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($propertyCards->isEmpty()): ?>
            <div class="rounded-2xl border border-dashed border-gray-300 bg-white px-6 py-16 text-center shadow-sm dark:border-gray-700 dark:bg-slate-900">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Belum ada properti yang cocok dengan filter ini</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Tambahkan properti baru untuk mulai mengelola listing Anda.</p>
            </div>
        <?php else: ?>
            <div class="space-y-6">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $propertyCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <article wire:key="<?php echo e($property['type']); ?>-<?php echo e($property['id']); ?>" class="group overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:shadow-xl dark:border-gray-800 dark:bg-slate-900">
                        <div class="flex flex-col md:flex-row">
                            <div class="relative h-[240px] w-full shrink-0 overflow-hidden md:w-[380px]">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($property['image_url'] !== ''): ?>
                                    <img alt="<?php echo e($property['name']); ?>" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" src="<?php echo e($property['image_url']); ?>">
                                <?php else: ?>
                                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 text-sm font-semibold text-slate-500 dark:from-slate-800 dark:to-slate-700 dark:text-slate-300">
                                        APPKONKOS
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <div class="absolute left-4 top-4 flex gap-2">
                                    <span class="rounded-lg bg-[#0F4C81]/90 px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest text-white backdrop-blur-md">
                                        <?php echo e($property['label']); ?>

                                    </span>
                                    <span class="<?php echo e($property['category_badge_classes']); ?> rounded-lg px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest backdrop-blur-md">
                                        <?php echo e($property['category_label']); ?>

                                    </span>
                                    <span class="<?php echo e($property['status_badge_classes']); ?> rounded-lg px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest">
                                        <?php echo e($property['status_label']); ?>

                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-1 flex-col justify-between p-6">
                                <div>
                                    <div class="mb-4 flex items-start justify-between gap-4">
                                        <div>
                                            <h3 class="mb-1 text-2xl font-bold text-slate-800 transition-colors group-hover:text-[#0F4C81] dark:text-white dark:group-hover:text-blue-400">
                                                <?php echo e($property['name']); ?>

                                            </h3>
                                            <div class="mb-2 flex flex-wrap items-center gap-2">
                                                <span class="<?php echo e($property['category_badge_classes']); ?> inline-flex items-center rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-widest">
                                                    <?php echo e($property['category_label']); ?>

                                                </span>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($property['moderation_badge_label']): ?>
                                                    <span class="<?php echo e($property['moderation_badge_classes']); ?> inline-flex items-center rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-widest">
                                                        <?php echo e($property['moderation_badge_label']); ?>

                                                    </span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($property['show_rejection_alert']): ?>
                                                <p class="mb-3 text-sm font-bold text-red-600 dark:text-red-300">
                                                    Alasan Penolakan: <?php echo e($property['rejection_reason']); ?>

                                                </p>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <div class="flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400">
                                                <span class="material-symbols-outlined text-[18px] text-[#0F4C81] dark:text-blue-400">location_on</span>
                                                <?php echo e($property['location_label']); ?>

                                            </div>
                                        </div>

                                        <div class="flex gap-2">
                                            <a href="<?php echo e($property['edit_url']); ?>" class="rounded-xl border border-gray-100 p-2.5 text-gray-400 transition-all hover:bg-blue-50 hover:text-[#0F4C81] dark:border-gray-700 dark:hover:bg-slate-800">
                                                <span class="material-symbols-outlined">edit</span>
                                            </a>
                                            <button
                                                type="button"
                                                wire:click="<?php echo e($property['delete_action']); ?>(<?php echo e($property['id']); ?>)"
                                                wire:confirm="<?php echo e($property['delete_confirm']); ?>"
                                                class="rounded-xl border border-gray-100 p-2.5 text-gray-400 transition-all hover:bg-red-50 hover:text-red-500 dark:border-gray-700 dark:hover:bg-slate-800"
                                            >
                                                <span class="material-symbols-outlined">delete</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mb-6 grid gap-4 md:grid-cols-3">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $property['stats']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="<?php echo e($stat['wrapper_classes']); ?> rounded-2xl p-4">
                                                <p class="<?php echo e($stat['label_classes']); ?> mb-1 text-[10px] font-bold uppercase tracking-widest">
                                                    <?php echo e($stat['label']); ?>

                                                </p>
                                                <div class="flex items-center gap-2">
                                                    <span class="material-symbols-outlined <?php echo e($stat['icon_classes']); ?> text-sm"><?php echo e($stat['icon']); ?></span>
                                                    <p class="<?php echo e($stat['value_classes']); ?> text-lg font-bold">
                                                        <?php echo e($stat['value']); ?>

                                                    </p>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between border-t border-gray-100 pt-4 dark:border-gray-800">
                                    <div class="flex flex-col">
                                        <p class="text-[10px] font-semibold uppercase text-gray-400">Harga Mulai</p>
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-2xl font-bold text-[#0F4C81] dark:text-blue-400"><?php echo e($property['price_label']); ?></span>
                                            <span class="text-xs text-gray-400"><?php echo e($property['price_suffix']); ?></span>
                                        </div>
                                    </div>

                                    <a href="<?php echo e($property['manage_url']); ?>" class="inline-flex items-center gap-2 rounded-xl bg-[#0F4C81] px-6 py-2.5 text-sm font-bold text-white shadow-md transition-all hover:bg-[#0c3d68]">
                                        <?php echo e($property['show_rejection_alert'] ? 'Edit & Ajukan Ulang' : 'Kelola Unit'); ?>

                                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\appkonkos-proyek2\resources\views/livewire/mitra/properti/daftar-properti.blade.php ENDPATH**/ ?>