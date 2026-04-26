@section('mitra-title', 'Pengaturan Profil')
@section('mitra-subtitle', 'Kelola informasi pribadi dan tingkatkan keamanan akun Anda.')

@push('styles')
    <style>
        .tab-active {
            background-color: #0f4c81;
            color: #ffffff;
            box-shadow: 0 8px 18px rgba(15, 76, 129, 0.18);
        }

        .tab-inactive {
            color: #64748b;
            background-color: transparent;
        }

        html.dark .tab-active {
            background-color: #0f4c81;
            color: #ffffff;
        }

        html.dark .tab-inactive {
            color: #94a3b8;
        }

        .jetstream-security-panel,
        .jetstream-security-panel * {
            font-family: 'Poppins', 'Inter', sans-serif;
        }

        .jetstream-security-panel button.bg-gray-800,
        .jetstream-security-panel button.bg-gray-800:hover,
        .jetstream-security-panel button[class*='bg-gray-800'],
        .jetstream-security-panel button[class*='bg-indigo'],
        .jetstream-security-panel button[class*='bg-blue'] {
            background-color: #113C7A !important;
            color: #ffffff !important;
        }

        .jetstream-security-panel button.bg-gray-800:hover,
        .jetstream-security-panel button[class*='bg-gray-800']:hover,
        .jetstream-security-panel button[class*='bg-indigo']:hover,
        .jetstream-security-panel button[class*='bg-blue']:hover {
            background-color: #0d3f6d !important;
        }

        .jetstream-security-panel input,
        .jetstream-security-panel textarea {
            border-radius: 0.75rem;
            border-color: #e5e7eb;
            font-size: 0.875rem;
        }

        .jetstream-security-panel input:focus,
        .jetstream-security-panel textarea:focus {
            border-color: #113C7A !important;
            box-shadow: 0 0 0 1px #113C7A !important;
        }

        html.dark .jetstream-security-panel input,
        html.dark .jetstream-security-panel textarea {
            border-color: #334155;
            background-color: #0f172a;
            color: #e2e8f0;
        }
    </style>
@endpush

