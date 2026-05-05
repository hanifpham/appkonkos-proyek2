<div>
    {{-- Rekomendasi Kos Pilihan - Dynamic --}}
    <section class="bg-[#F8FAFC] py-16" id="rekomendasi-kos">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="flex items-end justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-[#090a0b] sm:text-3xl">Rekomendasi Kos Pilihan</h2>
                    <p class="mt-1 text-sm text-[#6b7280]">Kos terbaik yang kami kurasi khusus untuk kamu</p>
                </div>
                <a href="{{ route('cari', ['tipe' => 'Kos']) }}" class="hidden items-center gap-1 text-sm font-semibold text-[#1967d2] transition hover:text-[#0f4fb5] sm:inline-flex" id="kos-lihat-semua">
                    Lihat Semua
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @if($kosanList->isEmpty())
                <div class="mt-10 rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center">
                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
                    <p class="mt-3 text-sm font-medium text-slate-500">Belum ada kos yang tersedia saat ini.</p>
                </div>
            @else
                {{-- Grid Cards --}}
                <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($kosanList->take(4) as $kos)
                    <div class="group relative flex flex-col overflow-hidden rounded-2xl bg-white border border-slate-100 shadow-[0_2px_12px_rgba(0,0,0,0.04)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_24px_rgba(0,0,0,0.08)]" id="kos-card-{{ $kos->id }}">
                        <div class="relative aspect-[4/3] w-full overflow-hidden bg-slate-100">
                            {{-- Favorit Button --}}
                            <button 
                                wire:click.prevent="toggleFavorit({{ $kos->id }}, 'kosan')" 
                                class="absolute top-3 right-3 p-2 rounded-full bg-white/80 backdrop-blur-sm shadow-sm hover:bg-white transition-all duration-200 z-10"
                            >
                                @if(in_array($kos->id, $favoritIds))
                                    <svg class="w-5 h-5 text-[#1967d2]" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-500 hover:text-[#1967d2]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                @endif
                            </button>

                            <a href="{{ route('properti.detail', ['tipe' => 'kosan', 'id' => $kos->id]) }}">
                                @if($kos->foto)
                                    <img src="{{ $kos->foto }}" alt="{{ $kos->nama }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-50">
                                        <svg class="h-12 w-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
                                    </div>
                                @endif
                            </a>
                            
                            {{-- Gradient Overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100 pointer-events-none"></div>

                            {{-- Badges di Kiri Atas --}}
                            <div class="absolute left-3 top-3 flex flex-col items-start gap-2 z-10 pointer-events-none">
                                <span class="inline-flex items-center rounded-full bg-[#1967d2] px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-white shadow-sm">
                                    {{ $kos->jenis_kos ? 'Kos ' . $kos->jenis_kos : 'Kos' }}
                                </span>
                                @if($kos->sisa_kamar > 0 && $kos->sisa_kamar <= 3)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/90 backdrop-blur-md px-3 py-1 text-[11px] font-bold text-red-600 shadow-sm">
                                        <span class="flex h-1.5 w-1.5 rounded-full bg-red-600 animate-pulse"></span>
                                        Sisa {{ $kos->sisa_kamar }} Kamar
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <a href="{{ route('properti.detail', ['tipe' => 'kosan', 'id' => $kos->id]) }}" class="flex flex-1 flex-col p-5">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="line-clamp-2 text-base font-bold leading-tight text-slate-900 group-hover:text-[#1967d2] transition-colors">{{ $kos->nama }}</h3>
                                @if($kos->rating)
                                <div class="flex shrink-0 items-center gap-1 rounded-md bg-amber-50 px-1.5 py-0.5 text-sm border border-amber-100/50">
                                    <svg class="h-3.5 w-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <span class="font-bold text-amber-700">{{ $kos->rating }}</span>
                                </div>
                                @endif
                            </div>
                            
                            <div class="mt-2 flex items-center gap-1.5 text-sm text-slate-500">
                                <svg class="h-4 w-4 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="truncate">{{ \Illuminate\Support\Str::limit($kos->alamat, 35) }}</span>
                            </div>
                            
                            <div class="mt-auto pt-5 flex items-end justify-between border-t border-slate-50">
                                <div>
                                    @if($kos->harga_min)
                                    <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Mulai dari</p>
                                    <p class="text-lg font-extrabold text-[#1967d2]">Rp {{ number_format($kos->harga_min, 0, ',', '.') }}<span class="text-xs font-normal text-slate-500"> /bln</span></p>
                                    @else
                                    <p class="text-sm font-medium text-slate-400 italic">Harga belum tersedia</p>
                                    @endif
                                </div>
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-50 text-[#1967d2] transition-colors duration-300 group-hover:bg-[#1967d2] group-hover:text-white">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Rekomendasi Kontrakan Pilihan - Dynamic --}}
    <section class="bg-white py-16" id="rekomendasi-kontrakan">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="flex items-end justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-[#090a0b] sm:text-3xl">Rekomendasi Kontrakan Pilihan</h2>
                    <p class="mt-1 text-sm text-[#6b7280]">Rumah dan paviliun terbaik siap huni untuk keluarga</p>
                </div>
                <a href="{{ route('cari', ['tipe' => 'Kontrakan']) }}" class="hidden items-center gap-1 text-sm font-semibold text-[#1967d2] transition hover:text-[#0f4fb5] sm:inline-flex" id="kontrakan-lihat-semua">
                    Lihat Semua
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @if($kontrakanList->isEmpty())
                <div class="mt-10 rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center">
                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <p class="mt-3 text-sm font-medium text-slate-500">Belum ada kontrakan yang tersedia saat ini.</p>
                </div>
            @else
                {{-- Grid Cards --}}
                <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($kontrakanList->take(4) as $kontrakan)
                    <div class="group relative flex flex-col overflow-hidden rounded-2xl bg-white border border-slate-100 shadow-[0_2px_12px_rgba(0,0,0,0.04)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_24px_rgba(0,0,0,0.08)]" id="kontrakan-card-{{ $kontrakan->id }}">
                        <div class="relative aspect-[4/3] w-full overflow-hidden bg-slate-100">
                            {{-- Favorit Button --}}
                            <button 
                                wire:click.prevent="toggleFavorit({{ $kontrakan->id }}, 'kontrakan')" 
                                class="absolute top-3 right-3 p-2 rounded-full bg-white/80 backdrop-blur-sm shadow-sm hover:bg-white transition-all duration-200 z-10"
                            >
                                @if(in_array($kontrakan->id, $favoritIds))
                                    <svg class="w-5 h-5 text-[#1967d2]" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-500 hover:text-[#1967d2]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                @endif
                            </button>

                            <a href="{{ route('properti.detail', ['tipe' => 'kontrakan', 'id' => $kontrakan->id]) }}">
                                @if($kontrakan->foto)
                                    <img src="{{ $kontrakan->foto }}" alt="{{ $kontrakan->nama }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-teal-50 to-emerald-50">
                                        <svg class="h-12 w-12 text-teal-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                @endif
                            </a>
                            
                            {{-- Gradient Overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100 pointer-events-none"></div>

                            {{-- Badges di Kiri Atas --}}
                            <div class="absolute left-3 top-3 flex flex-col items-start gap-2 z-10 pointer-events-none">
                                <span class="inline-flex items-center rounded-full bg-teal-600 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-white shadow-sm">
                                    Kontrakan
                                </span>
                                @if($kontrakan->sisa_kamar > 0 && $kontrakan->sisa_kamar <= 3)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/90 backdrop-blur-md px-3 py-1 text-[11px] font-bold text-red-600 shadow-sm">
                                        <span class="flex h-1.5 w-1.5 rounded-full bg-red-600 animate-pulse"></span>
                                        Sisa {{ $kontrakan->sisa_kamar }} Unit
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <a href="{{ route('properti.detail', ['tipe' => 'kontrakan', 'id' => $kontrakan->id]) }}" class="flex flex-1 flex-col p-5">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="line-clamp-2 text-base font-bold leading-tight text-slate-900 group-hover:text-teal-600 transition-colors">{{ $kontrakan->nama }}</h3>
                                @if($kontrakan->rating)
                                <div class="flex shrink-0 items-center gap-1 rounded-md bg-amber-50 px-1.5 py-0.5 text-sm border border-amber-100/50">
                                    <svg class="h-3.5 w-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <span class="font-bold text-amber-700">{{ $kontrakan->rating }}</span>
                                </div>
                                @endif
                            </div>
                            
                            <div class="mt-2 flex items-center gap-1.5 text-sm text-slate-500">
                                <svg class="h-4 w-4 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="truncate">{{ \Illuminate\Support\Str::limit($kontrakan->alamat, 35) }}</span>
                            </div>
                            
                            <div class="mt-auto pt-5 flex items-end justify-between border-t border-slate-50">
                                <div>
                                    @if($kontrakan->harga_tahun)
                                    <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Disewakan</p>
                                    <p class="text-lg font-extrabold text-teal-600">Rp {{ number_format($kontrakan->harga_tahun, 0, ',', '.') }}<span class="text-xs font-normal text-slate-500"> /thn</span></p>
                                    @else
                                    <p class="text-sm font-medium text-slate-400 italic">Harga belum tersedia</p>
                                    @endif
                                </div>
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-teal-50 text-teal-600 transition-colors duration-300 group-hover:bg-teal-600 group-hover:text-white">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</div>
