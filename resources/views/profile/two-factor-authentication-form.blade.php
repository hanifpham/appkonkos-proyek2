<div class="space-y-5">
    <div class="rounded-xl border px-4 py-3 text-sm dark:border-gray-700 @if ($this->enabled) border-green-100 bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-300 @else border-amber-100 bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-300 @endif">
        @if ($this->enabled)
            @if ($showingConfirmation)
                Selesaikan aktivasi autentikasi 2 langkah dengan memasukkan kode OTP.
            @else
                Autentikasi 2 langkah sudah aktif untuk akun ini.
            @endif
        @else
            Autentikasi 2 langkah belum aktif.
        @endif
    </div>

    <p class="max-w-xl text-sm leading-7 text-gray-600 dark:text-gray-400">
        Jika fitur ini aktif, Anda akan diminta memasukkan kode dari aplikasi autentikator saat login. Gunakan Google Authenticator, Authy, atau aplikasi sejenis.
    </p>

    @if ($this->enabled)
        @if ($showingQrCode)
            <div class="space-y-4">
                <p class="max-w-xl text-sm font-semibold leading-7 text-gray-700 dark:text-gray-300">
                    @if ($showingConfirmation)
                        Pindai QR Code berikut atau masukkan setup key ke aplikasi autentikator, lalu masukkan kode OTP yang muncul.
                    @else
                        Pindai QR Code berikut atau simpan setup key jika Anda ingin menambahkan ulang autentikator.
                    @endif
                </p>

                <div class="inline-block rounded-xl border border-gray-100 bg-white p-3 shadow-sm dark:border-gray-700">
                    {!! $this->user->twoFactorQrCodeSvg() !!}
                </div>

                <div class="max-w-xl rounded-xl bg-gray-50 px-4 py-3 font-mono text-xs text-gray-700 dark:bg-slate-800 dark:text-gray-300">
                    Setup Key: {{ decrypt($this->user->two_factor_secret) }}
                </div>

                @if ($showingConfirmation)
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
                        <x-formulir.error-input for="code" class="mt-2" />
                    </div>
                @endif
            </div>
        @endif

        @if ($showingRecoveryCodes)
            <div class="space-y-3">
                <p class="max-w-xl text-sm font-semibold leading-7 text-gray-700 dark:text-gray-300">
                    Simpan kode pemulihan ini di tempat aman. Kode ini dapat digunakan jika perangkat autentikator Anda hilang.
                </p>

                <div class="grid max-w-xl gap-1 rounded-xl bg-gray-100 px-4 py-4 font-mono text-sm text-gray-700 dark:bg-slate-800 dark:text-gray-300">
                    @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                        <div>{{ $code }}</div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif

    <div class="flex flex-wrap items-center gap-3 border-t border-gray-100 pt-5 dark:border-gray-700">
        @if (! $this->enabled)
            <x-formulir.konfirmasi-password wire:then="enableTwoFactorAuthentication">
                <button type="button" wire:loading.attr="disabled" class="rounded-lg bg-[#113C7A] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-[#0d3f6d] disabled:opacity-60">
                    Aktifkan 2FA
                </button>
            </x-formulir.konfirmasi-password>
        @else
            @if ($showingRecoveryCodes)
                <x-formulir.konfirmasi-password wire:then="regenerateRecoveryCodes">
                    <button type="button" class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 transition-all duration-200 hover:bg-gray-100 dark:border-gray-700 dark:bg-slate-900 dark:text-gray-300 dark:hover:bg-gray-800">
                        Buat Ulang Kode Pemulihan
                    </button>
                </x-formulir.konfirmasi-password>
            @elseif ($showingConfirmation)
                <x-formulir.konfirmasi-password wire:then="confirmTwoFactorAuthentication">
                    <button type="button" wire:loading.attr="disabled" class="rounded-lg bg-[#113C7A] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-[#0d3f6d] disabled:opacity-60">
                        Konfirmasi
                    </button>
                </x-formulir.konfirmasi-password>
            @else
                <x-formulir.konfirmasi-password wire:then="showRecoveryCodes">
                    <button type="button" class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 transition-all duration-200 hover:bg-gray-100 dark:border-gray-700 dark:bg-slate-900 dark:text-gray-300 dark:hover:bg-gray-800">
                        Tampilkan Kode Pemulihan
                    </button>
                </x-formulir.konfirmasi-password>
            @endif

            @if ($showingConfirmation)
                <x-formulir.konfirmasi-password wire:then="disableTwoFactorAuthentication">
                    <button type="button" wire:loading.attr="disabled" class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 transition-all duration-200 hover:bg-gray-100 dark:border-gray-700 dark:bg-slate-900 dark:text-gray-300 dark:hover:bg-gray-800">
                        Batal
                    </button>
                </x-formulir.konfirmasi-password>
            @else
                <x-formulir.konfirmasi-password wire:then="disableTwoFactorAuthentication">
                    <button type="button" wire:loading.attr="disabled" class="rounded-lg bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-red-700 disabled:opacity-60">
                        Nonaktifkan
                    </button>
                </x-formulir.konfirmasi-password>
            @endif
        @endif
    </div>
</div>
