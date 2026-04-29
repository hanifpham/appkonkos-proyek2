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
                <p class="mt-1 text-xs text-slate-400">Properti akan muncul setelah mitra mendaftarkan dan disetujui admin.</p>
            </div>
        @else
            {{-- Grid Cards --}}
            <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($kontrakanList->take(4) as $kontrakan)
                <a href="{{ route('properti.detail', ['tipe' => 'kontrakan', 'id' => $kontrakan->id]) }}" class="group overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg block" id="kontrakan-card-{{ $kontrakan->id }}">
                    <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                        @if($kontrakan->foto)
                            <img src="{{ $kontrakan->foto }}" alt="{{ $kontrakan->nama }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                        @else
                            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-teal-50 to-emerald-100">
                                <svg class="h-12 w-12 text-teal-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                        @endif
                        @if($kontrakan->sisa_kamar > 0 && $kontrakan->sisa_kamar <= 3)
                            <span class="absolute left-3 top-3 rounded-full bg-red-500 px-3 py-1 text-xs font-bold text-white shadow-sm">Sisa {{ $kontrakan->sisa_kamar }} Unit</span>
                        @elseif($kontrakan->rating && $kontrakan->rating >= 4.5)
                            <span class="absolute left-3 top-3 rounded-full bg-red-500 px-3 py-1 text-xs font-bold text-white shadow-sm">🔥 Sedang Tren</span>
                        @endif
                    </div>
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wide text-[#32baff]">Kontrakan</span>
                            @if($kontrakan->rating)
                            <div class="flex items-center gap-1 text-sm">
                                <svg class="h-4 w-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span class="font-semibold text-slate-700">{{ $kontrakan->rating }}</span>
                            </div>
                            @endif
                        </div>
                        <h3 class="mt-2 truncate text-base font-bold text-slate-800">{{ $kontrakan->nama }}</h3>
                        <div class="mt-1 flex items-center gap-1 text-sm text-slate-500">
                            <svg class="h-3.5 w-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="truncate">{{ Str::limit($kontrakan->alamat, 35) }}</span>
                        </div>
                        @if($kontrakan->fasilitas)
                            <p class="mt-3 text-sm text-slate-500">{{ Str::limit($kontrakan->fasilitas, 40) }}</p>
                        @endif
                        <div class="mt-4 border-t border-slate-100 pt-3">
                            <p class="text-lg font-bold text-slate-800">Rp {{ number_format($kontrakan->harga_tahun, 0, ',', '.') }} <span class="text-sm font-normal text-slate-500">/ tahun</span></p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @endif

        {{-- Mobile: Lihat Semua --}}
        <div class="mt-6 text-center sm:hidden">
            <a href="{{ route('cari', ['tipe' => 'Kontrakan']) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-[#1967d2]">
                Lihat Semua <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>
