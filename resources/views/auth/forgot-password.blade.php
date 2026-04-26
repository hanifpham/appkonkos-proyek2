<x-guest-layout>
    <x-autentikasi.kartu-autentikasi>
        <x-slot name="logo">
            <x-autentikasi.logo-kartu />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        <x-formulir.error-validasi class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="block">
                <x-formulir.label for="email" value="{{ __('Email') }}" />
                <x-formulir.input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-formulir.tombol>
                    {{ __('Email Password Reset Link') }}
                </x-formulir.tombol>
            </div>
        </form>
    </x-autentikasi.kartu-autentikasi>
</x-guest-layout>
