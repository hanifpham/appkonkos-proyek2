<?php
    $user = Auth::user();
    $dashboardUrl = match ($user->role) {
        'superadmin' => route('superadmin.dashboard'),
        'pemilik' => route('mitra.dashboard'),
        default => route('dashboard'),
    };

    $profilePhotoUrl = Auth::user()->profile_photo_path
        ? (rtrim(request()->getBaseUrl(), '/') === ''
            ? '/storage/'.ltrim(Auth::user()->profile_photo_path, '/')
            : rtrim(request()->getBaseUrl(), '/').'/storage/'.ltrim(Auth::user()->profile_photo_path, '/'))
            .'?v='.(Auth::user()->updated_at?->timestamp ?? now()->timestamp)
        : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=113C7A&background=EBF4FF';

    $navigationItems = match ($user->role) {
        'superadmin' => [
            ['label' => 'Dashboard Utama', 'url' => route('superadmin.dashboard'), 'active' => request()->routeIs('superadmin.dashboard')],
            ['label' => 'Manajemen Pengguna', 'url' => route('superadmin.pengguna'), 'active' => request()->routeIs('superadmin.pengguna*')],
            ['label' => 'Moderasi Properti', 'url' => route('superadmin.properti'), 'active' => request()->routeIs('superadmin.properti*') || request()->routeIs('superadmin.moderasi.*')],
            ['label' => 'Transaksi Midtrans', 'url' => route('superadmin.transaksi'), 'active' => request()->routeIs('superadmin.transaksi')],
            ['label' => 'Pencairan Dana', 'url' => route('superadmin.pencairan'), 'active' => request()->routeIs('superadmin.pencairan*')],
            ['label' => 'Pengajuan Refund', 'url' => route('superadmin.refund'), 'active' => request()->routeIs('superadmin.refund*')],
        ],
        'pemilik' => [
            ['label' => 'Dashboard Mitra', 'url' => route('mitra.dashboard'), 'active' => request()->routeIs('mitra.dashboard')],
            ['label' => 'Properti Saya', 'url' => route('mitra.properti'), 'active' => request()->routeIs('mitra.properti*')],
            ['label' => 'Pesanan Masuk', 'url' => route('mitra.pesanan'), 'active' => request()->routeIs('mitra.pesanan*')],
            ['label' => 'Keuangan', 'url' => route('mitra.keuangan'), 'active' => request()->routeIs('mitra.keuangan*')],
            ['label' => 'Ulasan Penyewa', 'url' => route('mitra.ulasan'), 'active' => request()->routeIs('mitra.ulasan*')],
            ['label' => 'Pengaturan Profil', 'url' => route('mitra.pengaturan-profil'), 'active' => request()->routeIs('mitra.pengaturan-profil*')],
        ],
        default => [
            ['label' => 'Beranda', 'url' => route('dashboard'), 'active' => request()->routeIs('dashboard')],
            ['label' => 'Cari Kos', 'url' => url('/cari-kos'), 'active' => request()->is('cari-kos*')],
            ['label' => 'Riwayat Booking', 'url' => url('/booking/riwayat'), 'active' => request()->is('booking/riwayat*')],
        ],
    };
?>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="<?php echo e($dashboardUrl); ?>">
                        <?php if (isset($component)) { $__componentOriginal87444f9211e03e4dddbc6c76f295a69c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal87444f9211e03e4dddbc6c76f295a69c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.aplikasi.tanda-aplikasi','data' => ['class' => 'block h-9 w-auto']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('aplikasi.tanda-aplikasi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'block h-9 w-auto']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal87444f9211e03e4dddbc6c76f295a69c)): ?>
