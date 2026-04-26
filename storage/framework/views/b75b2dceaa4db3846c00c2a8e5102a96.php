<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['submit']));

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

foreach (array_filter((['submit']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div <?php echo e($attributes->merge(['class' => 'md:grid md:grid-cols-3 md:gap-6'])); ?>>
    <?php if (isset($component)) { $__componentOriginale85cc2de9da7de6286301d9658854f8e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale85cc2de9da7de6286301d9658854f8e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.judul-bagian','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.judul-bagian'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
         <?php $__env->slot('title', null, []); ?> <?php echo e($title); ?> <?php $__env->endSlot(); ?>
         <?php $__env->slot('description', null, []); ?> <?php echo e($description); ?> <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale85cc2de9da7de6286301d9658854f8e)): ?>
<?php $attributes = $__attributesOriginale85cc2de9da7de6286301d9658854f8e; ?>
<?php unset($__attributesOriginale85cc2de9da7de6286301d9658854f8e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale85cc2de9da7de6286301d9658854f8e)): ?>
<?php $component = $__componentOriginale85cc2de9da7de6286301d9658854f8e; ?>
<?php unset($__componentOriginale85cc2de9da7de6286301d9658854f8e); ?>
<?php endif; ?>

    <div class="mt-5 md:mt-0 md:col-span-2">
        <form wire:submit="<?php echo e($submit); ?>">
            <div class="px-4 py-5 bg-white sm:p-6 shadow <?php echo e(isset($actions) ? 'sm:rounded-tl-md sm:rounded-tr-md' : 'sm:rounded-md'); ?>">
                <div class="grid grid-cols-6 gap-6">
                    <?php echo e($form); ?>

                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($actions)): ?>
                <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-end sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                    <?php echo e($actions); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </form>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\appkonkos_2\resources\views/components/formulir/bagian-form.blade.php ENDPATH**/ ?>