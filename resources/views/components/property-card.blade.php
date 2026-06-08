@props(['property', 'tipe' => null, 'favoritIds' => []])

@php
    // Detect property type (kosan or kontrakan)
    $tipeProperti = $tipe ?? ($property->tipe_properti ?? (isset($property->harga_tahun) ? 'kontrakan' : 'kosan'));
    if ($tipeProperti === 'kos') $tipeProperti = 'kosan';

    // Normalizing data from different Eloquent models or Search queries
    $id = $property->id;
    $foto = $property->foto ?? $property->foto_tampil ?? null;
    $nama = $property->nama ?? $property->nama_properti;
    $sisaKamar = $property->sisa_kamar ?? $property->sisa_kamar_tampil ?? 0;
    $jenis = $property->jenis_kos ?? null;
    $rating = $property->rating ?? $property->rating_tampil ?? null;
    $alamat = $property->alamat ?? $property->alamat_lengkap;
    $harga = $property->harga_min ?? $property->harga_tahun ?? $property->harga_tampil ?? null;
    
    // Dynamic styling
    $satuanHarga = $tipeProperti === 'kosan' ? 'bln' : 'thn';
    $badgeText = $tipeProperti === 'kosan' ? ($jenis ? 'Kos ' . ucfirst($jenis) : 'Kos') : 'Kontrakan';
    
    $textTheme = $tipeProperti === 'kosan' ? 'text-[#1967d2]' : 'text-teal-600';
    $bgTheme = $tipeProperti === 'kosan' ? 'bg-[#1967d2]' : 'bg-teal-600';
    $bgLightTheme = $tipeProperti === 'kosan' ? 'bg-blue-50' : 'bg-teal-50';
    $groupHoverText = $tipeProperti === 'kosan' ? 'group-hover:text-[#1967d2]' : 'group-hover:text-teal-600';
    $groupHoverBg = $tipeProperti === 'kosan' ? 'group-hover:bg-[#1967d2]' : 'group-hover:bg-teal-600';
    
    // Placeholder SVG config
    $placeholderGradient = $tipeProperti === 'kosan' ? 'from-blue-50 to-indigo-50' : 'from-teal-50 to-emerald-50';
    $placeholderColor = $tipeProperti === 'kosan' ? 'text-blue-200' : 'text-teal-200';
    $routeUrl = route('properti.detail', ['tipe' => $tipeProperti, 'id' => $id]);
@endphp

<div class="group relative flex flex-col overflow-hidden rounded-2xl bg-white border border-slate-100 shadow-[0_2px_12px_rgba(0,0,0,0.04)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_24px_rgba(0,0,0,0.08)]">
    <div class="relative aspect-[4/3] w-full overflow-hidden bg-slate-100">
        {{-- Favorit Button (Only works if livewire is active in parent, fallback handles gracefully) --}}
        @if(str_contains(request()->route()->getName(), 'home'))
        <button
            wire:click.prevent="toggleFavorit({{ $id }}, '{{ $tipeProperti }}')"
            class="absolute top-3 right-3 p-2 rounded-full bg-white/80 backdrop-blur-sm shadow-sm hover:bg-white transition-all duration-200 z-10 overflow-hidden">
            <div wire:loading.remove wire:target="toggleFavorit({{ $id }}, '{{ $tipeProperti }}')">
                @if(isset($favoritIds) && in_array($id, $favoritIds))
                <svg class="w-5 h-5 text-[#1967d2]" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                </svg>
                @else
                <svg class="w-5 h-5 text-gray-500 hover:text-[#1967d2]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                @endif
            </div>
            <div wire:loading wire:target="toggleFavorit({{ $id }}, '{{ $tipeProperti }}')">
                <svg class="w-5 h-5 animate-spin text-gray-400" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </button>
        @endif

        <a href="{{ $routeUrl }}">
            @if($foto)
            <img src="{{ $foto }}" alt="{{ $nama }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
            @else
            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br {{ $placeholderGradient }}">
                <svg class="h-12 w-12 {{ $placeholderColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            @endif
        </a>

        {{-- Gradient Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100 pointer-events-none"></div>

        {{-- Badges di Kiri Atas --}}
        <div class="absolute left-3 top-3 flex flex-col items-start gap-2 z-10 pointer-events-none">
            <span class="inline-flex items-center rounded-full {{ $bgTheme }} px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-white shadow-sm">
                {{ $badgeText }}
            </span>
            @if($sisaKamar > 0 && $sisaKamar <= 3)
            <span class="inline-flex items-center gap-1.5 rounded-full bg-white/90 backdrop-blur-md px-3 py-1 text-[11px] font-bold text-red-600 shadow-sm">
                <span class="flex h-1.5 w-1.5 rounded-full bg-red-600 animate-pulse"></span>
                Sisa {{ $sisaKamar }} {{ $tipeProperti === 'kosan' ? 'Kamar' : 'Unit' }}
            </span>
            @endif
        </div>
    </div>

    <a href="{{ $routeUrl }}" class="flex flex-1 flex-col p-5">
        <div class="flex items-start justify-between gap-3">
            <h3 class="line-clamp-2 text-base font-bold leading-tight text-slate-900 {{ $groupHoverText }} transition-colors">{{ $nama }}</h3>
            @if($rating)
            <div class="flex shrink-0 items-center gap-1 rounded-md bg-amber-50 px-1.5 py-0.5 text-sm border border-amber-100/50">
                <svg class="h-3.5 w-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span class="font-bold text-amber-700">{{ is_numeric($rating) ? number_format((float)$rating, 1) : $rating }}</span>
            </div>
            @endif
        </div>

        <div class="mt-2 flex items-center gap-1.5 text-sm text-slate-500">
            <svg class="h-4 w-4 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span class="truncate">{{ \Illuminate\Support\Str::limit($alamat, 35) }}</span>
        </div>

        <div class="mt-auto pt-5 flex items-end justify-between border-t border-slate-50">
            <div>
                @if($harga)
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-0.5">{{ $tipeProperti === 'kosan' ? 'Mulai dari' : 'Disewakan' }}</p>
                <p class="text-lg font-extrabold {{ $textTheme }}">Rp {{ number_format((float)$harga, 0, ',', '.') }}<span class="text-xs font-normal text-slate-500"> /{{ $satuanHarga }}</span></p>
                @else
                <p class="text-sm font-medium text-slate-400 italic">Harga belum tersedia</p>
                @endif
            </div>
            <div class="flex h-8 w-8 items-center justify-center rounded-full {{ $bgLightTheme }} {{ $textTheme }} transition-colors duration-300 {{ $groupHoverBg }} group-hover:text-white">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </div>
    </a>
</div>
