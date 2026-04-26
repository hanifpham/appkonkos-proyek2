<?php $__env->startSection('mitra-title', $editId ? 'Edit Profil Kosan' : 'Tambah Profil Kosan'); ?>
<?php $__env->startSection('mitra-subtitle', 'Lengkapi profil dasar kos sebelum mengatur tipe kamar dan nomor unit.'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<?php $__env->stopPush(); ?>

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
                        <?php echo e($editId ? 'Perbarui Profil Dasar Kosan' : 'Buat Profil Dasar Kosan'); ?>

                    </h3>
                    <p class="mt-1 max-w-3xl text-sm leading-6 text-slate-500 dark:text-slate-400">
                        Simpan nama properti, alamat, titik lokasi, peraturan kos, dan foto utama. Setelah itu Anda akan diarahkan ke halaman kelola unit untuk menambahkan tipe kamar dan nomor kamar spesifik.
                    </p>
                </div>
            </div>

            <a
                href="<?php echo e(route('mitra.properti')); ?>"
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
                                placeholder="Contoh: Kos An-Nur"
                            >
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nama_properti'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-rose-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tipe_properti === 'kosan'): ?>
                            <div>
                                <label for="jenis_kos" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">
                                    Kategori Kos <span class="text-rose-600">*</span>
                                </label>
                                <select
                                    id="jenis_kos"
                                    wire:model.defer="jenis_kos"
                                    class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                >
                                    <option value="">-- Pilih Kategori Kos --</option>
                                    <option value="putra">Kos Putra</option>
                                    <option value="putri">Kos Putri</option>
                                    <option value="campur">Kos Campur</option>
                                </select>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['jenis_kos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm font-medium text-rose-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div>
                            <label for="alamat_lengkap" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Alamat Lengkap <span class="text-rose-600">*</span></label>
                            <textarea
                                id="alamat_lengkap"
                                rows="5"
                                wire:model.defer="alamat_lengkap"
                                class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                placeholder="Masukkan alamat lengkap properti"
                            ></textarea>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['alamat_lengkap'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-rose-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div>
                            <label for="peraturan_kos" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Peraturan Kos <span class="text-rose-600">*</span></label>
                            <textarea
                                id="peraturan_kos"
                                rows="7"
                                wire:model.defer="peraturan_kos"
                                class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                placeholder="Contoh: Tidak menerima tamu menginap, jam malam pukul 22.00, wajib menjaga kebersihan."
                            ></textarea>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['peraturan_kos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-rose-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </section>

                <div class="space-y-6">
                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[#0F4C81] dark:text-blue-300">Foto Properti</p>
                                <h3 class="mt-2 text-lg font-bold text-slate-900 dark:text-white">Foto Utama Kosan</h3>
                            </div>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-medium text-slate-500 dark:bg-slate-800 dark:text-slate-300">1 file</span>
                        </div>

                        <div class="mt-5 space-y-4">
                            <input
                                id="foto_properti_kosan"
                                type="file"
                                wire:model="foto_properti"
                                accept=".jpg,.jpeg,.png,.webp"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm file:mr-4 file:rounded-lg file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:font-semibold file:text-[#0F4C81] hover:file:bg-blue-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200 dark:file:bg-slate-800 dark:file:text-blue-300"
                            >
                            <p class="text-xs leading-5 text-slate-500 dark:text-slate-400">Gunakan foto fasad atau tampilan utama properti. <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editId === null): ?>Kolom ini wajib diisi. <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?> Maksimal 2MB, format JPG, JPEG, PNG, atau WEBP.</p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['foto_properti'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-rose-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($foto_properti): ?>
                                <div class="overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700">
                                    <img src="<?php echo e($foto_properti->temporaryUrl()); ?>" alt="Preview foto kosan" class="h-60 w-full object-cover">
                                </div>
                            <?php elseif($existingPhotoUrl): ?>
                                <div class="overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700">
                                    <img src="<?php echo e($existingPhotoUrl); ?>" alt="Foto kosan saat ini" class="h-60 w-full object-cover">
                                </div>
                            <?php else: ?>
                                <div class="flex h-60 items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-slate-50 text-sm font-medium text-slate-400 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-500">
                                    Preview foto akan muncul di sini
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                                const defaultLat = <?php echo e($latitude !== '' ? (float) $latitude : -6.40690782); ?>;
                                const defaultLng = <?php echo e($longitude !== '' ? (float) $longitude : 108.28776285); ?>;
                                const defaultZoom = <?php echo e($latitude !== '' && $longitude !== '' ? 16 : 14); ?>;
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
                        "
                    >
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
                                class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            >
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['latitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-rose-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div>
                            <label for="longitude-kosan" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Longitude <span class="text-rose-600">*</span></label>
                            <input
                                id="longitude-kosan"
                                type="text"
                                wire:model.live="longitude"
                                class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            >
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['longitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-rose-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>

            <div class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    <?php echo e($editId ? 'Perubahan akan langsung memperbarui profil kosan yang sudah ada.' : 'Setelah disimpan, Anda akan langsung masuk ke halaman kelola tipe kamar dan nomor unit.'); ?>

                </p>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <a
                        href="<?php echo e(route('mitra.properti')); ?>"
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
                        <span wire:loading.remove wire:target="simpan,foto_properti"><?php echo e($editId ? 'Perbarui & Kelola Kamar' : 'Simpan & Lanjut Kelola Kamar'); ?></span>
                        <span wire:loading wire:target="simpan,foto_properti">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\appkonkos_2\resources\views/livewire/mitra/properti/form-kosan.blade.php ENDPATH**/ ?>