@props(['active' => ''])

@php
    $user = auth()->user();
    $profilePhoto = $user->profile_photo_path 
        ? asset('storage/' . ltrim($user->profile_photo_path, '/')) 
        : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=113C7A&background=EBF4FF';
@endphp

<div class="w-full md:w-[320px] shrink-0">
    {{-- Profile Card --}}
    <div class="bg-white dark:bg-slate-900 rounded-[24px] shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden mb-6 transition-colors">
        <div class="h-24 bg-[radial-gradient(circle_at_top_left,_rgba(25,103,210,0.15),_transparent_40%),linear-gradient(135deg,_#f0f9ff_0%,_#e0f2fe_100%)] dark:bg-[radial-gradient(circle_at_top_left,_rgba(25,103,210,0.25),_transparent_40%),linear-gradient(135deg,_rgba(30,41,59,1)_0%,_rgba(15,23,42,1)_100%)]"></div>
        <div class="px-6 pb-6 relative -mt-12 text-center">
            <div class="relative inline-block mb-3">
                <img src="{{ $profilePhoto }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full mx-auto object-cover ring-4 ring-white dark:ring-slate-900 shadow-md">
            </div>
            <h3 class="font-bold text-lg text-slate-900 dark:text-white">{{ $user->name }}</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ $user->email }}</p>
        </div>
    </div>

    {{-- Navigation Menu --}}
    <div class="bg-white dark:bg-slate-900 rounded-[24px] shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden py-3 transition-colors">
        <nav class="flex flex-col px-3 gap-1">
            {{-- Profil Saya --}}
            <a href="{{ route('pencari.profil') }}" wire:navigate 
               class="px-4 py-3 rounded-xl flex items-center gap-3 transition-colors {{ $active === 'profil' ? 'bg-blue-50 dark:bg-slate-800 text-[#1967d2] dark:text-blue-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-slate-200 font-medium' }}">
                <svg class="w-5 h-5 {{ $active === 'profil' ? '' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Profil Saya
            </a>

            {{-- Favorit Saya --}}
            <a href="{{ route('pencari.favorit') }}" wire:navigate 
               class="px-4 py-3 rounded-xl flex items-center gap-3 transition-colors {{ $active === 'favorit' ? 'bg-blue-50 dark:bg-slate-800 text-[#1967d2] dark:text-blue-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-slate-200 font-medium' }}">
                <svg class="w-5 h-5 {{ $active === 'favorit' ? '' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                Favorit Saya
            </a>

            {{-- Riwayat Pesanan --}}
            <a href="{{ route('pencari.riwayat-pesanan') }}" wire:navigate 
               class="px-4 py-3 rounded-xl flex items-center gap-3 transition-colors {{ $active === 'riwayat' ? 'bg-blue-50 dark:bg-slate-800 text-[#1967d2] dark:text-blue-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-slate-200 font-medium' }}">
                <svg class="w-5 h-5 {{ $active === 'riwayat' ? '' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                Riwayat Pesanan
            </a>

            {{-- Ulasan Saya --}}
            <a href="{{ route('pencari.ulasan-saya') }}" wire:navigate 
               class="px-4 py-3 rounded-xl flex items-center gap-3 transition-colors {{ $active === 'ulasan' ? 'bg-blue-50 dark:bg-slate-800 text-[#1967d2] dark:text-blue-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-slate-200 font-medium' }}">
                <svg class="w-5 h-5 {{ $active === 'ulasan' ? '' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                </svg>
                Ulasan Saya
            </a>

            <div class="h-px bg-slate-100 dark:bg-slate-800 my-2 mx-2"></div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}" class="block">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-3 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-xl font-medium flex items-center gap-3 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Keluar
                </button>
            </form>
        </nav>
    </div>
</div>
