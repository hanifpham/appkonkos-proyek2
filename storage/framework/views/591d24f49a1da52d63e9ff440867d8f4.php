<div
    x-data="{ open: false }"
    wire:poll.15s.visible
    class="relative"
>
    <button
        type="button"
        @click="open = !open"
        class="relative inline-flex h-10 w-10 items-center justify-center rounded-xl border border-transparent text-slate-500 transition hover:bg-slate-100 hover:text-[#0F4C81] dark:text-slate-300 dark:hover:bg-slate-900 dark:hover:text-white"
        aria-label="Notifikasi"
    >
        <span class="material-symbols-outlined text-[22px]">notifications</span>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($unreadCount > 0): ?>
            <span class="absolute right-1 top-1 h-2.5 w-2.5 rounded-full bg-red-500 shadow-[0_0_0_3px_rgba(255,255,255,0.92)] dark:shadow-[0_0_0_3px_rgba(16,25,44,0.92)]"></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </button>

    <div
        x-cloak
        x-show="open"
        x-transition.origin.top.right
        @click.outside="open = false"
        class="absolute right-0 top-[calc(100%+12px)] z-50 w-[340px] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_18px_40px_rgba(15,23,42,0.16)] dark:border-slate-700 dark:bg-slate-900"
    >
        <div class="border-b border-slate-200 px-4 py-3 dark:border-slate-700">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-semibold text-slate-900 dark:text-white">Notifikasi</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400"><?php echo e($subtitle); ?></p>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($unreadCount > 0): ?>
                    <button
                        type="button"
                        wire:click="markAllAsRead"
                        class="text-xs font-semibold text-[#0F4C81] transition hover:underline dark:text-blue-400"
                    >
                        Tandai semua
                    </button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <div class="max-h-[360px] overflow-y-auto">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $palette = $this->getNotificationPalette($notification);
                    $actionUrl = $this->getNotificationActionUrl($notification);
                    $reason = $this->getNotificationReason($notification);
                ?>

                <div wire:key="notification-<?php echo e($notification->id); ?>" class="border-b border-slate-100 px-4 py-3 last:border-b-0 dark:border-slate-800">
                    <div class="flex items-start gap-3">
                        <div class="<?php echo e($palette['wrapper']); ?> flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl">
                            <span class="material-symbols-outlined text-[18px] <?php echo e($palette['icon']); ?>"><?php echo e($this->getNotificationIcon($notification)); ?></span>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-3">
                                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                                    <?php echo e($this->getNotificationTitle($notification)); ?>

                                </p>
                                <button
                                    type="button"
                                    wire:click="markAsRead('<?php echo e($notification->id); ?>')"
                                    class="text-[11px] font-medium text-slate-400 transition hover:text-[#0F4C81] dark:text-slate-500 dark:hover:text-blue-400"
                                >
                                    Baca
                                </button>
                            </div>

                            <p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400">
                                <?php echo e($this->getNotificationMessage($notification)); ?>

                            </p>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($reason !== null): ?>
                                <p class="mt-2 rounded-xl bg-red-50 px-3 py-2 text-[11px] italic text-red-600 dark:bg-red-950/20 dark:text-red-300">
                                    Alasan: <?php echo e($reason); ?>

                                </p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <div class="mt-2 flex items-center justify-between gap-3">
                                <p class="text-[11px] text-slate-400 dark:text-slate-500">
                                    <?php echo e($this->getNotificationTime($notification)); ?>

                                </p>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($actionUrl !== null): ?>
                                    <a
                                        href="<?php echo e($actionUrl); ?>"
                                        @click="open = false"
                                        class="text-[11px] font-semibold text-[#0F4C81] hover:underline dark:text-blue-400"
                                    >
                                        <?php echo e($this->getNotificationActionLabel($notification)); ?>

                                    </a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="px-4 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                    Tidak ada notifikasi baru.
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->getNotificationListUrl() !== null): ?>
            <div class="border-t border-slate-200 px-4 py-3 dark:border-slate-700">
                <a
                    href="<?php echo e($this->getNotificationListUrl()); ?>"
                    @click="open = false"
                    class="inline-flex items-center gap-2 text-xs font-semibold text-[#0F4C81] transition hover:underline dark:text-blue-400"
                >
                    Lihat semua notifikasi
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\appkonkos_2\resources\views/livewire/common/notification-bell.blade.php ENDPATH**/ ?>