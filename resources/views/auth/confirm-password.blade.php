<x-guest-layout>
    <x-autentikasi.kartu-autentikasi>
        <x-slot name="logo">
            <x-autentikasi.logo-kartu />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Area ini merupakan bagian aplikasi yang aman. Harap konfirmasi kata sandi Anda sebelum melanjutkan.') }}
        </div>

        <x-formulir.error-validasi class="mb-4" />

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div>
                <x-formulir.label for="password" value="{{ __('Kata Sandi') }}" />
                <x-formulir.input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" autofocus />
            </div>

            <div class="flex justify-end mt-4">
                <x-formulir.tombol class="ms-4">
                    {{ __('Konfirmasi') }}
                </x-formulir.tombol>
            </div>
        </form>
    </x-autentikasi.kartu-autentikasi>
</x-guest-layout>
