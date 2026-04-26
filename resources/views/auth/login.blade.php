<x-guest-layout>
    <x-autentikasi.kartu-autentikasi>
        <x-slot name="logo">
            <x-autentikasi.logo-kartu />
        </x-slot>

        @php
            $loginPortal = session('login_portal');
            $loginPortalLabel = match ($loginPortal) {
                'pemilik' => 'Login Mitra',
                'superadmin' => 'Login Super Admin',
                default => 'Login Pencari',
            };
            $loginPortalText = match ($loginPortal) {
                'pemilik' => 'Masuk untuk mengelola properti, pesanan, keuangan, dan profil verifikasi mitra.',
                'superadmin' => 'Masuk ke panel pengawasan sistem APPKONKOS.',
                default => 'Masuk untuk mencari properti dan mengelola booking Anda.',
            };
        @endphp

        <x-formulir.error-validasi class="mb-4" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        @if ($loginPortal !== null)
            <div class="mb-4 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-slate-700">
                <p class="font-semibold text-[#113C7A]">{{ $loginPortalLabel }}</p>
                <p class="mt-1 text-xs leading-6 text-slate-600">{{ $loginPortalText }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-formulir.label for="email" value="{{ __('Email') }}" />
                <x-formulir.input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-formulir.label for="password" value="{{ __('Password') }}" />
                <x-formulir.input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-formulir.kotak-centang id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-formulir.tombol class="ms-4">
                    {{ __('Log in') }}
                </x-formulir.tombol>
            </div>
        </form>

        <div class="mt-5 text-center">
            <a href="{{ route('auth.pilih-role') }}" class="text-xs font-medium text-[#113C7A] transition-colors hover:text-[#0d3f6d]">
                Kembali ke pilihan peran
            </a>
        </div>
    </x-autentikasi.kartu-autentikasi>
</x-guest-layout>
