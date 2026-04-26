@section('mitra-title', 'Manajemen Pencairan Dana')
@section('mitra-subtitle', 'Kelola pengajuan pencairan dana mitra pemilik kos dan pantau status pencairannya.')

<div class="flex-1 p-8 space-y-8 overflow-y-auto">
    <section class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
            <div class="flex justify-between items-start mb-4">
                <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider">Total Dana Dicairkan</p>
                <span class="material-symbols-outlined text-[#113C7A] dark:text-blue-400 text-2xl">account_balance</span>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 dark:text-white">Rp {{ number_format($totalDicairkan, 0, ',', '.') }}</h3>
            <p class="text-[10px] text-gray-400 mt-2 font-medium uppercase tracking-tighter">Sepanjang Waktu</p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
            <div class="flex justify-between items-start mb-4">
                <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider">Permintaan Pending</p>
                <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-2xl">hourglass_empty</span>
            </div>
            <h3 class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ number_format($permintaanPending, 0, ',', '.') }} Pengajuan</h3>
            <p class="text-[10px] text-gray-400 mt-2 font-medium uppercase tracking-tighter">Menunggu Persetujuan</p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
            <div class="flex justify-between items-start mb-4">
                <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider">Berhasil Hari Ini</p>
                <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-2xl">check_circle</span>
            </div>
            <h3 class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($berhasilHariIni, 0, ',', '.') }}</h3>
            <p class="text-[10px] text-gray-400 mt-2 font-medium uppercase tracking-tighter">Transaksi selesai hari ini</p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
            <div class="flex justify-between items-start mb-4">
                <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider">Rata-rata Nominal</p>
                <span class="material-symbols-outlined text-[#113C7A] dark:text-blue-400 text-2xl">analytics</span>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 dark:text-white">Rp {{ number_format($rataRataNominal, 0, ',', '.') }}</h3>
            <p class="text-[10px] text-gray-400 mt-2 font-medium uppercase tracking-tighter">Per Pencairan</p>
        </div>
    </section>

    <section class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-md dark:border-gray-700 dark:bg-slate-900">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col gap-4 bg-slate-50/50 dark:bg-slate-800/20 xl:flex-row xl:items-center xl:justify-between">
            <div class="flex items-center gap-4">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#113C7A] dark:text-blue-400">account_balance_wallet</span>
                    Daftar Pengajuan Pencairan
                </h3>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <span class="material-symbols-outlined text-sm">search</span>
                    </span>
                    <input
                        wire:model.live.debounce.300ms="search"
                        class="pl-10 pr-4 py-2 text-xs border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-slate-800 text-gray-600 dark:text-gray-300 focus:ring-primary focus:border-primary"
                        placeholder="Cari ID atau Nama..."
                        type="text"
                    />
                </div>

                <select
                    wire:model.live="filterStatus"
                    class="w-40 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-600 focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-800 dark:text-gray-300"
                >
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="sukses">Sukses</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left table-actionable border-collapse">
                <thead class="bg-gray-50 dark:bg-slate-800 text-gray-600 dark:text-gray-300 uppercase text-[11px] tracking-widest font-bold">
                    <tr>
                        <th class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">ID Pencairan</th>
                        <th class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">Pemilik Kos</th>
                        <th class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">Bank Tujuan</th>
                        <th class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">Nominal</th>
                        <th class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">Status</th>
                        <th class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($listPencairan as $item)
                        @php
                            $bankName = strtoupper((string) ($item->pemilikProperti?->nama_bank ?? '-'));

                            if ($bankName === 'BCA') {
                                $bankWrapperClass = 'bg-blue-50 dark:bg-blue-900/20 border-blue-100 dark:border-blue-800';
                                $bankIconClass = 'text-blue-600 dark:text-blue-400';
                            } elseif ($bankName === 'MANDIRI') {
                                $bankWrapperClass = 'bg-orange-50 dark:bg-orange-900/20 border-orange-100 dark:border-orange-800';
                                $bankIconClass = 'text-orange-700 dark:text-orange-400';
                            } elseif ($bankName === 'BNI') {
                                $bankWrapperClass = 'bg-red-50 dark:bg-red-900/20 border-red-100 dark:border-red-800';
                                $bankIconClass = 'text-red-700 dark:text-red-400';
                            } elseif ($bankName === 'BRI') {
                                $bankWrapperClass = 'bg-blue-50 dark:bg-blue-900/20 border-blue-100 dark:border-blue-800';
                                $bankIconClass = 'text-blue-800 dark:text-blue-500';
                            } else {
                                $bankWrapperClass = 'bg-gray-50 dark:bg-slate-800 border-gray-100 dark:border-gray-700';
                                $bankIconClass = 'text-gray-500 dark:text-gray-300';
                            }

                            if ($item->status === 'pending' || $item->status === 'menunggu') {
                                $statusClass = 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300 border border-amber-200 dark:border-amber-800';
                            } elseif ($item->status === 'sukses') {
                                $statusClass = 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300 border border-green-200 dark:border-green-800';
                            } elseif ($item->status === 'ditolak') {
                                $statusClass = 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300 border border-red-200 dark:border-red-800';
                            } else {
                                $statusClass = 'bg-gray-100 text-gray-700 dark:bg-slate-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700';
                            }
                        @endphp

                        <tr wire:key="pencairan-row-{{ $item->id }}" class="transition hover:bg-blue-50/30 dark:hover:bg-slate-800/50">
                            <td class="px-6 py-4 font-bold text-gray-800 dark:text-white">
                                {{ $this->getWithdrawalDisplayId($item) }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $item->pemilikProperti?->user?->name ?? '-' }}</span>
                                    <span class="text-[11px] text-gray-500">{{ $item->pemilikProperti?->user?->email ?? '-' }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg {{ $bankWrapperClass }} flex items-center justify-center border">
                                        <span class="material-symbols-outlined text-[18px] {{ $bankIconClass }}">account_balance</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-xs text-gray-700 dark:text-gray-300">{{ $bankName }}</span>
                                        <span class="text-[10px] text-gray-500 font-mono tracking-wider">{{ $item->pemilikProperti?->nomor_rekening ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <span class="text-xl font-extrabold dark:text-blue-400">Rp {{ number_format((int) $item->nominal, 0, ',', '.') }}</span>
                            </td>

                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold {{ $statusClass }} uppercase">{{ $this->getStatusLabel((string) $item->status) }}</span>
                            </td>

                            <td class="px-6 py-4">
                                @if($item->status === 'pending' || $item->status === 'menunggu')
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            type="button"
                                            wire:click="konfirmasiSetuju({{ $item->id }})"
                                            wire:loading.attr="disabled"
                                            class="bg-[#0F4C81] hover:bg-[#0d3f6d] text-white px-3 py-1.5 rounded-md text-[11px] font-bold transition-all shadow-sm flex items-center gap-1"
                                        >
                                            <span class="material-symbols-outlined text-[14px]">check</span>
                                            Setuju
                                        </button>

                                        <button
                                            type="button"
                                            wire:click="konfirmasiTolak({{ $item->id }})"
                                            wire:loading.attr="disabled"
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-md text-[11px] font-bold transition-all shadow-sm flex items-center gap-1"
                                        >
                                            <span class="material-symbols-outlined text-[14px]">close</span>
                                            Tolak
                                        </button>
                                    </div>
                                @else
                                    <div class="flex items-center justify-center">
                                        <button type="button" disabled class="text-gray-400 cursor-not-allowed px-3 py-1.5 text-[11px] font-bold flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px]">history</span>
                                            {{ $item->status === 'ditolak' ? 'Ditolak' : ($item->status === 'disetujui' ? 'Diproses' : 'Selesai') }}
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                Belum ada pengajuan pencairan dana yang sesuai dengan pencarian atau filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 bg-slate-50 dark:bg-slate-800/40 border-t border-gray-100 dark:border-gray-700">
            {{ $listPencairan->links() }}
        </div>
    </section>
</div>
