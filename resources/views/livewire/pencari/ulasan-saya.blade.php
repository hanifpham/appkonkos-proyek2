<div class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row gap-8">

        {{-- SIDEBAR KIRI --}}
        <x-pencari.sidebar active="ulasan" />

        {{-- KONTEN UTAMA KANAN --}}
        <div class="flex-1 min-w-0">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Ulasan Saya</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Daftar ulasan yang telah Anda berikan untuk properti yang pernah Anda sewa.</p>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-[32px] shadow-sm border border-slate-200 dark:border-slate-800 p-12 text-center transition-colors">
                <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="text-slate-300 dark:text-slate-600 w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Belum ada ulasan</h3>
                <p class="text-slate-500 dark:text-slate-400 mt-2 max-w-xs mx-auto">Anda belum pernah memberikan ulasan untuk properti manapun. Ulasan Anda sangat membantu pencari kos lainnya!</p>
                <a href="{{ route('pencari.riwayat-pesanan') }}" class="mt-8 inline-flex items-center gap-2 bg-[#1967d2] hover:bg-[#0f4fb5] text-white px-6 py-3 rounded-xl font-bold transition-all shadow-lg shadow-blue-500/20">
                    <span class="material-symbols-outlined text-lg">history</span>
                    Lihat Riwayat Pesanan
                </a>
            </div>
        </div>
    </div>
</div>
