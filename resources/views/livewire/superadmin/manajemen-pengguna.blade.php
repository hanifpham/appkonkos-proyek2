@section('mitra-title', 'Manajemen Pengguna')
@section('mitra-subtitle', 'Kelola akun pencari dan pemilik kos, termasuk validasi, blokir, dan pemantauan status.')

<div class="flex-1 space-y-8 overflow-y-auto p-8">
    <section class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Pengguna
                </p>
                <span class="material-symbols-outlined text-3xl text-[#113C7A]">group</span>
            </div>
            <h3 class="text-3xl font-bold text-black dark:text-white">{{ number_format($totalPengguna, 0, ',', '.') }}
            </h3>
            <p class="mt-2 text-[10px] font-medium uppercase tracking-tighter text-gray-400">Database Terpadu</p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Aktif</p>
                <span class="material-symbols-outlined text-3xl text-emerald-600">check_circle</span>
            </div>
            <h3 class="text-3xl font-bold text-emerald-600">{{ number_format($totalAktif, 0, ',', '.') }}</h3>
            <p class="mt-2 text-[10px] font-medium uppercase tracking-tighter text-gray-400">Status Terverifikasi</p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Perlu Validasi
                </p>
                <span class="material-symbols-outlined text-3xl text-amber-600">verified_user</span>
            </div>
            <h3 class="text-3xl font-bold text-amber-600">{{ number_format($totalPending, 0, ',', '.') }}</h3>
            <p class="mt-2 text-[10px] font-medium uppercase tracking-tighter text-gray-400">Antrean Persetujuan</p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Terblokir</p>
                <span class="material-symbols-outlined text-3xl text-red-600">block</span>
            </div>
            <h3 class="text-3xl font-bold text-red-600">{{ number_format($totalTerblokir, 0, ',', '.') }}</h3>
            <p class="mt-2 text-[10px] font-medium uppercase tracking-tighter text-gray-400">Tindakan Pelanggaran</p>
        </div>
    </section>

    <section class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-slate-900">
        <div class="border-b border-gray-100 bg-slate-50/30 p-6 dark:border-gray-700 dark:bg-slate-800/20">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                <h3 class="flex items-center gap-2 text-lg font-bold text-gray-800 dark:text-white">
                    <span class="material-symbols-outlined text-[#113C7A] dark:text-blue-400">group</span>
                    Database Pengguna Terpadu
                </h3>

                <div class="flex flex-wrap items-center justify-start gap-2 xl:justify-end">
                    <div class="relative w-full sm:w-72 lg:w-80">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">search</span>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            class="w-full rounded-lg border border-gray-200 bg-white py-2 pl-10 pr-4 text-xs text-gray-600 shadow-sm transition-all focus:border-[#113C7A] focus:ring-[#113C7A] dark:border-slate-700 dark:bg-slate-800 dark:text-gray-300"
                            placeholder="Cari nama, email, HP, atau ID..."
                        />
                    </div>

                    <select
                        wire:model.live="filterPeran"
                        class="w-40 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-600 focus:border-[#113C7A] focus:ring-[#113C7A] dark:border-slate-700 dark:bg-slate-800 dark:text-gray-300"
                    >
                        <option value="">Semua Peran</option>
                        <option value="pemilik">Pemilik Kos</option>
                        <option value="pencari">Pencari Kos</option>
                    </select>

                    <select
                        wire:model.live="filterStatus"
                        class="w-40 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-600 focus:border-[#113C7A] focus:ring-[#113C7A] dark:border-slate-700 dark:bg-slate-800 dark:text-gray-300"
                    >
                        <option value="">Semua Status</option>
                        <option value="pending">Perlu Validasi</option>
                        <option value="aktif">Aktif</option>
                        <option value="diblokir">Diblokir</option>
                    </select>

                    <button
                        type="button"
                        wire:click="eksporData"
                        class="flex items-center gap-2 rounded-lg bg-[#113C7A] px-4 py-2 text-xs font-bold text-white shadow-md transition-colors hover:bg-[#0d2f60]"
                    >
                        <span class="material-symbols-outlined text-[18px]">file_download</span>
                        Ekspor Excel
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead
                    class="bg-gray-50 text-[11px] font-bold uppercase tracking-widest text-gray-600 dark:bg-slate-800 dark:text-gray-300">
                    <tr>
                        <th class="px-8 py-5">Informasi Pengguna</th>
                        <th class="px-6 py-5">Peran</th>
                        <th class="px-6 py-5">Kontak</th>
                        <th class="px-6 py-5">Status Akun</th>
                        <th class="px-8 py-5 text-center">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($users as $user)
                        <tr wire:key="user-{{ $user->id }}">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="{{ $this->getAvatarClasses($user) }} flex h-11 w-11 items-center justify-center rounded-xl font-bold">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="text-base font-bold text-gray-900 dark:text-gray-100">{{ $user->name }}</span>
                                        <span class="text-xs font-medium text-gray-400">ID: {{ $user->id }} |
                                            {{ $user->created_at?->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                <span
                                    class="{{ $this->getRoleBadgeClasses($user) }} rounded-lg px-3 py-1 text-[10px] font-bold uppercase tracking-wide">
                                    {{ $this->getRoleLabel($user) }}
                                </span>
                            </td>
                            <td class="px-6 py-6 text-gray-500">
                                <div class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $user->email }}</div>
                                <div class="text-xs">{{ $user->no_telepon }}</div>
                            </td>
                            <td class="px-6 py-6">
                                <span
                                    class="{{ $this->getStatusBadgeClasses($user) }} inline-flex items-center rounded-full px-4 py-1.5 text-[10px] font-black uppercase">
                                    {{ $this->getStatusLabel($user) }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center justify-center gap-2">
                                    @if ($user->status === 'diblokir')
                                        <button type="button" wire:click="bukaBlokir({{ $user->id }})"
                                            class="flex h-10 w-[148px] items-center justify-center gap-2 rounded-lg bg-red-600 px-3 text-xs font-bold text-white transition-all hover:bg-red-700">
                                            <span class="material-symbols-outlined text-[18px]">lock_open</span>
                                            Buka Blokir
                                        </button>
                                    @elseif ($user->role === 'pemilik' && $user->pemilikProperti?->status_verifikasi === 'pending')
                                        <button type="button" wire:click="validasiAkun({{ $user->id }})"
                                            class="flex h-10 w-[148px] items-center justify-center gap-2 rounded-lg bg-[#0F4C81] px-3 text-xs font-bold text-white transition-all hover:bg-[#0d3f6d]">
                                            <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
                                            Validasi Akun
                                        </button>
                                    @else
                                        <button type="button" wire:click="blokirAkun({{ $user->id }})"
                                            class="flex h-10 w-[148px] items-center justify-center gap-2 rounded-lg border border-red-200 bg-red-50 px-3 text-xs font-bold text-red-600 transition-all hover:bg-red-100">
                                            <span class="material-symbols-outlined text-[18px]">block</span>
                                            Blokir Akun
                                        </button>
                                    @endif

                                    <button type="button" wire:click="bukaDetail({{ $user->id }})"
                                        class="flex h-10 w-[148px] items-center justify-center rounded-lg border border-gray-300 bg-white px-3 text-xs font-bold text-gray-700 transition-all hover:bg-gray-50">
                                        Detail
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                Belum ada pengguna yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between border-t border-gray-100 bg-gray-50/30 p-6 dark:border-gray-700 dark:bg-slate-800/20">
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                Menampilkan <span class="font-bold text-gray-900 dark:text-white">{{ $users->firstItem() ?? 0 }} -
                    {{ $users->lastItem() ?? 0 }}</span>
                dari <span
                    class="font-bold text-gray-900 dark:text-white">{{ number_format($users->total(), 0, ',', '.') }}</span>
                pengguna terdaftar
            </span>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    </section>

    @if($showModalDetail && $detailUser)
        <div class="fixed inset-0 z-[999] bg-black/75 transition-opacity" wire:click="tutupDetail"></div>

        <div class="fixed inset-0 z-[1000] flex items-center justify-center p-4 sm:p-6 pointer-events-none">
            <div class="flex max-h-[90vh] w-full max-w-3xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-slate-900 pointer-events-auto"
                wire:click.stop>

                <div
                    class="flex items-center justify-between border-b border-gray-100 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-slate-800/50">
                    <h3 class="flex items-center gap-2 text-lg font-bold text-gray-900 dark:text-white">
                        <span class="material-symbols-outlined text-[#113C7A] dark:text-blue-400">badge</span>
                        Detail Verifikasi Mitra
                    </h3>
                    <button wire:click="tutupDetail" class="text-gray-400 transition-colors hover:text-red-500">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <div class="flex-1 space-y-6 overflow-y-auto p-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                        <div class="space-y-4">
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Nama Lengkap</span>
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $detailUser->name }}
                                </p>
                            </div>
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Kontak</span>
                                <p class="text-sm text-gray-800 dark:text-gray-200">{{ $detailUser->email }} |
                                    {{ $detailUser->no_telepon }}</p>
                            </div>
                            @if($detailUser->role === 'pemilik' && $detailUser->pemilikProperti)
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Alamat
                                        Domisili</span>
                                    <p class="text-sm text-gray-800 dark:text-gray-200">
                                        {{ $detailUser->pemilikProperti->alamat_domisili ?? '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Informasi Bank</span>
                                    <p class="text-sm font-medium text-[#113C7A] dark:text-blue-400">
                                        {{ $detailUser->pemilikProperti->nama_bank }} -
                                        {{ $detailUser->pemilikProperti->nomor_rekening }}</p>
                                    <p class="text-xs text-gray-500">a.n
                                        {{ $detailUser->pemilikProperti->nama_pemilik_rekening }}</p>
                                </div>
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Nomor NIK KTP</span>
                                    <p
                                        class="mt-1 inline-block rounded bg-gray-100 px-3 py-1 font-mono text-base font-bold text-gray-900 dark:bg-slate-800 dark:text-white">
                                        {{ $detailUser->pemilikProperti->nik_ktp ?? 'Belum diisi' }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        @if($detailUser->role === 'pemilik' && $detailUser->pemilikProperti)
                            <div class="space-y-4">
                                <div>
                                    <span class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-400">Foto
                                        KTP</span>
                                    <div
                                        class="flex h-48 items-center justify-center overflow-hidden rounded-xl border border-gray-200 bg-gray-200 dark:border-gray-700 dark:bg-slate-900">
                                        @if($detailUser->pemilikProperti->getMediaDisplayUrl('foto_ktp') !== '')
                                            <img src="{{ $detailUser->pemilikProperti->getMediaDisplayUrl('foto_ktp') }}"
                                                class="h-full w-full object-contain p-2 cursor-pointer transition-transform hover:scale-105"
                                                alt="KTP">
                                        @else
                                            <span class="text-sm text-gray-400">Tidak ada dokumen</span>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <span class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-400">Foto
                                        Selfie KTP</span>
                                    <div
                                        class="flex h-48 items-center justify-center overflow-hidden rounded-xl border border-gray-200 bg-gray-200 dark:border-gray-700 dark:bg-slate-900">
                                        @if($detailUser->pemilikProperti->getMediaDisplayUrl('foto_selfie') !== '')
                                            <img src="{{ $detailUser->pemilikProperti->getMediaDisplayUrl('foto_selfie') }}"
                                                class="h-full w-full object-contain p-2 cursor-pointer transition-transform hover:scale-105"
                                                alt="Selfie KTP">
                                        @else
                                            <span class="text-sm text-gray-400">Tidak ada dokumen</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div
                    class="flex justify-end gap-3 border-t border-gray-100 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-slate-800/50">
                    <button wire:click="tutupDetail"
                        class="rounded-lg border border-gray-300 bg-white px-5 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">Tutup</button>
                    @if($detailUser->role === 'pemilik' && $detailUser->pemilikProperti?->status_verifikasi === 'pending')
                        <button wire:click="validasiAkun({{ $detailUser->id }})"
                            class="flex items-center gap-2 rounded-lg bg-[#0F4C81] px-5 py-2 text-sm font-bold text-white hover:bg-[#0d3f6d]">
                            <span class="material-symbols-outlined text-[18px]">how_to_reg</span> Setujui Verifikasi
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
