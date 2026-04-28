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

        <form method="POST" action="<?php echo e(route('register')); ?>">
            <?php echo csrf_field(); ?>

            <div>
                <?php if (isset($component)) { $__componentOriginal3b84ef35fdaa902f47846deaaed81d50 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b84ef35fdaa902f47846deaaed81d50 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.label','data' => ['for' => 'name','value' => ''.e(__('Name')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'name','value' => ''.e(__('Name')).'']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.input','data' => ['id' => 'name','class' => 'block mt-1 w-full','type' => 'text','name' => 'name','value' => old('name'),'required' => true,'autofocus' => true,'autocomplete' => 'name']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'name','class' => 'block mt-1 w-full','type' => 'text','name' => 'name','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('name')),'required' => true,'autofocus' => true,'autocomplete' => 'name']); ?>
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

            <div class="mt-4">
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.input','data' => ['id' => 'email','class' => 'block mt-1 w-full','type' => 'email','name' => 'email','value' => old('email'),'required' => true,'autocomplete' => 'username']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'email','class' => 'block mt-1 w-full','type' => 'email','name' => 'email','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('email')),'required' => true,'autocomplete' => 'username']); ?>
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

            <div class="mt-4">
                <?php if (isset($component)) { $__componentOriginal3b84ef35fdaa902f47846deaaed81d50 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b84ef35fdaa902f47846deaaed81d50 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.label','data' => ['for' => 'no_telepon','value' => ''.e(__('No. Telepon')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'no_telepon','value' => ''.e(__('No. Telepon')).'']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.input','data' => ['id' => 'no_telepon','class' => 'block mt-1 w-full','type' => 'text','name' => 'no_telepon','value' => old('no_telepon'),'required' => true,'autocomplete' => 'tel']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'no_telepon','class' => 'block mt-1 w-full','type' => 'text','name' => 'no_telepon','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('no_telepon')),'required' => true,'autocomplete' => 'tel']); ?>
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

            <div class="mt-4">
                <?php if (isset($component)) { $__componentOriginal3b84ef35fdaa902f47846deaaed81d50 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b84ef35fdaa902f47846deaaed81d50 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.label','data' => ['for' => 'password','value' => ''.e(__('Password')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'password','value' => ''.e(__('Password')).'']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.input','data' => ['id' => 'password','class' => 'block mt-1 w-full','type' => 'password','name' => 'password','required' => true,'autocomplete' => 'new-password']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'password','class' => 'block mt-1 w-full','type' => 'password','name' => 'password','required' => true,'autocomplete' => 'new-password']); ?>
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

            <div class="mt-4">
                <?php if (isset($component)) { $__componentOriginal3b84ef35fdaa902f47846deaaed81d50 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b84ef35fdaa902f47846deaaed81d50 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.label','data' => ['for' => 'password_confirmation','value' => ''.e(__('Confirm Password')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'password_confirmation','value' => ''.e(__('Confirm Password')).'']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.input','data' => ['id' => 'password_confirmation','class' => 'block mt-1 w-full','type' => 'password','name' => 'password_confirmation','required' => true,'autocomplete' => 'new-password']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'password_confirmation','class' => 'block mt-1 w-full','type' => 'password','name' => 'password_confirmation','required' => true,'autocomplete' => 'new-password']); ?>
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

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature()): ?>
                <div class="mt-4">
                    <?php if (isset($component)) { $__componentOriginal3b84ef35fdaa902f47846deaaed81d50 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b84ef35fdaa902f47846deaaed81d50 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.label','data' => ['for' => 'terms']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'terms']); ?>
                        <div class="flex items-center">
                            <?php if (isset($component)) { $__componentOriginal2a1f018c9ea4cbb36808c7fde1fbbd4e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2a1f018c9ea4cbb36808c7fde1fbbd4e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.kotak-centang','data' => ['name' => 'terms','id' => 'terms','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.kotak-centang'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'terms','id' => 'terms','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2a1f018c9ea4cbb36808c7fde1fbbd4e)): ?>
<?php $attributes = $__attributesOriginal2a1f018c9ea4cbb36808c7fde1fbbd4e; ?>
<?php unset($__attributesOriginal2a1f018c9ea4cbb36808c7fde1fbbd4e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2a1f018c9ea4cbb36808c7fde1fbbd4e)): ?>
<?php $component = $__componentOriginal2a1f018c9ea4cbb36808c7fde1fbbd4e; ?>
<?php unset($__componentOriginal2a1f018c9ea4cbb36808c7fde1fbbd4e); ?>
<?php endif; ?>

                            <div class="ms-2">
                                <?php echo __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                                ]); ?>

                            </div>
                        </div>
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
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="<?php echo e(route('login')); ?>">
                    <?php echo e(__('Already registered?')); ?>

                </a>

                <?php if (isset($component)) { $__componentOriginal9b2e3638f911eb095a1a066b53831b29 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9b2e3638f911eb095a1a066b53831b29 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.tombol','data' => ['class' => 'ms-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.tombol'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'ms-4']); ?>
                    <?php echo e(__('Register')); ?>

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
<?php /**PATH C:\xampp\htdocs\appkonkos-proyek2\resources\views/auth/register.blade.php ENDPATH**/ ?>