<?php $attributes = $__attributesOriginal87444f9211e03e4dddbc6c76f295a69c; ?>
<?php unset($__attributesOriginal87444f9211e03e4dddbc6c76f295a69c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal87444f9211e03e4dddbc6c76f295a69c)): ?>
<?php $component = $__componentOriginal87444f9211e03e4dddbc6c76f295a69c; ?>
<?php unset($__componentOriginal87444f9211e03e4dddbc6c76f295a69c); ?>
<?php endif; ?>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden items-center space-x-2 sm:ms-8 sm:flex">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $navigationItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a
                            href="<?php echo e($item['url']); ?>"
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'inline-flex items-center rounded-lg px-4 py-2 text-sm font-medium transition',
                                'bg-gray-900 text-white' => $item['active'],
                                'text-gray-600 hover:bg-gray-100 hover:text-gray-900' => ! $item['active'],
                            ]); ?>"
                        >
                            <?php echo e($item['label']); ?>

                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Teams Dropdown -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Laravel\Jetstream\Jetstream::hasTeamFeatures()): ?>
                    <div class="ms-3 relative">
                        <?php if (isset($component)) { $__componentOriginal9a817a5e1137739b097986bb7b8fb5c9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9a817a5e1137739b097986bb7b8fb5c9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.dropdown','data' => ['align' => 'right','width' => '60']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right','width' => '60']); ?>
                             <?php $__env->slot('trigger', null, []); ?> 
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        <?php echo e(Auth::user()->currentTeam->name); ?>


                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </button>
                                </span>
                             <?php $__env->endSlot(); ?>

                             <?php $__env->slot('content', null, []); ?> 
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        <?php echo e(__('Manage Team')); ?>

                                    </div>

                                    <!-- Team Settings -->
                                    <?php if (isset($component)) { $__componentOriginal4adad594ad0775fd110322e7c91847d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4adad594ad0775fd110322e7c91847d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.tautan-dropdown','data' => ['href' => ''.e(route('teams.show', Auth::user()->currentTeam->id)).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.tautan-dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('teams.show', Auth::user()->currentTeam->id)).'']); ?>
                                        <?php echo e(__('Team Settings')); ?>

                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4adad594ad0775fd110322e7c91847d1)): ?>
<?php $attributes = $__attributesOriginal4adad594ad0775fd110322e7c91847d1; ?>
<?php unset($__attributesOriginal4adad594ad0775fd110322e7c91847d1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4adad594ad0775fd110322e7c91847d1)): ?>
<?php $component = $__componentOriginal4adad594ad0775fd110322e7c91847d1; ?>
<?php unset($__componentOriginal4adad594ad0775fd110322e7c91847d1); ?>
<?php endif; ?>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', Laravel\Jetstream\Jetstream::newTeamModel())): ?>
                                        <?php if (isset($component)) { $__componentOriginal4adad594ad0775fd110322e7c91847d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4adad594ad0775fd110322e7c91847d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.tautan-dropdown','data' => ['href' => ''.e(route('teams.create')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.tautan-dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('teams.create')).'']); ?>
                                            <?php echo e(__('Create New Team')); ?>

                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4adad594ad0775fd110322e7c91847d1)): ?>
