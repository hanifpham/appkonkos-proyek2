<div class="space-y-5">
    <div class="rounded-xl border px-4 py-3 text-sm dark:border-gray-700 <?php if($this->enabled): ?> border-green-100 bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-300 <?php else: ?> border-amber-100 bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-300 <?php endif; ?>">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->enabled): ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showingConfirmation): ?>
                Selesaikan aktivasi autentikasi 2 langkah dengan memasukkan kode OTP.
            <?php else: ?>
                Autentikasi 2 langkah sudah aktif untuk akun ini.
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php else: ?>
            Autentikasi 2 langkah belum aktif.
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <p class="max-w-xl text-sm leading-7 text-gray-600 dark:text-gray-400">
        Jika fitur ini aktif, Anda akan diminta memasukkan kode dari aplikasi autentikator saat login. Gunakan Google Authenticator, Authy, atau aplikasi sejenis.
    </p>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->enabled): ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showingQrCode): ?>
            <div class="space-y-4">
                <p class="max-w-xl text-sm font-semibold leading-7 text-gray-700 dark:text-gray-300">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showingConfirmation): ?>
                        Pindai QR Code berikut atau masukkan setup key ke aplikasi autentikator, lalu masukkan kode OTP yang muncul.
                    <?php else: ?>
                        Pindai QR Code berikut atau simpan setup key jika Anda ingin menambahkan ulang autentikator.
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </p>

                <div class="inline-block rounded-xl border border-gray-100 bg-white p-3 shadow-sm dark:border-gray-700">
                    <?php echo $this->user->twoFactorQrCodeSvg(); ?>

                </div>

                <div class="max-w-xl rounded-xl bg-gray-50 px-4 py-3 font-mono text-xs text-gray-700 dark:bg-slate-800 dark:text-gray-300">
                    Setup Key: <?php echo e(decrypt($this->user->two_factor_secret)); ?>

                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showingConfirmation): ?>
                    <div class="max-w-xs space-y-1.5">
                        <label for="code" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Kode OTP</label>
                        <input
                            id="code"
                            type="text"
                            name="code"
                            inputmode="numeric"
                            autofocus
                            autocomplete="one-time-code"
                            wire:model="code"
                            wire:keydown.enter="confirmTwoFactorAuthentication"
                            class="w-full rounded-lg border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-[#113C7A] focus:ring-[#113C7A] dark:border-gray-700 dark:bg-slate-800"
                        >
                        <?php if (isset($component)) { $__componentOriginal32238dede262f8e0c0df7459ace9932b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal32238dede262f8e0c0df7459ace9932b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.error-input','data' => ['for' => 'code','class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.error-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'code','class' => 'mt-2']); ?>
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
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showingRecoveryCodes): ?>
            <div class="space-y-3">
                <p class="max-w-xl text-sm font-semibold leading-7 text-gray-700 dark:text-gray-300">
                    Simpan kode pemulihan ini di tempat aman. Kode ini dapat digunakan jika perangkat autentikator Anda hilang.
                </p>

                <div class="grid max-w-xl gap-1 rounded-xl bg-gray-100 px-4 py-4 font-mono text-sm text-gray-700 dark:bg-slate-800 dark:text-gray-300">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = json_decode(decrypt($this->user->two_factor_recovery_codes), true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div><?php echo e($code); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="flex flex-wrap items-center gap-3 border-t border-gray-100 pt-5 dark:border-gray-700">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $this->enabled): ?>
            <?php if (isset($component)) { $__componentOriginald39cdb04cb7f937a0b20ca910bd61eea = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald39cdb04cb7f937a0b20ca910bd61eea = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.konfirmasi-password','data' => ['wire:then' => 'enableTwoFactorAuthentication']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.konfirmasi-password'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:then' => 'enableTwoFactorAuthentication']); ?>
                <button type="button" wire:loading.attr="disabled" class="rounded-lg bg-[#113C7A] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-[#0d3f6d] disabled:opacity-60">
                    Aktifkan 2FA
                </button>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald39cdb04cb7f937a0b20ca910bd61eea)): ?>
<?php $attributes = $__attributesOriginald39cdb04cb7f937a0b20ca910bd61eea; ?>
<?php unset($__attributesOriginald39cdb04cb7f937a0b20ca910bd61eea); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald39cdb04cb7f937a0b20ca910bd61eea)): ?>
<?php $component = $__componentOriginald39cdb04cb7f937a0b20ca910bd61eea; ?>
<?php unset($__componentOriginald39cdb04cb7f937a0b20ca910bd61eea); ?>
<?php endif; ?>
        <?php else: ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showingRecoveryCodes): ?>
                <?php if (isset($component)) { $__componentOriginald39cdb04cb7f937a0b20ca910bd61eea = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald39cdb04cb7f937a0b20ca910bd61eea = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.konfirmasi-password','data' => ['wire:then' => 'regenerateRecoveryCodes']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.konfirmasi-password'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:then' => 'regenerateRecoveryCodes']); ?>
                    <button type="button" class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 transition-all duration-200 hover:bg-gray-100 dark:border-gray-700 dark:bg-slate-900 dark:text-gray-300 dark:hover:bg-gray-800">
                        Buat Ulang Kode Pemulihan
                    </button>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald39cdb04cb7f937a0b20ca910bd61eea)): ?>
