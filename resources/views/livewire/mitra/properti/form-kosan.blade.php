@section('mitra-title', $editId ? 'Edit Profil Kosan' : 'Tambah Profil Kosan')
@section('mitra-subtitle', 'Lengkapi profil dasar kos sebelum mengatur tipe kamar dan nomor unit.')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
@endpush

<div class="px-4 py-8 sm:px-6 xl:px-8">
    <div class="mx-auto w-full max-w-6xl space-y-6 pb-16">
        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.24em] text-[#0F4C81] dark:bg-blue-950/40 dark:text-blue-300">
                    <span class="material-symbols-outlined text-[16px]">apartment</span>
                    Langkah 1 dari 2
                </div>

                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">
                        {{ $editId ? 'Perbarui Profil Dasar Kosan' : 'Buat Profil Dasar Kosan' }}
                    </h3>
                    <p class="mt-1 max-w-3xl text-sm leading-6 text-slate-500 dark:text-slate-400">
                        Simpan nama properti, alamat, titik lokasi, peraturan kos, dan foto utama. Setelah itu Anda akan diarahkan ke halaman kelola unit untuk menambahkan tipe kamar dan nomor kamar spesifik.
                    </p>
                </div>
            </div>

            <a
                href="{{ route('mitra.properti') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-slate-700 dark:text-slate-200 dark:hover:border-blue-400 dark:hover:text-blue-300">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Kembali ke Properti Saya
            </a>
        </div>

        <form wire:submit.prevent="simpan" class="space-y-6">
            <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_340px]">
                <section class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="border-b border-slate-100 px-6 py-5 dark:border-slate-800">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[#0F4C81] dark:text-blue-300">Informasi Utama</p>
                        <h3 class="mt-2 text-lg font-bold text-slate-900 dark:text-white">Detail Properti Kos</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Gunakan informasi yang jelas agar properti mudah dikenali calon penyewa.</p>
                    </div>

                    <div class="space-y-6 px-6 py-6">
                        <div>
                            <label for="nama_properti" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Nama Properti <span class="text-rose-600">*</span></label>
                            <input
                                id="nama_properti"
                                type="text"
                                wire:model.defer="nama_properti"
                                class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                placeholder="Contoh: Kos An-Nur">
                            @error('nama_properti') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        @if ($tipe_properti === 'kosan')
                        <div>
                            <label for="jenis_kos" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">
                                Kategori Kos <span class="text-rose-600">*</span>
                            </label>
                            <select
                                id="jenis_kos"
                                wire:model.defer="jenis_kos"
                                class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400">
                                <option value="">-- Pilih Kategori Kos --</option>
                                <option value="putra">Kos Putra</option>
                                <option value="putri">Kos Putri</option>
                                <option value="campur">Kos Campur</option>
                            </select>
                            @error('jenis_kos') <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        @endif

                        <div>
                            <label for="alamat_lengkap" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Alamat Lengkap <span class="text-rose-600">*</span></label>
                            <textarea
                                id="alamat_lengkap"
                                rows="5"
                                wire:model.defer="alamat_lengkap"
                                class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                placeholder="Masukkan alamat lengkap properti"></textarea>
                            @error('alamat_lengkap') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="peraturan_kos" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Peraturan Kos <span class="text-rose-600">*</span></label>
                            <textarea
                                id="peraturan_kos"
                                rows="5"
                                wire:model.defer="peraturan_kos"
                                class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                placeholder="Contoh: Tidak menerima tamu menginap, jam malam pukul 22.00, wajib menjaga kebersihan."></textarea>
                            @error('peraturan_kos') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="fasilitas_umum" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Fasilitas Umum (Opsional)</label>
                            <textarea
                                id="fasilitas_umum"
                                rows="5"
                                wire:model.defer="fasilitas_umum"
                                class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                placeholder="Pisahkan dengan koma atau baris baru. Contoh: WiFi Cepat, Dapur Bersama, Area Parkir Luas, CCTV 24 Jam"></textarea>
                            @error('fasilitas_umum') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>

                <div class="space-y-6">
                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#0F4C81] dark:text-blue-300">Foto Properti</p>
                                <h3 class="mt-1 text-base font-bold text-slate-900 dark:text-white">Foto Kosan</h3>
                            </div>
                        </div>

                        <div class="mt-4 space-y-3">
                            <div class="flex flex-col gap-3">
                                <!-- Foto 1: Utama -->
                                <div class="group relative flex flex-col gap-2 rounded-xl border border-slate-100 bg-slate-50/50 p-3 transition-all hover:border-blue-200 hover:bg-blue-50/30 dark:border-slate-800 dark:bg-slate-950/40">
                                    <div class="flex items-center justify-between">
                                        <label for="foto_1" class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Foto Utama *</label>
                                        <div class="flex items-center gap-2">
                                            @if ($foto_1 || isset($existingPhotoUrls[0]))
                                                <button type="button" wire:click="hapusFoto(1)" class="flex h-5 w-5 items-center justify-center rounded-full bg-rose-50 text-rose-600 transition hover:bg-rose-100">
                                                    <span class="material-symbols-outlined text-[14px]">close</span>
                                                </button>
                                                <span class="flex items-center gap-1 text-[10px] font-medium text-emerald-600">
                                                    <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                                    Ok
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1">
                                            <input
                                                id="foto_1"
                                                type="file"
                                                wire:model="foto_1"
                                                accept=".jpg,.jpeg,.png,.webp"
                                                class="block w-full text-[11px] text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-[#0F4C81] file:px-3 file:py-1.5 file:text-[10px] file:font-bold file:text-white transition hover:file:bg-[#0c3d68]">
                                        </div>
                                        <div class="shrink-0">
                                            @if ($foto_1)
                                                <img src="{{ $foto_1->temporaryUrl() }}" class="h-12 w-16 rounded-lg border border-white object-cover shadow-sm ring-1 ring-slate-200">
                                            @elseif(isset($existingPhotoUrls[0]))
                                                <img src="{{ $existingPhotoUrls[0] }}" class="h-12 w-16 rounded-lg border border-white object-cover shadow-sm ring-1 ring-slate-200">
                                            @else
                                                <div class="flex h-12 w-16 items-center justify-center rounded-full border border-dashed border-slate-300 bg-white text-slate-400">
                                                    <span class="material-symbols-outlined text-[18px]">image</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @error('foto_1') <p class="text-[10px] font-medium text-rose-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Foto 2: Samping -->
                                <div class="group relative flex flex-col gap-2 rounded-xl border border-slate-100 bg-slate-50/50 p-3 transition-all hover:border-blue-200 hover:bg-blue-50/30 dark:border-slate-800 dark:bg-slate-950/40">
                                    <div class="flex items-center justify-between">
                                        <label for="foto_2" class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Samping (Opsional)</label>
                                        @if ($foto_2 || isset($existingPhotoUrls[1]))
                                            <button type="button" wire:click="hapusFoto(2)" class="flex h-5 w-5 items-center justify-center rounded-full bg-rose-50 text-rose-600 transition hover:bg-rose-100">
                                                <span class="material-symbols-outlined text-[14px]">close</span>
                                            </button>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1">
                                            <input
                                                id="foto_2"
                                                type="file"
                                                wire:model="foto_2"
                                                accept=".jpg,.jpeg,.png,.webp"
                                                class="block w-full text-[11px] text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-200 file:px-3 file:py-1.5 file:text-[10px] file:font-bold file:text-slate-700 transition hover:file:bg-slate-300">
                                        </div>
                                        <div class="shrink-0">
                                            @if ($foto_2)
                                                <img src="{{ $foto_2->temporaryUrl() }}" class="h-12 w-16 rounded-lg border border-white object-cover shadow-sm ring-1 ring-slate-200">
                                            @elseif(isset($existingPhotoUrls[1]))
                                                <img src="{{ $existingPhotoUrls[1] }}" class="h-12 w-16 rounded-lg border border-white object-cover shadow-sm ring-1 ring-slate-200">
                                            @else
                                                <div class="flex h-12 w-16 items-center justify-center rounded-full border border-dashed border-slate-300 bg-white text-slate-400">
                                                    <span class="material-symbols-outlined text-[18px]">image</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @error('foto_2') <p class="text-[10px] font-medium text-rose-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Foto 3: Dalam -->
                                <div class="group relative flex flex-col gap-2 rounded-xl border border-slate-100 bg-slate-50/50 p-3 transition-all hover:border-blue-200 hover:bg-blue-50/30 dark:border-slate-800 dark:bg-slate-950/40">
                                    <div class="flex items-center justify-between">
                                        <label for="foto_3" class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Dalam (Opsional)</label>
                                        @if ($foto_3 || isset($existingPhotoUrls[2]))
                                            <button type="button" wire:click="hapusFoto(3)" class="flex h-5 w-5 items-center justify-center rounded-full bg-rose-50 text-rose-600 transition hover:bg-rose-100">
                                                <span class="material-symbols-outlined text-[14px]">close</span>
                                            </button>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1">
                                            <input
                                                id="foto_3"
                                                type="file"
                                                wire:model="foto_3"
                                                accept=".jpg,.jpeg,.png,.webp"
                                                class="block w-full text-[11px] text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-200 file:px-3 file:py-1.5 file:text-[10px] file:font-bold file:text-slate-700 transition hover:file:bg-slate-300">
                                        </div>
                                        <div class="shrink-0">
                                            @if ($foto_3)
                                                <img src="{{ $foto_3->temporaryUrl() }}" class="h-12 w-16 rounded-lg border border-white object-cover shadow-sm ring-1 ring-slate-200">
                                            @elseif(isset($existingPhotoUrls[2]))
                                                <img src="{{ $existingPhotoUrls[2] }}" class="h-12 w-16 rounded-lg border border-white object-cover shadow-sm ring-1 ring-slate-200">
                                            @else
                                                <div class="flex h-12 w-16 items-center justify-center rounded-full border border-dashed border-slate-300 bg-white text-slate-400">
                                                    <span class="material-symbols-outlined text-[18px]">image</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @error('foto_3') <p class="text-[10px] font-medium text-rose-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Foto 4: Fasilitas -->
                                <div class="group relative flex flex-col gap-2 rounded-xl border border-slate-100 bg-slate-50/50 p-3 transition-all hover:border-blue-200 hover:bg-blue-50/30 dark:border-slate-800 dark:bg-slate-950/40">
                                    <div class="flex items-center justify-between">
                                        <label for="foto_4" class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Fasilitas (Opsional)</label>
                                        @if ($foto_4 || isset($existingPhotoUrls[3]))
                                            <button type="button" wire:click="hapusFoto(4)" class="flex h-5 w-5 items-center justify-center rounded-full bg-rose-50 text-rose-600 transition hover:bg-rose-100">
                                                <span class="material-symbols-outlined text-[14px]">close</span>
                                            </button>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1">
                                            <input
                                                id="foto_4"
                                                type="file"
                                                wire:model="foto_4"
                                                accept=".jpg,.jpeg,.png,.webp"
                                                class="block w-full text-[11px] text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-200 file:px-3 file:py-1.5 file:text-[10px] file:font-bold file:text-slate-700 transition hover:file:bg-slate-300">
                                        </div>
                                        <div class="shrink-0">
                                            @if ($foto_4)
                                                <img src="{{ $foto_4->temporaryUrl() }}" class="h-12 w-16 rounded-lg border border-white object-cover shadow-sm ring-1 ring-slate-200">
                                            @elseif(isset($existingPhotoUrls[3]))
                                                <img src="{{ $existingPhotoUrls[3] }}" class="h-12 w-16 rounded-lg border border-white object-cover shadow-sm ring-1 ring-slate-200">
                                            @else
                                                <div class="flex h-12 w-16 items-center justify-center rounded-full border border-dashed border-slate-300 bg-white text-slate-400">
                                                    <span class="material-symbols-outlined text-[18px]">image</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @error('foto_4') <p class="text-[10px] font-medium text-rose-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <p class="text-[10px] leading-relaxed text-slate-400">
                                Maksimal 2MB, format JPG/PNG/WEBP. Unggahan baru akan menggantikan foto lama.
                            </p>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[#0F4C81] dark:text-blue-300">Langkah Berikutnya</p>
                        <div class="mt-3 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                            <div class="flex items-start gap-3">
                                <span class="mt-1 h-2.5 w-2.5 rounded-full bg-[#0F4C81] dark:bg-blue-400"></span>
                                <p>Simpan profil kos untuk membuka halaman kelola unit.</p>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="mt-1 h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                                <p>Tambahkan tipe kamar seperti Tipe AC, Tipe Standar, atau Tipe Kipas.</p>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="mt-1 h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                                <p>Isi nomor kamar spesifik agar penyewa bisa memilih unit yang tersedia.</p>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <section class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="border-b border-slate-100 px-6 py-5 dark:border-slate-800">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[#0F4C81] dark:text-blue-300">Lokasi Properti</p>
                    <h3 class="mt-2 text-lg font-bold text-slate-900 dark:text-white">Tandai Lokasi di Peta</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Gunakan pencarian atau klik langsung pada peta untuk meletakkan pin di lokasi paling akurat.</p>
                </div>

                <div class="space-y-5 px-6 py-6">
                    <div
                        wire:ignore
                        class="overflow-hidden rounded-2xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-950"
                        x-data
                        x-init="
                            setTimeout(() => {
                                const defaultLat = {{ $latitude !== '' ? (float) $latitude : -6.40690782 }};
                                const defaultLng = {{ $longitude !== '' ? (float) $longitude : 108.28776285 }};
                                const defaultZoom = {{ $latitude !== '' && $longitude !== '' ? 16 : 14 }};
                                const map = L.map($refs.mapKosan).setView([defaultLat, defaultLng], defaultZoom);

                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '&copy; OpenStreetMap contributors'
                                }).addTo(map);

                                let marker = L.marker([defaultLat, defaultLng]).addTo(map);

                                L.Control.geocoder({
                                    defaultMarkGeocode: false,
                                    placeholder: 'Cari desa, jalan, atau daerah...'
                                }).on('markgeocode', function (e) {
                                    let latlng = e.geocode.center;
                                    marker.setLatLng(latlng);
                                    map.setView(latlng, 16);
                                    $wire.set('latitude', latlng.lat.toFixed(8));
                                    $wire.set('longitude', latlng.lng.toFixed(8));
                                    $wire.set('alamat_lengkap', e.geocode.name);
                                }).addTo(map);

                                map.on('click', function (e) {
                                    marker.setLatLng(e.latlng);
                                    $wire.set('latitude', e.latlng.lat.toFixed(8));
                                    $wire.set('longitude', e.latlng.lng.toFixed(8));
                                });

                                setTimeout(() => { map.invalidateSize(); }, 500);
                            }, 100);
                        ">
                        <div id="map-kosan" x-ref="mapKosan" style="height: 420px; width: 100%; z-index: 0;"></div>
                    </div>

                    <div class="rounded-2xl border border-blue-100 bg-blue-50 px-4 py-4 text-sm leading-6 text-blue-800 dark:border-blue-900/40 dark:bg-blue-950/20 dark:text-blue-200">
                        <span class="font-semibold">Tips menandai lokasi:</span>
                        Jika alamat spesifik belum ditemukan, cari nama desa, kecamatan, atau patokan terdekat lebih dulu. Setelah peta bergeser ke area tersebut, klik langsung pada peta untuk memindahkan pin ke titik properti yang benar.
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="latitude-kosan" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Latitude <span class="text-rose-600">*</span></label>
                            <input
                                id="latitude-kosan"
                                type="text"
                                wire:model.live="latitude"
                                class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400">
                            @error('latitude') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="longitude-kosan" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Longitude <span class="text-rose-600">*</span></label>
                            <input
                                id="longitude-kosan"
                                type="text"
                                wire:model.live="longitude"
                                class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400">
                            @error('longitude') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </section>

            <div class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    {{ $editId ? 'Perubahan akan langsung memperbarui profil kosan yang sudah ada.' : 'Setelah disimpan, Anda akan langsung masuk ke halaman kelola tipe kamar dan nomor unit.' }}
                </p>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <a
                        href="{{ route('mitra.properti') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-slate-700 dark:text-slate-200 dark:hover:border-blue-400 dark:hover:text-blue-300">
                        Batal
                    </a>

                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="simpan,foto_properti"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0F4C81] px-5 py-3 text-sm font-semibold text-white shadow-md shadow-[#0F4C81]/20 transition hover:bg-[#0c3d68] disabled:cursor-not-allowed disabled:opacity-70">
                        <svg wire:loading wire:target="simpan,foto_properti" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4Z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="simpan,foto_properti">{{ $editId ? 'Perbarui & Kelola Kamar' : 'Simpan & Lanjut Kelola Kamar' }}</span>
                        <span wire:loading wire:target="simpan,foto_properti">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>