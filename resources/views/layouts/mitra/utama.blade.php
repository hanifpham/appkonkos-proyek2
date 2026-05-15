<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Appkonkos') }} - @yield('mitra-title', 'Dashboard Mitra')</title>

        <link rel="icon" type="image/png" href="{{ asset('images/appkonkos.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/appkonkos.png') }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .material-symbols-outlined {
                font-family: 'Material Symbols Outlined';
                font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
                font-style: normal;
                font-weight: normal;
                line-height: 1;
                letter-spacing: normal;
                text-transform: none;
                white-space: nowrap;
                word-wrap: normal;
                direction: ltr;
                -webkit-font-feature-settings: 'liga';
                font-feature-settings: 'liga';
                -webkit-font-smoothing: antialiased;
            }
        </style>

        @livewireStyles
        @stack('styles')
    </head>
    <body
        x-data="{
            darkMode: false,
            mobileSidebarOpen: false,
            init() {
                this.darkMode = window.appkonkosTheme?.isDark() ?? document.documentElement.classList.contains('dark');
                window.addEventListener('appkonkos-theme-changed', (event) => {
                    this.darkMode = event.detail.darkMode;
                });
            },
            toggleDarkMode() {
                this.darkMode = window.appkonkosTheme?.toggle() ?? !this.darkMode;
                document.documentElement.classList.toggle('dark', this.darkMode);
            }
        }"
        x-init="init()"
        class="h-screen overflow-hidden bg-[#eef1f5] font-['Poppins'] text-slate-800 antialiased dark:bg-[#0b1120] dark:text-slate-200"
    >
        @php
            use App\Models\Booking;
            use App\Models\PencairanDana;

            $user = auth()->user();
            $pemilikId = $user?->pemilikProperti?->id;
            $unreadNotificationCount = $user?->unreadNotifications()->count() ?? 0;

            if ($user?->role === 'pemilik' && $pemilikId !== null) {
                $pendingBadgeCount = Booking::query()
                    ->where('status_booking', 'pending')
                    ->where(function ($query) use ($pemilikId): void {
                        $query->whereHas('kontrakan', function ($kontrakanQuery) use ($pemilikId): void {
                            $kontrakanQuery->where('pemilik_properti_id', $pemilikId);
                        })->orWhereHas('kamar.tipeKamar.kosan', function ($kosanQuery) use ($pemilikId): void {
                            $kosanQuery->where('pemilik_properti_id', $pemilikId);
                        });
                    })
                    ->count();
            } elseif ($user?->role === 'superadmin') {
                $pendingBadgeCount = PencairanDana::query()
                    ->where('status', 'pending')
                    ->count();
            } else {
                $pendingBadgeCount = 0;
            }

            $profilePhotoPath = is_string($user?->profile_photo_path)
                ? ltrim($user->profile_photo_path, '/')
                : '';

            if ($profilePhotoPath !== '') {
                $profilePhotoPath = preg_replace('#^(storage/|public/storage/)#', '', $profilePhotoPath) ?? $profilePhotoPath;
                $sourceProfilePhoto = storage_path('app/public/'.$profilePhotoPath);
                $publicProfilePhoto = public_path('storage/'.$profilePhotoPath);

                if (file_exists($sourceProfilePhoto)) {
                    if (! is_dir(dirname($publicProfilePhoto))) {
                        mkdir(dirname($publicProfilePhoto), 0775, true);
                    }

                    if (! file_exists($publicProfilePhoto) || filesize($publicProfilePhoto) !== filesize($sourceProfilePhoto)) {
                        copy($sourceProfilePhoto, $publicProfilePhoto);
                    }
                }
            }

            $profilePhotoUrl = $profilePhotoPath !== ''
                ? (rtrim(request()->getBaseUrl(), '/') === ''
                    ? '/storage/'.$profilePhotoPath
                    : rtrim(request()->getBaseUrl(), '/').'/storage/'.$profilePhotoPath)
                    .'?v='.($user?->updated_at?->timestamp ?? now()->timestamp)
                : 'https://ui-avatars.com/api/?name='.urlencode($user?->name ?? 'User').'&color=113C7A&background=EBF4FF';
            $profilePhotoFallbackUrl = 'https://ui-avatars.com/api/?name='.urlencode($user?->name ?? 'User').'&color=113C7A&background=EBF4FF';

            $sidebarItems = $user?->role === 'superadmin'
                ? [
                    [
                        'label' => 'Dashboard Utama',
                        'icon' => 'dashboard',
                        'url' => route('superadmin.dashboard'),
                        'active' => request()->routeIs('superadmin.dashboard'),
                        'badge' => null,
                    ],
                    [
                        'label' => 'Manajemen Pengguna',
                        'icon' => 'group',
                        'url' => route('superadmin.pengguna'),
                        'active' => request()->routeIs('superadmin.pengguna*'),
                        'badge' => null,
                    ],
                    [
                        'label' => 'Moderasi Properti',
                        'icon' => 'real_estate_agent',
                        'url' => route('superadmin.properti'),
                        'active' => request()->routeIs('superadmin.properti*') || request()->routeIs('superadmin.moderasi.*'),
                        'badge' => null,
                    ],
                    [
                        'label' => 'Transaksi Midtrans',
                        'icon' => 'receipt_long',
                        'url' => route('superadmin.transaksi'),
                        'active' => request()->routeIs('superadmin.transaksi'),
                        'badge' => null,
                    ],
                    [
                        'label' => 'Pencairan Dana',
                        'icon' => 'account_balance_wallet',
                        'url' => route('superadmin.pencairan'),
                        'active' => request()->routeIs('superadmin.pencairan*'),
                        'badge' => $pendingBadgeCount > 0 ? $pendingBadgeCount : null,
                    ],
                    [
                        'label' => 'Pengajuan Refund',
                        'icon' => 'assignment_return',
                        'url' => route('superadmin.refund'),
                        'active' => request()->routeIs('superadmin.refund*'),
                        'badge' => null,
                    ],
                    [
                        'label' => 'Pengaturan Profil',
                        'icon' => 'settings',
                        'url' => route('superadmin.pengaturan-profil'),
                        'active' => request()->routeIs('superadmin.pengaturan-profil'),
                        'badge' => null,
                    ],
                ]
                : [
                    [
                        'label' => 'Dashboard',
                        'icon' => 'dashboard',
                        'url' => route('mitra.dashboard'),
                        'active' => request()->routeIs('mitra.dashboard'),
                        'badge' => null,
                    ],
                    [
                        'label' => 'Properti Saya',
                        'icon' => 'real_estate_agent',
                        'url' => route('mitra.properti'),
                        'active' => request()->routeIs('mitra.properti*'),
                        'badge' => null,
                    ],
                    [
                        'label' => 'Notifikasi',
                        'icon' => 'notifications',
                        'url' => route('mitra.notifikasi'),
                        'active' => request()->routeIs('mitra.notifikasi*'),
                        'badge' => $unreadNotificationCount > 0 ? $unreadNotificationCount : null,
                    ],
                    [
                        'label' => 'Riwayat Booking',
                        'icon' => 'inbox',
                        'url' => route('mitra.pesanan'),
                        'active' => request()->routeIs('mitra.pesanan*'),
                        'badge' => null,
                    ],
                    [
                        'label' => 'Keuangan',
                        'icon' => 'account_balance_wallet',
                        'url' => route('mitra.keuangan'),
                        'active' => request()->routeIs('mitra.keuangan*'),
                        'badge' => null,
                    ],
                    [
                        'label' => 'Ulasan Penyewa',
                        'icon' => 'star',
                        'url' => route('mitra.ulasan'),
                        'active' => request()->routeIs('mitra.ulasan*'),
                        'badge' => null,
                    ],
                    [
                        'label' => 'Pengaturan Profil',
                        'icon' => 'settings',
                        'url' => route('mitra.pengaturan-profil'),
                        'active' => request()->routeIs('mitra.pengaturan-profil'),
                        'badge' => null,
                    ],
                ];

            $sidebarWidthClass = $user?->role === 'superadmin' ? 'w-[270px]' : 'w-[250px]';
            $mainPaddingClass = $user?->role === 'superadmin' ? 'lg:pl-[270px]' : 'lg:pl-[250px]';
            $sidebarProfileWrapperClass = $user?->role === 'superadmin'
                ? 'p-3.5'
                : 'p-4';
            $sidebarProfileGapClass = $user?->role === 'superadmin'
                ? 'gap-2.5'
                : 'gap-3';
            $sidebarProfileImageClass = $user?->role === 'superadmin'
                ? 'h-10 w-10'
                : 'h-12 w-12';
            $sidebarProfileNameClass = $user?->role === 'superadmin'
                ? 'text-[13px]'
                : 'text-sm';
            $sidebarProfileEmailClass = $user?->role === 'superadmin'
                ? 'text-[10px]'
                : 'text-[11px]';
            $sidebarLogoutButtonClass = $user?->role === 'superadmin'
                ? 'gap-1.5 rounded-[9px] py-2 text-[13px]'
                : 'gap-2 rounded-[10px] py-2.5 text-sm';
            $sidebarLogoutIconClass = $user?->role === 'superadmin'
                ? 'text-[16px]'
                : 'text-[18px]';

            $defaultPageTitle = $user?->role === 'superadmin' ? 'Dashboard Super Admin' : 'Dashboard Utama';
            $defaultPageSubtitle = $user?->role === 'superadmin'
                ? 'Pantau operasional platform, transaksi, dan pencairan dana dari satu panel.'
                : 'Selamat datang kembali, kelola pesanan dan properti Anda hari ini.';

            $pageTitle = trim($__env->yieldContent('mitra-title', $defaultPageTitle));
            $pageSubtitle = trim($__env->yieldContent('mitra-subtitle', $defaultPageSubtitle));
            $currentTimeLabel = now('Asia/Jakarta')->locale('id')->translatedFormat('l, d F Y | H:i').' WIB';
        @endphp

        <div class="flex h-screen w-full overflow-hidden bg-[#f5f7fb] dark:bg-[#10192d]">
            <div
                class="fixed inset-0 z-40 bg-slate-950/45 backdrop-blur-sm lg:hidden"
                x-cloak
                x-show="mobileSidebarOpen"
                x-transition.opacity
                @click="mobileSidebarOpen = false"
            ></div>

            <aside
                class="fixed inset-y-0 left-0 z-50 flex {{ $sidebarWidthClass }} flex-col bg-[#163f7a] transition-transform duration-300 dark:bg-[#0a1224] lg:translate-x-0"
                :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            >
                <div class="flex min-h-[100px] shrink-0 items-center border-b border-white px-6">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center overflow-hidden rounded-full bg-white shadow-lg shadow-black/10">
                            <img class="h-10 w-10 object-contain" src="{{ asset('images/appkonkos.png') }}" alt="{{ config('app.name', 'APPKONKOS') }}">
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[18px] font-bold leading-tight tracking-wide text-white">APPKONKOS</span>
                            <span class="text-[10px] font-light text-blue-200">Pemesanan Kos &amp; Kontrakan</span>
                        </div>
                    </div>
                </div>

                <nav class="flex flex-1 flex-col gap-3 px-4 py-7">
                    @foreach ($sidebarItems as $item)
                        <a
                            href="{{ $item['url'] }}"
                            @click="mobileSidebarOpen = false"
                            @class([
                                'flex items-start rounded-xl px-4 py-3.5 text-[14px] leading-5 transition-colors',
                                'border border-white/10 bg-[#335b94] font-medium text-white shadow-[inset_0_1px_0_rgba(255,255,255,0.08)] hover:bg-[#3a659f]' => $item['active'],
                                'text-blue-100 hover:bg-white/10 hover:text-white' => ! $item['active'],
                            ])
                        >
                            <div class="flex min-w-0 flex-1 items-start gap-3">
                                <span class="material-symbols-outlined mt-0.5 shrink-0 text-[20px]">{{ $item['icon'] }}</span>
                                <span class="min-w-0 break-words pr-3 {{ $user?->role === 'superadmin' ? 'text-[13.5px]' : '' }}">{{ $item['label'] }}</span>
                            </div>

                            @if ($item['badge'] !== null)
                                <span class="ml-2 mt-0.5 shrink-0 rounded-full bg-red-500 px-1.5 py-0.5 text-[10px] font-bold text-white">{{ $item['badge'] }}</span>
                            @endif
                        </a>
                    @endforeach
                </nav>

                <div class="shrink-0 border-t border-white/10 bg-[#14366a] {{ $sidebarProfileWrapperClass }} dark:bg-black/20">
                    <div class="mb-3 flex items-center {{ $sidebarProfileGapClass }}">
                        <img alt="{{ $user?->name }}" class="{{ $sidebarProfileImageClass }} rounded-full border-2 border-white/30 object-cover shadow-sm" src="{{ $profilePhotoUrl }}" data-appkonkos-profile-photo onerror="this.onerror=null;this.src='{{ $profilePhotoFallbackUrl }}';">
                        <div class="min-w-0 flex-1">
                            <span class="block truncate {{ $sidebarProfileNameClass }} font-semibold text-white">{{ $user?->name }}</span>
                            <span class="block truncate {{ $sidebarProfileEmailClass }} text-blue-300">{{ $user?->email }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center justify-center {{ $sidebarLogoutButtonClass }} bg-red-600 font-medium text-white shadow-sm transition-colors hover:bg-red-700">
                            <span class="material-symbols-outlined {{ $sidebarLogoutIconClass }}">logout</span>
                            Keluar
                        </button>
                    </form>
                </div>
            </aside>

            <main class="flex min-w-0 flex-1 flex-col overflow-hidden {{ $mainPaddingClass }}">
                <header class="sticky top-0 z-30 shrink-0 border-b border-slate-200 bg-white dark:border-white dark:bg-[#10192c]">
                    <div class="flex min-h-[100px] items-center justify-between gap-4 px-4 py-4 sm:px-6 xl:px-8">
                        <div class="flex min-w-0 items-start gap-3">
                            <button
                                type="button"
                                class="mt-1 inline-flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 lg:hidden"
                                @click="mobileSidebarOpen = true"
                            >
                                <span class="material-symbols-outlined">menu</span>
                            </button>

                            <div class="min-w-0">
                                <span class="block truncate text-[24px] font-bold leading-tight text-slate-900 dark:text-white">{{ $pageTitle }}</span>
                                <span class="mt-1 block truncate text-[14px] font-normal text-slate-500 dark:text-slate-400">{{ $pageSubtitle }}</span>
                            </div>
                        </div>

                        <div class="relative flex shrink-0 items-center gap-2 sm:gap-3">
                            <div class="hidden items-center rounded-xl border border-slate-200 bg-[#f6f7fb] px-4 py-2.5 text-sm font-medium text-slate-600 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 lg:flex">
                                <span class="material-symbols-outlined mr-2 text-[18px]">calendar_today</span>
                                {{ $currentTimeLabel }}
                            </div>

                            @if(auth()->user()?->role === 'superadmin')
                                <livewire:superadmin.notification-bell />
                            @else
                                <livewire:common.notification-bell />
                            @endif

                            <button
                                type="button"
                                @click="toggleDarkMode()"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-transparent text-slate-500 transition hover:bg-slate-100 hover:text-[#0F4C81] dark:text-slate-300 dark:hover:bg-slate-900 dark:hover:text-white"
                            >
                                <span class="material-symbols-outlined text-[22px]" x-show="!darkMode">dark_mode</span>
                                <span class="material-symbols-outlined text-[22px]" x-show="darkMode" x-cloak>light_mode</span>
                            </button>
                        </div>
                    </div>
                </header>

                <div class="flex-1 overflow-y-auto overflow-x-hidden">
                    {{ $slot }}
                </div>
            </main>
        </div>

        @stack('modals')

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @livewireScripts
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (typeof Swal === 'undefined') {
                    return;
                }

                const showSystemToast = ({ icon = 'success', title = 'Berhasil', text = '' } = {}) => {
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        customClass: {
                            popup: 'swal2-system-toast',
                            title: 'swal2-system-toast-title',
                            htmlContainer: 'swal2-system-toast-text',
                        },
                        didOpen: (toastElement) => {
                            toastElement.onmouseenter = Swal.stopTimer;
                            toastElement.onmouseleave = Swal.resumeTimer;
                        },
                    });

                    toast.fire({
                        icon,
                        title,
                        text,
                    });
                };

                const modalBaseConfig = {
                    background: 'transparent',
                    backdrop: 'rgba(15, 23, 42, 0.58)',
                    buttonsStyling: false,
                    showClass: {
                        popup: 'swal2-app-show',
                    },
                    hideClass: {
                        popup: 'swal2-app-hide',
                    },
                    customClass: {
                        popup: 'swal2-app-modal',
                        title: 'swal2-app-title',
                        htmlContainer: 'swal2-app-text',
                        actions: 'swal2-app-actions',
                        confirmButton: 'swal2-app-confirm',
                        cancelButton: 'swal2-app-cancel',
                        input: 'swal2-app-input',
                        validationMessage: 'swal2-app-validation',
                    },
                };

                const resolveLivewireComponent = (event) => {
                    if (typeof window.Livewire === 'undefined') {
                        return null;
                    }

                    const target = event.target;

                    if (!(target instanceof Element)) {
                        return null;
                    }

                    const componentRoot = target.closest('[wire\\:id]');
                    const componentId = componentRoot?.getAttribute('wire:id');

                    if (!componentId) {
                        return null;
                    }

                    return window.Livewire.find(componentId);
                };

                const callLivewireMethod = (event, methodName, methodArgs = []) => {
                    const component = resolveLivewireComponent(event);

                    if (!component || typeof component.call !== 'function' || !methodName) {
                        return;
                    }

                    component.call(methodName, ...methodArgs);
                };

                window.addEventListener('appkonkos-validasi-error', (event) => {
                    showSystemToast({
                        icon: 'error',
                        title: event.detail?.title ?? 'Peringatan!',
                        text: event.detail?.text ?? 'Ada form yang belum diisi dengan benar. Silakan periksa kembali.',
                    });
                });

                window.addEventListener('appkonkos-toast', (event) => {
                    showSystemToast({
                        icon: event.detail?.icon ?? 'success',
                        title: event.detail?.title ?? 'Berhasil',
                        text: event.detail?.text ?? '',
                    });
                });

                window.addEventListener('appkonkos-profile-photo-updated', (event) => {
                    const updatedUrl = event.detail?.url;

                    if (!updatedUrl) {
                        return;
                    }

                    document.querySelectorAll('[data-appkonkos-profile-photo]').forEach((imageElement) => {
                        imageElement.src = updatedUrl;
                    });
                });

                window.addEventListener('swal:confirm-reject', (event) => {
                    const detail = event.detail ?? {};

                    Swal.fire({
                        ...modalBaseConfig,
                        title: detail.title ?? 'Alasan Penolakan',
                        text: detail.text ?? '',
                        icon: detail.icon ?? 'warning',
                        input: 'textarea',
                        inputPlaceholder: detail.input_placeholder ?? 'Tuliskan alasan penolakan di sini...',
                        showCancelButton: true,
                        confirmButtonText: detail.confirm_button_text ?? 'Tolak Sekarang',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: detail.confirm_button_color ?? '#EF4444',
                        reverseButtons: true,
                        inputValidator: (value) => {
                            if (!value || value.trim() === '') {
                                return 'Alasan penolakan wajib diisi!';
                            }

                            return null;
                        },
                    }).then((result) => {
                        if (!result.isConfirmed) {
                            return;
                        }

                        callLivewireMethod(event, detail.method_name, [
                            ...(Array.isArray(detail.method_args) ? detail.method_args : []),
                            result.value.trim(),
                        ]);
                    });
                });

                window.addEventListener('swal:confirm-approve', (event) => {
                    const detail = event.detail ?? {};

                    Swal.fire({
                        ...modalBaseConfig,
                        title: detail.title ?? 'Konfirmasi',
                        text: detail.text ?? 'Apakah Anda yakin ingin melanjutkan aksi ini?',
                        icon: detail.icon ?? 'warning',
                        showCancelButton: true,
                        confirmButtonText: detail.confirm_button_text ?? 'Ya, Lanjutkan',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: detail.confirm_button_color ?? '#0F4C81',
                        reverseButtons: true,
                    }).then((result) => {
                        if (!result.isConfirmed) {
                            return;
                        }

                        callLivewireMethod(
                            event,
                            detail.method_name,
                            Array.isArray(detail.method_args) ? detail.method_args : [],
                        );
                    });
                });
            });
        </script>
        <style>
            .swal2-system-toast {
                border-radius: 20px !important;
                border: 1px solid rgba(226, 232, 240, 0.9) !important;
                background: rgba(255, 255, 255, 0.96) !important;
                box-shadow: 0 20px 45px rgba(15, 23, 42, 0.16) !important;
                backdrop-filter: blur(10px);
                padding: 18px 18px !important;
                width: 420px !important;
            }

            .swal2-system-toast-title {
                font-size: 16px !important;
                font-weight: 700 !important;
                color: #0f172a !important;
            }

            .swal2-system-toast-text {
                font-size: 14px !important;
                line-height: 1.6 !important;
                color: #475569 !important;
                margin-top: 4px !important;
            }

            html.dark .swal2-system-toast {
                border-color: rgba(51, 65, 85, 0.9) !important;
                background: rgba(15, 23, 42, 0.96) !important;
                box-shadow: 0 20px 45px rgba(2, 6, 23, 0.45) !important;
            }

            html.dark .swal2-system-toast-title {
                color: #f8fafc !important;
            }

            html.dark .swal2-system-toast-text {
                color: #cbd5e1 !important;
            }

            .swal2-app-show {
                animation: swal2AppIn 180ms cubic-bezier(0.2, 0.9, 0.2, 1) forwards;
            }

            .swal2-app-hide {
                animation: swal2AppOut 140ms ease-in forwards;
            }

            .swal2-app-modal {
                width: min(560px, calc(100vw - 32px)) !important;
                border: 1px solid rgba(203, 213, 225, 0.75) !important;
                border-radius: 28px !important;
                background:
                    radial-gradient(circle at top right, rgba(96, 165, 250, 0.18), transparent 32%),
                    radial-gradient(circle at top left, rgba(14, 165, 233, 0.14), transparent 28%),
                    linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(248, 250, 252, 0.98)) !important;
                box-shadow:
                    0 30px 70px rgba(15, 23, 42, 0.22),
                    inset 0 1px 0 rgba(255, 255, 255, 0.65) !important;
                padding: 28px 28px 24px !important;
            }

            .swal2-app-title {
                font-size: 24px !important;
                line-height: 1.2 !important;
                font-weight: 800 !important;
                letter-spacing: -0.03em !important;
                color: #0f172a !important;
                padding: 0 !important;
                margin-top: 8px !important;
            }

            .swal2-app-text {
                font-size: 14px !important;
                line-height: 1.7 !important;
                color: #475569 !important;
                margin: 10px 0 0 !important;
            }

            .swal2-app-input {
                min-height: 140px !important;
                border-radius: 18px !important;
                border: 1px solid rgba(148, 163, 184, 0.4) !important;
                background: rgba(255, 255, 255, 0.92) !important;
                box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.04) !important;
                color: #0f172a !important;
                font-size: 14px !important;
                line-height: 1.6 !important;
                padding: 16px 18px !important;
                margin-top: 18px !important;
            }

            .swal2-app-input:focus {
                border-color: rgba(15, 76, 129, 0.5) !important;
                box-shadow:
                    0 0 0 4px rgba(15, 76, 129, 0.12),
                    inset 0 1px 2px rgba(15, 23, 42, 0.04) !important;
            }

            .swal2-app-validation {
                margin: 12px 0 0 !important;
                border: 1px solid rgba(254, 202, 202, 0.95) !important;
                border-radius: 14px !important;
                background: rgba(254, 242, 242, 0.96) !important;
                color: #b91c1c !important;
                font-size: 13px !important;
                font-weight: 600 !important;
                padding: 12px 14px !important;
            }

            .swal2-app-actions {
                width: 100% !important;
                gap: 12px !important;
                margin-top: 22px !important;
                justify-content: flex-end !important;
            }

            .swal2-app-confirm,
            .swal2-app-cancel {
                border: 0 !important;
                border-radius: 999px !important;
                padding: 12px 18px !important;
                font-size: 13px !important;
                font-weight: 700 !important;
                letter-spacing: 0.01em !important;
                transition:
                    transform 140ms ease,
                    box-shadow 140ms ease,
                    background 140ms ease,
                    color 140ms ease !important;
            }

            .swal2-app-confirm {
                background: linear-gradient(135deg, #0f4c81, #1d70b8) !important;
                color: #fff !important;
                box-shadow: 0 16px 28px rgba(15, 76, 129, 0.28) !important;
            }

            .swal2-app-confirm:hover {
                transform: translateY(-1px);
                box-shadow: 0 20px 34px rgba(15, 76, 129, 0.34) !important;
            }

            .swal2-app-cancel {
                background: rgba(241, 245, 249, 0.98) !important;
                color: #334155 !important;
                box-shadow: inset 0 0 0 1px rgba(148, 163, 184, 0.35) !important;
            }

            .swal2-app-cancel:hover {
                transform: translateY(-1px);
                background: rgba(226, 232, 240, 0.98) !important;
            }

            .swal2-popup .swal2-icon {
                margin: 0 auto 4px !important;
                border-width: 3px !important;
                transform: scale(0.92);
            }

            .swal2-popup .swal2-icon.swal2-warning {
                border-color: rgba(245, 158, 11, 0.35) !important;
                color: #f59e0b !important;
            }

            html.dark .swal2-app-modal {
                border-color: rgba(51, 65, 85, 0.88) !important;
                background:
                    radial-gradient(circle at top right, rgba(59, 130, 246, 0.18), transparent 32%),
                    radial-gradient(circle at top left, rgba(14, 165, 233, 0.14), transparent 28%),
                    linear-gradient(180deg, rgba(15, 23, 42, 0.98), rgba(2, 6, 23, 0.98)) !important;
                box-shadow:
                    0 30px 70px rgba(2, 6, 23, 0.5),
                    inset 0 1px 0 rgba(255, 255, 255, 0.04) !important;
            }

            html.dark .swal2-app-title {
                color: #f8fafc !important;
            }

            html.dark .swal2-app-text {
                color: #cbd5e1 !important;
            }

            html.dark .swal2-app-input {
                border-color: rgba(71, 85, 105, 0.75) !important;
                background: rgba(15, 23, 42, 0.88) !important;
                color: #f8fafc !important;
                box-shadow: inset 0 1px 2px rgba(2, 6, 23, 0.35) !important;
            }

            html.dark .swal2-app-input:focus {
                border-color: rgba(96, 165, 250, 0.58) !important;
                box-shadow:
                    0 0 0 4px rgba(59, 130, 246, 0.16),
                    inset 0 1px 2px rgba(2, 6, 23, 0.35) !important;
            }

            html.dark .swal2-app-cancel {
                background: rgba(30, 41, 59, 0.94) !important;
                color: #e2e8f0 !important;
                box-shadow: inset 0 0 0 1px rgba(71, 85, 105, 0.7) !important;
            }

            html.dark .swal2-app-cancel:hover {
                background: rgba(51, 65, 85, 0.94) !important;
            }

            @keyframes swal2AppIn {
                from {
                    opacity: 0;
                    transform: translateY(12px) scale(0.96);
                }

                to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            @keyframes swal2AppOut {
                from {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }

                to {
                    opacity: 0;
                    transform: translateY(8px) scale(0.98);
                }
            }
        </style>
        @stack('scripts')
    </body>
</html>
