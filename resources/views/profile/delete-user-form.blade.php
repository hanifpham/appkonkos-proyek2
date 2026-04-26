<div class="space-y-5">
    <p class="max-w-xl text-sm leading-7 text-red-700/80 dark:text-red-300/80">
        Setelah akun dihapus, seluruh data dan resource yang terhubung akan dihapus permanen. Pastikan Anda sudah menyimpan data penting sebelum melanjutkan.
    </p>

    <div class="border-t border-red-100 pt-5 dark:border-red-900/50">
        <button type="button" wire:click="confirmUserDeletion" wire:loading.attr="disabled" class="rounded-lg bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-red-700 disabled:opacity-60">
            Hapus Akun
        </button>
    </div>

    <x-modal.modal-dialog wire:model.live="confirmingUserDeletion">
        <x-slot name="title">
            Hapus Akun
        </x-slot>

        <x-slot name="content">
            <p class="text-sm leading-7 text-gray-600 dark:text-gray-400">
                Apakah Anda yakin ingin menghapus akun ini? Tindakan ini permanen. Masukkan password untuk mengonfirmasi penghapusan akun.
            </p>

            <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                <input
                    type="password"
                    class="mt-1 block w-full rounded-lg border-gray-200 px-4 py-2.5 text-sm focus:border-red-500 focus:ring-red-500 dark:border-gray-700 dark:bg-slate-800"
                    autocomplete="current-password"
                    placeholder="Password"
                    x-ref="password"
                    wire:model="password"
                    wire:keydown.enter="deleteUser"
                >

                <x-formulir.error-input for="password" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <button type="button" wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition-all duration-200 hover:bg-gray-100 dark:border-gray-700 dark:bg-slate-900 dark:text-gray-300 dark:hover:bg-gray-800">
                Batal
            </button>

            <button type="button" class="ms-3 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition-all duration-200 hover:bg-red-700" wire:click="deleteUser" wire:loading.attr="disabled">
                Hapus Permanen
            </button>
        </x-slot>
    </x-modal.modal-dialog>
</div>
