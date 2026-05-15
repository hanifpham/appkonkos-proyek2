<div class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row gap-8">

        <!-- SIDEBAR KIRI -->
        <x-pencari.sidebar active="profil" />

        <!-- KONTEN UTAMA KANAN -->
        <div class="flex-1 min-w-0" x-data="{ tab: 'informasi' }">

            <div class="bg-white dark:bg-slate-900 rounded-[24px] shadow-sm border border-slate-200 dark:border-slate-800 min-h-[400px] transition-colors">
                <!-- Tab Navigasi Atas -->
                <div class="flex gap-8 border-b border-slate-200 dark:border-slate-800 px-8 pt-4">
                    <button @click="tab = 'informasi'"
                        :class="{'border-[#1967d2] dark:border-blue-500 text-[#1967d2] dark:text-blue-400': tab === 'informasi', 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:border-slate-300 dark:hover:border-slate-700': tab !== 'informasi'}"
                        class="pb-4 px-1 border-b-2 font-bold text-[15px] transition-colors focus:outline-none">
                        Informasi Pribadi
                    </button>
                    <button @click="tab = 'keamanan'"
                        :class="{'border-[#1967d2] dark:border-blue-500 text-[#1967d2] dark:text-blue-400': tab === 'keamanan', 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:border-slate-300 dark:hover:border-slate-700': tab !== 'keamanan'}"
                        class="pb-4 px-1 border-b-2 font-bold text-[15px] transition-colors focus:outline-none">
                        Keamanan Akun
                    </button>
                </div>

                <!-- Isi Tab 1: Informasi Pribadi -->
                <div x-show="tab === 'informasi'" style="display: none;"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="p-8">

                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Informasi Dasar</h2>

                    @if (session()->has('success_profile'))
                    <div class="mb-6 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 px-4 py-3 rounded-xl flex items-center gap-3">
                        <svg class="w-5 h-5 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium text-sm">{{ session('success_profile') }}</span>
                    </div>
                    @endif

                    <form wire:submit.prevent="updateProfile" class="space-y-6 mt-2">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                            <!-- Nama Lengkap -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nama Lengkap</label>
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500">
                                        <span class="material-symbols-outlined text-[20px]">person</span>
                                    </div>
                                    <input type="text" wire:model="name" class="w-full border-slate-200 dark:border-slate-800 rounded-xl shadow-sm focus:border-[#1967d2] focus:ring focus:ring-[#1967d2]/20 transition-all text-slate-900 dark:text-white bg-slate-50/50 dark:bg-slate-950 pl-11 py-3 placeholder:text-slate-400 dark:placeholder:text-slate-600 hover:bg-slate-50 dark:hover:bg-slate-900">
                                </div>
                                @error('name') <span class="text-rose-500 dark:text-rose-400 text-xs mt-1.5 flex font-medium items-center gap-1"><span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}</span> @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Email</label>
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500">
                                        <span class="material-symbols-outlined text-[20px]">mail</span>
                                    </div>
                                    <input type="email" wire:model="email" disabled readonly class="w-full border-slate-200 dark:border-slate-800 bg-slate-100/50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 rounded-xl shadow-none cursor-not-allowed pl-11 py-3">
                                </div>
                            </div>

                            <!-- Nomor WhatsApp -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nomor WhatsApp</label>
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500">
                                        <span class="material-symbols-outlined text-[20px]">call</span>
                                    </div>
                                    <input type="text" wire:model="no_wa" class="w-full border-slate-200 dark:border-slate-800 rounded-xl shadow-sm focus:border-[#1967d2] focus:ring focus:ring-[#1967d2]/20 transition-all text-slate-900 dark:text-white bg-slate-50/50 dark:bg-slate-950 pl-11 py-3 placeholder:text-slate-400 dark:placeholder:text-slate-600 hover:bg-slate-50 dark:hover:bg-slate-900" placeholder="Contoh: 081234567890">
                                </div>
                                @error('no_wa') <span class="text-rose-500 dark:text-rose-400 text-xs mt-1.5 flex font-medium items-center gap-1"><span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}</span> @enderror
                            </div>

                            <!-- Jenis Kelamin -->
                            <div x-data="{ open: false, selected: @entangle('jenis_kelamin') }">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Jenis Kelamin</label>
                                <div class="relative">
                                    <button type="button" @click="open = !open" @click.outside="open = false"
                                        class="w-full flex items-center justify-between border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm focus:border-[#1967d2] focus:ring focus:ring-[#1967d2]/20 transition-all text-slate-900 dark:text-white bg-slate-50/50 dark:bg-slate-950 py-3 pl-11 pr-4 hover:bg-slate-50 dark:hover:bg-slate-900 text-left">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500 pointer-events-none">
                                            <span class="material-symbols-outlined text-[20px]">wc</span>
                                        </div>
                                        <span x-text="selected ? selected : 'Pilih Jenis Kelamin'" :class="!selected ? 'text-slate-400 dark:text-slate-600' : ''" class="block truncate"></span>
                                        <span class="material-symbols-outlined text-[20px] text-slate-500 dark:text-slate-400 pointer-events-none transition-transform duration-200" :class="open ? 'rotate-180' : ''">expand_more</span>
                                    </button>

                                    <div x-show="open" style="display: none;"
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute z-10 mt-2 w-full bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-100 dark:border-slate-700 overflow-hidden">
                                        <ul class="py-1 text-base focus:outline-none sm:text-sm m-0">
                                            <li @click="selected = 'Laki-laki'; open = false" class="cursor-pointer select-none relative py-3 pl-4 pr-9 text-slate-900 dark:text-slate-100 hover:bg-blue-50 dark:hover:bg-slate-700 hover:text-[#1967d2] dark:hover:text-blue-400 transition-colors">
                                                <span class="block font-medium" :class="selected === 'Laki-laki' ? 'text-[#1967d2] dark:text-blue-400 font-semibold' : ''">Laki-laki</span>
                                                <span x-show="selected === 'Laki-laki'" class="absolute inset-y-0 right-0 flex items-center pr-4 text-[#1967d2] dark:text-blue-400">
                                                    <span class="material-symbols-outlined text-[20px]">check</span>
                                                </span>
                                            </li>
                                            <li @click="selected = 'Perempuan'; open = false" class="cursor-pointer select-none relative py-3 pl-4 pr-9 text-slate-900 dark:text-slate-100 hover:bg-blue-50 dark:hover:bg-slate-700 hover:text-[#1967d2] dark:hover:text-blue-400 transition-colors">
                                                <span class="block font-medium" :class="selected === 'Perempuan' ? 'text-[#1967d2] dark:text-blue-400 font-semibold' : ''">Perempuan</span>
                                                <span x-show="selected === 'Perempuan'" class="absolute inset-y-0 right-0 flex items-center pr-4 text-[#1967d2] dark:text-blue-400">
                                                    <span class="material-symbols-outlined text-[20px]">check</span>
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                @error('jenis_kelamin') <span class="text-rose-500 dark:text-rose-400 text-xs mt-1.5 flex font-medium items-center gap-1"><span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}</span> @enderror
                            </div>

                            <!-- Pekerjaan -->
                            <div x-data="{ open: false, selected: @entangle('pekerjaan') }">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Pekerjaan/Status</label>
                                <div class="relative">
                                    <button type="button" @click="open = !open" @click.outside="open = false"
                                        class="w-full flex items-center justify-between border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm focus:border-[#1967d2] focus:ring focus:ring-[#1967d2]/20 transition-all text-slate-900 dark:text-white bg-slate-50/50 dark:bg-slate-950 py-3 pl-11 pr-4 hover:bg-slate-50 dark:hover:bg-slate-900 text-left">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500 pointer-events-none">
                                            <span class="material-symbols-outlined text-[20px]">work</span>
                                        </div>
                                        <span x-text="selected ? selected : 'Pilih Pekerjaan'" :class="!selected ? 'text-slate-400 dark:text-slate-600' : ''" class="block truncate"></span>
                                        <span class="material-symbols-outlined text-[20px] text-slate-500 dark:text-slate-400 pointer-events-none transition-transform duration-200" :class="open ? 'rotate-180' : ''">expand_more</span>
                                    </button>

                                    <div x-show="open" style="display: none;"
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute z-10 mt-2 w-full bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-100 dark:border-slate-700 overflow-hidden">
                                        <ul class="py-1 text-base focus:outline-none sm:text-sm m-0">
                                            <template x-for="option in ['Mahasiswa', 'Karyawan', 'Lainnya']" :key="option">
                                                <li @click="selected = option; open = false" class="cursor-pointer select-none relative py-3 pl-4 pr-9 text-slate-900 dark:text-slate-100 hover:bg-blue-50 dark:hover:bg-slate-700 hover:text-[#1967d2] dark:hover:text-blue-400 transition-colors">
                                                    <span class="block font-medium" x-text="option" :class="selected === option ? 'text-[#1967d2] dark:text-blue-400 font-semibold' : ''"></span>
                                                    <span x-show="selected === option" class="absolute inset-y-0 right-0 flex items-center pr-4 text-[#1967d2] dark:text-blue-400">
                                                        <span class="material-symbols-outlined text-[20px]">check</span>
                                                    </span>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>
                                @error('pekerjaan') <span class="text-rose-500 dark:text-rose-400 text-xs mt-1.5 flex font-medium items-center gap-1"><span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}</span> @enderror
                            </div>

                            <!-- Asal Kota / Domisili -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Asal Kota / Domisili</label>
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500">
                                        <span class="material-symbols-outlined text-[20px]">location_on</span>
                                    </div>
                                    <input type="text" wire:model="domisili" class="w-full border-slate-200 dark:border-slate-800 rounded-xl shadow-sm focus:border-[#1967d2] focus:ring focus:ring-[#1967d2]/20 transition-all text-slate-900 dark:text-white bg-slate-50/50 dark:bg-slate-950 pl-11 py-3 placeholder:text-slate-400 dark:placeholder:text-slate-600 hover:bg-slate-50 dark:hover:bg-slate-900" placeholder="Contoh: Jakarta">
                                </div>
                                @error('domisili') <span class="text-rose-500 dark:text-rose-400 text-xs mt-1.5 flex font-medium items-center gap-1"><span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="pt-6 mt-8 border-t border-slate-100 dark:border-slate-800/60 flex justify-end">
                            <button type="submit" wire:loading.attr="disabled" wire:target="updateProfile" class="bg-[#1967d2] hover:bg-[#0f4fb5] text-white font-bold py-3 px-8 rounded-xl transition-all shadow-md shadow-blue-500/20 dark:shadow-none flex items-center justify-center gap-2 w-full md:w-auto disabled:opacity-50 disabled:cursor-not-allowed">
                                <span wire:loading.remove wire:target="updateProfile" class="flex items-center gap-2"><span class="material-symbols-outlined text-[20px]">save</span> Simpan Perubahan</span>
                                <span wire:loading wire:target="updateProfile" class="flex items-center gap-2"><span class="material-symbols-outlined animate-spin text-[20px]">refresh</span> Menyimpan...</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Isi Tab 2: Keamanan Akun -->
                <div x-show="tab === 'keamanan'" style="display: none;"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="p-8">

                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Ubah Kata Sandi</h2>

                    @if (session()->has('success_password'))
                    <div class="mb-6 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 px-4 py-3 rounded-xl flex items-center gap-3">
                        <svg class="w-5 h-5 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium text-sm">{{ session('success_password') }}</span>
                    </div>
                    @endif

                    <form wire:submit.prevent="updatePassword" class="max-w-md flex flex-col gap-6 mt-2">

                        <!-- Kata Sandi Saat Ini -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Kata Sandi Saat Ini</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500">
                                    <span class="material-symbols-outlined text-[20px]">lock</span>
                                </div>
                                <input type="password" wire:model="current_password" class="w-full border-slate-200 dark:border-slate-800 rounded-xl shadow-sm focus:border-[#1967d2] focus:ring focus:ring-[#1967d2]/20 transition-all text-slate-900 dark:text-white bg-slate-50/50 dark:bg-slate-950 pl-11 py-3 hover:bg-slate-50 dark:hover:bg-slate-900 placeholder:text-slate-400 dark:placeholder:text-slate-600" placeholder="Masukkan kata sandi lama">
                            </div>
                            @error('current_password') <span class="text-rose-500 dark:text-rose-400 text-xs mt-1.5 flex font-medium items-center gap-1"><span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}</span> @enderror
                        </div>

                        <!-- Kata Sandi Baru -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Kata Sandi Baru</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500">
                                    <span class="material-symbols-outlined text-[20px]">key</span>
                                </div>
                                <input type="password" wire:model="new_password" class="w-full border-slate-200 dark:border-slate-800 rounded-xl shadow-sm focus:border-[#1967d2] focus:ring focus:ring-[#1967d2]/20 transition-all text-slate-900 dark:text-white bg-slate-50/50 dark:bg-slate-950 pl-11 py-3 hover:bg-slate-50 dark:hover:bg-slate-900 placeholder:text-slate-400 dark:placeholder:text-slate-600" placeholder="Minimal 8 karakter">
                            </div>
                            @error('new_password') <span class="text-rose-500 dark:text-rose-400 text-xs mt-1.5 flex font-medium items-center gap-1"><span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}</span> @enderror
                        </div>

                        <!-- Konfirmasi Kata Sandi Baru -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Konfirmasi Kata Sandi Baru</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500">
                                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                                </div>
                                <input type="password" wire:model="new_password_confirmation" class="w-full border-slate-200 dark:border-slate-800 rounded-xl shadow-sm focus:border-[#1967d2] focus:ring focus:ring-[#1967d2]/20 transition-all text-slate-900 dark:text-white bg-slate-50/50 dark:bg-slate-950 pl-11 py-3 hover:bg-slate-50 dark:hover:bg-slate-900 placeholder:text-slate-400 dark:placeholder:text-slate-600" placeholder="Ulangi kata sandi baru">
                            </div>
                        </div>

                        <!-- Button Submit -->
                        <div class="pt-2 mt-2">
                            <button type="submit" wire:loading.attr="disabled" wire:target="updatePassword" class="bg-[#1967d2] hover:bg-[#0f4fb5] text-white font-bold py-3 px-8 rounded-xl transition-all shadow-md shadow-blue-500/20 dark:shadow-none flex items-center justify-center gap-2 w-full md:w-auto disabled:opacity-50 disabled:cursor-not-allowed">
                                <span wire:loading.remove wire:target="updatePassword" class="flex items-center gap-2"><span class="material-symbols-outlined text-[20px]">security_update_good</span> Perbarui Kata Sandi</span>
                                <span wire:loading wire:target="updatePassword" class="flex items-center gap-2"><span class="material-symbols-outlined animate-spin text-[20px]">refresh</span> Memperbarui...</span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>