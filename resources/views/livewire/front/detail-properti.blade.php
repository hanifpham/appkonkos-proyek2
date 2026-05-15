@php
$isKosan = $tipe === 'kosan';
$ownerUser = $properti->pemilikProperti?->user;
$ownerName = $ownerUser?->name ?? 'Mitra APPKONKOS';
$ownerEmail = $ownerUser?->email;
$ownerAvatarUrl = $this->profilePhotoUrlFor($ownerUser, $ownerName);

$photos = collect($properti->getMediaDisplayUrls('foto_properti'))->filter()->values();
$galleryPhotos = $photos->all();
$mainPhoto = $photos->first();

$ratingAverage = (float) ($properti->ulasan->avg('rating') ?? 0);
$reviewCount = $properti->ulasan->count();

$splitList = static function (?string $value): array {
$items = preg_split('/[\r\n,]+/', (string) $value) ?: [];

return collect($items)
->map(static fn (string $item): string => trim($item))
->filter()
->values()
->all();
};

$roomTypes = $isKosan ? $properti->tipeKamar : collect();
$activeType = $isKosan ? $roomTypes->firstWhere('id', $selectedTipeKamarId) : null;
$selectedRoom = $activeType ? $activeType->kamar->firstWhere('id', $selectedKamarId) : null;
$availableRooms = $isKosan
? $roomTypes->sum(fn ($roomType) => $roomType->kamar->where('status_kamar', 'tersedia')->count())
: (int) $properti->sisa_kamar;

$categoryLabel = $isKosan
? match ($properti->jenis_kos) {
'putra' => 'Kos Putra',
'putri' => 'Kos Putri',
'campur' => 'Kos Campur',
default => 'Kos',
}
: 'Kontrakan';

$ruleText = $isKosan ? $properti->peraturan_kos : $properti->peraturan_kontrakan;
$rules = $splitList($ruleText);
$umumItems = $isKosan ? $splitList($properti->fasilitas_umum) : $splitList($properti->fasilitas);

$kamarGroups = [];
if ($isKosan) {
foreach ($roomTypes as $roomType) {
$items = $splitList($roomType->fasilitas_tipe);
if ($items !== []) {
$kamarGroups[] = [
'title' => $roomType->nama_tipe,
'items' => $items,
];
}
}
}

$allFacilities = collect($umumItems);
foreach ($kamarGroups as $group) {
$allFacilities = $allFacilities->merge($group['items']);
}

$currentPrice = $isKosan
? (int) ($activeType?->harga_per_bulan ?? $roomTypes->min('harga_per_bulan') ?? 0)
: (int) $properti->harga_sewa_tahun;
$duration = max(1, (int) $durasiSewa);
$estimatedTotal = $currentPrice * $duration;

$mapUrl = filled($properti->latitude) && filled($properti->longitude)
? 'https://www.google.com/maps/search/?api=1&query='.$properti->latitude.','.$properti->longitude
: null;

$durationOptions = $isKosan
? [
['value' => 1, 'label' => '1 Bulan'],
['value' => 3, 'label' => '3 Bulan'],
['value' => 6, 'label' => '6 Bulan'],
['value' => 12, 'label' => '1 Tahun'],
]
: [
['value' => 1, 'label' => '1 Tahun'],
['value' => 2, 'label' => '2 Tahun'],
['value' => 3, 'label' => '3 Tahun'],
];
@endphp

