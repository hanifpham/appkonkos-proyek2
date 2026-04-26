<?php if (isset($component)) { $__componentOriginalc22ac3a07dad58962914186e847999b2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc22ac3a07dad58962914186e847999b2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.bagian-form','data' => ['submit' => 'updateProfileInformation']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.bagian-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['submit' => 'updateProfileInformation']); ?>
     <?php $__env->slot('title', null, []); ?> 
        <?php echo e(__('Profile Information')); ?>

     <?php $__env->endSlot(); ?>

     <?php $__env->slot('description', null, []); ?> 
        <?php echo e(__('Update your account\'s profile information and email address.')); ?>

     <?php $__env->endSlot(); ?>

     <?php $__env->slot('form', null, []); ?> 
        <!-- Profile Photo -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Laravel\Jetstream\Jetstream::managesProfilePhotos()): ?>
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" id="photo" class="hidden"
                            wire:model.live="photo"
                            x-ref="photo"
                            x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <?php if (isset($component)) { $__componentOriginal3b84ef35fdaa902f47846deaaed81d50 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b84ef35fdaa902f47846deaaed81d50 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.label','data' => ['for' => 'photo','value' => ''.e(__('Photo')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'photo','value' => ''.e(__('Photo')).'']); ?>
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

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="<?php echo e($this->user->profile_photo_url); ?>" alt="<?php echo e($this->user->name); ?>" class="rounded-full size-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full size-20 bg-cover bg-no-repeat bg-center"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <?php if (isset($component)) { $__componentOriginal349c1fedee949fd5b23f9c36c3e9aef1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal349c1fedee949fd5b23f9c36c3e9aef1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.tombol-sekunder','data' => ['class' => 'mt-2 me-2','type' => 'button','xOn:click.prevent' => '$refs.photo.click()']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.tombol-sekunder'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-2 me-2','type' => 'button','x-on:click.prevent' => '$refs.photo.click()']); ?>
                    <?php echo e(__('Select A New Photo')); ?>

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

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->user->profile_photo_path): ?>
                    <?php if (isset($component)) { $__componentOriginal349c1fedee949fd5b23f9c36c3e9aef1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal349c1fedee949fd5b23f9c36c3e9aef1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.tombol-sekunder','data' => ['type' => 'button','class' => 'mt-2','wire:click' => 'deleteProfilePhoto']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.tombol-sekunder'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','class' => 'mt-2','wire:click' => 'deleteProfilePhoto']); ?>
                        <?php echo e(__('Remove Photo')); ?>

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
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal32238dede262f8e0c0df7459ace9932b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal32238dede262f8e0c0df7459ace9932b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.error-input','data' => ['for' => 'photo','class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.error-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'photo','class' => 'mt-2']); ?>
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
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.input','data' => ['id' => 'name','type' => 'text','class' => 'mt-1 block w-full','wire:model' => 'state.name','required' => true,'autocomplete' => 'name']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'name','type' => 'text','class' => 'mt-1 block w-full','wire:model' => 'state.name','required' => true,'autocomplete' => 'name']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.error-input','data' => ['for' => 'name','class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.error-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'name','class' => 'mt-2']); ?>
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

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.input','data' => ['id' => 'email','type' => 'email','class' => 'mt-1 block w-full','wire:model' => 'state.email','required' => true,'autocomplete' => 'username']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'email','type' => 'email','class' => 'mt-1 block w-full','wire:model' => 'state.email','required' => true,'autocomplete' => 'username']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.error-input','data' => ['for' => 'email','class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.error-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'email','class' => 'mt-2']); ?>
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

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail()): ?>
                <p class="text-sm mt-2">
                    <?php echo e(__('Your email address is unverified.')); ?>


                    <button type="button" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" wire:click.prevent="sendEmailVerification">
                        <?php echo e(__('Click here to re-send the verification email.')); ?>

                    </button>
                </p>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->verificationLinkSent): ?>
                    <p class="mt-2 font-medium text-sm text-green-600">
                        <?php echo e(__('A new verification link has been sent to your email address.')); ?>

                    </p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
     <?php $__env->endSlot(); ?>

     <?php $__env->slot('actions', null, []); ?> 
        <?php if (isset($component)) { $__componentOriginal2a5dc72a59c2daa47555926796a349a3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2a5dc72a59c2daa47555926796a349a3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.pesan-aksi','data' => ['class' => 'me-3','on' => 'saved']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.pesan-aksi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'me-3','on' => 'saved']); ?>
            <?php echo e(__('Saved.')); ?>

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

        <?php if (isset($component)) { $__componentOriginal9b2e3638f911eb095a1a066b53831b29 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9b2e3638f911eb095a1a066b53831b29 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.tombol','data' => ['wire:loading.attr' => 'disabled','wire:target' => 'photo']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.tombol'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:loading.attr' => 'disabled','wire:target' => 'photo']); ?>
            <?php echo e(__('Save')); ?>

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
<?php if (isset($__attributesOriginalc22ac3a07dad58962914186e847999b2)): ?>
<?php $attributes = $__attributesOriginalc22ac3a07dad58962914186e847999b2; ?>
<?php unset($__attributesOriginalc22ac3a07dad58962914186e847999b2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc22ac3a07dad58962914186e847999b2)): ?>
<?php $component = $__componentOriginalc22ac3a07dad58962914186e847999b2; ?>
<?php unset($__componentOriginalc22ac3a07dad58962914186e847999b2); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\appkonkos_2\resources\views/profile/update-profile-information-form.blade.php ENDPATH**/ ?>