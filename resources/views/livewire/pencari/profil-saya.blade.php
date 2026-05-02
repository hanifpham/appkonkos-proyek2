<div class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row gap-8">
        
        <!-- SIDEBAR KIRI -->
        <div class="w-full md:w-[320px] shrink-0">
            <!-- Profile Card -->
            <div class="bg-white dark:bg-slate-900 rounded-[24px] shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden mb-6 transition-colors">
                <!-- Cover / Gradient Top -->
                <div class="h-24 bg-[radial-gradient(circle_at_top_left,_rgba(25,103,210,0.15),_transparent_40%),linear-gradient(135deg,_#f0f9ff_0%,_#e0f2fe_100%)] dark:bg-[radial-gradient(circle_at_top_left,_rgba(25,103,210,0.25),_transparent_40%),linear-gradient(135deg,_rgba(30,41,59,1)_0%,_rgba(15,23,42,1)_100%)]"></div>
                
                <div class="px-6 pb-6 relative -mt-12 text-center">
                    <div class="relative inline-block mb-3">
                        @if($new_foto_profil)
                            <img src="{{ $new_foto_profil->temporaryUrl() }}" alt="Preview Foto" class="w-24 h-24 rounded-full mx-auto object-cover ring-4 ring-white dark:ring-slate-900 shadow-md">
                        @elseif($foto_profil)
                            <img src="{{ '/storage/' . ltrim($foto_profil, '/') . '?v=' . time() }}" alt="Foto Profil" class="w-24 h-24 rounded-full mx-auto object-cover ring-4 ring-white dark:ring-slate-900 shadow-md">
                        @else
                            <div class="w-24 h-24 rounded-full mx-auto bg-slate-100 dark:bg-slate-800 flex items-center justify-center ring-4 ring-white dark:ring-slate-900 shadow-md text-slate-400 dark:text-slate-500">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                        @endif
                        <label for="upload_foto" class="absolute bottom-0 right-0 bg-[#1967d2] text-white p-2 rounded-full cursor-pointer hover:bg-[#0f4fb5] transition-all ring-2 ring-white dark:ring-slate-900 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <input type="file" id="upload_foto" wire:model="new_foto_profil" class="hidden" accept="image/*">
                        </label>
                    </div>
                    <div wire:loading wire:target="new_foto_profil" class="text-xs text-[#1967d2] dark:text-blue-400 mb-2 font-medium">Mengunggah...</div>
                    @error('new_foto_profil') <span class="text-rose-500 dark:text-rose-400 text-xs block mb-2">{{ $message }}</span> @enderror
                    
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white">{{ $name }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ $email }}</p>
                </div>
            </div>

            <!-- List Menu Navigasi -->
            <div class="bg-white dark:bg-slate-900 rounded-[24px] shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden py-3 transition-colors">
                <nav class="flex flex-col px-3 gap-1">
                    <a href="{{ route('pencari.profil') }}" wire:navigate class="px-4 py-3 bg-blue-50 dark:bg-slate-800 text-[#1967d2] dark:text-blue-400 rounded-xl font-bold flex items-center gap-3 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Profil Saya
                    </a>
                    <a href="{{ url('favorit') }}" class="px-4 py-3 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-slate-200 rounded-xl font-medium flex items-center gap-3 transition-colors">
                        <svg class="w-5 h-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        Favorit Saya
                    </a>
                    <a href="{{ url('riwayat-pesanan') }}" class="px-4 py-3 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-slate-200 rounded-xl font-medium flex items-center gap-3 transition-colors">
                        <svg class="w-5 h-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Riwayat Booking
                    </a>
                    <a href="{{ url('ulasan-saya') }}" class="px-4 py-3 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-slate-200 rounded-xl font-medium flex items-center gap-3 transition-colors">
                        <svg class="w-5 h-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        Ulasan Saya
                    </a>
                    
                    <div class="h-px bg-slate-100 dark:bg-slate-800 my-2 mx-2"></div>
                    
                    <form method="POST" action="{{ route('logout') ?? '#' }}" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-xl font-medium flex items-center gap-3 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Keluar
                        </button>
                    </form>
                </nav>
            </div>
        </div>

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
                            <svg class="w-5 h-5 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
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
                            <button type="submit" class="bg-[#1967d2] hover:bg-[#0f4fb5] text-white font-bold py-3 px-8 rounded-xl transition-all shadow-md shadow-blue-500/20 dark:shadow-none flex items-center justify-center gap-2 w-full md:w-auto">
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
                            <svg class="w-5 h-5 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
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
                            <button type="submit" class="bg-[#1967d2] hover:bg-[#0f4fb5] text-white font-bold py-3 px-8 rounded-xl transition-all shadow-md shadow-blue-500/20 dark:shadow-none flex items-center justify-center gap-2 w-full md:w-auto">
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