<?php $attributes = $__attributesOriginal4adad594ad0775fd110322e7c91847d1; ?>
<?php unset($__attributesOriginal4adad594ad0775fd110322e7c91847d1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4adad594ad0775fd110322e7c91847d1)): ?>
<?php $component = $__componentOriginal4adad594ad0775fd110322e7c91847d1; ?>
<?php unset($__componentOriginal4adad594ad0775fd110322e7c91847d1); ?>
<?php endif; ?>
                                    <?php endif; ?>

                                    <!-- Team Switcher -->
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->allTeams()->count() > 1): ?>
                                        <div class="border-t border-gray-200"></div>

                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            <?php echo e(__('Switch Teams')); ?>

                                        </div>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = Auth::user()->allTeams(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if (isset($component)) { $__componentOriginal11495b210affaaa935eca763857a8bbc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal11495b210affaaa935eca763857a8bbc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tim.tim-aktif','data' => ['team' => $team]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tim.tim-aktif'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['team' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($team)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal11495b210affaaa935eca763857a8bbc)): ?>
<?php $attributes = $__attributesOriginal11495b210affaaa935eca763857a8bbc; ?>
<?php unset($__attributesOriginal11495b210affaaa935eca763857a8bbc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11495b210affaaa935eca763857a8bbc)): ?>
<?php $component = $__componentOriginal11495b210affaaa935eca763857a8bbc; ?>
<?php unset($__componentOriginal11495b210affaaa935eca763857a8bbc); ?>
<?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                             <?php $__env->endSlot(); ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9a817a5e1137739b097986bb7b8fb5c9)): ?>
<?php $attributes = $__attributesOriginal9a817a5e1137739b097986bb7b8fb5c9; ?>
<?php unset($__attributesOriginal9a817a5e1137739b097986bb7b8fb5c9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9a817a5e1137739b097986bb7b8fb5c9)): ?>
<?php $component = $__componentOriginal9a817a5e1137739b097986bb7b8fb5c9; ?>
<?php unset($__componentOriginal9a817a5e1137739b097986bb7b8fb5c9); ?>
<?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <?php if (isset($component)) { $__componentOriginal9a817a5e1137739b097986bb7b8fb5c9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9a817a5e1137739b097986bb7b8fb5c9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.dropdown','data' => ['align' => 'right','width' => '48']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right','width' => '48']); ?>
                         <?php $__env->slot('trigger', null, []); ?> 
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Laravel\Jetstream\Jetstream::managesProfilePhotos()): ?>
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="size-8 rounded-full object-cover" src="<?php echo e($profilePhotoUrl); ?>" alt="<?php echo e(Auth::user()->name); ?>" data-appkonkos-profile-photo />
                                </button>
                            <?php else: ?>
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        <?php echo e(Auth::user()->name); ?>


                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                         <?php $__env->endSlot(); ?>

                         <?php $__env->slot('content', null, []); ?> 
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                <?php echo e(__('Manage Account')); ?>

                            </div>

                            <?php if (isset($component)) { $__componentOriginal4adad594ad0775fd110322e7c91847d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4adad594ad0775fd110322e7c91847d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.tautan-dropdown','data' => ['href' => ''.e(route('profile.show')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.tautan-dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('profile.show')).'']); ?>
                                <?php echo e(__('Profile')); ?>

                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4adad594ad0775fd110322e7c91847d1)): ?>
<?php $attributes = $__attributesOriginal4adad594ad0775fd110322e7c91847d1; ?>
<?php unset($__attributesOriginal4adad594ad0775fd110322e7c91847d1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4adad594ad0775fd110322e7c91847d1)): ?>
<?php $component = $__componentOriginal4adad594ad0775fd110322e7c91847d1; ?>
<?php unset($__componentOriginal4adad594ad0775fd110322e7c91847d1); ?>
<?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Laravel\Jetstream\Jetstream::hasApiFeatures()): ?>
                                <?php if (isset($component)) { $__componentOriginal4adad594ad0775fd110322e7c91847d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4adad594ad0775fd110322e7c91847d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.tautan-dropdown','data' => ['href' => ''.e(route('api-tokens.index')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.tautan-dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('api-tokens.index')).'']); ?>
                                    <?php echo e(__('API Tokens')); ?>

                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4adad594ad0775fd110322e7c91847d1)): ?>
<?php $attributes = $__attributesOriginal4adad594ad0775fd110322e7c91847d1; ?>
<?php unset($__attributesOriginal4adad594ad0775fd110322e7c91847d1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4adad594ad0775fd110322e7c91847d1)): ?>
<?php $component = $__componentOriginal4adad594ad0775fd110322e7c91847d1; ?>
<?php unset($__componentOriginal4adad594ad0775fd110322e7c91847d1); ?>
<?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <div class="border-t border-gray-200"></div>

                            <!-- Authentication -->
                            <form method="POST" action="<?php echo e(route('logout')); ?>" x-data>
                                <?php echo csrf_field(); ?>

                                <button type="submit" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none">
                                    <?php echo e(__('Log Out')); ?>

                                </button>
                            </form>
                         <?php $__env->endSlot(); ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9a817a5e1137739b097986bb7b8fb5c9)): ?>
