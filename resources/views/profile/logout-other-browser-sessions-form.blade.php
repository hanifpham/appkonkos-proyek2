<div class="space-y-5">
    <p class="max-w-xl text-sm leading-7 text-gray-600 dark:text-gray-400">
        Kelola sesi aktif di perangkat dan browser lain. Jika akun terasa tidak aman, keluarkan sesi lain lalu segera ganti password.
    </p>

    @if (count($this->sessions) > 0)
        <div class="space-y-3">
            @foreach ($this->sessions as $session)
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-slate-800/60">
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-gray-700 dark:text-gray-200">
                            {{ $session->agent->platform() ?: 'Tidak diketahui' }} - {{ $session->agent->browser() ?: 'Tidak diketahui' }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ $session->ip_address }},
                            @if ($session->is_current_device)
                                <span class="font-semibold text-green-600 dark:text-green-400">Perangkat ini</span>
                            @else
                                Terakhir aktif {{ $session->last_active }}
                            @endif
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="flex flex-wrap items-center gap-3 border-t border-gray-100 pt-5 dark:border-gray-700">
        <button
            type="button"
            wire:click="confirmLogout"
            wire:loading.attr="disabled"
            class="rounded-lg bg-[#113C7A] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-[#0d3f6d] disabled:opacity-60"
        >
            Keluar dari Sesi Browser Lain
        </button>

        <x-formulir.pesan-aksi class="text-sm text-gray-500 dark:text-gray-400" on="loggedOut">
            Selesai.
        </x-formulir.pesan-aksi>
    </div>

    <x-modal.modal-dialog wire:model.live="confirmingLogout">
        <x-slot name="title">
            Keluar dari Sesi Browser Lain
        </x-slot>

        <x-slot name="content">
            <p class="text-sm leading-7 text-gray-600 dark:text-gray-400">
                Masukkan password untuk mengonfirmasi bahwa Anda ingin keluar dari sesi browser lain di semua perangkat.
            </p>

            <div class="mt-4" x-data="{}" x-on:confirming-logout-other-browser-sessions.window="setTimeout(() => $refs.password.focus(), 250)">
                <input
                    type="password"
                    class="mt-1 block w-full rounded-lg border-gray-200 px-4 py-2.5 text-sm focus:border-[#113C7A] focus:ring-[#113C7A] dark:border-gray-700 dark:bg-slate-800"
                    autocomplete="current-password"
                    placeholder="Password"
                    x-ref="password"
                    wire:model="password"
                    wire:keydown.enter="logoutOtherBrowserSessions"
                >

                <x-formulir.error-input for="password" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <button type="button" wire:click="$toggle('confirmingLogout')" wire:loading.attr="disabled" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition-all duration-200 hover:bg-gray-100 dark:border-gray-700 dark:bg-slate-900 dark:text-gray-300 dark:hover:bg-gray-800">
                Batal
            </button>

            <button type="button" class="ms-3 rounded-lg bg-[#113C7A] px-4 py-2 text-sm font-semibold text-white transition-all duration-200 hover:bg-[#0d3f6d]" wire:click="logoutOtherBrowserSessions" wire:loading.attr="disabled">
                Keluar dari Sesi Lain
            </button>
        </x-slot>
    </x-modal.modal-dialog>
</div>
