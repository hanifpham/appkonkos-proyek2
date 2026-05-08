<?php $__env->startSection('mitra-title', 'Notifikasi'); ?>
<?php $__env->startSection('mitra-subtitle', 'Lihat pembaruan booking, status pencairan dana, dan moderasi properti Anda.'); ?>

<div class="flex-1 space-y-8 p-6 md:p-8">
    <section class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Belum Dibaca</p>
            <div class="mt-3 flex items-center justify-between">
                <h3 class="text-3xl font-bold text-slate-900 dark:text-white"><?php echo e($unreadCount); ?></h3>
                <span class="material-symbols-outlined rounded-xl bg-red-50 p-2 text-red-500 dark:bg-red-950/30 dark:text-red-300">notifications_active</span>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Notifikasi</p>
            <div class="mt-3 flex items-center justify-between">
                <h3 class="text-3xl font-bold text-slate-900 dark:text-white"><?php echo e($totalCount); ?></h3>
                <span class="material-symbols-outlined rounded-xl bg-blue-50 p-2 text-[#0F4C81] dark:bg-blue-950/30 dark:text-blue-300">notifications</span>
            </div>
        </div>
    </section>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex flex-col gap-4 border-b border-slate-200 p-6 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-wrap items-center gap-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['all' => 'Semua', 'unread' => 'Belum Dibaca']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button
                        type="button"
                        wire:click="$set('tab', '<?php echo e($value); ?>')"
                        class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                            'rounded-full border px-4 py-2 text-sm font-semibold transition',
                            'border-[#0F4C81] bg-[#0F4C81] text-white shadow-md' => $tab === $value,
                            'border-slate-200 bg-white text-slate-600 hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:border-blue-400 dark:hover:text-blue-300' => $tab !== $value,
                        ]); ?>"
                    >
                        <?php echo e($label); ?>

                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($unreadCount > 0): ?>
                <button
                    type="button"
                    wire:click="markAllAsRead"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-blue-400 dark:hover:text-blue-300"
                >
                    <span class="material-symbols-outlined text-[18px]">done_all</span>
                    Tandai Semua Dibaca
                </button>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div class="divide-y divide-slate-100 dark:divide-slate-800">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $palette = $this->getNotificationPalette($notification);
                    $reason = $this->getNotificationReason($notification);
                    $actionUrl = $this->getNotificationActionUrl($notification);
                ?>
                <article wire:key="mitra-notification-<?php echo e($notification->id); ?>" class="px-6 py-5">
                    <div class="flex items-start gap-4">
                        <div class="<?php echo e($palette['wrapper']); ?> flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl">
                            <span class="material-symbols-outlined text-[20px] <?php echo e($palette['icon']); ?>"><?php echo e($this->getNotificationIcon($notification)); ?></span>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="text-base font-bold text-slate-900 dark:text-white"><?php echo e($this->getNotificationTitle($notification)); ?></h3>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($notification->read_at === null): ?>
                                            <span class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-red-600 dark:bg-red-950/30 dark:text-red-300">
                                                Baru
                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300"><?php echo e($this->getNotificationMessage($notification)); ?></p>
                                </div>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($notification->read_at === null): ?>
                                    <button
                                        type="button"
                                        wire:click="markAsRead('<?php echo e($notification->id); ?>')"
                                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:border-blue-400 dark:hover:text-blue-300"
                                    >
                                        <span class="material-symbols-outlined text-[16px]">done</span>
                                        Tandai Dibaca
                                    </button>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($reason !== null): ?>
                                <div class="mt-3 rounded-2xl border border-red-100 bg-red-50 px-4 py-3 text-sm font-medium text-red-600 dark:border-red-900/40 dark:bg-red-950/20 dark:text-red-300">
                                    Alasan: <?php echo e($reason); ?>

                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <div class="mt-3 flex flex-col gap-3 text-xs text-slate-400 dark:text-slate-500 sm:flex-row sm:items-center sm:justify-between">
                                <span><?php echo e($this->getNotificationTime($notification)); ?></span>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($actionUrl !== null): ?>
                                    <a
                                        href="<?php echo e($actionUrl); ?>"
                                        class="inline-flex items-center gap-2 font-semibold text-[#0F4C81] transition hover:underline dark:text-blue-400"
                                    >
                                        <?php echo e($this->getNotificationActionLabel($notification)); ?>

                                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                    </a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="px-6 py-16 text-center text-sm text-slate-500 dark:text-slate-400">
                    Tidak ada notifikasi pada tab ini.
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div class="border-t border-slate-200 px-6 py-4 dark:border-slate-800">
            <?php echo e($notifications->links()); ?>

        </div>
    </section>
</div>
<?php /**PATH C:\xampp\htdocs\appkonkos-proyek2\resources\views/livewire/mitra/notifikasi.blade.php ENDPATH**/ ?>