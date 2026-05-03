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
    $facilityGroups = [];

    if ($isKosan) {
        foreach ($roomTypes as $roomType) {
            $items = $splitList($roomType->fasilitas_tipe);
            if ($items !== []) {
                $facilityGroups[] = [
                    'title' => $roomType->nama_tipe,
                    'items' => $items,
                ];
            }
        }
    } else {
        $items = $splitList($properti->fasilitas);
        if ($items !== []) {
            $facilityGroups[] = [
                'title' => 'Fasilitas Kontrakan',
                'items' => $items,
            ];
        }
    }

    $overviewFacilities = collect($facilityGroups)
        ->flatMap(fn (array $group) => $group['items'])
        ->unique()
        ->take(6)
        ->values();

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

<div class="min-h-screen bg-[#F8FAFC] pb-16">
    <div
        class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8"
        x-data="{ galleryOpen: false, activePhoto: 0, photos: @js($galleryPhotos) }"
        x-on:keydown.escape.window="galleryOpen = false"
    >
        <nav class="mb-5 flex flex-wrap items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('home') }}" class="font-medium transition hover:text-[#1967d2]">Beranda</a>
            <span class="material-symbols-outlined text-base">chevron_right</span>
            <a href="{{ route('cari') }}" class="font-medium transition hover:text-[#1967d2]">Cari Properti</a>
            <span class="material-symbols-outlined text-base">chevron_right</span>
            <span class="font-semibold text-slate-900">{{ $properti->nama_properti }}</span>
        </nav>

        <section class="mb-6 grid gap-6 lg:grid-cols-[minmax(0,1fr)_360px] lg:items-end">
            <div class="animate-[detailFadeUp_.55s_ease-out_both]">
                <div class="mb-3 flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center rounded-full bg-[#1967d2] px-3 py-1 text-xs font-bold uppercase tracking-wide text-white">{{ $categoryLabel }}</span>
                    <span class="inline-flex items-center gap-1 rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-700 ring-1 ring-slate-200">
                        <span class="material-symbols-outlined text-sm text-[#1967d2]">star</span>
                        {{ number_format($ratingAverage, 1) }} dari {{ $reviewCount }} ulasan
                    </span>
                    <span class="inline-flex items-center rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-700 ring-1 ring-slate-200">
                        {{ $availableRooms > 0 ? $availableRooms.' unit tersedia' : 'Penuh' }}
                    </span>
                </div>
                <h1 class="max-w-4xl text-3xl font-extrabold leading-tight text-slate-950 sm:text-4xl">{{ $properti->nama_properti }}</h1>
                <p class="mt-3 flex max-w-4xl items-start gap-2 text-sm leading-6 text-slate-600">
                    <span class="material-symbols-outlined mt-0.5 text-lg text-[#1967d2]">location_on</span>
                    <span>{{ $properti->alamat_lengkap }}</span>
                </p>
            </div>

            <div class="flex flex-wrap gap-3 lg:justify-end animate-[detailFadeUp_.65s_ease-out_both]">
                @if($mapUrl)
                    <a href="{{ $mapUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-800 shadow-sm transition duration-300 hover:-translate-y-0.5 hover:border-[#1967d2] hover:text-[#1967d2] hover:shadow-md">
                        <span class="material-symbols-outlined text-lg">map</span>
                        Buka Maps
                    </a>
                @endif
                @if($ownerEmail)
                    <a href="mailto:{{ $ownerEmail }}?subject={{ rawurlencode('Tanya properti '.$properti->nama_properti) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-800 shadow-sm transition duration-300 hover:-translate-y-0.5 hover:border-[#1967d2] hover:text-[#1967d2] hover:shadow-md">
                        <span class="material-symbols-outlined text-lg">mail</span>
                        Email Mitra
                    </a>
                @endif
            </div>
        </section>

        <section class="relative mb-10 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 animate-[detailFadeUp_.75s_ease-out_both]">
            <div class="grid gap-1 md:h-[540px] md:max-h-[72vh] md:min-h-[360px] md:grid-cols-[1.35fr_.65fr]">
                <button type="button" class="group relative h-[320px] overflow-hidden bg-slate-100 text-left sm:h-[380px] md:h-full" @click="photos.length && (galleryOpen = true, activePhoto = 0)">
                    @if($mainPhoto)
                        <img src="{{ $mainPhoto }}" alt="Foto utama {{ $properti->nama_properti }}" class="h-full w-full object-cover transition duration-700 ease-out group-hover:scale-[1.035]">
                    @else
                        <div class="flex h-full min-h-[280px] flex-col items-center justify-center gap-3 text-slate-400">
                            <span class="material-symbols-outlined text-6xl">image</span>
                            <span class="text-sm font-semibold">Mitra belum mengunggah foto properti.</span>
                        </div>
                    @endif
                </button>

                <div class="grid h-[240px] grid-cols-2 gap-1 md:h-full md:grid-cols-1 md:grid-rows-2">
                    @php
                        $frontPhotos = $photos->skip(1)->take(2)->values();
                    @endphp

                    @for($index = 0; $index < 2; $index++)
                        @php
                            $photo = $frontPhotos->get($index);
                            $photoIndex = $index + 1;
                        @endphp

                        @if($photo)
                            <button type="button" class="group relative overflow-hidden bg-slate-100" @click="galleryOpen = true; activePhoto = {{ $photoIndex }}">
                                <img src="{{ $photo }}" alt="Foto {{ $photoIndex + 1 }} {{ $properti->nama_properti }}" class="h-full w-full object-cover transition duration-700 ease-out group-hover:scale-[1.04]">
                                @if($index === 1 && $photos->count() > 3)
                                    <div class="absolute inset-0 flex items-center justify-center bg-[#1967d2]/70 text-white transition duration-300 group-hover:bg-[#1967d2]/80">
                                        <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-sm font-extrabold backdrop-blur-md">
                                            <span class="material-symbols-outlined text-lg">photo_library</span>
                                            +{{ $photos->count() - 3 }} foto
                                        </span>
                                    </div>
                                @endif
                            </button>
                        @else
                            <div class="flex items-center justify-center bg-slate-100 text-sm font-semibold text-slate-400">
                                Foto tambahan belum tersedia
                            </div>
                        @endif
                    @endfor
                </div>
            </div>

            @if($photos->isNotEmpty())
                <div class="pointer-events-none absolute bottom-0 left-0 right-0 flex items-center justify-between bg-gradient-to-t from-slate-950/60 to-transparent p-4 pt-16 md:p-6 md:pt-24">
                    <span class="text-sm font-bold text-white drop-shadow-md">{{ $photos->count() }} foto dari mitra</span>
                    <button type="button" class="pointer-events-auto inline-flex items-center gap-2 rounded-xl bg-white/95 px-4 py-2.5 text-sm font-bold text-slate-900 shadow-lg backdrop-blur-md transition duration-300 hover:-translate-y-0.5 hover:bg-white hover:text-[#1967d2] hover:shadow-xl hover:shadow-slate-900/20" @click="galleryOpen = true; activePhoto = 0">
                        <span class="material-symbols-outlined text-lg">grid_view</span>
                        Lihat Semua Foto
                    </button>
                </div>
            @endif
        </section>

        <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_380px] lg:items-start">
            <main class="space-y-6">
                <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition duration-300 hover:-translate-y-0.5 hover:shadow-md">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-wide text-[#1967d2]">Detail Properti</p>
                            <h2 class="mt-1 text-2xl font-extrabold text-slate-950">Data yang Diinput Mitra</h2>
                        </div>
                        <img src="{{ $ownerAvatarUrl }}" alt="{{ $ownerName }}" class="h-14 w-14 rounded-full object-cover ring-2 ring-white" onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ rawurlencode($ownerName) }}&color=113C7A&background=EBF4FF';">
                    </div>

                    <dl class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-xl bg-slate-50 p-4">
                            <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">Nama Properti</dt>
                            <dd class="mt-1 font-semibold text-slate-950">{{ $properti->nama_properti }}</dd>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-4">
                            <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">Kategori</dt>
                            <dd class="mt-1 font-semibold text-slate-950">{{ $categoryLabel }}</dd>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-4">
                            <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">Pemilik</dt>
                            <dd class="mt-1 font-semibold text-slate-950">{{ $ownerName }}</dd>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-4">
                            <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">Koordinat</dt>
                            <dd class="mt-1 font-semibold text-slate-950">{{ filled($properti->latitude) && filled($properti->longitude) ? $properti->latitude.', '.$properti->longitude : '-' }}</dd>
                        </div>
                    </dl>

                    <div class="mt-5 rounded-xl bg-slate-50 p-4">
                        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Alamat Lengkap</p>
                        <p class="mt-2 leading-7 text-slate-700">{{ $properti->alamat_lengkap }}</p>
                    </div>
                </section>

                <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition duration-300 hover:-translate-y-0.5 hover:shadow-md">
                    <h2 class="text-2xl font-extrabold text-slate-950">Fasilitas</h2>
                    @if($overviewFacilities->isNotEmpty())
                        <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach($overviewFacilities as $facility)
                                <div class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 transition duration-300 hover:-translate-y-0.5 hover:border-blue-100 hover:bg-white hover:shadow-sm">
                                    <span class="material-symbols-outlined text-xl text-[#1967d2]">check_circle</span>
                                    <span class="text-sm font-semibold text-slate-800">{{ $facility }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-4 rounded-xl bg-slate-50 p-4 text-sm font-medium text-slate-500">Mitra belum mengisi fasilitas tambahan.</p>
                    @endif

                    @if(count($facilityGroups) > 0)
                        <div class="mt-6 space-y-4">
                            @foreach($facilityGroups as $group)
                                <div class="rounded-xl border border-slate-100 p-4">
                                    <h3 class="font-bold text-slate-950">{{ $group['title'] }}</h3>
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach($group['items'] as $item)
                                            <span class="rounded-full bg-slate-100 px-3 py-1 text-sm font-medium text-slate-700">{{ $item }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>

                @if($isKosan)
                    <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition duration-300 hover:-translate-y-0.5 hover:shadow-md">
                        <div class="flex flex-wrap items-end justify-between gap-3">
                            <div>
                                <h2 class="text-2xl font-extrabold text-slate-950">Tipe dan Nomor Kamar</h2>
                                <p class="mt-1 text-sm text-slate-500">Pilih tipe kamar lalu pilih nomor kamar yang masih tersedia.</p>
                            </div>
                            <span class="rounded-full bg-blue-50 px-3 py-1 text-sm font-bold text-[#1967d2]">{{ $availableRooms }} kamar tersedia</span>
                        </div>

                        @if($roomTypes->isNotEmpty())
                            <div class="mt-5 grid gap-4 md:grid-cols-2">
                                @foreach($roomTypes as $roomType)
                                    @php
                                        $interiorPhoto = $roomType->getMediaDisplayUrl('foto_interior');
                                        $roomAvailableCount = $roomType->kamar->where('status_kamar', 'tersedia')->count();
                                    @endphp
                                    <button type="button" wire:click="$set('selectedTipeKamarId', {{ $roomType->id }})" class="overflow-hidden rounded-2xl border-2 text-left transition duration-300 hover:-translate-y-1 {{ $selectedTipeKamarId === $roomType->id ? 'border-[#1967d2] bg-blue-50/60 shadow-md shadow-blue-500/10' : 'border-slate-100 bg-white hover:border-sky-300 hover:shadow-md' }}">
                                        @if($interiorPhoto)
                                            <img src="{{ $interiorPhoto }}" alt="Interior {{ $roomType->nama_tipe }}" class="h-40 w-full object-cover">
                                        @endif
                                        <div class="p-4">
                                            <div class="flex items-start justify-between gap-3">
                                                <div>
                                                    <h3 class="font-extrabold text-slate-950">{{ $roomType->nama_tipe }}</h3>
                                                    <p class="mt-1 text-sm font-semibold text-[#1967d2]">Rp {{ number_format((int) $roomType->harga_per_bulan, 0, ',', '.') }} / bulan</p>
                                                </div>
                                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600">{{ $roomAvailableCount }} tersedia</span>
                                            </div>
                                            <p class="mt-3 line-clamp-2 text-sm leading-6 text-slate-600">{{ $roomType->fasilitas_tipe }}</p>
                                        </div>
                                    </button>
                                @endforeach
                            </div>

                            <div class="mt-6 rounded-2xl border border-slate-100 bg-slate-50 p-5">
                                <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                                    <h3 class="text-lg font-extrabold text-slate-950">Nomor Kamar {{ $activeType?->nama_tipe }}</h3>
                                    <div class="flex flex-wrap items-center gap-3 text-xs font-semibold text-slate-500">
                                        <span class="inline-flex items-center gap-1"><span class="h-3 w-3 rounded bg-white ring-1 ring-slate-300"></span>Tersedia</span>
                                        <span class="inline-flex items-center gap-1"><span class="h-3 w-3 rounded bg-[#1967d2]"></span>Dipilih</span>
                                        <span class="inline-flex items-center gap-1"><span class="h-3 w-3 rounded bg-slate-300"></span>Tidak tersedia</span>
                                    </div>
                                </div>

                                @if($activeType && $activeType->kamar->isNotEmpty())
                                    <div class="grid grid-cols-4 gap-3 sm:grid-cols-6 lg:grid-cols-8">
                                        @foreach($activeType->kamar as $room)
                                            @if($room->status_kamar === 'tersedia')
                                                <button type="button" wire:click="selectKamar({{ $room->id }}, '{{ $room->status_kamar }}')" class="aspect-square rounded-xl text-sm font-extrabold transition duration-300 hover:-translate-y-0.5 {{ $selectedKamarId === $room->id ? 'bg-[#1967d2] text-white shadow-lg shadow-blue-500/20 ring-2 ring-blue-200' : 'bg-white text-slate-800 ring-1 ring-slate-200 hover:text-[#1967d2] hover:ring-[#1967d2] hover:shadow-sm' }}">
                                                    {{ $room->nomor_kamar }}
                                                </button>
                                            @else
                                                <div class="flex aspect-square items-center justify-center rounded-xl bg-slate-200 text-sm font-bold text-slate-400">
                                                    {{ $room->nomor_kamar }}
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <p class="rounded-xl bg-white p-4 text-sm font-medium text-slate-500">Belum ada nomor kamar untuk tipe ini.</p>
                                @endif
                            </div>
                        @else
                            <p class="mt-4 rounded-xl bg-slate-50 p-4 text-sm font-medium text-slate-500">Mitra belum menambahkan tipe kamar.</p>
                        @endif
                    </section>
                @endif

                <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition duration-300 hover:-translate-y-0.5 hover:shadow-md">
                    <h2 class="text-2xl font-extrabold text-slate-950">Peraturan</h2>
                    @if($rules !== [])
                        <ul class="mt-5 space-y-3">
                            @foreach($rules as $rule)
                                <li class="flex items-start gap-3 rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-700">
                                    <span class="material-symbols-outlined mt-0.5 text-lg text-[#1967d2]">rule</span>
                                    <span>{{ $rule }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mt-4 rounded-xl bg-slate-50 p-4 text-sm font-medium text-slate-500">Mitra belum menambahkan peraturan khusus.</p>
                    @endif
                </section>

                <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition duration-300 hover:-translate-y-0.5 hover:shadow-md">
                    <h2 class="flex items-center gap-2 text-2xl font-extrabold text-slate-950">
                        <span class="material-symbols-outlined text-2xl text-[#1967d2]">star</span>
                        {{ number_format($ratingAverage, 1) }} · {{ $reviewCount }} ulasan
                    </h2>

                    @if($reviewCount > 0)
                        <div class="mt-6 grid gap-5 md:grid-cols-2">
                            @foreach($properti->ulasan->take(6) as $review)
                                @php
                                    $reviewer = $review->pencariKos?->user;
                                @endphp
                                <article class="rounded-2xl border border-slate-100 p-5">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $this->profilePhotoUrlFor($reviewer, 'Pengguna') }}" alt="{{ $reviewer?->name ?? 'Pengguna' }}" class="h-11 w-11 rounded-full object-cover" onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ rawurlencode($reviewer?->name ?? 'Pengguna') }}&color=113C7A&background=EBF4FF';">
                                        <div>
                                            <h3 class="font-bold text-slate-950">{{ $reviewer?->name ?? 'Pengguna' }}</h3>
                                            <p class="text-xs font-medium text-slate-500">{{ $review->created_at?->translatedFormat('F Y') ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-3 flex gap-0.5 text-[#1967d2]">
                                        @for($star = 1; $star <= 5; $star++)
                                            <span class="material-symbols-outlined text-base">{{ $star <= (int) $review->rating ? 'star' : 'star_outline' }}</span>
                                        @endfor
                                    </div>
                                    <p class="mt-3 text-sm leading-6 text-slate-600">{{ $review->komentar }}</p>
                                    @if(filled($review->balasan_pemilik))
                                        <div class="mt-4 rounded-xl bg-blue-50 p-3 text-sm leading-6 text-slate-700">
                                            <span class="font-bold text-[#1967d2]">Balasan mitra:</span> {{ $review->balasan_pemilik }}
                                        </div>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-4 rounded-xl bg-slate-50 p-4 text-sm font-medium text-slate-500">Belum ada ulasan untuk properti ini.</p>
                    @endif
                </section>
            </main>

            <aside class="lg:sticky lg:top-24">
                <div class="overflow-hidden rounded-2xl bg-white shadow-xl shadow-slate-900/10 ring-1 ring-slate-100">
                    <div class="bg-[#1967d2] p-6 text-white">
                        <div class="mb-5 flex items-center gap-3">
                            <img src="{{ $ownerAvatarUrl }}" alt="{{ $ownerName }}" class="h-12 w-12 rounded-full object-cover ring-2 ring-white/30" onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ rawurlencode($ownerName) }}&color=113C7A&background=EBF4FF';">
                            <div class="min-w-0">
                                <p class="text-xs font-semibold uppercase tracking-wide text-white/75">Disewakan oleh</p>
                                <p class="truncate text-sm font-extrabold">{{ $ownerName }}</p>
                            </div>
                        </div>
                        <p class="text-sm font-semibold text-white/80">Mulai dari</p>
                        @if($currentPrice > 0)
                            <p class="mt-1 text-3xl font-extrabold">Rp {{ number_format($currentPrice, 0, ',', '.') }}</p>
                            <p class="text-sm font-semibold text-white/80">/{{ $isKosan ? 'bulan' : 'tahun' }}</p>
                        @else
                            <p class="mt-1 text-xl font-extrabold">Harga belum tersedia</p>
                        @endif
                    </div>

                    <form wire:submit.prevent="buatBooking" class="space-y-4 p-6">
                        <div class="space-y-3 rounded-xl bg-slate-50 p-4">
                            @if($isKosan)
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-sm font-semibold text-slate-500">Tipe Kamar</span>
                                    <span class="text-right text-sm font-bold text-slate-950">{{ $activeType?->nama_tipe ?? '-' }}</span>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-sm font-semibold text-slate-500">Nomor Kamar</span>
                                    <span class="text-right text-sm font-bold text-[#1967d2]">{{ $selectedRoom ? 'Kamar '.$selectedRoom->nomor_kamar : 'Pilih dulu' }}</span>
                                </div>
                            @else
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-sm font-semibold text-slate-500">Unit Tersedia</span>
                                    <span class="text-sm font-bold text-[#1967d2]">{{ $availableRooms }} unit</span>
                                </div>
                            @endif
                            <div class="flex items-center justify-between gap-3 border-t border-slate-200 pt-3">
                                <span class="text-sm font-semibold text-slate-500">Estimasi Total</span>
                                <span class="text-right text-base font-extrabold text-slate-950">Rp {{ number_format($estimatedTotal, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div>
                            <label for="tanggalCheckIn" class="mb-2 block text-sm font-bold text-slate-700">Tanggal Masuk</label>
                            <div class="group flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm transition duration-300 focus-within:border-[#1967d2] focus-within:ring-4 focus-within:ring-[#1967d2]/10 hover:border-[#1967d2]/60">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#1967d2]/10 text-[#1967d2] transition duration-300 group-focus-within:bg-[#1967d2] group-focus-within:text-white">
                                    <span class="material-symbols-outlined text-xl">calendar_month</span>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400">Mulai sewa</p>
                                    <input id="tanggalCheckIn" type="date" min="{{ now()->toDateString() }}" wire:model.live="tanggalCheckIn" class="mt-0.5 w-full border-none bg-transparent p-0 text-sm font-extrabold text-slate-950 outline-none focus:ring-0">
                                </div>
                            </div>
                            @error('tanggalCheckIn') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <div class="mb-2 flex items-center justify-between gap-3">
                                <label class="block text-sm font-bold text-slate-700">Durasi Sewa</label>
                                <span class="text-xs font-bold text-[#1967d2]">{{ $isKosan ? 'Bulanan' : 'Tahunan' }}</span>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-1.5 shadow-sm">
                                <div class="grid gap-1.5 {{ $isKosan ? 'grid-cols-2' : 'grid-cols-3' }}">
                                    @foreach($durationOptions as $option)
                                        @php
                                            $isSelectedDuration = (int) $durasiSewa === $option['value'];
                                        @endphp
                                        <button
                                            type="button"
                                            wire:click="$set('durasiSewa', {{ $option['value'] }})"
                                            class="min-h-11 rounded-xl px-3 py-2 text-sm font-extrabold transition duration-300 {{ $isSelectedDuration ? 'bg-[#1967d2] text-white shadow-md shadow-blue-500/20' : 'bg-white text-slate-600 ring-1 ring-slate-100 hover:text-[#1967d2] hover:ring-[#1967d2]/30' }}"
                                        >
                                            {{ $option['label'] }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            @error('durasiSewa') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                        </div>

                        @if (session()->has('error'))
                            <div class="rounded-xl border border-red-100 bg-red-50 p-4 text-sm font-semibold text-red-700">
                                {{ session('error') }}
                            </div>
                        @endif

                        @php
                            $bookingDisabled = ($isKosan && ! $selectedRoom) || (! $isKosan && $availableRooms < 1) || $currentPrice < 1;
                        @endphp

                        @auth
                            <button type="submit" @disabled($bookingDisabled) class="group relative inline-flex w-full items-center justify-center overflow-hidden rounded-xl px-4 py-4 text-base font-extrabold text-white shadow-lg transition duration-300 {{ $bookingDisabled ? 'cursor-not-allowed bg-slate-300 shadow-none' : 'bg-[#1967d2] shadow-blue-500/25 hover:-translate-y-0.5 hover:bg-[#0f4fb5] hover:shadow-xl hover:shadow-blue-500/30' }}">
                                @unless($bookingDisabled)
                                    <span class="absolute inset-y-0 -left-1/2 w-1/2 skew-x-[-18deg] bg-white/20 transition-transform duration-700 group-hover:translate-x-[320%]"></span>
                                @endunless
                                <span class="material-symbols-outlined text-xl">payments</span>
                                <span>Ajukan Sewa</span>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="group relative inline-flex w-full items-center justify-center overflow-hidden rounded-xl bg-[#1967d2] px-4 py-4 text-base font-extrabold text-white shadow-lg shadow-blue-500/20 transition duration-300 hover:-translate-y-0.5 hover:bg-[#0f4fb5] hover:shadow-xl hover:shadow-blue-500/30">
                                <span class="absolute inset-y-0 -left-1/2 w-1/2 skew-x-[-18deg] bg-white/20 transition-transform duration-700 group-hover:translate-x-[320%]"></span>
                                <span class="material-symbols-outlined text-xl">login</span>
                                <span>Masuk untuk Menyewa</span>
                            </a>
                        @endauth

                        <p class="text-center text-xs font-medium leading-5 text-slate-500">Anda akan diarahkan ke halaman checkout untuk melengkapi data sewa dan pembayaran.</p>
                    </form>
                </div>
            </aside>
        </div>

        @if($photos->isNotEmpty())
            <div
                x-cloak
                x-show="galleryOpen"
                class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/90 p-4"
                x-transition.opacity
            >
                <button type="button" class="absolute right-4 top-4 rounded-full bg-white/10 p-3 text-white transition hover:bg-white/20" @click="galleryOpen = false" aria-label="Tutup galeri">
                    <span class="material-symbols-outlined">close</span>
                </button>

                <button type="button" x-show="photos.length > 1" class="absolute left-4 rounded-full bg-white/10 p-3 text-white transition hover:bg-white/20" @click="activePhoto = activePhoto === 0 ? photos.length - 1 : activePhoto - 1" aria-label="Foto sebelumnya">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>

                <img :src="photos[activePhoto]" alt="Galeri foto properti" class="max-h-[82vh] max-w-full rounded-xl object-contain shadow-2xl">

                <button type="button" x-show="photos.length > 1" class="absolute right-4 rounded-full bg-white/10 p-3 text-white transition hover:bg-white/20" @click="activePhoto = activePhoto === photos.length - 1 ? 0 : activePhoto + 1" aria-label="Foto berikutnya">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>

                <div class="absolute bottom-4 rounded-full bg-white/10 px-4 py-2 text-sm font-bold text-white">
                    <span x-text="activePhoto + 1"></span> / <span x-text="photos.length"></span>
                </div>
            </div>
        @endif
    </div>

    <style>
        @keyframes detailFadeUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</div>
