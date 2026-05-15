<div class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row gap-8">

        {{-- SIDEBAR KIRI --}}
        <x-pencari.sidebar active="favorit" />

        {{-- KONTEN UTAMA KANAN --}}
        <div class="flex-1 min-w-0">

            {{-- Flash Messages --}}
            @if(session('success'))
            <div class="mb-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-xl flex items-center gap-3">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
            @endif
            @if(session('error'))
            <div class="mb-4 bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 px-4 py-3 rounded-xl flex items-center gap-3">
                <span class="material-symbols-outlined text-[20px]">error</span>
                <span class="font-medium text-sm">{{ session('error') }}</span>
            </div>
            @endif

            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Kos Favorit Saya</h1>
            </div>

            @if($favorits->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 text-center bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[32px] shadow-sm">
                    <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-6">
                        <svg class="text-slate-300 dark:text-slate-600 w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Belum ada kosan yang difavoritkan</h3>
                    <p class="text-slate-500 dark:text-slate-400 mt-2 max-w-xs mx-auto">Ayo cari kos idamanmu dan simpan di sini agar mudah ditemukan kembali!</p>
                    <a href="{{ route('home') }}" class="mt-6 inline-flex items-center gap-2 bg-[#1967d2] hover:bg-[#0f4fb5] text-white px-6 py-3 rounded-xl font-bold transition-all shadow-lg shadow-blue-500/20">
                        <span class="material-symbols-outlined text-lg">search</span>
                        Cari Kos Sekarang
                    </a>
                </div>
            @else
                <div wire:loading.remove wire:target="hapusFavorit, gotoPage" class="w-full">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($favorits as $favorit)
                        @php
                            $properti = $favorit->favoritable;
                            if (!$properti) continue;

                            $isKosan = $favorit->favoritable_type === \App\Models\Kosan::class;
                            $tipeLabel = $isKosan ? ($properti->jenis_kos ? 'Kos ' . $properti->jenis_kos : 'Kos') : 'Kontrakan';
                            $fotoUrl = $properti->getMediaDisplayUrl('foto_properti');
                            
                            $harga = 0;
                            $hargaLabel = '';
                            if ($isKosan) {
                                $harga = $properti->tipeKamar->min('harga_per_bulan');
                                $hargaLabel = '/bln';
                            } else {
                                $harga = $properti->harga_sewa_tahun;
                                $hargaLabel = '/thn';
                            }

                            $sisaUnit = 0;
                            if ($isKosan) {
                                $sisaUnit = $properti->tipeKamar->sum(fn ($tipe) => $tipe->kamar->where('status_kamar', 'tersedia')->count());
                            } else {
                                $sisaUnit = $properti->sisa_kamar;
                            }
                        @endphp

                        <div class="group relative flex flex-col overflow-hidden rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                            <div class="relative aspect-[4/3] w-full overflow-hidden bg-slate-100 dark:bg-slate-800">
                                {{-- Remove Favorit Button --}}
                                <button 
                                    wire:click="hapusFavorit({{ $favorit->id }})" 
                                    wire:confirm="Hapus properti ini dari daftar favorit Anda?"
                                    class="absolute top-3 right-3 p-2 rounded-full bg-white/90 dark:bg-slate-800/90 backdrop-blur-sm shadow-sm hover:bg-rose-50 dark:hover:bg-rose-900/30 text-slate-400 hover:text-rose-600 transition-all duration-200 z-10"
                                    title="Hapus dari Favorit"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>

                                <a href="{{ route('properti.detail', ['tipe' => $isKosan ? 'kosan' : 'kontrakan', 'id' => $properti->id]) }}" class="block h-full">
                                    @if($fotoUrl)
                                        <img src="{{ $fotoUrl }}" alt="{{ $properti->nama_properti }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-900">
                                            <svg class="h-12 w-12 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
                                        </div>
                                    @endif
                                </a>

                                {{-- Badges --}}
                                <div class="absolute left-3 top-3 flex flex-col items-start gap-2 z-10 pointer-events-none">
                                    <span class="inline-flex items-center rounded-full {{ $isKosan ? 'bg-[#1967d2]' : 'bg-teal-600' }} px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-white shadow-sm">
                                        {{ $tipeLabel }}
                                    </span>
                                    @if($sisaUnit > 0 && $sisaUnit <= 3)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white/90 backdrop-blur-md px-3 py-1 text-[11px] font-bold text-red-600 shadow-sm">
                                            <span class="flex h-1.5 w-1.5 rounded-full bg-red-600 animate-pulse"></span>
                                            Sisa {{ $sisaUnit }} {{ $isKosan ? 'Kamar' : 'Unit' }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <a href="{{ route('properti.detail', ['tipe' => $isKosan ? 'kosan' : 'kontrakan', 'id' => $properti->id]) }}" class="flex flex-1 flex-col p-5">
                                <h3 class="line-clamp-2 text-base font-bold leading-tight text-slate-900 dark:text-white group-hover:text-[#1967d2] transition-colors">{{ $properti->nama_properti }}</h3>
                                
                                <div class="mt-2 flex items-center gap-1.5 text-sm text-slate-500 dark:text-slate-400">
                                    <svg class="h-4 w-4 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span class="truncate">{{ \Illuminate\Support\Str::limit($properti->alamat_lengkap, 35) }}</span>
                                </div>

                                <div class="mt-auto pt-5 flex items-end justify-between border-t border-slate-50 dark:border-slate-800">
                                    <div>
                                        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-0.5">{{ $isKosan ? 'Mulai dari' : 'Disewakan' }}</p>
                                        <p class="text-lg font-extrabold text-[#1967d2] dark:text-blue-400">
                                            Rp {{ number_format($harga ?? 0, 0, ',', '.') }}<span class="text-xs font-normal text-slate-500">{{ $hargaLabel }}</span>
                                        </p>
                                    </div>
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-50 dark:bg-slate-800 text-[#1967d2] dark:text-blue-400 transition-colors duration-300 group-hover:bg-[#1967d2] group-hover:text-white">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Skeleton Grid -->
                <div wire:loading.flex wire:target="hapusFavorit, gotoPage" class="w-full">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6 w-full">
                        @for($i=0; $i<6; $i++)
                        <div class="flex flex-col overflow-hidden rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm animate-pulse">
                            <div class="aspect-[4/3] w-full bg-slate-200 dark:bg-slate-700"></div>
                            <div class="flex flex-1 flex-col p-5 space-y-4">
                                <div class="h-5 w-3/4 bg-slate-200 dark:bg-slate-700 rounded"></div>
                                <div class="h-4 w-full bg-slate-200 dark:bg-slate-700 rounded"></div>
                                <div class="mt-auto pt-5 border-t border-slate-50 dark:border-slate-800 flex justify-between items-end">
                                    <div class="space-y-2">
                                        <div class="h-3 w-16 bg-slate-200 dark:bg-slate-700 rounded"></div>
                                        <div class="h-5 w-24 bg-slate-200 dark:bg-slate-700 rounded"></div>
                                    </div>
                                    <div class="h-8 w-8 bg-slate-200 dark:bg-slate-700 rounded-full"></div>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>

                <div class="mt-8">
                    {{ $favorits->links() }}
                </div>
            @endif

        </div>
    </div>
</div>
