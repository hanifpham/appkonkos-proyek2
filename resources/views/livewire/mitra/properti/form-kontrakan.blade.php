@section('mitra-title', $editId ? 'Edit Kontrakan' : 'Tambah Kontrakan')
@section('mitra-subtitle', 'Atur informasi rumah kontrakan dengan presentasi yang rapi dan mudah dipahami.')

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
                <div class="inline-flex items-center gap-2 rounded-full bg-amber-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.24em] text-amber-700 dark:bg-amber-950/30 dark:text-amber-300">
                    <span class="material-symbols-outlined text-[16px]">home_work</span>
                    Listing Kontrakan
                </div>

                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">
                        {{ $editId ? 'Perbarui Data Kontrakan' : 'Buat Listing Kontrakan Baru' }}
                    </h3>
                    <p class="mt-1 max-w-3xl text-sm leading-6 text-slate-500 dark:text-slate-400">
                        Lengkapi informasi sewa tahunan, kapasitas unit, fasilitas, peraturan, lokasi, dan foto utama agar calon penyewa bisa menilai properti dengan cepat.
                    </p>
                </div>
            </div>

            <a
                href="{{ route('mitra.properti') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-slate-700 dark:text-slate-200 dark:hover:border-blue-400 dark:hover:text-blue-300"
            >
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Kembali ke Properti Saya
            </a>
        </div>

        <form wire:submit.prevent="simpan" class="space-y-6">
            <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_340px]">
                <section class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="border-b border-slate-100 px-6 py-5 dark:border-slate-800">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[#0F4C81] dark:text-blue-300">Informasi Properti</p>
                        <h3 class="mt-2 text-lg font-bold text-slate-900 dark:text-white">Detail Utama Kontrakan</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Tampilkan identitas properti, harga sewa, dan informasi hunian secara jelas.</p>
                    </div>

                    <div class="grid gap-6 px-6 py-6 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="nama_properti" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Nama Properti <span class="text-rose-600">*</span></label>
                            <input
                                id="nama_properti"
                                type="text"
                                wire:model.defer="nama_properti"
                                class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                placeholder="Contoh: Kontrakan An-Nur"
                            >
                            @error('nama_properti') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="alamat_lengkap" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Alamat Lengkap <span class="text-rose-600">*</span></label>
                            <textarea
                                id="alamat_lengkap"
                                rows="5"
                                wire:model.defer="alamat_lengkap"
                                class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                placeholder="Masukkan alamat lengkap kontrakan"
                            ></textarea>
                            @error('alamat_lengkap') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="harga_sewa_tahun" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Harga Sewa per Tahun <span class="text-rose-600">*</span></label>
                            <input
                                id="harga_sewa_tahun"
                                type="number"
                                min="0"
                                wire:model.defer="harga_sewa_tahun"
                                class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                placeholder="6000000"
                            >
                            @error('harga_sewa_tahun') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="sisa_kamar" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Unit Tersedia <span class="text-rose-600">*</span></label>
                            <input
                                id="sisa_kamar"
                                type="number"
                                min="0"
                                wire:model.defer="sisa_kamar"
                                class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                placeholder="1"
                            >
                            @error('sisa_kamar') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="fasilitas" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Fasilitas <span class="text-rose-600">*</span></label>
                            <textarea
                                id="fasilitas"
                                rows="6"
                                wire:model.defer="fasilitas"
                                class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                placeholder="Contoh: 2 kamar tidur, dapur, carport, PAM, listrik token"
                            ></textarea>
                            @error('fasilitas') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="peraturan_kontrakan" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Peraturan Kontrakan <span class="text-rose-600">*</span></label>
                            <textarea
                                id="peraturan_kontrakan"
                                rows="6"
                                wire:model.defer="peraturan_kontrakan"
                                class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                placeholder="Contoh: Tidak boleh renovasi tanpa izin, wajib menjaga kebersihan area rumah."
                            ></textarea>
                            @error('peraturan_kontrakan') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>

                <div class="space-y-6">
                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[#0F4C81] dark:text-blue-300">Foto Properti</p>
                                <h3 class="mt-2 text-lg font-bold text-slate-900 dark:text-white">Foto Utama Kontrakan</h3>
                            </div>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-medium text-slate-500 dark:bg-slate-800 dark:text-slate-300">1 file</span>
                        </div>

                        <div class="mt-5 space-y-4">
                            <input
                                id="foto_properti_kontrakan"
                                type="file"
                                wire:model="foto_properti"
                                accept=".jpg,.jpeg,.png,.webp"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm file:mr-4 file:rounded-lg file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:font-semibold file:text-[#0F4C81] hover:file:bg-blue-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200 dark:file:bg-slate-800 dark:file:text-blue-300"
                            >
                            <p class="text-xs leading-5 text-slate-500 dark:text-slate-400">Pilih foto utama yang paling mewakili tampilan kontrakan. @if ($editId === null)Kolom ini wajib diisi. @endif Maksimal 2MB, format JPG, JPEG, PNG, atau WEBP.</p>
                            @error('foto_properti') <p class="text-sm text-rose-600">{{ $message }}</p> @enderror

                            @if ($foto_properti)
                                <div class="overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700">
                                    <img src="{{ $foto_properti->temporaryUrl() }}" alt="Preview foto kontrakan" class="h-60 w-full object-cover">
                                </div>
                            @elseif ($existingPhotoUrl)
                                <div class="overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700">
                                    <img src="{{ $existingPhotoUrl }}" alt="Foto kontrakan saat ini" class="h-60 w-full object-cover">
                                </div>
                            @else
                                <div class="flex h-60 items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-slate-50 text-sm font-medium text-slate-400 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-500">
                                    Preview foto akan muncul di sini
                                </div>
                            @endif
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-amber-700 dark:text-amber-300">Catatan Listing</p>
                        <div class="mt-3 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                            <div class="flex items-start gap-3">
                                <span class="mt-1 h-2.5 w-2.5 rounded-full bg-[#0F4C81] dark:bg-blue-400"></span>
                                <p>Harga sewa ditampilkan per tahun pada halaman properti dan pencarian.</p>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="mt-1 h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                                <p>Jumlah unit tersedia akan memengaruhi status penuh atau tersedia di daftar properti.</p>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="mt-1 h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                                <p>Pastikan fasilitas dan peraturan ditulis spesifik agar mengurangi pertanyaan dari calon penyewa.</p>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <section class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="border-b border-slate-100 px-6 py-5 dark:border-slate-800">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[#0F4C81] dark:text-blue-300">Lokasi Properti</p>
                    <h3 class="mt-2 text-lg font-bold text-slate-900 dark:text-white">Tandai Lokasi Kontrakan</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Cari lokasi atau klik langsung pada peta untuk menempatkan pin properti.</p>
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
                                const map = L.map($refs.mapKontrakan).setView([defaultLat, defaultLng], defaultZoom);

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
                        "
                    >
                        <div id="map-kontrakan" x-ref="mapKontrakan" style="height: 420px; width: 100%; z-index: 0;"></div>
                    </div>

                    <div class="rounded-2xl border border-blue-100 bg-blue-50 px-4 py-4 text-sm leading-6 text-blue-800 dark:border-blue-900/40 dark:bg-blue-950/20 dark:text-blue-200">
                        <span class="font-semibold">Tips menandai lokasi:</span>
                        Jika alamat spesifik belum terdeteksi, cari wilayah terdekat terlebih dahulu, lalu klik peta pada posisi rumah kontrakan yang paling akurat.
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="latitude-kontrakan" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Latitude <span class="text-rose-600">*</span></label>
                            <input
                                id="latitude-kontrakan"
                                type="text"
                                wire:model.live="latitude"
                                class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            >
                            @error('latitude') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="longitude-kontrakan" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Longitude <span class="text-rose-600">*</span></label>
                            <input
                                id="longitude-kontrakan"
                                type="text"
                                wire:model.live="longitude"
                                class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            >
                            @error('longitude') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </section>

            <div class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    {{ $editId ? 'Perubahan akan langsung memperbarui data kontrakan yang sedang aktif.' : 'Setelah disimpan, kontrakan akan langsung muncul pada daftar properti Anda.' }}
                </p>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <a
                        href="{{ route('mitra.properti') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-slate-700 dark:text-slate-200 dark:hover:border-blue-400 dark:hover:text-blue-300"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="simpan,foto_properti"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0F4C81] px-5 py-3 text-sm font-semibold text-white shadow-md shadow-[#0F4C81]/20 transition hover:bg-[#0c3d68] disabled:cursor-not-allowed disabled:opacity-70"
                    >
                        <svg wire:loading wire:target="simpan,foto_properti" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4Z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="simpan,foto_properti">{{ $editId ? 'Perbarui Kontrakan' : 'Simpan Kontrakan' }}</span>
                        <span wire:loading wire:target="simpan,foto_properti">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