<div class="flex-1 p-6 pb-12 md:p-8">
    <div class="mb-8 flex justify-center">
        <nav aria-label="Tabs" class="flex items-center gap-1 rounded-full border border-gray-200 bg-white p-1 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <button
                type="button"
                wire:click="$set('activeTab', 'informasi_pribadi')"
                @class([
                    'flex items-center gap-2 rounded-full px-5 py-2.5 text-sm transition-all',
                    'tab-active font-semibold' => $activeTab === 'informasi_pribadi',
                    'tab-inactive font-medium' => $activeTab !== 'informasi_pribadi',
                ])
            >
                <span class="material-symbols-outlined text-[20px]">person</span>
                Informasi Pribadi
            </button>

            <button
                type="button"
                wire:click="$set('activeTab', 'keamanan_akun')"
                @class([
                    'flex items-center gap-2 rounded-full px-5 py-2.5 text-sm transition-all',
                    'tab-active font-semibold' => $activeTab === 'keamanan_akun',
                    'tab-inactive font-medium' => $activeTab !== 'keamanan_akun',
                ])
            >
                <span class="material-symbols-outlined text-[20px]">lock</span>
                Keamanan Akun
            </button>

            <button
                type="button"
                wire:click="$set('activeTab', 'preferensi_notifikasi')"
                @class([
                    'flex items-center gap-2 rounded-full px-5 py-2.5 text-sm transition-all',
                    'tab-active font-semibold' => $activeTab === 'preferensi_notifikasi',
                    'tab-inactive font-medium' => $activeTab !== 'preferensi_notifikasi',
                ])
            >
                <span class="material-symbols-outlined text-[20px]">notifications_active</span>
                Preferensi Notifikasi
            </button>

            <button
                type="button"
                wire:click="$set('activeTab', 'pengaturan_sistem')"
                @class([
                    'flex items-center gap-2 rounded-full px-5 py-2.5 text-sm transition-all',
                    'tab-active font-semibold' => $activeTab === 'pengaturan_sistem',
                    'tab-inactive font-medium' => $activeTab !== 'pengaturan_sistem',
                ])
            >
                <span class="material-symbols-outlined text-[20px]">tune</span>
                Pengaturan Sistem
            </button>
        </nav>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
        <div class="space-y-6 lg:col-span-4">
            <div class="flex flex-col items-center rounded-xl border border-gray-100 bg-white p-6 text-center shadow-sm dark:border-gray-700 dark:bg-slate-900">
                <div class="group relative mb-4 h-32 w-32">
                    @if ($foto_baru)
                        <img
                            alt="Preview"
                            class="h-full w-full rounded-full border-4 border-white object-cover shadow-md dark:border-gray-800"
                            src="{{ $foto_baru->temporaryUrl() }}"
                        >
                    @elseif ($profilePhotoUrl !== '')
                        <img
                            alt="{{ auth()->user()->name }}"
                            class="h-full w-full rounded-full border-4 border-white object-cover shadow-md dark:border-gray-800"
                            src="{{ $profilePhotoUrl }}"
                            data-appkonkos-profile-photo
                        >
                    @else
                        <img
                            alt="{{ auth()->user()->name }}"
                            class="h-full w-full rounded-full border-4 border-white object-cover shadow-md dark:border-gray-800"
                            src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=113C7A&background=EBF4FF"
                            data-appkonkos-profile-photo
                        >
                    @endif

                    <label class="absolute bottom-0 right-0 z-10 cursor-pointer rounded-full border-2 border-white bg-[#0F4C81] p-2 text-white shadow-lg transition-all duration-200 hover:bg-[#0d3f6d] dark:border-gray-800">
                        <span class="material-symbols-outlined text-base">photo_camera</span>
                        <input type="file" wire:model.live="foto_baru" class="hidden" accept="image/png, image/jpeg, image/jpg, image/webp">
                    </label>

                    <div wire:loading wire:target="foto_baru" class="absolute inset-0 flex items-center justify-center rounded-full bg-black/50">
                        <span class="material-symbols-outlined animate-spin text-white">sync</span>
                    </div>
                </div>

                @error('foto_baru')
                    <p class="mb-3 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror

                <h4 class="text-lg font-bold text-gray-800 dark:text-white">{{ auth()->user()->name }}</h4>
                <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                    Super Admin APPKONKOS sejak {{ auth()->user()->created_at?->format('Y') ?? now()->format('Y') }}
                </p>

                <div class="w-full space-y-4 border-t border-gray-100 pt-6 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium uppercase tracking-wider text-gray-500">Status Akun</span>
                        <span class="rounded-full border border-green-100 bg-green-50 px-2 py-0.5 text-[10px] font-bold uppercase text-green-600 dark:border-green-800 dark:bg-green-900/30 dark:text-green-400">
                            Aktif
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium uppercase tracking-wider text-gray-500">Role</span>
                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Super Admin</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6 lg:col-span-8">
            @if ($activeTab === 'informasi_pribadi')
                <form wire:submit.prevent="simpanProfil" class="space-y-6">
                    <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-slate-900">
                        <div class="border-b border-gray-100 p-6 dark:border-gray-700">
                            <h3 class="text-base font-bold text-gray-800 dark:text-white">Informasi Dasar</h3>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Detail akun Super Admin yang digunakan pada dashboard.</p>
                        </div>

                        <div class="space-y-6 p-6">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div class="space-y-1.5">
                                    <label class="text-xs font-semibold text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                                    <input
                                        type="text"
                                        wire:model="name"
                                        class="w-full rounded-lg border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-gray-700 dark:bg-slate-800"
                                    >
                                    @error('name')
                                        <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-xs font-semibold text-gray-700 dark:text-gray-300">Nomor Handphone</label>
                                    <input
                                        type="tel"
                                        wire:model="no_telepon"
                                        class="w-full rounded-lg border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-gray-700 dark:bg-slate-800"
                                    >
                                    @error('no_telepon')
                                        <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div class="space-y-1.5">
                                    <label class="text-xs font-semibold text-gray-700 dark:text-gray-300">Alamat Email</label>
                                    <div class="relative">
                                        <input
                                            type="email"
                                            wire:model="email"
                                            readonly
                                            class="w-full rounded-lg border-gray-200 bg-gray-100 px-4 py-2.5 text-sm text-gray-500 dark:border-gray-700 dark:bg-slate-900 dark:text-gray-400"
                                        >
                                        <span class="material-symbols-outlined absolute right-3 top-2.5 text-[18px] text-gray-400">lock</span>
                                    </div>
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-xs font-semibold text-gray-700 dark:text-gray-300">Jabatan</label>
                                    <input
                                        type="text"
                                        readonly
                                        value="Super Admin"
                                        class="w-full rounded-lg border-gray-200 bg-gray-100 px-4 py-2.5 text-sm text-gray-500 dark:border-gray-700 dark:bg-slate-900 dark:text-gray-400"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4">
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:target="simpanProfil"
                            class="flex items-center gap-2 rounded-lg bg-[#0F4C81] px-8 py-2.5 text-sm font-bold text-white shadow-md transition-all duration-200 hover:bg-[#0d3f6d] disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            <span class="material-symbols-outlined text-sm">save</span>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            @endif

            @if ($activeTab === 'keamanan_akun')
                <div class="jetstream-security-panel space-y-6">
                    <section class="overflow-hidden rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
                        <div class="mb-6 border-b border-gray-100 pb-5 dark:border-gray-700">
                            <h3 class="text-base font-bold text-gray-800 dark:text-white">Ganti Password</h3>
                            <p class="mt-1 text-xs leading-6 text-gray-500 dark:text-gray-400">Gunakan form Jetstream agar validasi password saat ini dan hashing tetap mengikuti standar Laravel.</p>
                        </div>

                        @livewire('profile.update-password-form')
                    </section>

                    <section class="overflow-hidden rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
                        <div class="mb-6 border-b border-gray-100 pb-5 dark:border-gray-700">
                            <h3 class="text-base font-bold text-gray-800 dark:text-white">Autentikasi 2 Langkah</h3>
                            <p class="mt-1 text-xs leading-6 text-gray-500 dark:text-gray-400">Aktifkan kode autentikator untuk menambah lapisan keamanan akun Super Admin.</p>
                        </div>

                        @livewire('profile.two-factor-authentication-form')
                    </section>
                </div>
            @endif

            @if ($activeTab === 'preferensi_notifikasi')
                <div class="space-y-6">
                    <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-slate-900">
                        <div class="border-b border-gray-100 p-6 dark:border-gray-700">
                            <h3 class="text-base font-bold text-gray-800 dark:text-white">Preferensi Notifikasi</h3>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Atur kanal notifikasi yang ingin Anda terima.</p>
                        </div>

                        <div class="space-y-5 p-6">
                            @foreach ([
                                ['Notifikasi Email', 'Terima laporan harian dan pengumuman sistem melalui email.', 'notifEmail'],
                                ['Push Notifikasi', 'Dapatkan peringatan instan untuk moderasi dan pengajuan refund baru.', 'notifPush'],
                                ['Notifikasi WhatsApp', 'Terima pesan darurat melalui WhatsApp untuk transaksi gagal.', 'notifWhatsapp'],
                            ] as [$title, $description, $field])
                                <div class="flex items-center justify-between gap-4 rounded-lg border border-white/80 bg-gray-50 px-4 py-3 dark:border-slate-700 dark:bg-slate-800/60">
                                    <div>
                                        <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $title }}</p>
                                        <p class="mt-1 text-[11px] text-gray-500 dark:text-gray-400">{{ $description }}</p>
                                    </div>

                                    <button
                                        type="button"
                                        wire:click="$toggle('{{ $field }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="{{ $field }}"
                                        @class([
                                            'relative inline-flex h-7 w-12 items-center rounded-full transition-all duration-200',
                                            'bg-[#113C7A]' => $this->{$field},
                                            'bg-gray-300 dark:bg-slate-600' => ! $this->{$field},
                                        ])
                                        aria-pressed="{{ $this->{$field} ? 'true' : 'false' }}"
                                    >
                                        <span
                                            @class([
                                                'inline-block h-5 w-5 transform rounded-full bg-white shadow transition-all duration-200',
                                                'translate-x-6' => $this->{$field},
                                                'translate-x-1' => ! $this->{$field},
                                            ])
                                        ></span>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-slate-900">
                        <div class="border-b border-gray-100 p-6 dark:border-gray-700">
                            <h3 class="text-base font-bold text-gray-800 dark:text-white">Kategori Pemberitahuan</h3>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pilih kategori aktivitas yang ingin diprioritaskan.</p>
                        </div>

                        <div class="space-y-3 p-6">
                            @foreach ([
                                ['Pengajuan Refund Baru', 'notifRefund'],
                                ['Properti Butuh Moderasi', 'notifModerasi'],
                                ['Pencairan Dana Berhasil', 'notifPencairan'],
                            ] as [$label, $field])
                                <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-100 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-slate-800/60">
                                    <input class="h-4 w-4 rounded border-gray-300 text-[#113C7A] focus:ring-[#113C7A]" type="checkbox" wire:model="{{ $field }}">
                                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4">
                        <button
                            type="button"
                            wire:click="simpanNotifikasi"
                            class="flex items-center gap-2 rounded-lg bg-[#0F4C81] px-8 py-2.5 text-sm font-bold text-white shadow-md transition-all duration-200 hover:bg-[#0d3f6d]"
                        >
                            <span class="material-symbols-outlined text-sm">save</span>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            @endif

            @if ($activeTab === 'pengaturan_sistem')
                <form wire:submit.prevent="simpanPengaturanSistem" class="space-y-6">
                    <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-slate-900">
                        <div class="border-b border-gray-100 p-6 dark:border-gray-700">
                            <h3 class="text-base font-bold text-gray-800 dark:text-white">Pengaturan Sistem Global</h3>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Nilai ini akan digunakan secara otomatis untuk menghitung pendapatan platform dari Midtrans dan kalkulasi pengembalian dana pengguna.
                            </p>
                        </div>

                        <div class="space-y-6 p-6">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div class="space-y-1.5">
                                    <label class="text-xs font-semibold text-gray-700 dark:text-gray-300">Komisi Platform (%)</label>
                                    <input
                                        type="number"
                                        min="0"
                                        max="100"
                                        step="0.01"
                                        wire:model="komisiPlatform"
                                        class="w-full rounded-lg border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-gray-700 dark:bg-slate-800"
                                    >
                                    @error('komisiPlatform')
                                        <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-xs font-semibold text-gray-700 dark:text-gray-300">Potongan Refund (%)</label>
                                    <input
                                        type="number"
                                        min="0"
                                        max="100"
                                        step="0.01"
                                        wire:model="potonganRefund"
                                        class="w-full rounded-lg border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-gray-700 dark:bg-slate-800"
                                    >
                                    @error('potonganRefund')
                                        <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="rounded-xl border border-blue-100 bg-blue-50/70 p-4 text-xs leading-6 text-blue-900 dark:border-blue-900/60 dark:bg-blue-950/20 dark:text-blue-100">
                                Pengaturan ini dipakai oleh dashboard Super Admin, sinkronisasi transaksi Midtrans, dan proses refund agar seluruh kalkulasi finansial memakai sumber nilai yang sama.
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4">
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:target="simpanPengaturanSistem"
                            class="flex items-center gap-2 rounded-lg bg-[#0F4C81] px-8 py-2.5 text-sm font-bold text-white shadow-md transition-all duration-200 hover:bg-[#0d3f6d] disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            <span class="material-symbols-outlined text-sm">save</span>
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
