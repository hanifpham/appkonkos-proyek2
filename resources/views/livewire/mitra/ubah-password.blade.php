<div>
    @if (session()->has('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 border border-green-200 dark:bg-green-900/20 dark:text-green-300 dark:border-green-800" role="alert">
            <div class="flex items-center">
                <span class="material-symbols-outlined mr-2">check_circle</span>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <form wire:submit.prevent="updatePassword" class="space-y-6">
        <!-- Current Password -->
        <div class="space-y-1.5">
            <label for="current_password" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Password Saat Ini</label>
            <input 
                id="current_password" 
                type="password" 
                wire:model="current_password" 
                class="w-full rounded-lg border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-gray-700 dark:bg-slate-800"
                placeholder="Masukkan password saat ini"
            >
            @error('current_password')
                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- New Password -->
        <div class="space-y-1.5">
            <label for="password" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Password Baru</label>
            <input 
                id="password" 
                type="password" 
                wire:model="password" 
                class="w-full rounded-lg border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-gray-700 dark:bg-slate-800"
                placeholder="Minimal 8 karakter"
            >
            @error('password')
                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="space-y-1.5">
            <label for="password_confirmation" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Konfirmasi Password Baru</label>
            <input 
                id="password_confirmation" 
                type="password" 
                wire:model="password_confirmation" 
                class="w-full rounded-lg border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-gray-700 dark:bg-slate-800"
                placeholder="Ulangi password baru"
            >
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end pt-4">
            <button 
                type="submit" 
                wire:loading.attr="disabled" 
                wire:target="updatePassword"
                class="flex items-center gap-2 rounded-lg bg-[#0F4C81] px-6 py-2.5 text-sm font-bold text-white shadow-md transition-all duration-200 hover:bg-[#0d3f6d] disabled:cursor-not-allowed disabled:opacity-70"
            >
                <span wire:loading.remove wire:target="updatePassword" class="material-symbols-outlined text-sm">save</span>
                <span wire:loading wire:target="updatePassword" class="material-symbols-outlined text-sm animate-spin">sync</span>
                <span wire:loading.remove wire:target="updatePassword">Simpan Password</span>
                <span wire:loading wire:target="updatePassword">Menyimpan...</span>
            </button>
        </div>
    </form>
</div>
