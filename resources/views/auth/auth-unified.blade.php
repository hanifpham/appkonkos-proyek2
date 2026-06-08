<x-guest-layout>
    <div x-data="{ isRightPanelActive: {{ request()->routeIs('register') ? 'true' : 'false' }}, showPassword: false, showConfirmPassword: false }">

        {{-- ========================================== --}}
        {{-- DESKTOP VERSION (Visible on md and up)     --}}
        {{-- ========================================== --}}
        <div class="hidden md:block relative h-screen w-full overflow-hidden bg-white">
            
            {{-- REGISTER FORM (LEFT) --}}
            <div class="absolute left-0 top-0 z-10 h-full w-1/2 p-8 transition-all duration-700 ease-in-out"
                :class="isRightPanelActive ? 'translate-x-0 opacity-100' : 'pointer-events-none translate-x-[20%] opacity-0'">

                <div class="mx-auto flex h-full w-full max-w-md flex-col justify-center px-4">
                    <div class="mb-3 flex justify-center">
                        <img src="{{ asset('images/appkonkos.png') }}" alt="APPKONKOS" class="h-10 object-contain">
                    </div>
                    <h2 class="mb-3 text-center text-2xl font-bold text-[#090a0b]">Daftar Akun</h2>

                    <form method="POST" action="{{ route('register') }}" class="space-y-3" x-data="{ selectedRole: '{{ old('role', 'pencari') }}' }">
                        @csrf
                        <input type="hidden" name="role" x-model="selectedRole">

                        {{-- Desktop Segmented Sliding Role Selector --}}
                        <div class="mb-5">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2 pl-1">Daftar Sebagai</label>
                            <div class="relative bg-slate-100 p-1 rounded-full flex w-full h-11 items-center z-0">
                                {{-- Sliding background card using bulletproof inline style and z-index z-10 --}}
                                <div class="absolute h-9 rounded-full shadow transition-all duration-300 ease-out z-10 top-1"
                                     :style="selectedRole === 'pemilik' ? 'left: calc(50% + 2px); width: calc(50% - 6px); background-color: #ff8a00;' : 'left: 4px; width: calc(50% - 6px); background-color: #1967d2;'"></div>

                                {{-- Pencari Kos Button --}}
                                <button type="button" @click="selectedRole = 'pencari'"
                                        class="relative z-20 w-1/2 text-center py-2 text-xs font-bold transition duration-300 rounded-full flex items-center justify-center gap-2"
                                        :class="selectedRole === 'pencari' ? 'text-white' : 'text-slate-500 hover:text-slate-800'">
                                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Pencari Kos
                                </button>

                                {{-- Pemilik / Mitra Button --}}
                                <button type="button" @click="selectedRole = 'pemilik'"
                                        class="relative z-20 w-1/2 text-center py-2 text-xs font-bold transition duration-300 rounded-full flex items-center justify-center gap-2"
                                        :class="selectedRole === 'pemilik' ? 'text-white' : 'text-slate-500 hover:text-slate-800'">
                                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    Pemilik / Mitra
                                </button>
                            </div>
                        </div>

                        {{-- Nama Lengkap Input --}}
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 transition group-focus-within:text-[#1967d2]">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input id="name" type="text" name="name" :value="old('name')" required placeholder="Nama Lengkap" class="w-full rounded-full border border-slate-200 bg-slate-50/50 py-3.5 pl-12 pr-5 text-xs font-semibold text-slate-800 placeholder-slate-400 outline-none transition duration-200 focus:border-[#1967d2] focus:bg-white focus:ring-4 focus:ring-[#1967d2]/10" />
                        </div>

                        {{-- Alamat Email Input --}}
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 transition group-focus-within:text-[#1967d2]">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                </svg>
                            </div>
                            <input id="email_reg" type="email" name="email" :value="old('email')" required placeholder="Alamat Email" class="w-full rounded-full border border-slate-200 bg-slate-50/50 py-3.5 pl-12 pr-5 text-xs font-semibold text-slate-800 placeholder-slate-400 outline-none transition duration-200 focus:border-[#1967d2] focus:bg-white focus:ring-4 focus:ring-[#1967d2]/10" />
                        </div>

                        {{-- Nomor Telepon Input --}}
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 transition group-focus-within:text-[#1967d2]">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <input id="no_telepon" type="text" name="no_telepon" :value="old('no_telepon')" required placeholder="Nomor Telepon (WhatsApp)" class="w-full rounded-full border border-slate-200 bg-slate-50/50 py-3.5 pl-12 pr-5 text-xs font-semibold text-slate-800 placeholder-slate-400 outline-none transition duration-200 focus:border-[#1967d2] focus:bg-white focus:ring-4 focus:ring-[#1967d2]/10" />
                        </div>

                        {{-- Password Input --}}
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 transition group-focus-within:text-[#1967d2]">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password_reg" :type="showPassword ? 'text' : 'password'" name="password" required placeholder="Kata Sandi" class="w-full rounded-full border border-slate-200 bg-slate-50/50 py-3.5 pl-12 pr-11 text-xs font-semibold text-slate-800 placeholder-slate-400 outline-none transition duration-200 focus:border-[#1967d2] focus:bg-white focus:ring-4 focus:ring-[#1967d2]/10" />
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition">
                                <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>

                        {{-- Confirm Password Input --}}
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 transition group-focus-within:text-[#1967d2]">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password_confirmation" :type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" required placeholder="Konfirmasi Kata Sandi" class="w-full rounded-full border border-slate-200 bg-slate-50/50 py-3.5 pl-12 pr-11 text-xs font-semibold text-slate-800 placeholder-slate-400 outline-none transition duration-200 focus:border-[#1967d2] focus:bg-white focus:ring-4 focus:ring-[#1967d2]/10" />
                            <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition">
                                <svg x-show="!showConfirmPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showConfirmPassword" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 01-1.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>

                        <div class="mt-5">
                            <button type="submit" class="w-full rounded-full bg-[#1967d2] hover:bg-[#1154c1] py-3.5 text-xs font-bold text-white shadow-lg shadow-blue-500/10 active:scale-[0.98] transition duration-200">
                                Daftar
                            </button>
                        </div>

                        <div class="relative flex items-center justify-center py-2">
                            <div class="w-full border-t border-slate-100"></div>
                            <span class="absolute bg-white px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">atau</span>
                        </div>

                        <a href="{{ route('auth.google', ['role' => session('login_portal', 'pencari')]) }}" class="flex w-full items-center justify-center gap-2.5 rounded-full border border-slate-200 bg-white py-3.5 text-xs font-bold text-slate-700 shadow-sm active:bg-slate-50 active:scale-[0.98] transition duration-200">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" />
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
                            </svg>
                            Daftar dengan Google
                        </a>
                    </form>
                </div>
            </div>

            {{-- LOGIN FORM (RIGHT) --}}
            <div class="absolute right-0 top-0 z-10 h-full w-1/2 p-8 transition-all duration-700 ease-in-out"
                :class="!isRightPanelActive ? 'translate-x-0 opacity-100' : 'pointer-events-none -translate-x-[20%] opacity-0'">

                <div class="mx-auto flex h-full w-full max-w-md flex-col justify-center px-4">
                    <div class="mb-6 flex justify-center">
                        <img src="{{ asset('images/appkonkos.png') }}" alt="APPKONKOS" class="h-10 object-contain">
                    </div>
                    <h2 class="mb-6 text-center text-2xl font-bold text-[#090a0b]">Masuk</h2>

                    @php
                    $loginPortal = session('login_portal');
                    $loginPortalLabel = match ($loginPortal) {
                        'pemilik' => 'Login Mitra',
                        'superadmin' => 'Login Super Admin',
                        default => 'Login Pencari',
                    };
                    $loginPortalText = match ($loginPortal) {
                        'pemilik' => 'Masuk untuk mengelola properti, pesanan, keuangan, dan profil verifikasi mitra.',
                        'superadmin' => 'Masuk ke panel pengawasan sistem APPKONKOS.',
                        default => 'Masuk untuk mencari properti dan mengelola booking Anda.',
                    };
                    @endphp

                    @session('status')
                    <div class="mb-4 text-sm font-medium text-green-600">
                        {{ $value }}
                    </div>
                    @endsession

                    @if ($loginPortal !== null)
                    <div class="mb-5 rounded-2xl border border-blue-50 bg-gradient-to-r from-blue-50 to-indigo-50/50 p-4 text-xs flex items-start gap-3 shadow-sm">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-100/80 text-[#1967d2]">
                            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-extrabold text-slate-800 text-xs">{{ $loginPortalLabel }}</p>
                            <p class="mt-0.5 text-slate-500 leading-relaxed text-[11px] font-medium">{{ $loginPortalText }}</p>
                        </div>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        {{-- Alamat Email Input --}}
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 transition group-focus-within:text-[#1967d2]">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" :value="old('email')" required placeholder="Alamat Email" class="w-full rounded-full border border-slate-200 bg-slate-50/50 py-3.5 pl-12 pr-5 text-xs font-semibold text-slate-800 placeholder-slate-400 outline-none transition duration-200 focus:border-[#1967d2] focus:bg-white focus:ring-4 focus:ring-[#1967d2]/10" />
                        </div>

                        {{-- Password Input --}}
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 transition group-focus-within:text-[#1967d2]">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required placeholder="Kata Sandi" class="w-full rounded-full border border-slate-200 bg-slate-50/50 py-3.5 pl-12 pr-11 text-xs font-semibold text-slate-800 placeholder-slate-400 outline-none transition duration-200 focus:border-[#1967d2] focus:bg-white focus:ring-4 focus:ring-[#1967d2]/10" />
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition">
                                <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>

                        <div class="mt-2 text-right">
                            @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-semibold text-[#1967d2] hover:text-[#0f4fb5] transition">
                                Lupa Kata Sandi?
                            </a>
                            @endif
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="w-full rounded-full bg-[#1967d2] hover:bg-[#1154c1] py-3.5 text-xs font-bold text-white shadow-lg shadow-blue-500/10 active:scale-[0.98] transition duration-200">
                                Masuk
                            </button>
                        </div>

                        <div class="relative flex items-center justify-center py-2">
                            <div class="w-full border-t border-slate-100"></div>
                            <span class="absolute bg-white px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">atau</span>
                        </div>

                        <a href="{{ route('auth.google', ['role' => session('login_portal', 'pencari')]) }}" class="flex w-full items-center justify-center gap-2.5 rounded-full border border-slate-200 bg-white py-3.5 text-xs font-bold text-slate-700 shadow-sm active:bg-slate-50 active:scale-[0.98] transition duration-200">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" />
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
                            </svg>
                            Masuk dengan Google
                        </a>
                    </form>
                </div>
            </div>

            {{-- OVERLAY CONTAINER (SLIDING PANEL) --}}
            <div class="absolute left-0 top-0 z-50 flex h-full w-1/2 flex-col justify-center bg-[#1967d2] px-8 text-center text-white transition-all duration-700 ease-in-out"
                x-bind:class="isRightPanelActive ? 'translate-x-full rounded-l-full' : 'translate-x-0 rounded-r-full'">

                {{-- Content when Login is active (Overlay on left side, prompting to Register) --}}
                <div class="absolute inset-0 flex flex-col items-center justify-center p-8 transition-all duration-700 ease-in-out"
                    x-bind:class="isRightPanelActive ? 'pointer-events-none -translate-x-[20%] opacity-0' : 'translate-x-0 opacity-100'">
                    <h2 class="mb-4 text-4xl font-bold leading-tight">Belum punya Akun?</h2>
                    <p class="mb-10 text-base leading-relaxed text-blue-100">Yuk gabung jadi bagian dari APPKONKOS sekarang dan dapatkan manfaatnya!</p>
                    <img src="{{ asset('images/illustration-1.svg') }}" alt="Register" class="mb-10 max-h-64 object-contain opacity-90" onerror="this.src='https://illustrations.popsy.co/blue/freelancer.svg'">
                    <button x-on:click="isRightPanelActive = true" class="rounded-full border-2 border-white px-10 py-3 text-sm font-semibold text-white transition-colors hover:bg-white hover:text-[#1967d2]">
                        Daftar
                    </button>
                </div>

                {{-- Content when Register is active (Overlay on right side, prompting to Login) --}}
                <div class="absolute inset-0 flex flex-col items-center justify-center p-8 transition-all duration-700 ease-in-out"
                    x-bind:class="!isRightPanelActive ? 'pointer-events-none translate-x-[20%] opacity-0' : 'translate-x-0 opacity-100'">
                    <h2 class="mb-4 text-4xl font-bold leading-tight">Sudah Punya Akun?</h2>
                    <p class="mb-10 text-base leading-relaxed text-blue-100">Untuk tetap terhubung dengan kami, silakan Masuk sekarang.</p>
                    <img src="{{ asset('images/illustration-2.svg') }}" alt="Login" class="mb-10 max-h-64 object-contain opacity-90" onerror="this.src='https://illustrations.popsy.co/blue/work-from-home.svg'">
                    <button x-on:click="isRightPanelActive = false" class="rounded-full border-2 border-white px-10 py-3 text-sm font-semibold text-white transition-colors hover:bg-white hover:text-[#1967d2]">
                        Masuk
                    </button>
                </div>
            </div>

        </div>

        {{-- ========================================== --}}
        {{-- MOBILE VERSION (Visible on mobile below md)--}}
        {{-- ========================================== --}}
        <div class="block md:hidden min-h-screen text-slate-800 flex flex-col justify-between" style="background: linear-gradient(180deg, #1154c1 0%, #1967d2 100%);">
            
            <div class="flex flex-col flex-grow">
                {{-- Top Navigation Bar --}}
                <div class="flex items-center justify-between px-4 py-3.5">
                    <a href="{{ route('home') }}" class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-[#1967d2] shadow border border-slate-200/50 transition active:scale-90">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div class="flex items-center gap-3">
                        <button x-show="!isRightPanelActive" @click="isRightPanelActive = true" class="rounded-full border-2 border-white bg-white/10 px-6 py-2 text-xs font-extrabold text-white shadow-sm transition active:scale-90 duration-150 tracking-wide">Daftar</button>
                        <button x-show="isRightPanelActive" x-cloak @click="isRightPanelActive = false" class="rounded-full border-2 border-white bg-white/10 px-6 py-2 text-xs font-extrabold text-white shadow-sm transition active:scale-90 duration-150 tracking-wide">Masuk</button>
                    </div>
                </div>

                {{-- Centered Glassmorphic Logo Section --}}
                <div class="flex flex-col items-center pt-3 pb-8 mb-8">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-white shadow-md border border-white/20 p-3 mb-2 transition active:scale-95 duration-200">
                        <img src="{{ asset('images/appkonkos.png') }}" alt="APPKONKOS" class="h-full w-full object-contain">
                    </div>
                    <span class="text-white text-[10px] font-black tracking-widest uppercase opacity-90">APPKONKOS</span>
                </div>

                {{-- Full-Width White Card Overlay --}}
                <div class="w-full bg-white rounded-t-[2.5rem] px-6 pb-12 pt-9 shadow-[0_-12px_45px_rgba(0,0,0,0.18)] mt-auto flex-grow flex flex-col justify-between">
                    <div>
                        {{-- ===== MOBILE LOGIN ===== --}}
                        <div x-show="!isRightPanelActive" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="pt-2">

                            @if ($loginPortal !== null)
                            <div class="mt-4 rounded-2xl border border-blue-50 bg-gradient-to-r from-blue-50 to-indigo-50/50 p-4 text-xs flex items-start gap-3 shadow-sm">
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-100/80 text-[#1967d2]">
                                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-extrabold text-slate-800 text-xs">{{ $loginPortalLabel }}</p>
                                    <p class="mt-0.5 text-slate-500 leading-relaxed text-[11px] font-medium">{{ $loginPortalText }}</p>
                                </div>
                            </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
                                @csrf
                                {{-- Email Input --}}
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 transition group-focus-within:text-[#1967d2]">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                        </svg>
                                    </div>
                                    <input id="email_mobile" type="email" name="email" value="{{ old('email') }}" required placeholder="Alamat Email" class="w-full rounded-full border border-slate-200 bg-slate-50/50 py-4 pl-12 pr-4 text-xs font-semibold text-slate-800 placeholder-slate-400 outline-none transition duration-200 focus:border-[#1967d2] focus:bg-white focus:ring-4 focus:ring-[#1967d2]/10" />
                                </div>

                                {{-- Password Input --}}
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 transition group-focus-within:text-[#1967d2]">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <input id="password_mobile" :type="showPassword ? 'text' : 'password'" name="password" required placeholder="Kata Sandi" class="w-full rounded-full border border-slate-200 bg-slate-50/50 py-4 pl-12 pr-11 text-xs font-semibold text-slate-800 placeholder-slate-400 outline-none transition duration-200 focus:border-[#1967d2] focus:bg-white focus:ring-4 focus:ring-[#1967d2]/10" />
                                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition">
                                        <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="text-right">
                                    @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-[11px] font-extrabold text-[#1967d2] hover:underline tracking-wide">Lupa Kata Sandi?</a>
                                    @endif
                                </div>

                                <button type="submit" class="mt-2 w-full rounded-full bg-[#1967d2] py-4 text-xs font-bold text-white shadow-lg shadow-blue-500/10 active:scale-[0.98] transition duration-200">Masuk</button>

                                <div class="relative flex items-center justify-center py-2">
                                    <div class="w-full border-t border-slate-100"></div>
                                    <span class="absolute bg-white px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">atau</span>
                                </div>

                                <a href="{{ route('auth.google', ['role' => session('login_portal', 'pencari')]) }}" class="flex w-full items-center justify-center gap-2.5 rounded-full border border-slate-200 bg-white py-4 text-xs font-bold text-slate-700 shadow-sm active:bg-slate-50 active:scale-[0.98] transition duration-200">
                                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24">
                                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
                                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" />
                                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
                                    </svg>
                                    Masuk dengan Google
                                </a>
                            </form>
                        </div>

                        {{-- ===== MOBILE REGISTER ===== --}}
                        <div x-show="isRightPanelActive" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="pt-2">
                            <form method="POST" action="{{ route('register') }}" class="mt-2 space-y-4" x-data="{ selectedRoleMobile: '{{ old('role', 'pencari') }}' }">
                                @csrf
                                <input type="hidden" name="role" x-model="selectedRoleMobile">

                                {{-- Mobile Segmented Sliding Role Selector --}}
                                <div class="mb-4">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2 pl-1">Daftar Sebagai</label>
                                    <div class="relative bg-slate-100 p-1 rounded-full flex w-full h-11 items-center z-0">
                                        {{-- Sliding background card using bulletproof inline style and z-index z-10 --}}
                                        <div class="absolute h-9 rounded-full shadow transition-all duration-300 ease-out z-10 top-1"
                                             :style="selectedRoleMobile === 'pemilik' ? 'left: calc(50% + 2px); width: calc(50% - 6px); background-color: #ff8a00;' : 'left: 4px; width: calc(50% - 6px); background-color: #1967d2;'"></div>
                                        
                                        <button type="button" @click="selectedRoleMobile = 'pencari'"
                                                class="relative z-20 w-1/2 text-center py-2 text-xs font-bold transition duration-300 rounded-full flex items-center justify-center gap-2"
                                                :class="selectedRoleMobile === 'pencari' ? 'text-white' : 'text-slate-500'">
                                            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                            Pencari Kos
                                        </button>
                                        
                                        <button type="button" @click="selectedRoleMobile = 'pemilik'"
                                                class="relative z-20 w-1/2 text-center py-2 text-xs font-bold transition duration-300 rounded-full flex items-center justify-center gap-2"
                                                :class="selectedRoleMobile === 'pemilik' ? 'text-white' : 'text-slate-500'">
                                            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                            Pemilik / Mitra
                                        </button>
                                    </div>
                                </div>

                                {{-- Name Input --}}
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 transition group-focus-within:text-[#1967d2]">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <input id="name_mobile" type="text" name="name" value="{{ old('name') }}" required placeholder="Nama Lengkap" class="w-full rounded-full border border-slate-200 bg-slate-50/50 py-4 pl-12 pr-4 text-xs font-semibold text-slate-800 placeholder-slate-400 outline-none transition duration-200 focus:border-[#1967d2] focus:bg-white focus:ring-4 focus:ring-[#1967d2]/10" />
                                </div>

                                {{-- Email Input --}}
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 transition group-focus-within:text-[#1967d2]">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                        </svg>
                                    </div>
                                    <input id="email_reg_mobile" type="email" name="email" value="{{ old('email') }}" required placeholder="Alamat Email" class="w-full rounded-full border border-slate-200 bg-slate-50/50 py-4 pl-12 pr-4 text-xs font-semibold text-slate-800 placeholder-slate-400 outline-none transition duration-200 focus:border-[#1967d2] focus:bg-white focus:ring-4 focus:ring-[#1967d2]/10" />
                                </div>

                                {{-- Phone Input --}}
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 transition group-focus-within:text-[#1967d2]">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                    <input id="no_telepon_mobile" type="text" name="no_telepon" value="{{ old('no_telepon') }}" required placeholder="Nomor Telepon (WhatsApp)" class="w-full rounded-full border border-slate-200 bg-slate-50/50 py-4 pl-12 pr-4 text-xs font-semibold text-slate-800 placeholder-slate-400 outline-none transition duration-200 focus:border-[#1967d2] focus:bg-white focus:ring-4 focus:ring-[#1967d2]/10" />
                                </div>

                                {{-- Password Input --}}
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 transition group-focus-within:text-[#1967d2]">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <input id="password_reg_mobile" :type="showPassword ? 'text' : 'password'" name="password" required placeholder="Kata Sandi" class="w-full rounded-full border border-slate-200 bg-slate-50/50 py-4 pl-12 pr-11 text-xs font-semibold text-slate-800 placeholder-slate-400 outline-none transition duration-200 focus:border-[#1967d2] focus:bg-white focus:ring-4 focus:ring-[#1967d2]/10" />
                                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition">
                                        <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>

                                {{-- Confirm Password Input --}}
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 transition group-focus-within:text-[#1967d2]">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <input id="password_confirmation_mobile" :type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" required placeholder="Konfirmasi Kata Sandi" class="w-full rounded-full border border-slate-200 bg-slate-50/50 py-4 pl-12 pr-11 text-xs font-semibold text-slate-800 placeholder-slate-400 outline-none transition duration-200 focus:border-[#1967d2] focus:bg-white focus:ring-4 focus:ring-[#1967d2]/10" />
                                    <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition">
                                        <svg x-show="!showConfirmPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="showConfirmPassword" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>

                                <button type="submit" class="mt-2 w-full rounded-full bg-[#1967d2] py-4 text-xs font-bold text-white shadow-lg shadow-blue-500/10 active:scale-[0.98] transition duration-200">Daftar</button>

                                <div class="relative flex items-center justify-center py-2">
                                    <div class="w-full border-t border-slate-100"></div>
                                    <span class="absolute bg-white px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">atau</span>
                                </div>

                                <a href="{{ route('auth.google', ['role' => session('login_portal', 'pencari')]) }}" class="flex w-full items-center justify-center gap-2.5 rounded-full border border-slate-200 bg-white py-4 text-xs font-bold text-slate-700 shadow-sm active:bg-slate-50 active:scale-[0.98] transition duration-200">
                                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24">
                                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
                                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" />
                                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
                                    </svg>
                                    Daftar dengan Google
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @if ($errors->any())
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div id="form-errors-data" data-errors="{{ json_encode($errors->all()) }}" class="hidden"></div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let errorsData = JSON.parse(document.getElementById('form-errors-data').dataset.errors || '[]');
            let errorList = '';
            errorsData.forEach(function(error) {
                errorList += '<li>' + error + '</li>';
            });

            Swal.fire({
                html: `
                        <div class="flex flex-col items-center">
                            <div class="mb-5 flex h-20 w-20 items-center justify-center rounded-full bg-red-100 ring-8 ring-red-50">
                                <svg class="h-10 w-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <h3 class="mb-2 text-2xl font-extrabold text-slate-800">Ups! Gagal Memproses</h3>
                            <p class="mb-6 text-sm text-slate-500">Silakan periksa kembali informasi berikut:</p>
                            
                            <div class="w-full rounded-2xl bg-red-50/80 p-5 text-left border border-red-100">
                                <ul class="list-disc pl-5 space-y-1.5 text-sm font-medium text-red-700">
                                    ${errorList}
                                </ul>
                            </div>
                        </div>
                    `,
                showConfirmButton: true,
                confirmButtonText: 'Baik, Saya Mengerti',
                buttonsStyling: false,
                background: '#ffffff',
                backdrop: `rgba(15, 23, 42, 0.6) backdrop-filter backdrop-blur-sm`,
                customClass: {
                    popup: '!rounded-[2rem] shadow-2xl p-8 border border-slate-100',
                    confirmButton: 'mt-8 w-full rounded-full bg-[#1967d2] px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-500/30 transition-all hover:bg-[#0f4fb5] hover:shadow-blue-600/40 focus:outline-none focus:ring-4 focus:ring-blue-100',
                    htmlContainer: '!m-0 !p-0'
                }
            }).then((result) => {
                // Redirect ke beranda (home) setelah alert ditutup atau tombol diklik
                window.location.href = "{{ route('home') }}";
            });
        });
    </script>
    @endif

<style>[x-cloak]{display:none!important}</style>
</x-guest-layout>
