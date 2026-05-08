<x-guest-layout>
    <x-autentikasi.kartu-autentikasi>
        <x-slot name="logo">
            <x-autentikasi.logo-kartu />
        </x-slot>

        <div x-data="{ recovery: false }">
            <div class="mb-4 text-sm text-gray-600" x-show="! recovery">
                {{ __('Harap konfirmasi akses ke akun Anda dengan memasukkan kode autentikasi dari aplikasi authenticator Anda.') }}
            </div>

            <div class="mb-4 text-sm text-gray-600" x-cloak x-show="recovery">
                {{ __('Harap konfirmasi akses ke akun Anda dengan memasukkan salah satu kode pemulihan darurat Anda.') }}
            </div>

            <x-formulir.error-validasi class="mb-4" />

            <form method="POST" action="{{ route('two-factor.login') }}">
                @csrf

                <div class="mt-4" x-show="! recovery">
                    <x-formulir.label for="code" value="{{ __('Kode Autentikasi') }}" />
                    <x-formulir.input id="code" class="block mt-1 w-full" type="text" inputmode="numeric" name="code" autofocus x-ref="code" autocomplete="one-time-code" />
                </div>

                <div class="mt-4" x-cloak x-show="recovery">
                    <x-formulir.label for="recovery_code" value="{{ __('Kode Pemulihan') }}" />
                    <x-formulir.input id="recovery_code" class="block mt-1 w-full" type="text" name="recovery_code" x-ref="recovery_code" autocomplete="one-time-code" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button type="button" class="text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer"
                                    x-show="! recovery"
                                    x-on:click="
                                        recovery = true;
                                        $nextTick(() => { $refs.recovery_code.focus() })
                                    ">
                        {{ __('Gunakan kode pemulihan') }}
                    </button>

                    <button type="button" class="text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer"
                                    x-cloak
                                    x-show="recovery"
                                    x-on:click="
                                        recovery = false;
                                        $nextTick(() => { $refs.code.focus() })
                                    ">
                        {{ __('Gunakan kode autentikasi') }}
                    </button>

                    <x-formulir.tombol class="ms-4">
                        {{ __('Masuk') }}
                    </x-formulir.tombol>
                </div>
            </form>
        </div>
    </x-autentikasi.kartu-autentikasi>
</x-guest-layout>
