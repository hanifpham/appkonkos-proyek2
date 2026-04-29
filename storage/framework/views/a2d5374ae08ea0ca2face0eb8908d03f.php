<?php if (isset($component)) { $__componentOriginal69dc84650370d1d4dc1b42d016d7226b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b = $attributes; } ?>
<?php $component = App\View\Components\GuestLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\GuestLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php if (isset($component)) { $__componentOriginalc9e8d8d62b2de722f1fdf5ca7127aa6d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc9e8d8d62b2de722f1fdf5ca7127aa6d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.autentikasi.kartu-autentikasi','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('autentikasi.kartu-autentikasi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
         <?php $__env->slot('logo', null, []); ?> 
            <?php if (isset($component)) { $__componentOriginal3b66484a11852720cd79213d084703c7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b66484a11852720cd79213d084703c7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.autentikasi.logo-kartu','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('autentikasi.logo-kartu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3b66484a11852720cd79213d084703c7)): ?>
<?php $attributes = $__attributesOriginal3b66484a11852720cd79213d084703c7; ?>
<?php unset($__attributesOriginal3b66484a11852720cd79213d084703c7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3b66484a11852720cd79213d084703c7)): ?>
<?php $component = $__componentOriginal3b66484a11852720cd79213d084703c7; ?>
<?php unset($__componentOriginal3b66484a11852720cd79213d084703c7); ?>
<?php endif; ?>
         <?php $__env->endSlot(); ?>

        <div class="mb-4 text-sm text-gray-600">
            <?php echo e(__('Lupa kata sandi Anda? Tidak masalah. Cukup beri tahu kami alamat email Anda dan kami akan mengirimkan tautan pengaturan ulang kata sandi melalui email.')); ?>

        </div>

        <?php $__sessionArgs = ['status'];
if (session()->has($__sessionArgs[0])) :
if (isset($value)) { $__sessionPrevious[] = $value; }
$value = session()->get($__sessionArgs[0]); ?>
            <div class="mb-4 font-medium text-sm text-green-600">
                <?php echo e($value); ?>

            </div>
        <?php unset($value);
if (isset($__sessionPrevious) && !empty($__sessionPrevious)) { $value = array_pop($__sessionPrevious); }
if (isset($__sessionPrevious) && empty($__sessionPrevious)) { unset($__sessionPrevious); }
endif;
unset($__sessionArgs); ?>

        <?php if (isset($component)) { $__componentOriginal24cec81afdafef259d0448d3493e7365 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal24cec81afdafef259d0448d3493e7365 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.error-validasi','data' => ['class' => 'mb-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.error-validasi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal24cec81afdafef259d0448d3493e7365)): ?>
<?php $attributes = $__attributesOriginal24cec81afdafef259d0448d3493e7365; ?>
<?php unset($__attributesOriginal24cec81afdafef259d0448d3493e7365); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal24cec81afdafef259d0448d3493e7365)): ?>
<?php $component = $__componentOriginal24cec81afdafef259d0448d3493e7365; ?>
<?php unset($__componentOriginal24cec81afdafef259d0448d3493e7365); ?>
<?php endif; ?>

        <form method="POST" action="<?php echo e(route('password.email')); ?>">
            <?php echo csrf_field(); ?>

            <div class="block">
                <?php if (isset($component)) { $__componentOriginal3b84ef35fdaa902f47846deaaed81d50 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b84ef35fdaa902f47846deaaed81d50 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.label','data' => ['for' => 'email','value' => ''.e(__('Email')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'email','value' => ''.e(__('Email')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3b84ef35fdaa902f47846deaaed81d50)): ?>
<?php $attributes = $__attributesOriginal3b84ef35fdaa902f47846deaaed81d50; ?>
<?php unset($__attributesOriginal3b84ef35fdaa902f47846deaaed81d50); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3b84ef35fdaa902f47846deaaed81d50)): ?>
<?php $component = $__componentOriginal3b84ef35fdaa902f47846deaaed81d50; ?>
<?php unset($__componentOriginal3b84ef35fdaa902f47846deaaed81d50); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal23639cc2d22570df415edfb2f7c53ac8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal23639cc2d22570df415edfb2f7c53ac8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.input','data' => ['id' => 'email','class' => 'block mt-1 w-full','type' => 'email','name' => 'email','value' => old('email'),'required' => true,'autofocus' => true,'autocomplete' => 'username']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'email','class' => 'block mt-1 w-full','type' => 'email','name' => 'email','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('email')),'required' => true,'autofocus' => true,'autocomplete' => 'username']); ?>
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
            </div>

            <div class="flex items-center justify-end mt-4">
                <?php if (isset($component)) { $__componentOriginal9b2e3638f911eb095a1a066b53831b29 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9b2e3638f911eb095a1a066b53831b29 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.tombol','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.tombol'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                    <?php echo e(__('Kirim Tautan Atur Ulang Kata Sandi')); ?>

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
            </div>
        </form>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc9e8d8d62b2de722f1fdf5ca7127aa6d)): ?>
<?php $attributes = $__attributesOriginalc9e8d8d62b2de722f1fdf5ca7127aa6d; ?>
<?php unset($__attributesOriginalc9e8d8d62b2de722f1fdf5ca7127aa6d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc9e8d8d62b2de722f1fdf5ca7127aa6d)): ?>
<?php $component = $__componentOriginalc9e8d8d62b2de722f1fdf5ca7127aa6d; ?>
<?php unset($__componentOriginalc9e8d8d62b2de722f1fdf5ca7127aa6d); ?>
<?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $attributes = $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $component = $__componentOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\appkonkos_2\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>