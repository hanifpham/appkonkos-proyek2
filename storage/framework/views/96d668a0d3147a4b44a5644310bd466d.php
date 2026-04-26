<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title' => __('Confirm Password'), 'content' => __('For your security, please confirm your password to continue.'), 'button' => __('Confirm')]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['title' => __('Confirm Password'), 'content' => __('For your security, please confirm your password to continue.'), 'button' => __('Confirm')]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $confirmableId = md5($attributes->wire('then'));
?>

<span
    <?php echo e($attributes->wire('then')); ?>

    x-data
    x-ref="span"
    x-on:click="$wire.startConfirmingPassword('<?php echo e($confirmableId); ?>')"
    x-on:password-confirmed.window="setTimeout(() => $event.detail.id === '<?php echo e($confirmableId); ?>' && $refs.span.dispatchEvent(new CustomEvent('then', { bubbles: false })), 250);"
>
    <?php echo e($slot); ?>

</span>

<?php if (! $__env->hasRenderedOnce('a4b1585f-40a2-4242-9d32-5d7c64ab80d0')): $__env->markAsRenderedOnce('a4b1585f-40a2-4242-9d32-5d7c64ab80d0'); ?>
<?php if (isset($component)) { $__componentOriginalf035e7f8c4f13beaf7da0b49976299e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf035e7f8c4f13beaf7da0b49976299e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal.modal-dialog','data' => ['wire:model.live' => 'confirmingPassword']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal.modal-dialog'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'confirmingPassword']); ?>
     <?php $__env->slot('title', null, []); ?> 
        <?php echo e($title); ?>

     <?php $__env->endSlot(); ?>

     <?php $__env->slot('content', null, []); ?> 
        <?php echo e($content); ?>


        <div class="mt-4" x-data="{}" x-on:confirming-password.window="setTimeout(() => $refs.confirmable_password.focus(), 250)">
            <?php if (isset($component)) { $__componentOriginal23639cc2d22570df415edfb2f7c53ac8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal23639cc2d22570df415edfb2f7c53ac8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.input','data' => ['type' => 'password','class' => 'mt-1 block w-3/4','placeholder' => ''.e(__('Password')).'','autocomplete' => 'current-password','xRef' => 'confirmable_password','wire:model' => 'confirmablePassword','wire:keydown.enter' => 'confirmPassword']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'password','class' => 'mt-1 block w-3/4','placeholder' => ''.e(__('Password')).'','autocomplete' => 'current-password','x-ref' => 'confirmable_password','wire:model' => 'confirmablePassword','wire:keydown.enter' => 'confirmPassword']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal23639cc2d22570df415edfb2f7c53ac8)): ?>
<?php $attributes = $__attributesOriginal23639cc2d22570df415edfb2f7c53ac8; ?>
<?php unset($__attributesOriginal23639cc2d22570df415edfb2f7c53ac8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal23639cc2d22570df415edfb2f7c53ac8)): ?>
<?php $component = $__componentOriginal23639cc2d22570df415edfb2f7c53ac8; ?>
<?php unset($__componentOriginal23639cc2d22570df415edfb2f7c53ac8); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal32238dede262f8e0c0df7459ace9932b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal32238dede262f8e0c0df7459ace9932b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.error-input','data' => ['for' => 'confirmable_password','class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.error-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'confirmable_password','class' => 'mt-2']); ?>
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
        <?php if (isset($component)) { $__componentOriginal349c1fedee949fd5b23f9c36c3e9aef1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal349c1fedee949fd5b23f9c36c3e9aef1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.tombol-sekunder','data' => ['wire:click' => 'stopConfirmingPassword','wire:loading.attr' => 'disabled']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.tombol-sekunder'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'stopConfirmingPassword','wire:loading.attr' => 'disabled']); ?>
            <?php echo e(__('Cancel')); ?>

         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal349c1fedee949fd5b23f9c36c3e9aef1)): ?>
<?php $attributes = $__attributesOriginal349c1fedee949fd5b23f9c36c3e9aef1; ?>
<?php unset($__attributesOriginal349c1fedee949fd5b23f9c36c3e9aef1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal349c1fedee949fd5b23f9c36c3e9aef1)): ?>
<?php $component = $__componentOriginal349c1fedee949fd5b23f9c36c3e9aef1; ?>
<?php unset($__componentOriginal349c1fedee949fd5b23f9c36c3e9aef1); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal9b2e3638f911eb095a1a066b53831b29 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9b2e3638f911eb095a1a066b53831b29 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.tombol','data' => ['class' => 'ms-3','dusk' => 'confirm-password-button','wire:click' => 'confirmPassword','wire:loading.attr' => 'disabled']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.tombol'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'ms-3','dusk' => 'confirm-password-button','wire:click' => 'confirmPassword','wire:loading.attr' => 'disabled']); ?>
            <?php echo e($button); ?>

         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9b2e3638f911eb095a1a066b53831b29)): ?>
<?php $attributes = $__attributesOriginal9b2e3638f911eb095a1a066b53831b29; ?>
<?php unset($__attributesOriginal9b2e3638f911eb095a1a066b53831b29); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9b2e3638f911eb095a1a066b53831b29)): ?>
<?php $component = $__componentOriginal9b2e3638f911eb095a1a066b53831b29; ?>
<?php unset($__componentOriginal9b2e3638f911eb095a1a066b53831b29); ?>
<?php endif; ?>
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
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\appkonkos_2\resources\views/components/formulir/konfirmasi-password.blade.php ENDPATH**/ ?>