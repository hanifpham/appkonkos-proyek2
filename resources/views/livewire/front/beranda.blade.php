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
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            {{-- Skeleton Loading Kos --}}
            <div wire:loading wire:target="toggleFavorit" class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @for ($i = 0; $i
                < 4; $i++)
                    <x-skeleton.card />
                @endfor
            </div>

            <div wire:loading.remove wire:target="toggleFavorit">
                @if($kosanList->isEmpty())
                <div class="mt-10 rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center">
                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4" />
                    </svg>
                    <p class="mt-3 text-sm font-medium text-slate-500">Belum ada kos yang tersedia saat ini.</p>
                </div>
                @else
                {{-- Grid Cards --}}
                <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($kosanList->take(4) as $kos)
                    <x-property-card :property="$kos" tipe="kosan" :favoritIds="$favoritIds" />
                    @endforeach
                </div>
                @endif
            </div>
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
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            {{-- Skeleton Loading Kontrakan --}}
            <div wire:loading wire:target="toggleFavorit" class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @for ($i = 0; $i
                < 4; $i++)
                    <x-skeleton.card />
                @endfor
            </div>

            <div wire:loading.remove wire:target="toggleFavorit">
                @if($kontrakanList->isEmpty())
                <div class="mt-10 rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center">
                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <p class="mt-3 text-sm font-medium text-slate-500">Belum ada kontrakan yang tersedia saat ini.</p>
                </div>
                @else
                {{-- Grid Cards --}}
                <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($kontrakanList->take(4) as $kontrakan)
                    <x-property-card :property="$kontrakan" tipe="kontrakan" :favoritIds="$favoritIds" />
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </section>
</div>