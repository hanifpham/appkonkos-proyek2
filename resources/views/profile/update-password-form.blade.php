<form wire:submit="updatePassword" class="space-y-5">
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <div class="space-y-1.5 md:col-span-2">
            <label for="current_password" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Password Saat Ini</label>
            <input
                id="current_password"
                type="password"
                wire:model="state.current_password"
                autocomplete="current-password"
                class="w-full rounded-lg border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-[#113C7A] focus:ring-[#113C7A] dark:border-gray-700 dark:bg-slate-800"
            >
            <x-formulir.error-input for="current_password" class="mt-2" />
        </div>

        <div class="space-y-1.5">
            <label for="password" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Password Baru</label>
            <input
                id="password"
                type="password"
                wire:model="state.password"
                autocomplete="new-password"
                class="w-full rounded-lg border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-[#113C7A] focus:ring-[#113C7A] dark:border-gray-700 dark:bg-slate-800"
            >
            <x-formulir.error-input for="password" class="mt-2" />
        </div>

        <div class="space-y-1.5">
            <label for="password_confirmation" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Konfirmasi Password Baru</label>
            <input
                id="password_confirmation"
                type="password"
                wire:model="state.password_confirmation"
                autocomplete="new-password"
                class="w-full rounded-lg border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-[#113C7A] focus:ring-[#113C7A] dark:border-gray-700 dark:bg-slate-800"
            >
            <x-formulir.error-input for="password_confirmation" class="mt-2" />
        </div>
    </div>

    <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-5 dark:border-gray-700">
        <x-formulir.pesan-aksi class="text-sm text-gray-500 dark:text-gray-400" on="saved">
            Tersimpan.
        </x-formulir.pesan-aksi>

        <button
            type="submit"
            wire:loading.attr="disabled"
            class="inline-flex items-center rounded-lg bg-[#113C7A] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-[#0d3f6d] disabled:cursor-not-allowed disabled:opacity-60"
        >
            Simpan Password
        </button>
    </div>
</form>
