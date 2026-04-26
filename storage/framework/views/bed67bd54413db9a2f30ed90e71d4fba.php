<div class="space-y-5">
    <p class="max-w-xl text-sm leading-7 text-gray-600 dark:text-gray-400">
        Kelola sesi aktif di perangkat dan browser lain. Jika akun terasa tidak aman, keluarkan sesi lain lalu segera ganti password.
    </p>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($this->sessions) > 0): ?>
        <div class="space-y-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $this->sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-slate-800/60">
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-gray-700 dark:text-gray-200">
                            <?php echo e($session->agent->platform() ?: 'Tidak diketahui'); ?> - <?php echo e($session->agent->browser() ?: 'Tidak diketahui'); ?>

                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            <?php echo e($session->ip_address); ?>,
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($session->is_current_device): ?>
                                <span class="font-semibold text-green-600 dark:text-green-400">Perangkat ini</span>
                            <?php else: ?>
                                Terakhir aktif <?php echo e($session->last_active); ?>

                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="flex flex-wrap items-center gap-3 border-t border-gray-100 pt-5 dark:border-gray-700">
        <button
            type="button"
            wire:click="confirmLogout"
            wire:loading.attr="disabled"
            class="rounded-lg bg-[#113C7A] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-[#0d3f6d] disabled:opacity-60"
        >
            Keluar dari Sesi Browser Lain
        </button>

        <?php if (isset($component)) { $__componentOriginal2a5dc72a59c2daa47555926796a349a3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2a5dc72a59c2daa47555926796a349a3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.pesan-aksi','data' => ['class' => 'text-sm text-gray-500 dark:text-gray-400','on' => 'loggedOut']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.pesan-aksi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'text-sm text-gray-500 dark:text-gray-400','on' => 'loggedOut']); ?>
            Selesai.
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2a5dc72a59c2daa47555926796a349a3)): ?>
<?php $attributes = $__attributesOriginal2a5dc72a59c2daa47555926796a349a3; ?>
<?php unset($__attributesOriginal2a5dc72a59c2daa47555926796a349a3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2a5dc72a59c2daa47555926796a349a3)): ?>
<?php $component = $__componentOriginal2a5dc72a59c2daa47555926796a349a3; ?>
<?php unset($__componentOriginal2a5dc72a59c2daa47555926796a349a3); ?>
<?php endif; ?>
    </div>

    <?php if (isset($component)) { $__componentOriginalf035e7f8c4f13beaf7da0b49976299e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf035e7f8c4f13beaf7da0b49976299e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal.modal-dialog','data' => ['wire:model.live' => 'confirmingLogout']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal.modal-dialog'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'confirmingLogout']); ?>
         <?php $__env->slot('title', null, []); ?> 
            Keluar dari Sesi Browser Lain
         <?php $__env->endSlot(); ?>

         <?php $__env->slot('content', null, []); ?> 
            <p class="text-sm leading-7 text-gray-600 dark:text-gray-400">
                Masukkan password untuk mengonfirmasi bahwa Anda ingin keluar dari sesi browser lain di semua perangkat.
            </p>

            <div class="mt-4" x-data="{}" x-on:confirming-logout-other-browser-sessions.window="setTimeout(() => $refs.password.focus(), 250)">
                <input
                    type="password"
                    class="mt-1 block w-full rounded-lg border-gray-200 px-4 py-2.5 text-sm focus:border-[#113C7A] focus:ring-[#113C7A] dark:border-gray-700 dark:bg-slate-800"
                    autocomplete="current-password"
                    placeholder="Password"
                    x-ref="password"
                    wire:model="password"
                    wire:keydown.enter="logoutOtherBrowserSessions"
                >

                <?php if (isset($component)) { $__componentOriginal32238dede262f8e0c0df7459ace9932b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal32238dede262f8e0c0df7459ace9932b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.error-input','data' => ['for' => 'password','class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.error-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'password','class' => 'mt-2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal32238dede262f8e0c0df7459ace9932b)): ?>
<?php $attributes = $__attributesOriginal32238dede262f8e0c0df7459ace9932b; ?>
<?php unset($__attributesOriginal32238dede262f8e0c0df7459ace9932b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal32238dede262f8e0c0df7459ace9932b)): ?>
<?php $component = $__componentOriginal32238dede262f8e0c0df7459ace9932b; ?>
<?php unset($__componentOriginal32238dede262f8e0c0df7459ace9932b); ?>
<?php endif; ?>
            </div>
         <?php $__env->endSlot(); ?>

         <?php $__env->slot('footer', null, []); ?> 
            <button type="button" wire:click="$toggle('confirmingLogout')" wire:loading.attr="disabled" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition-all duration-200 hover:bg-gray-100 dark:border-gray-700 dark:bg-slate-900 dark:text-gray-300 dark:hover:bg-gray-800">
                Batal
            </button>

            <button type="button" class="ms-3 rounded-lg bg-[#113C7A] px-4 py-2 text-sm font-semibold text-white transition-all duration-200 hover:bg-[#0d3f6d]" wire:click="logoutOtherBrowserSessions" wire:loading.attr="disabled">
                Keluar dari Sesi Lain
            </button>
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf035e7f8c4f13beaf7da0b49976299e9)): ?>
<?php $attributes = $__attributesOriginalf035e7f8c4f13beaf7da0b49976299e9; ?>
<?php unset($__attributesOriginalf035e7f8c4f13beaf7da0b49976299e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf035e7f8c4f13beaf7da0b49976299e9)): ?>
<?php $component = $__componentOriginalf035e7f8c4f13beaf7da0b49976299e9; ?>
<?php unset($__componentOriginalf035e7f8c4f13beaf7da0b49976299e9); ?>
<?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\appkonkos_2\resources\views/profile/logout-other-browser-sessions-form.blade.php ENDPATH**/ ?>