<div class="min-h-screen bg-slate-50 pb-24 font-[Inter] text-slate-900 selection:bg-blue-200 selection:text-blue-900"
     x-data="{ galleryOpen: false, activePhoto: 0, photos: @js($galleryPhotos) }"
     x-on:keydown.escape.window="galleryOpen = false">

    {{-- Header / Title Section --}}
    <div class="mx-auto max-w-7xl px-4 pt-8 pb-6 sm:px-6 lg:px-8 animate-fade-in-up">
        <nav class="mb-4 flex items-center gap-2 text-sm font-medium text-slate-500">
            <a href="{{ route('home') }}" class="transition-colors hover:text-[#1967d2]">Beranda</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <a href="{{ route('cari') }}" class="transition-colors hover:text-[#1967d2]">Cari Properti</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <span class="text-slate-800">{{ $properti->nama_properti }}</span>
        </nav>

        <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
            <div>
                <div class="flex flex-wrap items-center gap-3 mb-3">
                    <span class="inline-flex items-center rounded-lg bg-blue-100/80 px-3 py-1 text-xs font-bold uppercase tracking-wider text-[#1967d2]">{{ $categoryLabel }}</span>
                    <div class="flex items-center gap-1.5 rounded-lg bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700 ring-1 ring-amber-200/50">
                        <span class="material-symbols-outlined text-[14px] text-amber-500">star</span>
                        {{ number_format($ratingAverage, 1) }} <span class="font-medium text-amber-600/80">({{ $reviewCount }} Ulasan)</span>
                    </div>
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-slate-900">{{ $properti->nama_properti }}</h1>
                <p class="mt-4 flex items-start gap-2 text-base text-slate-600">
                    <span class="material-symbols-outlined shrink-0 text-[#1967d2]">location_on</span>
                    <span class="leading-relaxed">{{ $properti->alamat_lengkap }}</span>
                </p>
            </div>
            
            <div class="flex items-center gap-3 self-start md:self-end">
                <button wire:click="toggleFavorit" class="group flex h-12 w-12 items-center justify-center rounded-full bg-white shadow-sm ring-1 ring-slate-200 transition-all hover:scale-105 hover:shadow-md hover:ring-slate-300">
                    <span class="material-symbols-outlined text-2xl transition-colors {{ $isFavorit ? 'text-red-500 fill-current' : 'text-slate-400 group-hover:text-red-500' }}">favorite</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Gallery Hero --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mb-12 animate-fade-in-up" style="animation-delay: 100ms;">
        <div class="relative grid h-[350px] sm:h-[450px] lg:h-[500px] grid-cols-4 grid-rows-2 gap-2 overflow-hidden rounded-[2rem]">
            {{-- Main Photo --}}
            <div class="col-span-4 row-span-2 md:col-span-2 relative group cursor-pointer" @click="photos.length && (galleryOpen = true, activePhoto = 0)">
                @if($mainPhoto)
                    <img src="{{ $mainPhoto }}" alt="{{ $properti->nama_properti }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">
                    <div class="absolute inset-0 bg-black/10 transition-opacity duration-300 group-hover:bg-transparent"></div>
                @else
                    <div class="flex h-full w-full items-center justify-center bg-slate-200 text-slate-500">
                        <span class="material-symbols-outlined text-4xl">image</span>
                    </div>
                @endif
            </div>
            
            {{-- Secondary Photos --}}
            @php $frontPhotos = $photos->skip(1)->take(4)->values(); @endphp
            @for($i = 0; $i < 4; $i++)
                @php $photo = $frontPhotos->get($i); @endphp
                <div class="hidden md:block col-span-1 row-span-1 relative group cursor-pointer overflow-hidden" @click="galleryOpen = true; activePhoto = {{ $i + 1 }}">
                    @if($photo)
                        <img src="{{ $photo }}" alt="Foto {{ $i+2 }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                        <div class="absolute inset-0 bg-black/10 transition-opacity duration-300 group-hover:bg-transparent"></div>
                        @if($i === 3 && $photos->count() > 5)
                            <div class="absolute inset-0 flex items-center justify-center bg-black/40 backdrop-blur-sm transition-colors group-hover:bg-black/50">
                                <span class="text-lg font-bold text-white">+{{ $photos->count() - 5 }} Foto</span>
                            </div>
                        @endif
                    @else
                        <div class="flex h-full w-full items-center justify-center bg-slate-100 text-slate-300">
                            <span class="material-symbols-outlined text-2xl">image</span>
                        </div>
                    @endif
                </div>
            @endfor

            @if($photos->isNotEmpty())
            <button @click="galleryOpen = true; activePhoto = 0" class="absolute bottom-6 right-6 z-10 flex items-center gap-2 rounded-xl bg-white/90 px-4 py-2.5 text-sm font-bold text-slate-800 shadow-lg backdrop-blur-md transition-all hover:scale-105 hover:bg-white">
                <span class="material-symbols-outlined text-[18px]">grid_view</span>
                Lihat Semua Foto
            </button>
            @endif
        </div>
    </div>

    {{-- Main Content & Sidebar --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-12">
            
            {{-- Left Content --}}
            <div class="w-full lg:w-[65%] space-y-12 animate-fade-in-up" style="animation-delay: 200ms;">
                
                {{-- Owner Info & Highlights --}}
                <div class="flex flex-wrap items-center justify-between gap-6 border-b border-slate-200 pb-8">
                    <div class="flex items-center gap-4">
                        <img src="{{ $ownerAvatarUrl }}" alt="{{ $ownerName }}" class="h-16 w-16 rounded-full object-cover ring-4 ring-white shadow-md" loading="lazy">
                        <div>
                            <p class="text-sm font-bold text-slate-500 uppercase tracking-wide">Disewakan Oleh</p>
                            <h3 class="text-xl font-black text-slate-900">{{ $ownerName }}</h3>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center justify-center rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
                            <span class="material-symbols-outlined text-[#1967d2] text-2xl mb-1">verified_user</span>
                            <span class="text-xs font-bold text-slate-600">Mitra Terverifikasi</span>
                        </div>
                    </div>
                </div>

                {{-- Facilities --}}
                <section class="border-t border-slate-200 pt-10">
                    <h2 class="text-2xl font-black text-slate-900 mb-8">Fasilitas Properti</h2>

                    <div class="space-y-6">
                        {{-- Fasilitas Umum --}}
                        @if(count($umumItems) > 0)
                        <div class="group rounded-3xl border border-slate-100 bg-white p-6 sm:p-8 shadow-sm transition-all duration-300 hover:shadow-md hover:border-blue-100">
                            <h3 class="flex items-center gap-4 font-black text-slate-800 mb-6 pb-4 border-b border-slate-100">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-[#1967d2] transition-colors duration-300 group-hover:bg-[#1967d2] group-hover:text-white shadow-sm ring-1 ring-blue-100/50">
                                    <span class="material-symbols-outlined text-[24px]">apartment</span>
                                </div>
                                <span class="text-xl">Fasilitas Umum</span>
                            </h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-y-5 gap-x-4">
                                @foreach($umumItems as $item)
                                <div class="flex items-center gap-3">
                                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-blue-50/80 text-[#1967d2] ring-1 ring-blue-100 transition-transform duration-300 group-hover:scale-110 group-hover:bg-[#1967d2] group-hover:text-white group-hover:ring-[#1967d2]">
                                        <span class="material-symbols-outlined text-[16px] font-bold">check</span>
                                    </span>
                                    <span class="text-sm font-bold text-slate-700">{{ $item }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- Fasilitas Kamar / Tipe Kamar --}}
                        @if(count($kamarGroups) > 0)
                        <div class="group rounded-3xl border border-slate-100 bg-white p-6 sm:p-8 shadow-sm transition-all duration-300 hover:shadow-md hover:border-emerald-100">
                            <h3 class="flex items-center gap-4 font-black text-slate-800 mb-6 pb-4 border-b border-slate-100">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 transition-colors duration-300 group-hover:bg-emerald-600 group-hover:text-white shadow-sm ring-1 ring-emerald-100/50">
                                    <span class="material-symbols-outlined text-[24px]">bed</span>
                                </div>
                                <span class="text-xl">Fasilitas Kamar</span>
                            </h3>
                            <div class="space-y-8">
                                @foreach($kamarGroups as $index => $group)
                                <div class="{{ $index > 0 ? 'pt-8 border-t border-slate-50' : '' }}">
                                    <p class="inline-flex items-center gap-2 rounded-lg bg-slate-50 px-3 py-1.5 text-xs font-bold tracking-wider text-slate-500 mb-5 border border-slate-100">
                                        <span class="material-symbols-outlined text-[14px]">hotel_class</span>
                                        Tipe: {{ $group['title'] }}
                                    </p>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-y-5 gap-x-4">
                                        @foreach($group['items'] as $item)
                                        <div class="flex items-center gap-3">
                                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-50/80 text-emerald-600 ring-1 ring-emerald-100 transition-transform duration-300 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white group-hover:ring-emerald-600">
                                                <span class="material-symbols-outlined text-[16px] font-bold">check</span>
                                            </span>
                                            <span class="text-sm font-bold text-slate-700">{{ $item }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </section>

                {{-- Room Selection (Kosan Only) --}}
                @if($isKosan)
                <section class="border-t border-slate-200 pt-10">
                    <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-black text-slate-900">Pilih Tipe & Nomor Kamar</h2>
                            <p class="text-sm text-slate-500 mt-1">Silakan pilih tipe kamar yang Anda inginkan</p>
                        </div>
                        <div class="rounded-full bg-green-50 px-4 py-1.5 text-sm font-bold text-green-600 ring-1 ring-green-200/50">
                            {{ $availableRooms }} Kamar Tersedia
                        </div>
                    </div>

                    @if($roomTypes->isNotEmpty())
                    <div class="grid gap-5 md:grid-cols-2 mb-8">
                        @foreach($roomTypes as $roomType)
                        @php
                        $interiorPhoto = $roomType->getMediaDisplayUrl('foto_interior');
                        $roomAvailableCount = $roomType->kamar->where('status_kamar', 'tersedia')->count();
                        $isSelected = $selectedTipeKamarId === $roomType->id;
                        @endphp
                        <button type="button" wire:click="$set('selectedTipeKamarId', {{ $roomType->id }})" 
                                class="relative flex flex-col overflow-hidden rounded-3xl border-2 text-left transition-all duration-300 hover:-translate-y-1 {{ $isSelected ? 'border-[#1967d2] bg-blue-50/30 shadow-lg shadow-blue-500/10' : 'border-slate-100 bg-white hover:border-blue-200 hover:shadow-md' }}">
                            @if($isSelected)
                            <div class="absolute right-4 top-4 z-10 flex h-6 w-6 items-center justify-center rounded-full bg-[#1967d2] text-white shadow-md">
                                <span class="material-symbols-outlined text-[14px] font-bold">check</span>
                            </div>
                            @endif
                            
                            @if($interiorPhoto)
                            <img src="{{ $interiorPhoto }}" alt="{{ $roomType->nama_tipe }}" class="h-48 w-full object-cover" loading="lazy">
                            @endif
                            <div class="flex-1 p-5">
                                <h3 class="text-lg font-black text-slate-900">{{ $roomType->nama_tipe }}</h3>
                                <p class="mt-1 text-base font-bold text-[#1967d2]">Rp {{ number_format((int) $roomType->harga_per_bulan, 0, ',', '.') }}<span class="text-xs font-medium text-slate-500">/bln</span></p>
                                <div class="mt-4 inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600">
                                    {{ $roomAvailableCount }} unit tersisa
                                </div>
                            </div>
                        </button>
                        @endforeach
                    </div>

                    {{-- Room Numbers --}}
                    <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                        <div class="mb-5 flex flex-wrap items-center justify-between gap-4">
                            <h3 class="text-base font-bold text-slate-900">Pilih Nomor Kamar</h3>
                            <div class="flex gap-4 text-xs font-bold text-slate-500">
                                <div class="flex items-center gap-1.5"><span class="h-3 w-3 rounded-full bg-slate-100 ring-1 ring-slate-200"></span>Tersedia</div>
                                <div class="flex items-center gap-1.5"><span class="h-3 w-3 rounded-full bg-[#1967d2]"></span>Dipilih</div>
                                <div class="flex items-center gap-1.5"><span class="h-3 w-3 rounded-full bg-slate-200 line-through"></span>Penuh</div>
                            </div>
                        </div>

                        @if($activeType && $activeType->kamar->isNotEmpty())
                        <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-3">
                            @foreach($activeType->kamar as $room)
                            @if($room->status_kamar === 'tersedia')
                            <button type="button" wire:click="selectKamar({{ $room->id }}, '{{ $room->status_kamar }}')" 
                                    class="relative flex aspect-square items-center justify-center rounded-2xl text-sm font-black transition-all duration-300 {{ $selectedKamarId === $room->id ? 'bg-[#1967d2] text-white shadow-lg shadow-blue-500/30 scale-105' : 'bg-slate-50 text-slate-700 ring-1 ring-slate-200 hover:bg-blue-50 hover:text-[#1967d2] hover:ring-blue-200' }}">
                                {{ $room->nomor_kamar }}
                            </button>
                            @else
                            <div class="relative flex aspect-square items-center justify-center rounded-2xl bg-slate-100 text-sm font-bold text-slate-400 opacity-60 cursor-not-allowed overflow-hidden">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="h-px w-full bg-slate-300 -rotate-45"></div>
                                </div>
                                <span class="z-10">{{ $room->nomor_kamar }}</span>
                            </div>
                            @endif
                            @endforeach
                        </div>
                        @else
                        <div class="flex items-center justify-center rounded-2xl bg-slate-50 py-8 text-sm font-medium text-slate-500">
                            Tidak ada data kamar.
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="rounded-3xl border border-slate-100 bg-white p-8 text-center text-slate-500 shadow-sm">
                        Belum ada tipe kamar yang ditambahkan.
                    </div>
                    @endif
                </section>
                @endif

                {{-- Peraturan --}}
                <section class="border-t border-slate-200 pt-10">
                    <h2 class="text-2xl font-black text-slate-900 mb-6">Peraturan Properti</h2>
                    @if($rules !== [])
                    <div class="grid sm:grid-cols-2 gap-4">
                        @foreach($rules as $rule)
                        <div class="flex items-start gap-3 rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
                            <span class="material-symbols-outlined text-[#1967d2] text-xl">info</span>
                            <span class="text-sm font-medium text-slate-700 leading-relaxed">{{ $rule }}</span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-slate-500 italic">Tidak ada peraturan khusus.</p>
                    @endif
                </section>

                {{-- Map --}}
                <section class="border-t border-slate-200 pt-10">
                    <h2 class="text-2xl font-black text-slate-900 mb-6">Lokasi</h2>
                    <div class="relative h-[300px] sm:h-[400px] w-full overflow-hidden rounded-3xl bg-slate-100 shadow-sm ring-1 ring-slate-200 group">
                        @if(filled($properti->latitude) && filled($properti->longitude))
                        <iframe width="100%" height="100%" frameborder="0" src="https://maps.google.com/maps?q={{ $properti->latitude }},{{ $properti->longitude }}&t=&z=15&ie=UTF8&iwloc=&output=embed" class="grayscale transition-all duration-500 group-hover:grayscale-0"></iframe>
                        @else
                        <iframe width="100%" height="100%" frameborder="0" src="https://maps.google.com/maps?q={{ urlencode($properti->alamat_lengkap) }}&t=&z=15&ie=UTF8&iwloc=&output=embed" class="grayscale transition-all duration-500 group-hover:grayscale-0"></iframe>
                        @endif
                        
                        <div class="absolute bottom-6 left-1/2 -translate-x-1/2">
                            <a href="{{ $mapUrl ?? 'https://www.google.com/maps/search/?api=1&query='.urlencode($properti->alamat_lengkap) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full bg-slate-900/90 backdrop-blur-md px-6 py-3 text-sm font-bold text-white shadow-lg transition-all hover:scale-105 hover:bg-slate-900">
                                <span class="material-symbols-outlined text-[18px]">navigation</span>
                                Buka di Google Maps
                            </a>
                        </div>
                    </div>
                </section>

                {{-- Reviews --}}
                <section class="border-t border-slate-200 pt-10">
                    <div class="flex items-center gap-3 mb-8">
                        <span class="material-symbols-outlined text-4xl text-amber-400">star</span>
                        <div>
                            <h2 class="text-3xl font-black text-slate-900">{{ number_format($ratingAverage, 1) }}</h2>
                            <p class="text-sm font-bold text-slate-500">Berdasarkan {{ $reviewCount }} Ulasan</p>
                        </div>
                    </div>

                    @if($reviewCount > 0)
                    <div class="grid md:grid-cols-2 gap-6">
                        @foreach($properti->ulasan->take(6) as $review)
                        @php
                            $reviewer = $review->pencariKos?->user;
                            $isAnon = $review->is_anonymous;
                            $reviewerName = $isAnon ? 'Anonim' : ($reviewer?->name ?? 'Pengguna');
                            $encodedName = rawurlencode($reviewerName);
                            $avatarUrl = $isAnon ? "https://ui-avatars.com/api/?name={$encodedName}&color=113C7A&background=EBF4FF" : $this->profilePhotoUrlFor($reviewer, 'Pengguna');
                        @endphp
                        <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                            <div class="flex items-center gap-4 mb-4">
                                <img src="{{ $avatarUrl }}" alt="{{ $reviewerName }}" class="h-12 w-12 rounded-full object-cover ring-2 ring-slate-100" loading="lazy">
                                <div>
                                    <h4 class="font-bold text-slate-900">{{ $reviewerName }}</h4>
                                    <p class="text-xs font-medium text-slate-500">{{ $review->created_at?->translatedFormat('d F Y') }}</p>
                                </div>
                            </div>
                            <div class="flex gap-1 mb-3 text-amber-400">
                                @for($i = 1; $i <= 5; $i++)
                                <span class="material-symbols-outlined text-[16px] {{ $i <= (int) $review->rating ? 'fill-current' : '' }}">star</span>
                                @endfor
                            </div>
                            <p class="text-sm leading-relaxed text-slate-700">{{ $review->komentar }}</p>
                            
                            @if(filled($review->balasan_pemilik))
                            <div class="mt-4 relative rounded-2xl bg-blue-50/50 p-4 pl-5">
                                <div class="absolute left-0 top-4 bottom-4 w-1 rounded-r-full bg-[#1967d2]/30"></div>
                                <p class="text-xs font-bold text-[#1967d2] mb-1">Balasan Pemilik</p>
                                <p class="text-sm leading-relaxed text-slate-700">{{ $review->balasan_pemilik }}</p>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="rounded-3xl border border-slate-100 bg-white py-12 text-center shadow-sm">
                        <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">reviews</span>
                        <p class="text-sm font-bold text-slate-500">Belum ada ulasan untuk properti ini.</p>
                    </div>
                    @endif
                </section>
            </div>

            {{-- Right Sidebar (Booking Card) --}}
            <div class="w-full lg:w-[35%] relative animate-fade-in-up" style="animation-delay: 300ms;">
                <div class="sticky top-28 rounded-[2rem] bg-white p-6 sm:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.08)] ring-1 ring-slate-100">
                    <div class="mb-6">
                        <p class="text-sm font-bold text-slate-500 uppercase tracking-wide mb-1">Harga Sewa</p>
                        @if($currentPrice > 0)
                        <div class="flex items-end gap-1">
                            <span class="text-3xl font-black text-[#1967d2]">Rp {{ number_format($currentPrice, 0, ',', '.') }}</span>
                            <span class="text-sm font-bold text-slate-500 pb-1">/{{ $isKosan ? 'bln' : 'thn' }}</span>
                        </div>
                        @else
                        <span class="text-xl font-black text-slate-900">Belum Tersedia</span>
                        @endif
                    </div>

                    <form wire:submit.prevent="buatBooking" class="space-y-6">
                        
                        {{-- Booking Summary Box --}}
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5 space-y-4">
                            @if($isKosan)
                            <div class="flex justify-between items-center text-sm">
                                <span class="font-bold text-slate-500">Tipe Kamar</span>
                                <span class="font-bold text-slate-900">{{ $activeType?->nama_tipe ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="font-bold text-slate-500">Nomor Kamar</span>
                                <span class="font-bold {{ $selectedRoom ? 'text-[#1967d2]' : 'text-slate-400' }}">{{ $selectedRoom ? 'Kamar '.$selectedRoom->nomor_kamar : 'Belum dipilih' }}</span>
                            </div>
                            @else
                            <div class="flex justify-between items-center text-sm">
                                <span class="font-bold text-slate-500">Status</span>
                                <span class="font-bold {{ $availableRooms > 0 ? 'text-green-600' : 'text-red-500' }}">{{ $availableRooms > 0 ? 'Tersedia' : 'Penuh' }}</span>
                            </div>
                            @endif
                        </div>

                        {{-- Date Selection --}}
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-900">Mulai Sewa</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-[#1967d2]">
                                    <span class="material-symbols-outlined text-[20px]">event</span>
                                </div>
                                <input type="date" wire:model.live="tanggalCheckIn" min="{{ now()->toDateString() }}" 
                                       class="w-full rounded-2xl border border-slate-200 bg-white py-3.5 pl-12 pr-4 text-sm font-bold text-slate-900 outline-none transition-all focus:border-[#1967d2] focus:ring-4 focus:ring-[#1967d2]/10 hover:border-slate-300">
                            </div>
                            @error('tanggalCheckIn') <p class="mt-1.5 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Duration --}}
                        <div>
                            <label class="mb-2 flex items-center justify-between text-sm font-bold text-slate-900">
                                <span>Durasi Sewa</span>
                                <span class="text-[10px] uppercase tracking-wider text-slate-400">{{ $isKosan ? 'Bulan' : 'Tahun' }}</span>
                            </label>
                            <div class="grid {{ $isKosan ? 'grid-cols-4' : 'grid-cols-3' }} gap-2 bg-slate-50 p-1.5 rounded-2xl border border-slate-200">
                                @foreach($durationOptions as $option)
                                @php $isSelected = (int) $durasiSewa === $option['value']; @endphp
                                <button type="button" wire:click="$set('durasiSewa', {{ $option['value'] }})"
                                        class="rounded-xl py-2.5 text-sm font-bold transition-all duration-300 {{ $isSelected ? 'bg-white text-[#1967d2] shadow-sm ring-1 ring-slate-200 scale-[1.02]' : 'text-slate-500 hover:bg-white/60' }}">
                                    {{ $option['value'] }}
                                </button>
                                @endforeach
                            </div>
                            @error('durasiSewa') <p class="mt-1.5 text-xs font-bold text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Total Price --}}
                        <div class="flex items-center justify-between border-t border-slate-200 pt-6">
                            <span class="text-sm font-bold text-slate-900">Total Harga</span>
                            <span class="text-xl font-black text-[#1967d2]">Rp {{ number_format($estimatedTotal, 0, ',', '.') }}</span>
                        </div>

                        @if(session()->has('error'))
                        <div class="rounded-2xl bg-red-50 p-4 border border-red-100 text-sm font-bold text-red-600 flex items-start gap-3">
                            <span class="material-symbols-outlined text-[20px]">error</span>
                            <p>{{ session('error') }}</p>
                        </div>
                        @endif

                        @php
                        $bookingDisabled = ($isKosan && !$selectedRoom) || (!$isKosan && $availableRooms < 1) || $currentPrice < 1;
                        @endphp

                        @auth
                            @if(!$bookingDisabled)
                            <button type="submit" wire:loading.attr="disabled" wire:target="buatBooking" class="group relative w-full overflow-hidden rounded-2xl bg-[#1967d2] py-4 text-center font-black text-white shadow-lg shadow-blue-500/25 transition-all duration-300 hover:scale-[1.02] hover:bg-[#1556b0] hover:shadow-blue-500/40 disabled:opacity-75 disabled:cursor-not-allowed">
                                <div class="absolute inset-0 bg-white/20 translate-y-full transition-transform duration-300 group-hover:translate-y-0 ease-out"></div>
                                <span wire:loading.remove wire:target="buatBooking" class="relative flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined">payments</span>
                                    Lanjut Pembayaran
                                </span>
                                <span wire:loading wire:target="buatBooking" class="relative flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined animate-spin">refresh</span>
                                    Memproses...
                                </span>
                            </button>
                            @else
                            <button disabled type="button" class="w-full rounded-2xl bg-slate-100 py-4 text-center font-bold text-slate-400 cursor-not-allowed flex items-center justify-center gap-2 border border-slate-200">
                                <span class="material-symbols-outlined">lock</span>
                                {{ $isKosan ? 'Pilih Kamar Dulu' : 'Unit Habis' }}
                            </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="group relative w-full overflow-hidden flex justify-center rounded-2xl bg-[#1967d2] py-4 font-black text-white shadow-lg transition-all duration-300 hover:scale-[1.02] hover:bg-[#1556b0] hover:shadow-blue-500/30">
                                <span class="relative flex items-center gap-2">
                                    <span class="material-symbols-outlined">login</span>
                                    Masuk untuk Lanjut
                                </span>
                            </a>
                        @endauth
                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- Gallery Modal --}}
    <div x-cloak x-show="galleryOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 backdrop-blur-md p-4" x-transition.opacity>
        <button type="button" @click="galleryOpen = false" class="absolute right-6 top-6 rounded-full bg-white/10 p-3 text-white transition hover:bg-white hover:text-black">
            <span class="material-symbols-outlined">close</span>
        </button>

        <button type="button" x-show="photos.length > 1" @click="activePhoto = activePhoto === 0 ? photos.length - 1 : activePhoto - 1" class="absolute left-6 rounded-full bg-white/10 p-4 text-white transition hover:bg-white hover:text-black">
            <span class="material-symbols-outlined text-2xl">arrow_back_ios_new</span>
        </button>

        <img :src="photos[activePhoto]" class="max-h-[85vh] max-w-[90vw] rounded-2xl object-contain shadow-2xl transition-transform duration-300" alt="Gallery Photo">

        <button type="button" x-show="photos.length > 1" @click="activePhoto = activePhoto === photos.length - 1 ? 0 : activePhoto + 1" class="absolute right-6 rounded-full bg-white/10 p-4 text-white transition hover:bg-white hover:text-black">
            <span class="material-symbols-outlined text-2xl">arrow_forward_ios</span>
        </button>

        <div class="absolute bottom-8 rounded-full bg-white/10 px-5 py-2.5 text-sm font-black tracking-widest text-white backdrop-blur-md">
            <span x-text="activePhoto + 1"></span> / <span x-text="photos.length"></span>
        </div>
    </div>

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }
        [x-cloak] { display: none !important; }
    </style>
</div>