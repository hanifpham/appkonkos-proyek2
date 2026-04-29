<x-guest-layout>
    <div class="relative h-screen w-full overflow-hidden bg-white"
         x-data="{ isRightPanelActive: {{ request()->routeIs('register') ? 'true' : 'false' }}, showPassword: false, showConfirmPassword: false }">
            
            {{-- REGISTER FORM (LEFT) --}}
            <div class="absolute left-0 top-0 z-10 h-full w-1/2 p-8 transition-all duration-700 ease-in-out"
                 :class="isRightPanelActive ? 'translate-x-0 opacity-100' : 'pointer-events-none translate-x-[20%] opacity-0'">
                
                <div class="mx-auto flex h-full w-full max-w-md flex-col justify-center px-4">
                    <div class="mb-3 flex justify-center">
                        <img src="{{ asset('images/appkonkos.png') }}" alt="APPKONKOS" class="h-10 object-contain">
                    </div>
                    <h2 class="mb-3 text-center text-2xl font-bold text-[#090a0b]">Daftar Akun</h2>
                    
                    <x-formulir.error-validasi class="mb-3" />

                    <form method="POST" action="{{ route('register') }}" class="space-y-3">
                        @csrf
                        
                        <div>
                            <x-formulir.label for="name" value="{{ __('Nama Lengkap') }}" />
                            <x-formulir.input id="name" class="mt-1 block w-full !rounded-full px-5 py-2.5 text-sm" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        </div>
                        
                        <div>
                            <x-formulir.label for="email_reg" value="{{ __('Email') }}" />
                            <x-formulir.input id="email_reg" class="mt-1 block w-full !rounded-full px-5 py-2.5 text-sm" type="email" name="email" :value="old('email')" required autocomplete="username" />
                        </div>

                        <div>
                            <x-formulir.label for="no_telepon" value="{{ __('No. Telepon') }}" />
                            <x-formulir.input id="no_telepon" class="mt-1 block w-full !rounded-full px-5 py-2.5 text-sm" type="text" name="no_telepon" :value="old('no_telepon')" required autocomplete="tel" />
                        </div>

                        <div class="relative">
                            <x-formulir.label for="password_reg" value="{{ __('Kata Sandi') }}" />
                            <div class="relative">
                                <x-formulir.input id="password_reg" class="mt-1 block w-full !rounded-full px-5 py-2.5 pr-10 text-sm" x-bind:type="showPassword ? 'text' : 'password'" name="password" required autocomplete="new-password" />
                                <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="relative">
                            <x-formulir.label for="password_confirmation" value="{{ __('Konfirmasi Kata Sandi') }}" />
                            <div class="relative">
                                <x-formulir.input id="password_confirmation" class="mt-1 block w-full !rounded-full px-5 py-2.5 pr-10 text-sm" x-bind:type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password" />
                                <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <svg x-show="!showConfirmPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="showConfirmPassword" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="w-full rounded-full bg-[#1967d2] px-4 py-2.5 text-sm font-semibold text-white shadow-md transition-colors hover:bg-[#0f4fb5]">
                                Daftar
                            </button>
                        </div>
                        
                        <div class="relative mt-4 flex items-center justify-center">
                            <span class="absolute bg-white px-2 text-xs text-slate-400">atau</span>
                            <div class="w-full border-t border-slate-200"></div>
                        </div>

                        <div class="mt-4">
                            <button type="button" class="flex w-full items-center justify-center gap-2 rounded-full border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition-colors hover:bg-slate-50">
                                <svg class="h-5 w-5" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/><path d="M1 1h22v22H1z" fill="none"/></svg>
                                Daftar dengan Google
                            </button>
                        </div>
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

                    <x-formulir.error-validasi class="mb-4" />

                    @session('status')
                        <div class="mb-4 text-sm font-medium text-green-600">
                            {{ $value }}
                        </div>
                    @endsession

                    @if ($loginPortal !== null)
                        <div class="mb-4 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-slate-700">
                            <p class="font-semibold text-[#113C7A]">{{ $loginPortalLabel }}</p>
                            <p class="mt-1 text-xs leading-6 text-slate-600">{{ $loginPortalText }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf
                        
                        <div>
                            <x-formulir.label for="email" value="{{ __('Email') }}" />
                            <x-formulir.input id="email" class="mt-1 block w-full !rounded-full px-5 py-2.5 text-sm" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        </div>

                        <div class="relative">
                            <x-formulir.label for="password" value="{{ __('Kata Sandi') }}" />
                            <div class="relative">
                                <x-formulir.input id="password" class="mt-1 block w-full !rounded-full px-5 py-2.5 pr-10 text-sm" x-bind:type="showPassword ? 'text' : 'password'" name="password" required autocomplete="current-password" />
                                <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                            
                            <div class="mt-2 text-right">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-xs font-medium text-[#1967d2] hover:text-[#0f4fb5]">
                                        Lupa Kata Sandi?
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="w-full rounded-full bg-[#1967d2] px-4 py-2.5 text-sm font-semibold text-white shadow-md transition-colors hover:bg-[#0f4fb5]">
                                Masuk
                            </button>
                        </div>
                        
                        <div class="relative mt-6 flex items-center justify-center">
                            <span class="absolute bg-white px-2 text-xs text-slate-400">atau</span>
                            <div class="w-full border-t border-slate-200"></div>
                        </div>

                        <div class="mt-6">
                            <button type="button" class="flex w-full items-center justify-center gap-2 rounded-full border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition-colors hover:bg-slate-50">
                                <svg class="h-5 w-5" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/><path d="M1 1h22v22H1z" fill="none"/></svg>
                                Masuk dengan Google
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- OVERLAY CONTAINER (SLIDING PANEL) --}}
            <div class="absolute left-0 top-0 z-50 flex h-full w-1/2 flex-col justify-center bg-[#1967d2] px-8 text-center text-white transition-all duration-700 ease-in-out"
                 :class="isRightPanelActive ? 'translate-x-full rounded-l-full' : 'translate-x-0 rounded-r-full'">
                
                {{-- Content when Login is active (Overlay on left side, prompting to Register) --}}
                <div class="absolute inset-0 flex flex-col items-center justify-center p-8 transition-all duration-700 ease-in-out"
                     :class="isRightPanelActive ? 'pointer-events-none -translate-x-[20%] opacity-0' : 'translate-x-0 opacity-100'">
                    <h2 class="mb-4 text-4xl font-bold leading-tight">Belum punya Akun?</h2>
                    <p class="mb-10 text-base leading-relaxed text-blue-100">Yuk gabung jadi bagian dari APPKONKOS sekarang dan dapatkan manfaatnya!</p>
                    <img src="{{ asset('images/illustration-1.svg') }}" alt="Register" class="mb-10 max-h-64 object-contain opacity-90" onerror="this.src='https://illustrations.popsy.co/blue/freelancer.svg'">
                    <button @click="isRightPanelActive = true" class="rounded-full border-2 border-white px-10 py-3 text-sm font-semibold text-white transition-colors hover:bg-white hover:text-[#1967d2]">
                        Daftar
                    </button>
                </div>

                {{-- Content when Register is active (Overlay on right side, prompting to Login) --}}
                <div class="absolute inset-0 flex flex-col items-center justify-center p-8 transition-all duration-700 ease-in-out"
                     :class="!isRightPanelActive ? 'pointer-events-none translate-x-[20%] opacity-0' : 'translate-x-0 opacity-100'">
                    <h2 class="mb-4 text-4xl font-bold leading-tight">Sudah Punya Akun?</h2>
                    <p class="mb-10 text-base leading-relaxed text-blue-100">Untuk tetap terhubung dengan kami, silakan Masuk sekarang.</p>
                    <img src="{{ asset('images/illustration-2.svg') }}" alt="Login" class="mb-10 max-h-64 object-contain opacity-90" onerror="this.src='https://illustrations.popsy.co/blue/work-from-home.svg'">
                    <button @click="isRightPanelActive = false" class="rounded-full border-2 border-white px-10 py-3 text-sm font-semibold text-white transition-colors hover:bg-white hover:text-[#1967d2]">
                        Masuk
                    </button>
                </div>
            </div>

    </div>
</x-guest-layout>
