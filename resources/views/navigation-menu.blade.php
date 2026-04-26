@php
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
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ $dashboardUrl }}">
                        <x-aplikasi.tanda-aplikasi class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden items-center space-x-2 sm:ms-8 sm:flex">
                    @foreach ($navigationItems as $item)
                        <a
                            href="{{ $item['url'] }}"
                            @class([
                                'inline-flex items-center rounded-lg px-4 py-2 text-sm font-medium transition',
                                'bg-gray-900 text-white' => $item['active'],
                                'text-gray-600 hover:bg-gray-100 hover:text-gray-900' => ! $item['active'],
                            ])
                        >
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ms-3 relative">
                        <x-layout.dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ Auth::user()->currentTeam->name }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <!-- Team Settings -->
                                    <x-layout.tautan-dropdown href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                        {{ __('Team Settings') }}
                                    </x-layout.tautan-dropdown>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-layout.tautan-dropdown href="{{ route('teams.create') }}">
                                            {{ __('Create New Team') }}
                                        </x-layout.tautan-dropdown>
                                    @endcan

                                    <!-- Team Switcher -->
                                    @if (Auth::user()->allTeams()->count() > 1)
                                        <div class="border-t border-gray-200"></div>

                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Switch Teams') }}
                                        </div>

                                        @foreach (Auth::user()->allTeams() as $team)
                                            <x-tim.tim-aktif :team="$team" />
                                        @endforeach
                                    @endif
                                </div>
                            </x-slot>
                        </x-layout.dropdown>
                    </div>
                @endif

                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <x-layout.dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="size-8 rounded-full object-cover" src="{{ $profilePhotoUrl }}" alt="{{ Auth::user()->name }}" data-appkonkos-profile-photo />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ Auth::user()->name }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-layout.tautan-dropdown href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-layout.tautan-dropdown>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-layout.tautan-dropdown href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-layout.tautan-dropdown>
                            @endif

                            <div class="border-t border-gray-200"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <button type="submit" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none">
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </x-slot>
                    </x-layout.dropdown>
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
            @foreach ($navigationItems as $item)
                <a
                    href="{{ $item['url'] }}"
                    @class([
                        'mx-3 block rounded-lg px-4 py-2 text-base font-medium transition',
                        'bg-gray-900 text-white' => $item['active'],
                        'text-gray-600 hover:bg-gray-100 hover:text-gray-900' => ! $item['active'],
                    ])
                >
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-full object-cover" src="{{ $profilePhotoUrl }}" alt="{{ Auth::user()->name }}" data-appkonkos-profile-photo />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-layout.tautan-navigasi-responsif href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-layout.tautan-navigasi-responsif>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-layout.tautan-navigasi-responsif href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-layout.tautan-navigasi-responsif>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <button type="submit" class="block w-full border-l-4 border-transparent py-2 pe-4 ps-3 text-start text-base font-medium text-gray-600 transition duration-150 ease-in-out hover:border-gray-300 hover:bg-gray-50 hover:text-gray-800 focus:border-gray-300 focus:bg-gray-50 focus:text-gray-800 focus:outline-none">
                        {{ __('Log Out') }}
                    </button>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-gray-200"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Team') }}
                    </div>

                    <!-- Team Settings -->
                    <x-layout.tautan-navigasi-responsif href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" :active="request()->routeIs('teams.show')">
                        {{ __('Team Settings') }}
                    </x-layout.tautan-navigasi-responsif>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-layout.tautan-navigasi-responsif href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                            {{ __('Create New Team') }}
                        </x-layout.tautan-navigasi-responsif>
                    @endcan

                    <!-- Team Switcher -->
                    @if (Auth::user()->allTeams()->count() > 1)
                        <div class="border-t border-gray-200"></div>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Switch Teams') }}
                        </div>

                        @foreach (Auth::user()->allTeams() as $team)
                            <x-tim.tim-aktif :team="$team" component="layout.tautan-navigasi-responsif" />
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
</nav>
