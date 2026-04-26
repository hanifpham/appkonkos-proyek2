@section('mitra-title', 'Pengajuan Refund')
@section('mitra-subtitle', 'Kelola dan tinjau permintaan pengembalian dana dari pengguna secara efisien.')

<div class="flex-1 p-8 pb-12 space-y-8 overflow-y-auto">
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

    <section class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-md dark:border-gray-700 dark:bg-slate-900">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-slate-50/30 dark:bg-slate-800/20">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#113C7A] dark:text-blue-400">history</span>
                    Daftar Pengajuan Refund
                </h3>

                <div class="flex flex-wrap items-center justify-start gap-2 xl:justify-end">
                    <div class="relative w-full sm:w-72 lg:w-80">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">search</span>
                        <input
                            wire:model.live.debounce.300ms="search"
                            class="w-full rounded-lg border border-gray-200 bg-white py-2 pl-10 pr-4 text-xs text-gray-600 shadow-sm transition-all focus:border-[#113C7A] focus:ring-[#113C7A] dark:border-slate-700 dark:bg-slate-800 dark:text-gray-300"
                            placeholder="Cari ID Refund atau Transaksi..."
                            type="text"
                        />
                    </div>

                    <select
                        wire:model.live="filterStatus"
                        class="w-40 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-600 focus:border-[#113C7A] focus:ring-[#113C7A] dark:border-slate-700 dark:bg-slate-800 dark:text-gray-300"
                    >
                        <option value="">Semua Status</option>
                        <option value="pending">Perlu Ditinjau</option>
                        <option value="approved">Disetujui</option>
                        <option value="rejected">Ditolak</option>
                    </select>

                    <button
                        type="button"
                        wire:click="exportData"
                        class="flex items-center gap-2 rounded-lg bg-[#113C7A] px-4 py-2 text-xs font-bold text-white shadow-md transition-colors hover:bg-[#0d2f60]"
                    >
                        <span class="material-symbols-outlined text-[18px]">file_download</span>
                        Ekspor Data
                    </button>
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
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold {{ $this->getStatusBadgeClasses((string) $refund->status_refund) }} uppercase tracking-tight">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $this->getStatusDotClasses((string) $refund->status_refund) }}"></span>
                                    {{ $this->getStatusLabel((string) $refund->status_refund) }}
                                </span>
                            </td>

                            <td class="px-8 py-5">
                                <div class="flex items-center justify-center gap-2">
                                    @if($refund->status_refund === 'pending')
                                        <button
                                            type="button"
                                            wire:click="tinjauRefund({{ $refund->id }})"
                                            wire:loading.attr="disabled"
                                            class="bg-[#113C7A] hover:bg-[#0d2f60] text-white px-5 py-2.5 rounded-lg text-xs font-bold transition-all shadow-md active:scale-95 flex items-center gap-2 ring-offset-2 focus:ring-2 focus:ring-[#113C7A]"
                                        >
                                            <span class="material-symbols-outlined text-[18px]">rate_review</span>
                                            Tinjau Pengajuan
                                        </button>
                                    @else
                                        <button
                                            type="button"
                                            wire:click="tinjauPengajuan({{ $refund->id }})"
                                            wire:loading.attr="disabled"
                                            class="border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 px-4 py-2.5 rounded-lg text-xs font-bold transition-all"
                                        >
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
