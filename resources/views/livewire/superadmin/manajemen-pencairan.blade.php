@section('mitra-title', 'Manajemen Pencairan Dana')
@section('mitra-subtitle', 'Kelola pengajuan pencairan dana mitra pemilik kos dan pantau status pencairannya.')

@php
    $filterStatusOptions = [
        '' => 'Semua Status',
        'pending' => 'Pending',
        'sukses' => 'Sukses',
        'ditolak' => 'Ditolak',
    ];
@endphp

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

    <section class="overflow-visible rounded-xl border border-gray-100 bg-white shadow-md dark:border-gray-700 dark:bg-slate-900">
        <div class="relative z-10 p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col gap-4 bg-slate-50/50 dark:bg-slate-800/20 xl:flex-row xl:items-center xl:justify-between">
            <div class="flex items-center gap-4">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#113C7A] dark:text-blue-400">account_balance_wallet</span>
                    Daftar Pengajuan Pencairan
                </h3>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center xl:mr-5 xl:flex-nowrap xl:justify-end">
                <div class="relative w-full sm:w-[250px] xl:w-[220px]">
                    <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400 dark:text-slate-500">
                        <span class="material-symbols-outlined text-[18px]">search</span>
                    </span>
                    <input
                        wire:model.live.debounce.300ms="search"
                        class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 pl-12 pr-4 text-sm text-slate-700 placeholder:text-slate-400 shadow-sm transition-all dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500 focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:focus:border-blue-400 dark:focus:ring-blue-400"
                        placeholder="Cari ID atau Nama..."
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

                <tbody wire:loading.remove wire:target="search, filterStatus, gotoPage, nextPage, previousPage" class="divide-y divide-gray-100 dark:divide-gray-700">
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
                                    <div class="w-8 h-8 rounded-full {{ $bankWrapperClass }} flex items-center justify-center border">
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
                                            <span wire:loading.remove wire:target="konfirmasiSetuju({{ $item->id }})" class="material-symbols-outlined text-[14px]">check</span>
                                            <span wire:loading.remove wire:target="konfirmasiSetuju({{ $item->id }})">Setuju</span>
                                            <span wire:loading wire:target="konfirmasiSetuju({{ $item->id }})" class="flex items-center gap-1">
                                                <span class="material-symbols-outlined animate-spin text-[14px]">sync</span>
                                                Memproses...
                                            </span>
                                        </button>

                                        <button
                                            type="button"
                                            wire:click="konfirmasiTolak({{ $item->id }})"
                                            wire:loading.attr="disabled"
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-md text-[11px] font-bold transition-all shadow-sm flex items-center gap-1"
                                        >
                                            <span wire:loading.remove wire:target="konfirmasiTolak({{ $item->id }})" class="material-symbols-outlined text-[14px]">close</span>
                                            <span wire:loading.remove wire:target="konfirmasiTolak({{ $item->id }})">Tolak</span>
                                            <span wire:loading wire:target="konfirmasiTolak({{ $item->id }})" class="flex items-center gap-1">
                                                <span class="material-symbols-outlined animate-spin text-[14px]">sync</span>
                                                Memproses...
                                            </span>
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
                <x-skeleton.table wire:loading wire:target="search, filterStatus, gotoPage, nextPage, previousPage" rows="5" cols="6" />
            </table>
        </div>

        <div class="p-6 bg-slate-50 dark:bg-slate-800/40 border-t border-gray-100 dark:border-gray-700">
            {{ $listPencairan->links() }}
        </div>
    </section>
</div>