<form wire:submit="updatePassword" class="space-y-5">
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <div class="space-y-1.5 md:col-span-2">
            <label for="current_password" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Password Saat Ini</label>
            <input
                id="current_password"
                type="password"
                wire:model="state.current_password"
                autocomplete="current-password"
                class="w-full rounded-lg border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-[#113C7A] focus:ring-[#113C7A] dark:border-gray-700 dark:bg-slate-800"
            >
            <?php if (isset($component)) { $__componentOriginal32238dede262f8e0c0df7459ace9932b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal32238dede262f8e0c0df7459ace9932b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.error-input','data' => ['for' => 'current_password','class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.error-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'current_password','class' => 'mt-2']); ?>
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

        <div class="space-y-1.5">
            <label for="password" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Password Baru</label>
            <input
                id="password"
                type="password"
                wire:model="state.password"
                autocomplete="new-password"
                class="w-full rounded-lg border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-[#113C7A] focus:ring-[#113C7A] dark:border-gray-700 dark:bg-slate-800"
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

        <div class="space-y-1.5">
            <label for="password_confirmation" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Konfirmasi Password Baru</label>
            <input
                id="password_confirmation"
                type="password"
                wire:model="state.password_confirmation"
                autocomplete="new-password"
                class="w-full rounded-lg border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-[#113C7A] focus:ring-[#113C7A] dark:border-gray-700 dark:bg-slate-800"
            >
            <?php if (isset($component)) { $__componentOriginal32238dede262f8e0c0df7459ace9932b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal32238dede262f8e0c0df7459ace9932b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.error-input','data' => ['for' => 'password_confirmation','class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.error-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'password_confirmation','class' => 'mt-2']); ?>
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
    </div>

    <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-5 dark:border-gray-700">
        <?php if (isset($component)) { $__componentOriginal2a5dc72a59c2daa47555926796a349a3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2a5dc72a59c2daa47555926796a349a3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.pesan-aksi','data' => ['class' => 'text-sm text-gray-500 dark:text-gray-400','on' => 'saved']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.pesan-aksi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'text-sm text-gray-500 dark:text-gray-400','on' => 'saved']); ?>
            Tersimpan.
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

        <button
            type="submit"
            wire:loading.attr="disabled"
            class="inline-flex items-center rounded-lg bg-[#113C7A] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-[#0d3f6d] disabled:cursor-not-allowed disabled:opacity-60"
        >
            Simpan Password
        </button>
    </div>
</form>
<?php /**PATH C:\xampp\htdocs\appkonkos_2\resources\views/profile/update-password-form.blade.php ENDPATH**/ ?>