<?php $attributes = $__attributesOriginald39cdb04cb7f937a0b20ca910bd61eea; ?>
<?php unset($__attributesOriginald39cdb04cb7f937a0b20ca910bd61eea); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald39cdb04cb7f937a0b20ca910bd61eea)): ?>
<?php $component = $__componentOriginald39cdb04cb7f937a0b20ca910bd61eea; ?>
<?php unset($__componentOriginald39cdb04cb7f937a0b20ca910bd61eea); ?>
<?php endif; ?>
            <?php elseif($showingConfirmation): ?>
                <?php if (isset($component)) { $__componentOriginald39cdb04cb7f937a0b20ca910bd61eea = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald39cdb04cb7f937a0b20ca910bd61eea = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.konfirmasi-password','data' => ['wire:then' => 'confirmTwoFactorAuthentication']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.konfirmasi-password'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:then' => 'confirmTwoFactorAuthentication']); ?>
                    <button type="button" wire:loading.attr="disabled" class="rounded-lg bg-[#113C7A] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-[#0d3f6d] disabled:opacity-60">
                        Konfirmasi
                    </button>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald39cdb04cb7f937a0b20ca910bd61eea)): ?>
<?php $attributes = $__attributesOriginald39cdb04cb7f937a0b20ca910bd61eea; ?>
<?php unset($__attributesOriginald39cdb04cb7f937a0b20ca910bd61eea); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald39cdb04cb7f937a0b20ca910bd61eea)): ?>
<?php $component = $__componentOriginald39cdb04cb7f937a0b20ca910bd61eea; ?>
<?php unset($__componentOriginald39cdb04cb7f937a0b20ca910bd61eea); ?>
<?php endif; ?>
            <?php else: ?>
                <?php if (isset($component)) { $__componentOriginald39cdb04cb7f937a0b20ca910bd61eea = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald39cdb04cb7f937a0b20ca910bd61eea = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.konfirmasi-password','data' => ['wire:then' => 'showRecoveryCodes']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.konfirmasi-password'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:then' => 'showRecoveryCodes']); ?>
                    <button type="button" class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 transition-all duration-200 hover:bg-gray-100 dark:border-gray-700 dark:bg-slate-900 dark:text-gray-300 dark:hover:bg-gray-800">
                        Tampilkan Kode Pemulihan
                    </button>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald39cdb04cb7f937a0b20ca910bd61eea)): ?>
<?php $attributes = $__attributesOriginald39cdb04cb7f937a0b20ca910bd61eea; ?>
<?php unset($__attributesOriginald39cdb04cb7f937a0b20ca910bd61eea); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald39cdb04cb7f937a0b20ca910bd61eea)): ?>
<?php $component = $__componentOriginald39cdb04cb7f937a0b20ca910bd61eea; ?>
<?php unset($__componentOriginald39cdb04cb7f937a0b20ca910bd61eea); ?>
<?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showingConfirmation): ?>
                <?php if (isset($component)) { $__componentOriginald39cdb04cb7f937a0b20ca910bd61eea = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald39cdb04cb7f937a0b20ca910bd61eea = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.konfirmasi-password','data' => ['wire:then' => 'disableTwoFactorAuthentication']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.konfirmasi-password'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:then' => 'disableTwoFactorAuthentication']); ?>
                    <button type="button" wire:loading.attr="disabled" class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 transition-all duration-200 hover:bg-gray-100 dark:border-gray-700 dark:bg-slate-900 dark:text-gray-300 dark:hover:bg-gray-800">
                        Batal
                    </button>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald39cdb04cb7f937a0b20ca910bd61eea)): ?>
<?php $attributes = $__attributesOriginald39cdb04cb7f937a0b20ca910bd61eea; ?>
<?php unset($__attributesOriginald39cdb04cb7f937a0b20ca910bd61eea); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald39cdb04cb7f937a0b20ca910bd61eea)): ?>
<?php $component = $__componentOriginald39cdb04cb7f937a0b20ca910bd61eea; ?>
<?php unset($__componentOriginald39cdb04cb7f937a0b20ca910bd61eea); ?>
<?php endif; ?>
            <?php else: ?>
                <?php if (isset($component)) { $__componentOriginald39cdb04cb7f937a0b20ca910bd61eea = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald39cdb04cb7f937a0b20ca910bd61eea = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.formulir.konfirmasi-password','data' => ['wire:then' => 'disableTwoFactorAuthentication']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('formulir.konfirmasi-password'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:then' => 'disableTwoFactorAuthentication']); ?>
                    <button type="button" wire:loading.attr="disabled" class="rounded-lg bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-red-700 disabled:opacity-60">
                        Nonaktifkan
                    </button>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald39cdb04cb7f937a0b20ca910bd61eea)): ?>
<?php $attributes = $__attributesOriginald39cdb04cb7f937a0b20ca910bd61eea; ?>
<?php unset($__attributesOriginald39cdb04cb7f937a0b20ca910bd61eea); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald39cdb04cb7f937a0b20ca910bd61eea)): ?>
<?php $component = $__componentOriginald39cdb04cb7f937a0b20ca910bd61eea; ?>
<?php unset($__componentOriginald39cdb04cb7f937a0b20ca910bd61eea); ?>
<?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\appkonkos-proyek2\resources\views/profile/two-factor-authentication-form.blade.php ENDPATH**/ ?>