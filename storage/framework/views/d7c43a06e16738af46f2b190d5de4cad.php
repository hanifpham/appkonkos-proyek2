<div class="space-y-5">
    <p class="max-w-xl text-sm leading-7 text-red-700/80 dark:text-red-300/80">
        Setelah akun dihapus, seluruh data dan resource yang terhubung akan dihapus permanen. Pastikan Anda sudah menyimpan data penting sebelum melanjutkan.
    </p>

    <div class="border-t border-red-100 pt-5 dark:border-red-900/50">
        <button type="button" wire:click="confirmUserDeletion" wire:loading.attr="disabled" class="rounded-lg bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-red-700 disabled:opacity-60">
            Hapus Akun
        </button>
    </div>

    <?php if (isset($component)) { $__componentOriginalf035e7f8c4f13beaf7da0b49976299e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf035e7f8c4f13beaf7da0b49976299e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal.modal-dialog','data' => ['wire:model.live' => 'confirmingUserDeletion']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal.modal-dialog'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'confirmingUserDeletion']); ?>
         <?php $__env->slot('title', null, []); ?> 
            Hapus Akun
         <?php $__env->endSlot(); ?>

         <?php $__env->slot('content', null, []); ?> 
            <p class="text-sm leading-7 text-gray-600 dark:text-gray-400">
                Apakah Anda yakin ingin menghapus akun ini? Tindakan ini permanen. Masukkan password untuk mengonfirmasi penghapusan akun.
            </p>

            <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                <input
                    type="password"
                    class="mt-1 block w-full rounded-lg border-gray-200 px-4 py-2.5 text-sm focus:border-red-500 focus:ring-red-500 dark:border-gray-700 dark:bg-slate-800"
                    autocomplete="current-password"
                    placeholder="Password"
                    x-ref="password"
                    wire:model="password"
                    wire:keydown.enter="deleteUser"
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
            <button type="button" wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition-all duration-200 hover:bg-gray-100 dark:border-gray-700 dark:bg-slate-900 dark:text-gray-300 dark:hover:bg-gray-800">
                Batal
            </button>

            <button type="button" class="ms-3 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition-all duration-200 hover:bg-red-700" wire:click="deleteUser" wire:loading.attr="disabled">
                Hapus Permanen
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
<?php /**PATH C:\xampp\htdocs\appkonkos_2\resources\views/profile/delete-user-form.blade.php ENDPATH**/ ?>