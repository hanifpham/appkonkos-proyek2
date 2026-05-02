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
                <p class="mt-1 text-xs text-slate-400">Properti akan muncul setelah mitra mendaftarkan dan disetujui admin.</p>
            </div>
        @else
            {{-- Grid Cards --}}
            <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($kosanList->take(4) as $kos)
                <a href="{{ route('properti.detail', ['tipe' => 'kosan', 'id' => $kos->id]) }}" class="group relative flex flex-col overflow-hidden rounded-2xl bg-white border border-slate-100 shadow-[0_2px_12px_rgba(0,0,0,0.04)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_24px_rgba(0,0,0,0.08)]" id="kos-card-{{ $kos->id }}">
                    <div class="relative aspect-[4/3] w-full overflow-hidden bg-slate-100">
                        @if($kos->foto)
                            <img src="{{ $kos->foto }}" alt="{{ $kos->nama }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                        @else
                            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-50">
                                <svg class="h-12 w-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
                            </div>
                        @endif
                        
                        {{-- Gradient Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>

                        {{-- Badges di Kiri Atas --}}
                        <div class="absolute left-3 top-3 flex flex-col items-start gap-2">
                            <span class="inline-flex items-center rounded-full bg-white/90 backdrop-blur-md px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-[#1967d2] shadow-sm">
                                {{ $kos->jenis_kos ? 'Kos ' . $kos->jenis_kos : 'Kos' }}
                            </span>
                            @if($kos->sisa_kamar > 0 && $kos->sisa_kamar <= 5)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/90 backdrop-blur-md px-2.5 py-1 text-[11px] font-bold text-red-600 shadow-sm">
                                    <span class="flex h-1.5 w-1.5 rounded-full bg-red-600 animate-pulse"></span>
                                    Sisa {{ $kos->sisa_kamar }} Kamar
                                </span>
                            @elseif($kos->rating && $kos->rating >= 4.5)
                                <span class="inline-flex items-center gap-1 rounded-full bg-white/90 backdrop-blur-md px-2.5 py-1 text-[11px] font-bold text-orange-600 shadow-sm">
                                    🔥 Sedang Tren
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex flex-1 flex-col p-5">
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
                            <svg class="h-4 w-4 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="truncate">{{ Str::limit($kos->alamat, 35) }}</span>
                        </div>
                        
                        @if($kos->fasilitas)
                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            @php $fas = strtolower($kos->fasilitas); @endphp
                            @if(str_contains($fas, 'wifi') || str_contains($fas, 'internet'))
                            <span class="inline-flex items-center gap-1 rounded-md bg-slate-50 px-2 py-1 text-xs font-medium text-slate-600 border border-slate-100">
                                <svg class="h-3 w-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.858 15.355-5.858 21.213 0"/></svg>
                                WiFi
                            </span>
                            @endif
                            @if(str_contains($fas, 'ac'))
                            <span class="inline-flex items-center gap-1 rounded-md bg-slate-50 px-2 py-1 text-xs font-medium text-slate-600 border border-slate-100">
                                <svg class="h-3 w-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                AC
                            </span>
                            @endif
                            @if(str_contains($fas, 'kamar mandi'))
                            <span class="inline-flex items-center gap-1 rounded-md bg-slate-50 px-2 py-1 text-xs font-medium text-slate-600 border border-slate-100">
                                <svg class="h-3 w-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 10v8a2 2 0 002 2h14a2 2 0 002-2v-8M3 10V6a2 2 0 012-2h2a2 2 0 012 2v4"/></svg>
                                K. Mandi
                            </span>
                            @endif
                        </div>
                        @endif
                        
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
                    </div>
                </a>
                @endforeach
            </div>
        @endif

        {{-- Mobile: Lihat Semua --}}
        <div class="mt-6 text-center sm:hidden">
            <a href="{{ route('cari', ['tipe' => 'Kos']) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-[#1967d2]">
                Lihat Semua <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>