<?php $attributes = $__attributesOriginal9a817a5e1137739b097986bb7b8fb5c9; ?>
<?php unset($__attributesOriginal9a817a5e1137739b097986bb7b8fb5c9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9a817a5e1137739b097986bb7b8fb5c9)): ?>
<?php $component = $__componentOriginal9a817a5e1137739b097986bb7b8fb5c9; ?>
<?php unset($__componentOriginal9a817a5e1137739b097986bb7b8fb5c9); ?>
<?php endif; ?>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $navigationItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a
                    href="<?php echo e($item['url']); ?>"
                    class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                        'mx-3 block rounded-lg px-4 py-2 text-base font-medium transition',
                        'bg-gray-900 text-white' => $item['active'],
                        'text-gray-600 hover:bg-gray-100 hover:text-gray-900' => ! $item['active'],
                    ]); ?>"
                >
                    <?php echo e($item['label']); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Laravel\Jetstream\Jetstream::managesProfilePhotos()): ?>
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-full object-cover" src="<?php echo e($profilePhotoUrl); ?>" alt="<?php echo e(Auth::user()->name); ?>" data-appkonkos-profile-photo />
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div>
                    <div class="font-medium text-base text-gray-800"><?php echo e(Auth::user()->name); ?></div>
                    <div class="font-medium text-sm text-gray-500"><?php echo e(Auth::user()->email); ?></div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <?php if (isset($component)) { $__componentOriginal6cb1d266ca9ce0e045e74f734e536c1d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6cb1d266ca9ce0e045e74f734e536c1d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.tautan-navigasi-responsif','data' => ['href' => ''.e(route('profile.show')).'','active' => request()->routeIs('profile.show')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.tautan-navigasi-responsif'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('profile.show')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('profile.show'))]); ?>
                    <?php echo e(__('Profile')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6cb1d266ca9ce0e045e74f734e536c1d)): ?>
<?php $attributes = $__attributesOriginal6cb1d266ca9ce0e045e74f734e536c1d; ?>
<?php unset($__attributesOriginal6cb1d266ca9ce0e045e74f734e536c1d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6cb1d266ca9ce0e045e74f734e536c1d)): ?>
<?php $component = $__componentOriginal6cb1d266ca9ce0e045e74f734e536c1d; ?>
<?php unset($__componentOriginal6cb1d266ca9ce0e045e74f734e536c1d); ?>
<?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Laravel\Jetstream\Jetstream::hasApiFeatures()): ?>
                    <?php if (isset($component)) { $__componentOriginal6cb1d266ca9ce0e045e74f734e536c1d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6cb1d266ca9ce0e045e74f734e536c1d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.tautan-navigasi-responsif','data' => ['href' => ''.e(route('api-tokens.index')).'','active' => request()->routeIs('api-tokens.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.tautan-navigasi-responsif'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('api-tokens.index')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('api-tokens.index'))]); ?>
                        <?php echo e(__('API Tokens')); ?>

                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6cb1d266ca9ce0e045e74f734e536c1d)): ?>
