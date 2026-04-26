@section('mitra-title', 'Manajemen Keuangan')
@section('mitra-subtitle', 'Pantau saldo dan ajukan penarikan dana melalui sistem Midtrans.')

@push('styles')
    <style>
        .step-active {
            background: #0f4c81;
            color: #ffffff;
            box-shadow: 0 0 0 8px rgba(15, 76, 129, 0.14);
        }

        .step-completed {
            background: #d1fae5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }

        .step-inactive {
            background: #ffffff;
            color: #94a3b8;
            border: 1px solid #dbe3ee;
        }

        html.dark .step-active {
            background: #2563eb;
            box-shadow: 0 0 0 8px rgba(37, 99, 235, 0.18);
        }

        html.dark .step-completed {
            background: rgba(5, 150, 105, 0.18);
            color: #6ee7b7;
            border-color: rgba(16, 185, 129, 0.35);
        }

        html.dark .step-inactive {
            background: #0f172a;
            color: #64748b;
            border-color: #334155;
        }
    </style>
@endpush

@php
    $activeNominal = $penarikanAktif?->nominal ?? 0;
@endphp

<div class="flex-1 p-6 md:p-8 space-y-8">
    <section class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <div class="group relative overflow-hidden rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="absolute right-0 top-0 -mr-4 -mt-4 h-24 w-24 rounded-bl-full bg-blue-50 transition-transform group-hover:scale-110 dark:bg-blue-900/20"></div>
            <div class="relative z-10">
                <div class="mb-1 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px] text-[#0F4C81] dark:text-blue-400">account_balance_wallet</span>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Saldo Tersedia</p>
                </div>
                <h3 class="mb-2 text-3xl font-bold text-gray-800 dark:text-white">Rp {{ number_format($saldoTersedia, 0, ',', '.') }}</h3>
                <p class="text-[11px] text-gray-400">Saldo yang dapat ditarik ke rekening terdaftar</p>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="absolute right-0 top-0 -mr-4 -mt-4 h-24 w-24 rounded-bl-full bg-orange-50 transition-transform group-hover:scale-110 dark:bg-orange-900/20"></div>
            <div class="relative z-10">
                <div class="mb-1 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px] text-orange-500 dark:text-orange-400">pending_actions</span>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Dalam Proses</p>
                </div>
                <h3 class="mb-2 text-3xl font-bold text-gray-800 dark:text-white">Rp {{ number_format($dalamProses, 0, ',', '.') }}</h3>
                <p class="text-[11px] text-gray-400">Menunggu konfirmasi oleh Super Admin</p>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="absolute right-0 top-0 -mr-4 -mt-4 h-24 w-24 rounded-bl-full bg-green-50 transition-transform group-hover:scale-110 dark:bg-green-900/20"></div>
            <div class="relative z-10">
                <div class="mb-1 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px] text-emerald-500 dark:text-emerald-400">check_circle</span>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Berhasil</p>
                </div>
                <h3 class="mb-2 text-3xl font-bold text-gray-800 dark:text-white">Rp {{ number_format($totalBerhasil, 0, ',', '.') }}</h3>
                <p class="text-[11px] text-gray-400">Akumulasi seluruh dana yang telah cair</p>
            </div>
        </div>
    </section>

    <section class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50/50 p-6 dark:border-gray-700 dark:bg-slate-800/30">
            <div>
                <h3 class="flex items-center gap-2 text-lg font-bold text-gray-800 dark:text-white">
                    <span class="material-symbols-outlined text-[#0F4C81] dark:text-blue-400">payments</span>
                    Ajukan Penarikan Dana
                </h3>
                <p class="mt-1 text-xs text-gray-500">Lengkapi data untuk mengirim permintaan pencairan dana.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Pencairan via</span>
                <div class="flex items-center gap-1 text-sm font-bold italic text-[#0F4C81] dark:text-blue-400">
                    MIDTRANS
                </div>
            </div>
        </div>

        <div class="p-8">
            <div class="mb-14">
                <div class="mx-auto max-w-4xl px-4">
                    <div class="relative flex items-center justify-between">
                        <div class="absolute left-0 top-1/2 -z-0 h-1 w-full -translate-y-1/2 rounded-full bg-gray-200 dark:bg-gray-700"></div>
                        <div class="absolute left-0 top-1/2 -z-0 h-1 -translate-y-1/2 rounded-full bg-[#0F4C81] shadow-[0_0_8px_rgba(15,76,129,0.3)] transition-all duration-700 dark:bg-blue-500" style="width: {{ $this->getProgressWidth() }}%;"></div>

                        <div class="relative z-10 flex flex-col items-center">
                            <div class="{{ $this->getStepCircleClass('input') }} flex h-12 w-12 items-center justify-center rounded-full">
                                <span class="material-symbols-outlined text-[24px]">edit</span>
                            </div>
                            <div class="absolute top-14 flex w-32 flex-col items-center">
                                <span class="{{ $this->getStepTitleClass('input') }} whitespace-nowrap text-[11px] font-bold uppercase tracking-tight">Input Data</span>
                                <span class="{{ $this->getStepSubtitleClass('input') }} mt-0.5 text-center text-[9px] leading-tight">Sedang Diisi</span>
                            </div>
                        </div>

                        <div class="relative z-10 flex flex-col items-center">
                            <div class="{{ $this->getStepCircleClass('approval') }} flex h-12 w-12 items-center justify-center rounded-full">
                                <span class="material-symbols-outlined text-[20px]">admin_panel_settings</span>
                            </div>
                            <div class="absolute top-14 flex w-44 flex-col items-center">
                                <span class="{{ $this->getStepTitleClass('approval') }} whitespace-nowrap text-[11px] font-bold uppercase tracking-tight">Menunggu Persetujuan</span>
                                <span class="{{ $this->getStepSubtitleClass('approval') }} mt-0.5 text-center text-[9px] leading-tight">Tahap Berikutnya</span>
                            </div>
                        </div>

                        <div class="relative z-10 flex flex-col items-center">
                            <div class="{{ $this->getStepCircleClass('midtrans') }} flex h-12 w-12 items-center justify-center rounded-full">
                                <span class="material-symbols-outlined text-[20px]">sync_alt</span>
                            </div>
                            <div class="absolute top-14 flex w-32 flex-col items-center">
                                <span class="{{ $this->getStepTitleClass('midtrans') }} whitespace-nowrap text-[11px] font-bold uppercase tracking-tight">Diproses Midtrans</span>
                                <span class="{{ $this->getStepSubtitleClass('midtrans') }} mt-0.5 text-center text-[9px] leading-tight">Antrian Transfer</span>
                            </div>
                        </div>

                        <div class="relative z-10 flex flex-col items-center">
                            <div class="{{ $this->getStepCircleClass('done') }} flex h-12 w-12 items-center justify-center rounded-full">
                                <span class="material-symbols-outlined text-[20px]">verified</span>
                            </div>
                            <div class="absolute top-14 flex w-32 flex-col items-center">
                                <span class="{{ $this->getStepTitleClass('done') }} whitespace-nowrap text-[11px] font-bold uppercase tracking-tight">Selesai</span>
                                <span class="{{ $this->getStepSubtitleClass('done') }} mt-0.5 text-center text-[9px] leading-tight">Dana Terkirim</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (! $penarikanAktif)
                <form wire:submit.prevent="ajukanPenarikan" class="grid grid-cols-1 gap-x-12 gap-y-6 border-t border-gray-100 pt-10 dark:border-gray-800 lg:grid-cols-2">
                    <div class="space-y-6">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Jumlah Penarikan (IDR)</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                    <span class="font-bold text-gray-400">Rp</span>
                                </div>
                                <input
                                    type="number"
                                    min="50000"
                                    wire:model="jumlah_penarikan"
                                    placeholder="0"
                                    class="block w-full rounded-xl border-gray-200 bg-gray-50 py-4 pl-12 pr-4 text-xl font-bold transition-all focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-gray-700 dark:bg-slate-800 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-400"
                                >
                            </div>
                            <div class="mt-2 flex items-center gap-2">
                                <span class="material-symbols-outlined text-[14px] text-gray-400">info</span>
                                <p class="text-[11px] font-medium italic text-gray-400">Minimal penarikan Rp 50.000 (Potongan biaya admin Midtrans Rp 2.500)</p>
                            </div>
                            @error('jumlah_penarikan') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">Pilih Rekening Bank Tujuan</label>
                            <select
                                wire:model="rekening_tujuan"
                                class="block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3.5 transition-all focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-gray-700 dark:bg-slate-800 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            >
                                <option value="">-- Pilih Rekening Terdaftar --</option>
                                @foreach ($rekeningOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('rekening_tujuan') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex flex-col justify-between space-y-6">
                        <div class="rounded-xl border border-blue-100/50 bg-blue-50/50 p-5 dark:border-blue-900/30 dark:bg-blue-900/10">
                            <h4 class="mb-3 flex items-center gap-2 text-xs font-bold uppercase tracking-wide text-[#0F4C81] dark:text-blue-400">
                                <span class="material-symbols-outlined text-[18px]">verified_user</span>
                                Ringkasan &amp; Konfirmasi
                            </h4>
                            <div class="space-y-3">
                                <div class="flex items-start gap-3">
                                    <div class="mt-0.5 flex h-5 w-5 flex-shrink-0 items-center justify-center rounded bg-[#0F4C81]/10 dark:bg-blue-500/10">
                                        <span class="material-symbols-outlined text-[14px] text-[#0F4C81] dark:text-blue-400">check</span>
                                    </div>
                                    <p class="text-xs leading-tight text-gray-600 dark:text-gray-400">Pastikan data rekening sudah benar. Kesalahan input sepenuhnya tanggung jawab pengguna.</p>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="mt-0.5 flex h-5 w-5 flex-shrink-0 items-center justify-center rounded bg-[#0F4C81]/10 dark:bg-blue-500/10">
                                        <span class="material-symbols-outlined text-[14px] text-[#0F4C81] dark:text-blue-400">check</span>
                                    </div>
                                    <p class="text-xs leading-tight text-gray-600 dark:text-gray-400">Proses verifikasi admin memerlukan waktu maksimal 1x24 jam di hari kerja.</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <button type="button" wire:click="resetForm" class="px-6 py-3 text-sm font-semibold text-gray-500 transition-colors hover:text-gray-700 dark:hover:text-gray-300">Bersihkan</button>
                            <button type="submit" class="flex items-center gap-2 rounded-xl bg-[#0F4C81] px-8 py-4 text-sm font-bold text-white shadow-lg transition-all duration-200 hover:bg-[#0d3f6d] hover:shadow-[#0F4C81]/30" wire:loading.attr="disabled">
                                <span class="material-symbols-outlined text-[20px]">send</span>
                                Kirim Pengajuan
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <div class="border-t border-gray-100 pt-10 dark:border-gray-800">
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 px-6 py-5 text-amber-800 dark:border-amber-900/40 dark:bg-amber-950/20 dark:text-amber-200">
                        <p class="text-sm font-semibold">Pengajuan penarikan masih diproses</p>
                        <p class="mt-2 text-sm leading-6">
                            Anda memiliki pengajuan penarikan dana yang sedang diproses sebesar <span class="font-bold">Rp {{ number_format($activeNominal, 0, ',', '.') }}</span>.
                            Harap tunggu hingga selesai sebelum mengajukan penarikan baru.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <section class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex items-center justify-between border-b border-gray-100 p-6 dark:border-gray-700">
            <h3 class="flex items-center gap-2 text-lg font-bold text-gray-800 dark:text-white">
                <span class="material-symbols-outlined text-[#0F4C81] dark:text-blue-400">history</span>
                Riwayat Penarikan Dana
            </h3>

            <div class="flex gap-2">
                <div class="relative">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="searchRiwayat"
                        placeholder="Cari ID..."
                        class="rounded-lg border-gray-200 bg-gray-50 py-2 pl-9 pr-4 text-xs focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-gray-700 dark:bg-slate-800 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-400"
                    >
                    <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-[18px] text-gray-400">search</span>
                </div>

                <div x-data="{ open: false }" class="relative">
                    <button
                        type="button"
                        @click="open = ! open"
                        @click.outside="open = false"
                        class="flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-600 transition-colors hover:bg-gray-100 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-slate-800"
                    >
                        <span class="material-symbols-outlined text-[18px]">filter_list</span>
                        {{ $this->getRiwayatFilterLabel() }}
                    </button>

                    <div
                        x-cloak
                        x-show="open"
                        x-transition.origin.top.right
                        class="absolute right-0 z-20 mt-2 w-48 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xl dark:border-slate-700 dark:bg-slate-900"
                    >
                        @foreach ($riwayatStatusOptions as $value => $label)
                            <button
                                type="button"
                                wire:click="$set('filterRiwayatStatus', '{{ $value }}')"
                                @click="open = false"
                                @class([
                                    'flex w-full items-center justify-between px-4 py-3 text-left text-sm transition',
                                    'bg-blue-50 text-[#0F4C81] dark:bg-blue-500/10 dark:text-blue-300' => $filterRiwayatStatus === $value,
                                    'text-slate-600 hover:bg-gray-50 dark:text-slate-200 dark:hover:bg-slate-800/70' => $filterRiwayatStatus !== $value,
                                ])
                            >
                                <span>{{ $label }}</span>
                                @if ($filterRiwayatStatus === $value)
                                    <span class="material-symbols-outlined text-[18px]">check</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead class="bg-gray-50 text-[11px] uppercase tracking-wider text-gray-500 dark:bg-slate-800 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-4 font-bold">ID Transaksi</th>
                        <th class="px-6 py-4 font-bold">Tanggal</th>
                        <th class="px-6 py-4 font-bold">Jumlah (Rp)</th>
                        <th class="px-6 py-4 font-bold">Bank Tujuan</th>
                        <th class="px-6 py-4 text-center font-bold">Status Tahapan</th>
                        <th class="px-6 py-4 text-right font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($riwayatPenarikan as $riwayat)
                        <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-slate-800/50">
                            <td class="px-6 py-5 font-bold text-[#0F4C81] dark:text-blue-400">{{ $this->getWithdrawalDisplayId($riwayat) }}</td>
                            <td class="px-6 py-5 text-gray-600 dark:text-gray-400">{{ $riwayat->created_at?->locale('id')->translatedFormat('d M Y, H:i') }}</td>
                            <td class="px-6 py-5 font-bold text-slate-800 dark:text-white">Rp {{ number_format($riwayat->nominal, 0, ',', '.') }}</td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-800 dark:text-white">{{ $this->getWithdrawalBankName($riwayat) }}</span>
                                    <span class="text-[10px] italic text-gray-400">{{ $this->getWithdrawalBankMeta($riwayat) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="{{ $this->getStatusBadgeClasses($riwayat->status) }} inline-flex items-center rounded-full px-3 py-1 text-[10px] font-bold uppercase">
                                        {{ $this->getStatusLabel($riwayat->status) }}
                                    </span>
                                    @if ($riwayat->status === 'ditolak')
                                        <p class="mt-1 max-w-[220px] text-center text-[10px] italic text-red-500 dark:text-red-300">
                                            Alasan: {{ $riwayat->alasan_penolakan ?? 'Pengajuan pencairan ditolak oleh admin.' }}
                                        </p>
                                    @endif
                                    <div class="mt-1 flex items-center gap-0.5">
                                        @for ($dot = 1; $dot <= 4; $dot++)
                                            <div class="h-1.5 w-1.5 rounded-full {{ $this->getStatusProgressDotClass($riwayat->status, $dot) }}"></div>
                                        @endfor
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">
                                    {{ $riwayat->alasan_penolakan !== null && $riwayat->alasan_penolakan !== '' ? 'Ada catatan' : '-' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-14 text-center text-sm text-gray-500 dark:text-gray-400">
                                Belum ada riwayat penarikan dana.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between border-t border-gray-100 bg-gray-50/50 p-4 dark:border-gray-700 dark:bg-slate-800/30">
            <p class="text-[11px] italic text-gray-500">Menampilkan riwayat penarikan dana terbaru</p>
            <span class="text-xs font-bold uppercase tracking-wider text-[#0F4C81] dark:text-blue-400">{{ $riwayatPenarikan->count() }} transaksi</span>
        </div>
    </section>
</div>
