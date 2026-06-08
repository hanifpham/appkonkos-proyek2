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
                    <x-property-card :property="$properti" />
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @include('public.partials.footer')
</x-guest-layout>