<?php $attributes = $__attributesOriginal6cb1d266ca9ce0e045e74f734e536c1d; ?>
<?php unset($__attributesOriginal6cb1d266ca9ce0e045e74f734e536c1d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6cb1d266ca9ce0e045e74f734e536c1d)): ?>
<?php $component = $__componentOriginal6cb1d266ca9ce0e045e74f734e536c1d; ?>
<?php unset($__componentOriginal6cb1d266ca9ce0e045e74f734e536c1d); ?>
<?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Authentication -->
                <form method="POST" action="<?php echo e(route('logout')); ?>" x-data>
                    <?php echo csrf_field(); ?>

                    <button type="submit" class="block w-full border-l-4 border-transparent py-2 pe-4 ps-3 text-start text-base font-medium text-gray-600 transition duration-150 ease-in-out hover:border-gray-300 hover:bg-gray-50 hover:text-gray-800 focus:border-gray-300 focus:bg-gray-50 focus:text-gray-800 focus:outline-none">
                        <?php echo e(__('Log Out')); ?>

                    </button>
                </form>

                <!-- Team Management -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Laravel\Jetstream\Jetstream::hasTeamFeatures()): ?>
                    <div class="border-t border-gray-200"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        <?php echo e(__('Manage Team')); ?>

                    </div>

                    <!-- Team Settings -->
                    <?php if (isset($component)) { $__componentOriginal6cb1d266ca9ce0e045e74f734e536c1d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6cb1d266ca9ce0e045e74f734e536c1d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.tautan-navigasi-responsif','data' => ['href' => ''.e(route('teams.show', Auth::user()->currentTeam->id)).'','active' => request()->routeIs('teams.show')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.tautan-navigasi-responsif'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('teams.show', Auth::user()->currentTeam->id)).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('teams.show'))]); ?>
                        <?php echo e(__('Team Settings')); ?>

                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6cb1d266ca9ce0e045e74f734e536c1d)): ?>
<?php $attributes = $__attributesOriginal6cb1d266ca9ce0e045e74f734e536c1d; ?>
<?php unset($__attributesOriginal6cb1d266ca9ce0e045e74f734e536c1d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6cb1d266ca9ce0e045e74f734e536c1d)): ?>
<?php $component = $__componentOriginal6cb1d266ca9ce0e045e74f734e536c1d; ?>
<?php unset($__componentOriginal6cb1d266ca9ce0e045e74f734e536c1d); ?>
<?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', Laravel\Jetstream\Jetstream::newTeamModel())): ?>
                        <?php if (isset($component)) { $__componentOriginal6cb1d266ca9ce0e045e74f734e536c1d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6cb1d266ca9ce0e045e74f734e536c1d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout.tautan-navigasi-responsif','data' => ['href' => ''.e(route('teams.create')).'','active' => request()->routeIs('teams.create')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout.tautan-navigasi-responsif'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('teams.create')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('teams.create'))]); ?>
                            <?php echo e(__('Create New Team')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6cb1d266ca9ce0e045e74f734e536c1d)): ?>
<?php $attributes = $__attributesOriginal6cb1d266ca9ce0e045e74f734e536c1d; ?>
<?php unset($__attributesOriginal6cb1d266ca9ce0e045e74f734e536c1d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6cb1d266ca9ce0e045e74f734e536c1d)): ?>
<?php $component = $__componentOriginal6cb1d266ca9ce0e045e74f734e536c1d; ?>
<?php unset($__componentOriginal6cb1d266ca9ce0e045e74f734e536c1d); ?>
<?php endif; ?>
                    <?php endif; ?>

                    <!-- Team Switcher -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->allTeams()->count() > 1): ?>
                        <div class="border-t border-gray-200"></div>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            <?php echo e(__('Switch Teams')); ?>

                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = Auth::user()->allTeams(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if (isset($component)) { $__componentOriginal11495b210affaaa935eca763857a8bbc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal11495b210affaaa935eca763857a8bbc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tim.tim-aktif','data' => ['team' => $team,'component' => 'layout.tautan-navigasi-responsif']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tim.tim-aktif'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['team' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($team),'component' => 'layout.tautan-navigasi-responsif']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal11495b210affaaa935eca763857a8bbc)): ?>
<?php $attributes = $__attributesOriginal11495b210affaaa935eca763857a8bbc; ?>
<?php unset($__attributesOriginal11495b210affaaa935eca763857a8bbc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11495b210affaaa935eca763857a8bbc)): ?>
<?php $component = $__componentOriginal11495b210affaaa935eca763857a8bbc; ?>
<?php unset($__componentOriginal11495b210affaaa935eca763857a8bbc); ?>
<?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<?php /**PATH C:\xampp\htdocs\appkonkos_2\resources\views/navigation-menu.blade.php ENDPATH**/ ?>