<x-guest-layout>
    @include('public.partials.navbar')

    <div class="min-h-screen bg-[#F8FAFC] pt-24 pb-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            
            {{-- Search Header --}}
            <div class="mb-8 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                <form action="{{ route('cari') }}" method="GET" class="flex flex-col gap-4 md:flex-row md:items-center">
                    
                    {{-- Dropdown Jenis Properti --}}
                    <div class="relative w-full md:w-56" x-data="{ open: false, selected: '{{ $request->query('tipe', 'Semua Tipe') }}' }" @click.outside="open = false">
                        <input type="hidden" name="tipe" x-model="selected">
                        <button type="button" @click="open = !open" class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100">
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-[#1967d2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                <span x-text="selected"></span>
                            </div>
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-cloak class="absolute left-0 top-full z-20 mt-1 w-full rounded-xl border border-slate-100 bg-white py-1 shadow-lg">
                            <button type="button" @click="selected = 'Semua Tipe'; open = false" class="block w-full px-4 py-2 text-left text-sm text-slate-600 hover:bg-sky-50">Semua Tipe</button>
                            <button type="button" @click="selected = 'Kos'; open = false" class="block w-full px-4 py-2 text-left text-sm text-slate-600 hover:bg-sky-50">Kos</button>
                            <button type="button" @click="selected = 'Kontrakan'; open = false" class="block w-full px-4 py-2 text-left text-sm text-slate-600 hover:bg-sky-50">Kontrakan</button>
                        </div>
                    </div>

                    {{-- Input Lokasi --}}
                    <div class="relative flex-1">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" name="lokasi" value="{{ $request->query('lokasi') }}" placeholder="Cari lokasi atau nama properti..." class="w-full rounded-xl border border-[#e5e7eb] bg-slate-50 py-3 pl-11 pr-4 text-sm text-[#090a0b] transition focus:border-[#1967d2] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1967d2]">
                    </div>

                    {{-- Dropdown Harga --}}
                    <div class="relative w-full md:w-56" x-data="{ open: false, selected: '{{ $request->query('harga', 'Rentang Harga') }}' }" @click.outside="open = false">
                        <input type="hidden" name="harga" x-model="selected">
                        <button type="button" @click="open = !open" class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100">
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-[#1967d2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span x-text="selected"></span>
                            </div>
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-cloak class="absolute left-0 top-full z-20 mt-1 w-full rounded-xl border border-slate-100 bg-white py-1 shadow-lg">
                            <button type="button" @click="selected = 'Rentang Harga'; open = false" class="block w-full px-4 py-2 text-left text-sm text-slate-600 hover:bg-sky-50">Semua Harga</button>
                            <button type="button" @click="selected = '< Rp 1 Juta'; open = false" class="block w-full px-4 py-2 text-left text-sm text-slate-600 hover:bg-sky-50">&lt; Rp 1 Juta</button>
                            <button type="button" @click="selected = 'Rp 1 - 2 Juta'; open = false" class="block w-full px-4 py-2 text-left text-sm text-slate-600 hover:bg-sky-50">Rp 1 - 2 Juta</button>
                            <button type="button" @click="selected = 'Rp 2 - 3 Juta'; open = false" class="block w-full px-4 py-2 text-left text-sm text-slate-600 hover:bg-sky-50">Rp 2 - 3 Juta</button>
                            <button type="button" @click="selected = '> Rp 3 Juta'; open = false" class="block w-full px-4 py-2 text-left text-sm text-slate-600 hover:bg-sky-50">&gt; Rp 3 Juta</button>
                        </div>
                    </div>

                    {{-- Tombol Cari --}}
                    <button type="submit" class="rounded-xl bg-[#1967d2] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#0f4fb5]">
                        Cari
                    </button>
                </form>
            </div>

            {{-- Results Header --}}
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-xl font-bold text-slate-800">Menampilkan {{ $results->count() }} Properti</h1>
            </div>

            {{-- Results Grid --}}
            @if($results->isEmpty())
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white py-16 text-center">
                    <svg class="mx-auto h-16 w-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3 class="mt-4 text-lg font-bold text-slate-800">Yah, tidak ada hasil yang cocok</h3>
                    <p class="mt-1 text-sm text-slate-500">Coba ubah filter atau kata kunci pencarian kamu.</p>
                </div>
            @else
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($results as $properti)
                    @php
                        $detailTipe = $properti->tipe_properti === 'kos' ? 'kosan' : 'kontrakan';
                    @endphp
                    <a href="{{ route('properti.detail', ['tipe' => $detailTipe, 'id' => $properti->id]) }}" class="group overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-[#1967d2] focus:ring-offset-2">
                        <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                            @if($properti->foto_tampil)
                                <img src="{{ $properti->foto_tampil }}" alt="{{ $properti->nama_properti }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100">
                                    <svg class="h-10 w-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                            @if($properti->sisa_kamar_tampil > 0 && $properti->sisa_kamar_tampil <= 3)
                                <span class="absolute left-3 top-3 rounded-full bg-red-500 px-3 py-1 text-xs font-bold text-white shadow-sm">Sisa {{ $properti->sisa_kamar_tampil }}</span>
                            @endif
                        </div>
                        <div class="p-4">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold uppercase tracking-wide {{ $properti->tipe_properti === 'kos' ? 'text-[#1967d2]' : 'text-[#32baff]' }}">
                                    {{ $properti->tipe_properti === 'kos' ? ($properti->jenis_kos ? 'Kos ' . ucfirst($properti->jenis_kos) : 'Kos') : 'Kontrakan' }}
                                </span>
                                @if($properti->rating_tampil)
                                <div class="flex items-center gap-1 text-sm">
                                    <svg class="h-4 w-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <span class="font-semibold text-slate-700">{{ $properti->rating_tampil }}</span>
                                </div>
                                @endif
                            </div>
                            <h3 class="mt-2 truncate text-base font-bold text-slate-800">{{ $properti->nama_properti }}</h3>
                            <div class="mt-1 flex items-center gap-1 text-sm text-slate-500">
                                <svg class="h-3.5 w-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="truncate">{{ Str::limit($properti->alamat_lengkap, 35) }}</span>
                            </div>
                            <div class="mt-3 flex items-center justify-between text-xs text-slate-500">
                                <span>{{ $properti->sisa_kamar_tampil > 0 ? $properti->sisa_kamar_tampil.' unit tersedia' : 'Penuh' }}</span>
                                <span class="font-semibold text-[#1967d2]">Lihat detail</span>
                            </div>
                            
                            <div class="mt-4 border-t border-slate-100 pt-3">
                                @if($properti->harga_tampil)
                                <p class="text-lg font-bold text-slate-800">Rp {{ number_format((float)$properti->harga_tampil, 0, ',', '.') }} 
                                    <span class="text-sm font-normal text-slate-500">{{ $properti->tipe_properti === 'kos' ? '/ bln' : '/ thn' }}</span>
                                </p>
                                @else
                                <p class="text-sm text-slate-400 italic">Harga belum tersedia</p>
                                @endif
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @include('public.partials.footer')
</x-guest-layout>
