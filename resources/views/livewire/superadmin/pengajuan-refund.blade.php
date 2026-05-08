@section('mitra-title', 'Pengajuan Refund')
@section('mitra-subtitle', 'Kelola dan tinjau permintaan pengembalian dana dari pengguna secara efisien.')

@php
    $filterStatusOptions = [
        '' => 'Semua Status',
        'pending' => 'Perlu Ditinjau',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
    ];
@endphp

<div class="flex-1 p-8 pb-12 space-y-8 overflow-y-auto">
    @if(session()->has('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-green-700 shadow-sm dark:border-green-800/50 dark:bg-green-900/40 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-700 shadow-sm dark:border-red-800/50 dark:bg-red-900/40 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <section class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
            <div class="flex justify-between items-start mb-4">
                <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider">Total Pengajuan</p>
                <span class="material-symbols-outlined text-[#113C7A] dark:text-blue-400 text-3xl">assignment_returned</span>
            </div>
            <h3 class="text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($totalPengajuanBulanIni, 0, ',', '.') }}</h3>
            <p class="text-[10px] text-gray-400 mt-2 font-medium uppercase tracking-tighter">Bulan Ini</p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm ring-2 ring-amber-100 dark:border-gray-700 dark:bg-slate-900 dark:ring-amber-900/40">
            <div class="flex justify-between items-start mb-4">
                <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider">Perlu Ditinjau</p>
                <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-3xl">pending_actions</span>
            </div>
            <h3 class="text-3xl font-bold text-amber-600 dark:text-amber-400">{{ number_format($perluDitinjau, 0, ',', '.') }}</h3>
            <p class="text-[10px] text-gray-400 mt-2 font-medium uppercase tracking-tighter">Antrean Refund</p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
            <div class="flex justify-between items-start mb-4">
                <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider">Disetujui</p>
                <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-3xl">check_circle</span>
            </div>
            <h3 class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($disetujui, 0, ',', '.') }}</h3>
            <p class="text-[10px] text-gray-400 mt-2 font-medium uppercase tracking-tighter">Proses Selesai</p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
            <div class="flex justify-between items-start mb-4">
                <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider">Ditolak</p>
                <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-3xl">cancel</span>
            </div>
            <h3 class="text-3xl font-bold text-red-600 dark:text-red-400">{{ number_format($ditolak, 0, ',', '.') }}</h3>
            <p class="text-[10px] text-gray-400 mt-2 font-medium uppercase tracking-tighter">Tidak Memenuhi Syarat</p>
        </div>
    </section>

    <div class="flex justify-end">
        <button
            type="button"
            wire:click="exportData"
            class="inline-flex items-center gap-2 rounded-xl bg-[#113C7A] px-4 py-3 text-sm font-bold text-white shadow-md transition-colors hover:bg-[#0d2f60]"
        >
            <span class="material-symbols-outlined text-[18px]">file_download</span>
            Ekspor Data
        </button>
    </div>

    <section class="overflow-visible rounded-xl border border-gray-100 bg-white shadow-md dark:border-gray-700 dark:bg-slate-900">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-slate-50/30 dark:bg-slate-800/20">
            <div class="relative z-10 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#113C7A] dark:text-blue-400">history</span>
                    Daftar Pengajuan Refund
                </h3>

                <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center xl:mr-5 xl:flex-nowrap xl:justify-end">
                    <div class="relative w-full sm:w-[250px] xl:w-[220px]">
                        <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400 dark:text-slate-500">
                            <span class="material-symbols-outlined text-[18px]">search</span>
                        </span>
                        <input
                            wire:model.live.debounce.300ms="search"
                            class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 pl-12 pr-4 text-sm text-slate-700 placeholder:text-slate-400 shadow-sm transition-all focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            placeholder="Cari ID Refund atau Transaksi..."
                            type="text"
                        />
                    </div>

                    <div x-data="{ open: false }" class="relative w-full sm:w-[190px]">
                        <button type="button" @click="open = ! open" @click.outside="open = false" class="flex h-11 w-full items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-blue-400 dark:hover:text-blue-300">
                            <span class="flex min-w-0 items-center gap-2">
                                <span class="material-symbols-outlined shrink-0 text-[18px]">filter_list</span>
                                <span class="truncate">{{ $filterStatusOptions[(string) $filterStatus] ?? 'Semua Status' }}</span>
                            </span>
                            <span class="material-symbols-outlined shrink-0 text-[18px] text-slate-400 dark:text-slate-500">expand_more</span>
                        </button>
                        <div x-cloak x-show="open" x-transition.origin.top.right class="absolute right-0 top-[calc(100%+8px)] z-20 w-full min-w-[220px] overflow-hidden rounded-2xl border border-slate-200 bg-white p-1 shadow-xl dark:border-slate-700 dark:bg-slate-800">
                            @foreach ($filterStatusOptions as $value => $label)
                                <button type="button" wire:click="$set('filterStatus', '{{ $value }}')" @click="open = false" @class(['flex w-full items-center justify-between rounded-xl px-4 py-3 text-left text-sm transition','bg-blue-50 font-semibold text-[#0F4C81] dark:bg-blue-500/10 dark:text-blue-300' => $filterStatus === $value,'text-slate-600 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-700/70' => $filterStatus !== $value])>
                                    <span>{{ $label }}</span>
                                    @if ($filterStatus === $value)
                                        <span class="material-symbols-outlined text-[18px]">check</span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left table-actionable border-collapse">
                <thead class="bg-gray-50 text-gray-600 dark:bg-slate-800 dark:text-gray-300 uppercase text-[11px] tracking-widest font-bold">
                    <tr>
                        <th class="px-8 py-5 border-b border-gray-100 dark:border-gray-700">Informasi Refund</th>
                        <th class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">Pengguna</th>
                        <th class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">Nominal</th>
                        <th class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">Metode Pembayaran</th>
                        <th class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">Status</th>
                        <th class="px-8 py-5 border-b border-gray-100 dark:border-gray-700 text-center">Aksi Manajemen</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($listRefund as $refund)
                        <tr wire:key="refund-row-{{ $refund->id }}" class="transition hover:bg-blue-50/30 dark:hover:bg-slate-800/50">
                            <td class="px-8 py-5">
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-800 dark:text-gray-200 text-base">{{ $this->getRefundDisplayId($refund) }}</span>
                                    <span class="text-[10px] text-gray-400 font-medium uppercase tracking-tighter">
                                        TRX: {{ $this->getTransactionDisplayId($refund) }} | {{ $refund->created_at?->format('d M Y') ?? '-' }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="text-gray-800 dark:text-gray-200 font-semibold">{{ $this->getUserName($refund) }}</span>
                                    <span class="text-xs text-gray-500">Pencari Kos</span>
                                </div>
                            </td>

                            <td class="px-6 py-5">
                                <span class="font-bold text-slate-900 dark:text-white">Rp {{ number_format((int) $refund->nominal_refund, 0, ',', '.') }}</span>
                            </td>

                            <td class="px-6 py-5">
                                @if($refund->pembayaran && $refund->pembayaran->metode_bayar)
                                    <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-md text-xs uppercase tracking-wider font-semibold">
                                        {{ str_replace('_', ' ', $refund->pembayaran->metode_bayar) }}
                                    </span>
                                @else
                                    <span class="text-gray-400 italic text-xs">Belum Tersedia</span>
                                @endif
                            </td>

                            <td class="px-6 py-5">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold {{ $this->getStatusBadgeClasses((string) $refund->status_refund) }} uppercase tracking-tight">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $this->getStatusDotClasses((string) $refund->status_refund) }}"></span>
                                    {{ $this->getStatusLabel((string) $refund->status_refund) }}
                                </span>
                            </td>

                            <td class="px-8 py-5">
                                <div class="flex items-center justify-center gap-2">
                                    @if($refund->status_refund === 'pending')
                                        @php
                                            $metode = strtolower($refund->pembayaran?->metode_bayar ?? '');
                                            $metodeManual = ['bank_transfer', 'echannel', 'bca_va', 'bni_va', 'bri_va', 'cstore'];
                                        @endphp

                                        @if(in_array($metode, $metodeManual))
                                            <button wire:click="tandaiSudahDitransfer({{ $refund->id }})" class="inline-flex items-center px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold rounded-lg transition shadow-sm">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Tandai Ditransfer
                                            </button>
                                            <span class="px-2 py-1 bg-amber-100 text-amber-700 text-[10px] font-semibold rounded-md border border-amber-200">Transfer Manual</span>
                                        @else
                                            <button wire:click="prosesRefund({{ $refund->id }})" class="inline-flex items-center px-3 py-1.5 bg-[#1967d2] hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition shadow-sm">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                Refund Otomatis
                                            </button>
                                        @endif
                                        
                                        <button wire:click="tinjauPengajuan({{ $refund->id }})" class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-semibold rounded-lg transition shadow-sm">
                                            Detail
                                        </button>
                                    @else
                                        <button wire:click="tinjauPengajuan({{ $refund->id }})" class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-semibold rounded-lg transition shadow-sm">
                                            Detail
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                Belum ada pengajuan refund yang sesuai dengan pencarian atau filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-gray-100 dark:border-gray-700 bg-gray-50/30 dark:bg-slate-800/20">
            {{ $listRefund->links() }}
        </div>
    </section>

    @if($showModalDetail && $detailRefund)
        <div class="fixed inset-0 z-[999] bg-black/75 transition-opacity" wire:click="tutupDetail"></div>

        <div class="fixed inset-0 z-[1000] flex items-center justify-center p-4 sm:p-6 pointer-events-none">
            <div class="flex max-h-[90vh] w-full max-w-3xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-slate-900 pointer-events-auto" wire:click.stop>
                <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-slate-800/50">
                    <h3 class="flex items-center gap-2 text-lg font-bold text-gray-900 dark:text-white">
                        <span class="material-symbols-outlined text-[#113C7A] dark:text-blue-400">assignment_return</span>
                        Detail Pengajuan Refund
                    </h3>
                    <button type="button" wire:click="tutupDetail" class="text-gray-400 transition-colors hover:text-red-500">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <div class="flex-1 space-y-6 overflow-y-auto p-6">
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-700 dark:bg-slate-800/50">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">ID Refund</span>
                            <p class="mt-1 text-sm font-bold text-gray-900 dark:text-white">{{ $this->getRefundDisplayId($detailRefund) }}</p>
                            <p class="text-xs text-gray-500">TRX: {{ $this->getTransactionDisplayId($detailRefund) }}</p>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-700 dark:bg-slate-800/50">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Status</span>
                            <div class="mt-2">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-[10px] font-bold {{ $this->getStatusBadgeClasses((string) $detailRefund->status_refund) }} uppercase tracking-tight">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $this->getStatusDotClasses((string) $detailRefund->status_refund) }}"></span>
                                    {{ $this->getStatusLabel((string) $detailRefund->status_refund) }}
                                </span>
                            </div>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-700 dark:bg-slate-800/50">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Pengguna</span>
                            <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $this->getUserName($detailRefund) }}</p>
                            <p class="text-xs text-gray-500">{{ $detailRefund->booking?->pencariKos?->user?->email ?? '-' }}</p>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-700 dark:bg-slate-800/50">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Nominal Refund</span>
                            <p class="mt-1 text-lg font-black text-[#113C7A] dark:text-blue-400">Rp {{ number_format((int) $detailRefund->nominal_refund, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">Diajukan {{ $detailRefund->created_at?->format('d M Y H:i') ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-100 bg-white p-4 dark:border-gray-700 dark:bg-slate-900">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Alasan Refund</span>
                        <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-700 dark:text-gray-300">{{ $detailRefund->alasan_refund ?: '-' }}</p>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div class="rounded-xl border border-gray-100 bg-white p-4 dark:border-gray-700 dark:bg-slate-900">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Booking</span>
                            <p class="mt-1 break-all text-sm font-semibold text-gray-900 dark:text-white">{{ $detailRefund->booking_id }}</p>
                            <p class="text-xs text-gray-500">Status: {{ $detailRefund->booking?->status_booking ?? '-' }}</p>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-white p-4 dark:border-gray-700 dark:bg-slate-900">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Pembayaran</span>
                            <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $this->getTransactionDisplayId($detailRefund) }}</p>
                            <p class="text-xs text-gray-500">
                                {{ strtoupper((string) ($detailRefund->pembayaran?->metode_bayar ?? '-')) }}
                                |
                                Rp {{ number_format((int) ($detailRefund->pembayaran?->nominal_bayar ?? 0), 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-end gap-3 border-t border-gray-100 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-slate-800/50">
                    <button type="button" wire:click="tutupDetail" class="rounded-lg border border-gray-300 bg-white px-5 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-300 dark:hover:bg-slate-600">
                        Tutup
                    </button>

                    @if($detailRefund->status_refund === 'pending')
                        <button
                            type="button"
                            wire:click="tolakRefund({{ $detailRefund->id }})"
                            wire:loading.attr="disabled"
                            class="rounded-lg bg-red-600 px-5 py-2 text-sm font-bold text-white shadow-sm transition-colors hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            Tolak Refund
                        </button>

                        <button
                            type="button"
                            wire:click="tinjauRefund({{ $detailRefund->id }})"
                            wire:loading.attr="disabled"
                            class="rounded-lg bg-[#113C7A] px-5 py-2 text-sm font-bold text-white shadow-sm transition-colors hover:bg-[#0d2f60] disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            Tinjau Refund Parsial
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if($showModalRefund && $selectedRefund)
        <div class="fixed inset-0 z-[999] bg-black/75 transition-opacity" wire:click="tutupModalRefund"></div>

        <div class="fixed inset-0 z-[1000] flex items-center justify-center p-4 sm:p-6 pointer-events-none">
            <div class="pointer-events-auto flex w-full max-w-2xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-slate-900" wire:click.stop>
                <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-slate-800/50">
                    <div>
                        <h3 class="flex items-center gap-2 text-lg font-bold text-gray-900 dark:text-white">
                            <span class="material-symbols-outlined text-[#113C7A] dark:text-blue-400">assignment_returned</span>
                            Tinjauan Refund Parsial
                        </h3>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Skema pengembalian {{ rtrim(rtrim(number_format(100 - $potonganRefundPersen, 2, ',', '.'), '0'), ',') }}% dengan potongan pembatalan {{ rtrim(rtrim(number_format($potonganRefundPersen, 2, ',', '.'), '0'), ',') }}%.
                        </p>
                    </div>

                    <button type="button" wire:click="tutupModalRefund" class="text-gray-400 transition-colors hover:text-red-500">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <div class="space-y-6 p-6">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-700 dark:bg-slate-800/40">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Refund</span>
                            <p class="mt-1 text-sm font-bold text-gray-900 dark:text-white">{{ $this->getRefundDisplayId($selectedRefund) }}</p>
                            <p class="text-xs text-gray-500">{{ $this->getUserName($selectedRefund) }}</p>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-700 dark:bg-slate-800/40">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Order Midtrans</span>
                            <p class="mt-1 text-sm font-bold text-gray-900 dark:text-white">{{ $selectedRefund->pembayaran?->midtrans_order_id ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ strtoupper((string) ($selectedRefund->pembayaran?->metode_bayar ?? '-')) }}</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-slate-900">
                        <div class="space-y-4 text-sm">
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-gray-500 dark:text-gray-400">Total Transaksi</span>
                                <span class="font-bold text-gray-900 dark:text-white">
                                    Rp {{ number_format((int) ($selectedRefund->pembayaran?->nominal_bayar ?? $selectedRefund->booking?->total_biaya ?? 0), 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-gray-500 dark:text-gray-400">Potongan Pembatalan ({{ rtrim(rtrim(number_format($potonganRefundPersen, 2, ',', '.'), '0'), ',') }}%)</span>
                                <span class="font-bold text-red-600 dark:text-red-400">
                                    - Rp {{ number_format($totalPotongan, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="h-px bg-gray-100 dark:bg-gray-700"></div>
                            <div class="flex items-center justify-between gap-4 text-base">
                                <span class="font-semibold text-gray-900 dark:text-white">Dana Dikembalikan ({{ rtrim(rtrim(number_format(100 - $potonganRefundPersen, 2, ',', '.'), '0'), ',') }}%)</span>
                                <span class="text-xl font-black text-[#113C7A] dark:text-blue-400">
                                    Rp {{ number_format($totalKembali, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-800 dark:border-amber-900/60 dark:bg-amber-900/20 dark:text-amber-200">
                        Sistem akan mencoba refund melalui API Midtrans bila metode pembayaran mendukung. Jika tidak didukung atau API gagal, proses lokal tetap diselesaikan dan dana perlu ditransfer manual.
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-end gap-3 border-t border-gray-100 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-slate-800/50">
                    <button
                        type="button"
                        wire:click="tutupModalRefund"
                        class="rounded-lg border border-gray-300 bg-white px-5 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-300 dark:hover:bg-slate-600"
                    >
                        Batal
                    </button>

                    <button
                        type="button"
                        wire:click="prosesRefund"
                        wire:loading.attr="disabled"
                        class="rounded-lg bg-[#113C7A] px-5 py-2 text-sm font-bold text-white shadow-sm transition-colors hover:bg-[#0d2f60] disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        Konfirmasi &amp; Proses Refund
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
