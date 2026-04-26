@section('mitra-title', 'Pengaturan Profil')
@section('mitra-subtitle', 'Perbarui data pribadi, rekening, dan dokumen verifikasi mitra.')

@push('styles')
    <style>
        .tab-active {
            color: #0f4c81;
            border-bottom: 2px solid #0f4c81;
        }

        .tab-inactive {
            color: #64748b;
            border-bottom: 2px solid transparent;
        }

        html.dark .tab-active {
            color: #60a5fa;
            border-bottom-color: #60a5fa;
        }

        html.dark .tab-inactive {
            color: #94a3b8;
        }

        .jetstream-security-panel,
        .jetstream-security-panel * {
            font-family: 'Poppins', 'Inter', sans-serif;
        }

        .jetstream-security-panel [type='button'],
        .jetstream-security-panel [type='submit'],
        .jetstream-security-panel button:not([class*='text-red']) {
            transition: all 200ms ease;
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
    @php
        $selectedBankLabel = $nama_bank !== '' && array_key_exists($nama_bank, $bankOptions)
            ? $bankOptions[$nama_bank]
            : 'Pilih Bank';
    @endphp

    <div class="mb-8 border-b border-gray-200 dark:border-gray-700">
        <nav aria-label="Tabs" class="-mb-px flex space-x-8">
            <button
                type="button"
                wire:click="$set('activeTab', 'informasi_pribadi')"
                @class([
                    'flex items-center gap-2 px-1 py-4 text-sm',
                    'tab-active font-semibold' => $activeTab === 'informasi_pribadi',
                    'tab-inactive font-medium' => $activeTab !== 'informasi_pribadi',
                ])
            >
                <span class="material-symbols-outlined text-lg">person</span>
                Informasi Pribadi
            </button>
            <button
                type="button"
                wire:click="$set('activeTab', 'keamanan_akun')"
                @class([
                    'flex items-center gap-2 px-1 py-4 text-sm',
                    'tab-active font-semibold' => $activeTab === 'keamanan_akun',
                    'tab-inactive font-medium' => $activeTab !== 'keamanan_akun',
                ])
            >
                <span class="material-symbols-outlined text-lg">security</span>
                Keamanan Akun
            </button>
            <button
                type="button"
                wire:click="$set('activeTab', 'notifikasi')"
                @class([
                    'flex items-center gap-2 px-1 py-4 text-sm',
                    'tab-active font-semibold' => $activeTab === 'notifikasi',
                    'tab-inactive font-medium' => $activeTab !== 'notifikasi',
                ])
            >
                <span class="material-symbols-outlined text-lg">notifications</span>
                Notifikasi
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
                    Partner APPKONKOS sejak {{ auth()->user()->created_at?->format('Y') ?? now()->format('Y') }}
                </p>

                <div class="w-full space-y-4 border-t border-gray-100 pt-6 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium uppercase tracking-wider text-gray-500">Status Akun</span>
                        <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase {{ $this->getStatusBadgeClasses() }}">
                            {{ $this->getStatusLabel() }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium uppercase tracking-wider text-gray-500">Keamanan</span>
                        <div class="h-2 w-24 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
                            <div
                                @class([
                                    'h-full',
                                    'w-1/4 bg-red-500' => in_array($status_verifikasi, ['belum', 'ditolak'], true),
                                    'w-2/4 bg-amber-500' => $status_verifikasi === 'pending',
                                    'w-full bg-green-500' => $status_verifikasi === 'terverifikasi',
                                ])
                            ></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
                <h5 class="mb-4 text-sm font-bold text-gray-800 dark:text-white">Bantuan Cepat</h5>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined mt-0.5 text-blue-500">help</span>
                        <div>
                            <p class="text-sm font-semibold text-gray-800 dark:text-white">Butuh bantuan?</p>
                            <p class="text-xs text-gray-500">Kunjungi pusat bantuan atau hubungi CS kami.</p>
                        </div>
                    </div>
                    <button type="button" class="w-full rounded-lg bg-blue-50 py-2 text-center text-sm font-medium text-[#0F4C81] transition-colors hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-300 dark:hover:bg-blue-900/40">
                        Hubungi CS
                    </button>
                </div>
            </div>
        </div>

        <div class="space-y-6 lg:col-span-8">
        @if ($activeTab === 'informasi_pribadi')
        <form wire:submit.prevent="simpanProfil" class="space-y-6">
            <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-slate-900">
                <div class="border-b border-gray-100 p-6 dark:border-gray-700">
                    <h3 class="text-base font-bold text-gray-800 dark:text-white">Informasi Dasar</h3>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Detail yang akan muncul pada profil publik Anda.</p>
                </div>
                <div class="space-y-6 p-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                            <input
                                type="text"
                                wire:model="nama_lengkap"
                                class="w-full rounded-lg border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-gray-700 dark:bg-slate-800"
                            >
                            @error('nama_lengkap')
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
                            @error('email')
                                <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-gray-700 dark:text-gray-300">Nomor WhatsApp</label>
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

                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-gray-700 dark:text-gray-300">Alamat Domisili</label>
                        <textarea
                            rows="3"
                            wire:model="alamat_domisili"
                            class="w-full rounded-lg border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-gray-700 dark:bg-slate-800"
                        ></textarea>
                        @error('alamat_domisili')
                            <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="overflow-visible rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-slate-900">
                <div class="border-b border-gray-100 p-6 dark:border-gray-700">
                    <h3 class="text-base font-bold text-gray-800 dark:text-white">Informasi Rekening Bank</h3>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Rekening tujuan untuk pencairan dana pendapatan kos.</p>
                </div>
                <div class="relative z-10 p-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-gray-700 dark:text-gray-300">Nama Bank</label>
                            <div x-data="{ open: false }" class="relative">
                                <button
                                    type="button"
                                    @click="open = ! open"
                                    @click.outside="open = false"
                                    class="flex h-11 w-full items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-blue-400 dark:hover:text-blue-300"
                                >
                                    <span class="flex min-w-0 items-center gap-2">
                                        <span class="material-symbols-outlined shrink-0 text-[18px]">account_balance</span>
                                        <span class="truncate">{{ $selectedBankLabel }}</span>
                                    </span>
                                    <span class="material-symbols-outlined shrink-0 text-[18px] text-slate-400 dark:text-slate-500">expand_more</span>
                                </button>

                                <div
                                    x-cloak
                                    x-show="open"
                                    x-transition.origin.top.right
                                    class="absolute left-0 right-0 top-[calc(100%+8px)] z-30 max-h-72 overflow-y-auto rounded-2xl border border-slate-200 bg-white p-1 shadow-xl dark:border-slate-700 dark:bg-slate-800"
                                >
                                    <button
                                        type="button"
                                        wire:click="$set('nama_bank', '')"
                                        @click="open = false"
                                        @class([
                                            'flex w-full items-center justify-between rounded-xl px-4 py-3 text-left text-sm transition',
                                            'bg-blue-50 font-semibold text-[#0F4C81] dark:bg-blue-500/10 dark:text-blue-300' => $nama_bank === '',
                                            'text-slate-600 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-700/70' => $nama_bank !== '',
                                        ])
                                    >
                                        <span>Pilih Bank</span>
                                        @if ($nama_bank === '')
                                            <span class="material-symbols-outlined text-[18px]">check</span>
                                        @endif
                                    </button>

                                    @foreach ($bankOptions as $value => $label)
                                        <button
                                            type="button"
                                            wire:click="$set('nama_bank', '{{ $value }}')"
                                            @click="open = false"
                                            @class([
                                                'flex w-full items-center justify-between rounded-xl px-4 py-3 text-left text-sm transition',
                                                'bg-blue-50 font-semibold text-[#0F4C81] dark:bg-blue-500/10 dark:text-blue-300' => $nama_bank === $value,
                                                'text-slate-600 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-700/70' => $nama_bank !== $value,
                                            ])
                                        >
                                            <span>{{ $label }}</span>
                                            @if ($nama_bank === $value)
                                                <span class="material-symbols-outlined text-[18px]">check</span>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            @error('nama_bank')
                                <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-gray-700 dark:text-gray-300">Nomor Rekening</label>
                            <input
                                type="text"
                                wire:model="nomor_rekening"
                                placeholder="Contoh: 1234567890"
                                class="w-full rounded-lg border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-gray-700 dark:bg-slate-800"
                            >
                            @error('nomor_rekening')
                                <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-xs font-semibold text-gray-700 dark:text-gray-300">Nama Pemilik Rekening</label>
                            <input
                                type="text"
                                wire:model="nama_pemilik_rekening"
                                placeholder="Contoh: Faldy Ardiansyah"
                                class="w-full rounded-lg border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-gray-700 dark:bg-slate-800"
                            >
                            <p class="mt-1 text-[10px] italic text-gray-400">Harus sesuai dengan nama KTP</p>
                            @error('nama_pemilik_rekening')
                                <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b border-gray-100 p-6 dark:border-gray-700">
                    <div>
                        <h3 class="text-base font-bold text-gray-800 dark:text-white">Identitas &amp; Verifikasi</h3>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Status verifikasi dokumen resmi Anda.</p>
                    </div>
                    <span class="material-symbols-outlined rounded-full p-2 {{ $this->getVerificationIconClasses() }}">verified_user</span>
                </div>

                <div class="space-y-6 p-6">
                    @if ($status_verifikasi === 'pending')
                        <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-700 dark:border-amber-800 dark:bg-amber-900/20 dark:text-amber-300">
                            Dokumen Anda sedang ditinjau. Anda masih dapat mengunggah ulang jika diperlukan.
                        </div>
                    @elseif ($status_verifikasi === 'ditolak')
                        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-xs text-red-700 dark:border-red-800 dark:bg-red-900/20 dark:text-red-300">
                            Verifikasi sebelumnya ditolak. Silakan perbarui data dan unggah dokumen yang lebih jelas.
                        </div>
                    @endif

                    <div class="space-y-3">
                        <label class="text-xs font-semibold text-gray-700 dark:text-gray-300">Nomor NIK (KTP)</label>

                        @if ($this->isVerified())
                            <div class="flex items-center gap-3">
                                <div class="flex-1 rounded-lg border border-gray-200 bg-gray-100 px-4 py-2.5 text-sm text-gray-500 dark:border-gray-700 dark:bg-slate-900">
                                    {{ $this->getMaskedNik() }}
                                </div>
                                <span class="material-symbols-outlined text-green-500">check_circle</span>
                            </div>
                        @else
                            <div class="space-y-1.5">
                                <input
                                    type="text"
                                    wire:model="nik_ktp"
                                    placeholder="Masukkan 16 digit NIK"
                                    class="w-full rounded-lg border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-gray-700 dark:bg-slate-800"
                                >
                                @error('nik_ktp')
                                    <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="text-xs font-semibold text-gray-700 dark:text-gray-300">Foto KTP</label>

                            @if ($this->isVerified())
                                <div class="rounded-xl border border-green-100 bg-green-50 p-4 dark:border-green-800 dark:bg-green-900/20">
                                    @if ($existingFotoKtpUrl !== null)
                                        <img src="{{ $existingFotoKtpUrl }}" alt="Foto KTP" class="h-32 w-full rounded-lg object-cover">
                                    @endif
                                    <p class="mt-3 text-xs font-medium text-green-700 dark:text-green-300">Foto KTP telah tersimpan dan diverifikasi.</p>
                                </div>
                            @else
                                <label class="group flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 p-6 transition-all hover:bg-gray-100 dark:border-gray-700 dark:bg-slate-800/50 dark:hover:bg-slate-800">
                                    <input type="file" wire:model="foto_ktp" class="hidden" accept=".jpg,.jpeg,.png,.webp">

                                    @if ($foto_ktp)
                                        <img src="{{ $foto_ktp->temporaryUrl() }}" alt="Preview Foto KTP" class="h-24 rounded-lg object-cover">
                                        <p class="mt-3 text-xs font-medium text-gray-600 dark:text-gray-300">Pratinjau Foto KTP baru</p>
                                    @elseif ($existingFotoKtpUrl !== null)
                                        <img src="{{ $existingFotoKtpUrl }}" alt="Foto KTP" class="h-24 rounded-lg object-cover">
                                        <p class="mt-3 text-xs font-medium text-gray-600 dark:text-gray-300">Klik untuk mengganti Foto KTP</p>
                                    @else
                                        <span class="material-symbols-outlined mb-2 text-3xl text-gray-400 group-hover:text-[#0F4C81]">cloud_upload</span>
                                        <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Klik untuk mengunggah Foto KTP</p>
                                        <p class="mt-1 text-[10px] text-gray-400">JPG, PNG, WEBP (Maks. 2MB)</p>
                                    @endif
                                </label>
                                @error('foto_ktp')
                                    <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            @endif
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-semibold text-gray-700 dark:text-gray-300">Foto Selfie dengan KTP</label>

                            @if ($this->isVerified())
                                <div class="rounded-xl border border-green-100 bg-green-50 p-4 dark:border-green-800 dark:bg-green-900/20">
                                    @if ($existingFotoSelfieUrl !== null)
                                        <img src="{{ $existingFotoSelfieUrl }}" alt="Foto Selfie KTP" class="h-32 w-full rounded-lg object-cover">
                                    @endif
                                    <p class="mt-3 text-xs font-medium text-green-700 dark:text-green-300">Foto selfie dengan KTP telah tersimpan dan diverifikasi.</p>
                                </div>
                            @else
                                <label class="group flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 p-6 text-center transition-all hover:bg-gray-100 dark:border-gray-700 dark:bg-slate-800/50 dark:hover:bg-slate-800">
                                    <input type="file" wire:model="foto_selfie" class="hidden" accept=".jpg,.jpeg,.png,.webp">

                                    @if ($foto_selfie)
                                        <img src="{{ $foto_selfie->temporaryUrl() }}" alt="Preview Foto Selfie KTP" class="h-24 rounded-lg object-cover">
                                        <p class="mt-3 text-xs font-medium text-gray-600 dark:text-gray-300">Pratinjau Foto Selfie baru</p>
                                    @elseif ($existingFotoSelfieUrl !== null)
                                        <img src="{{ $existingFotoSelfieUrl }}" alt="Foto Selfie KTP" class="h-24 rounded-lg object-cover">
                                        <p class="mt-3 text-xs font-medium text-gray-600 dark:text-gray-300">Klik untuk mengganti Foto Selfie dengan KTP</p>
                                    @else
                                        <span class="material-symbols-outlined mb-2 text-3xl text-gray-400 group-hover:text-[#0F4C81]">face</span>
                                        <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Unggah Foto Selfie dengan KTP</p>
                                        <p class="mt-1 text-[10px] text-gray-400">Wajah harus terlihat jelas</p>
                                    @endif
                                </label>
                                @error('foto_selfie')
                                    <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4">
                <button
                    type="button"
                    wire:click="resetForm"
                    class="rounded-lg border border-gray-300 px-6 py-2.5 text-sm font-medium text-gray-600 transition-all duration-200 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="simpanProfil,foto_ktp,foto_selfie"
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
                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                    <section class="jetstream-security-card overflow-hidden rounded-xl border border-gray-100 bg-surface-light p-6 shadow-sm dark:border-gray-700 dark:bg-surface-dark">
                        <div class="mb-6 border-b border-gray-100 pb-5 dark:border-gray-700">
                            <h3 class="text-base font-bold text-gray-800 dark:text-white">Ganti Password</h3>
                            <p class="mt-1 text-xs leading-6 text-gray-500 dark:text-gray-400">Gunakan form Jetstream agar validasi password saat ini dan hashing tetap mengikuti standar Laravel.</p>
                        </div>

                        @livewire('profile.update-password-form')
                    </section>
                @endif

                @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                    <section class="jetstream-security-card overflow-hidden rounded-xl border border-gray-100 bg-surface-light p-6 shadow-sm dark:border-gray-700 dark:bg-surface-dark">
                        <div class="mb-6 border-b border-gray-100 pb-5 dark:border-gray-700">
                            <h3 class="text-base font-bold text-gray-800 dark:text-white">Autentikasi 2 Langkah</h3>
                            <p class="mt-1 text-xs leading-6 text-gray-500 dark:text-gray-400">Aktifkan kode autentikator untuk menambah lapisan keamanan akun mitra.</p>
                        </div>

                        @livewire('profile.two-factor-authentication-form')
                    </section>
                @endif

                <section class="jetstream-security-card overflow-hidden rounded-xl border border-gray-100 bg-surface-light p-6 shadow-sm dark:border-gray-700 dark:bg-surface-dark">
                    <div class="mb-6 border-b border-gray-100 pb-5 dark:border-gray-700">
                        <h3 class="text-base font-bold text-gray-800 dark:text-white">Sesi Browser</h3>
                        <p class="mt-1 text-xs leading-6 text-gray-500 dark:text-gray-400">Keluar dari perangkat lain menggunakan konfirmasi password bawaan Jetstream.</p>
                    </div>

                    @livewire('profile.logout-other-browser-sessions-form')
                </section>

                @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                    <section class="jetstream-security-card overflow-hidden rounded-xl border border-red-100 bg-red-50/60 p-6 shadow-sm dark:border-red-900/50 dark:bg-red-950/20">
                        <div class="mb-6 border-b border-red-100 pb-5 dark:border-red-900/50">
                            <h3 class="text-base font-bold text-red-700 dark:text-red-300">Hapus Akun</h3>
                            <p class="mt-1 text-xs leading-6 text-red-600/80 dark:text-red-300/80">Jetstream akan meminta password sebelum akun benar-benar dihapus.</p>
                        </div>

                        @livewire('profile.delete-user-form')
                    </section>
                @endif
            </div>
        @endif

        @if ($activeTab === 'notifikasi')
            <div class="space-y-6">
                <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-slate-900">
                    <div class="border-b border-gray-100 p-6 dark:border-gray-700">
                        <h3 class="text-base font-bold text-gray-800 dark:text-white">Preferensi Notifikasi</h3>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Atur kanal notifikasi yang ingin Anda terima. Perubahan akan disimpan otomatis.</p>
                    </div>
                    <div class="space-y-5 p-6">
                        @php
                            $notificationGroups = [
                                'Notifikasi WhatsApp' => [
                                    'Pesanan Baru' => 'notif_whatsapp_pesanan_baru',
                                    'Pembayaran Sukses' => 'notif_whatsapp_pembayaran_sukses',
                                    'Ulasan Baru' => 'notif_whatsapp_ulasan_baru',
                                ],
                                'Notifikasi Email' => [
                                    'Pesanan Baru' => 'notif_email_pesanan_baru',
                                    'Pembayaran Sukses' => 'notif_email_pembayaran_sukses',
                                    'Ulasan Baru' => 'notif_email_ulasan_baru',
                                ],
                                'Notifikasi Aplikasi' => [
                                    'Pesanan Baru' => 'notif_aplikasi_pesanan_baru',
                                    'Pembayaran Sukses' => 'notif_aplikasi_pembayaran_sukses',
                                    'Ulasan Baru' => 'notif_aplikasi_ulasan_baru',
                                ],
                            ];
                        @endphp

                        @foreach ($notificationGroups as $groupTitle => $items)
                            <div class="rounded-xl border border-gray-100 bg-gray-50 p-5 dark:border-gray-700 dark:bg-slate-800/60">
                                <div class="mb-4 flex items-center justify-between gap-4">
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-800 dark:text-white">{{ $groupTitle }}</h4>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            {{ $groupTitle === 'Notifikasi WhatsApp' ? 'Gunakan nomor WhatsApp yang tersimpan untuk pengingat cepat.' : ($groupTitle === 'Notifikasi Email' ? 'Kirim ringkasan aktivitas ke email akun terdaftar.' : 'Tampilkan pembaruan langsung di dashboard APPKONKOS.') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    @foreach ($items as $label => $field)
                                        <div class="flex items-center justify-between gap-4 rounded-lg border border-white/80 bg-white px-4 py-3 dark:border-slate-700 dark:bg-slate-900">
                                            <div>
                                                <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $label }}</p>
                                                <p class="mt-1 text-[11px] text-gray-500 dark:text-gray-400">
                                                    {{ $label === 'Pesanan Baru' ? 'Aktif saat ada booking baru yang masuk.' : ($label === 'Pembayaran Sukses' ? 'Aktif saat pembayaran penyewa berhasil diterima.' : 'Aktif saat penyewa mengirim ulasan baru.') }}
                                                </p>
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
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
        </div>
</div>
