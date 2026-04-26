<x-guest-layout>
    <x-autentikasi.kartu-autentikasi>
        <x-slot name="logo">
            <x-autentikasi.logo-kartu />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </div>

        <x-formulir.error-validasi class="mb-4" />

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div>
                <x-formulir.label for="password" value="{{ __('Password') }}" />
                <x-formulir.input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" autofocus />
            </div>

            <div class="flex justify-end mt-4">
                <x-formulir.tombol class="ms-4">
                    {{ __('Confirm') }}
                </x-formulir.tombol>
            </div>
        </form>
    </x-autentikasi.kartu-autentikasi>
</x-guest-layout>
