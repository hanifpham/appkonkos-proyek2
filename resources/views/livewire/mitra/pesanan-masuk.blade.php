@section('mitra-title', 'Pesanan Masuk')
@section('mitra-subtitle', 'Kelola konfirmasi pesanan dari calon penyewa baru.')

<div class="flex-1 p-6 md:p-8 space-y-8">
    <section class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 dark:shadow-[0_10px_24px_rgba(2,6,23,0.28)]">
            <p class="mb-1 text-sm font-medium text-gray-500 dark:text-slate-400">Menunggu Konfirmasi</p>
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-bold text-gray-800 dark:text-slate-100">{{ $statMenunggu }} Pesanan</h3>
                <span class="material-symbols-outlined rounded-lg bg-orange-50 p-2 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400">pending_actions</span>
            </div>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 dark:shadow-[0_10px_24px_rgba(2,6,23,0.28)]">
            <p class="mb-1 text-sm font-medium text-gray-500 dark:text-slate-400">Telah Dikonfirmasi</p>
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-bold text-gray-800 dark:text-slate-100">{{ $statDikonfirmasi }} Pesanan</h3>
                <span class="material-symbols-outlined rounded-lg bg-green-50 p-2 text-green-600 dark:bg-green-900/30 dark:text-green-400">check_circle</span>
            </div>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 dark:shadow-[0_10px_24px_rgba(2,6,23,0.28)]">
            <p class="mb-1 text-sm font-medium text-gray-500 dark:text-slate-400">Total Bulan Ini</p>
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-bold text-gray-800 dark:text-slate-100">{{ $statTotal }} Pesanan</h3>
                <span class="material-symbols-outlined rounded-lg bg-blue-50 p-2 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">analytics</span>
            </div>
        </div>
    </section>

    <section class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900 dark:shadow-[0_14px_30px_rgba(2,6,23,0.32)]">
        <div class="flex flex-col justify-between gap-4 border-b border-gray-100 p-6 dark:border-slate-800 sm:flex-row sm:items-center">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[#0F4C81] dark:text-blue-400">receipt_long</span>
                <h3 class="text-lg font-bold text-gray-800 dark:text-slate-100">Daftar Pesanan Masuk</h3>
            </div>

            <div class="flex flex-col items-stretch gap-3 sm:flex-row sm:items-center">
                <div class="relative min-w-[280px]">
                    <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center justify-center text-slate-400 dark:text-slate-500">
                        <span class="material-symbols-outlined leading-none">search</span>
                    </span>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari ID atau Nama..."
                        class="h-10 w-full rounded-xl border border-gray-200 bg-gray-50 pl-14 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                    >
                </div>

                <div x-data="{ open: false }" class="relative">
                    <button
                        type="button"
                        @click="open = ! open"
                        @click.outside="open = false"
                        class="flex h-10 items-center gap-2 rounded-xl border border-gray-200 bg-gray-50 px-4 text-sm font-medium text-gray-600 transition hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:border-blue-400 dark:hover:text-blue-300"
                    >
                        <span class="material-symbols-outlined text-[18px]">filter_list</span>
                        {{ $this->getActiveFilterLabel() }}
                    </button>

                    <div
                        x-cloak
                        x-show="open"
                        x-transition.origin.top.right
                        class="absolute right-0 z-20 mt-2 w-52 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xl dark:border-slate-700 dark:bg-slate-800"
                    >
                        @foreach ($filterOptions as $value => $label)
                            <button
                                type="button"
                                wire:click="$set('statusFilter', '{{ $value }}')"
                                @click="open = false"
                                @class([
                                    'flex w-full items-center justify-between px-4 py-3 text-left text-sm transition',
                                    'bg-blue-50 text-[#0F4C81] dark:bg-blue-500/10 dark:text-blue-300' => $statusFilter === $value,
                                    'text-slate-600 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-700 dark:hover:text-white' => $statusFilter !== $value,
                                ])
                            >
                                <span>{{ $label }}</span>
                                @if ($statusFilter === $value)
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
                <thead class="bg-gray-50 text-xs uppercase text-gray-600 dark:bg-slate-800 dark:text-slate-300">
                    <tr>
                        <th class="px-6 py-4 font-semibold">ID Booking</th>
                        <th class="px-6 py-4 font-semibold">Nama Penyewa</th>
                        <th class="px-6 py-4 font-semibold">Properti</th>
                        <th class="px-6 py-4 font-semibold">Tanggal Mulai</th>
                        <th class="px-6 py-4 font-semibold">Status Bayar</th>
                        <th class="px-6 py-4 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800 dark:bg-slate-900">
                    @forelse ($pesanan as $item)
                        @php
                            $penyewa = $item->pencariKos?->user;
                            $propertyImageUrl = $this->getPropertyImageUrl($item);
                        @endphp

                        <tr wire:key="pesanan-{{ $item->id }}" class="transition-colors hover:bg-gray-50/80 dark:hover:bg-slate-800/70">
                            <td class="px-6 py-5 font-medium text-gray-800 dark:text-slate-100" title="{{ $item->id }}">
                                {{ $this->getBookingDisplayId($item) }}
                            </td>

                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    @if (($penyewa?->profile_photo_url ?? '') !== '')
                                        <div class="h-10 w-10 flex-shrink-0 overflow-hidden rounded-full border border-gray-200 bg-slate-100 dark:border-slate-700 dark:bg-slate-800">
                                            <img
                                                src="{{ $penyewa?->profile_photo_url }}"
                                                alt="{{ $penyewa?->name ?? 'Penyewa' }}"
                                                class="h-full w-full object-cover"
                                            >
                                        </div>
                                    @else
                                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full border border-gray-200 bg-slate-100 text-xs font-bold text-slate-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                            {{ strtoupper(substr($penyewa?->name ?? 'P', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="flex flex-col">
                                        <span class="font-medium text-gray-900 dark:text-slate-100">{{ $penyewa?->name ?? 'Penyewa' }}</span>
                                        <span class="text-[11px] text-gray-500 dark:text-slate-400">{{ $penyewa?->email ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    @if ($propertyImageUrl !== '')
                                        <img
                                            src="{{ $propertyImageUrl }}"
                                            alt="{{ $this->getPropertyName($item) }}"
                                            class="aspect-square h-12 w-12 flex-shrink-0 rounded-lg object-cover shadow-sm"
                                        >
                                    @else
                                        <div class="flex aspect-square h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-slate-100 to-slate-200 text-[10px] font-bold text-slate-500 shadow-sm dark:from-slate-800 dark:to-slate-700 dark:text-slate-300">
                                            APK
                                        </div>
                                    @endif

                                    <div class="flex flex-col">
                                        <span class="font-medium text-gray-700 dark:text-slate-200">{{ $this->getPropertyName($item) }}</span>
                                        <span class="text-[11px] text-gray-500 dark:text-slate-400">{{ $this->getPropertyUnitLabel($item) }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-5 text-gray-600 dark:text-slate-300">
                                {{ $item->tgl_mulai_sewa?->locale('id')->translatedFormat('d M Y') ?? '-' }}
                            </td>

                            <td class="px-6 py-5">
                                <span class="{{ $this->getStatusBayarClasses($item) }} rounded-full px-3 py-1 text-[11px] font-bold">
                                    {{ $this->getStatusBayarLabel($item) }}
                                </span>
                            </td>

                            <td class="px-6 py-5 text-right">
                                @if ($this->canProcessBooking($item))
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            type="button"
                                            wire:click="konfirmasiPesanan('{{ $item->id }}')"
                                            wire:loading.attr="disabled"
                                            wire:target="konfirmasiPesanan,tolakPesanan"
                                            class="rounded bg-[#0F4C81] px-4 py-2 text-xs font-semibold text-white shadow-sm transition-colors hover:bg-[#0c3c66] disabled:cursor-not-allowed disabled:opacity-60"
                                        >
                                            Konfirmasi
                                        </button>
                                        <button
                                            type="button"
                                            wire:click="tolakPesanan('{{ $item->id }}')"
                                            wire:loading.attr="disabled"
                                            wire:target="konfirmasiPesanan,tolakPesanan"
                                            class="rounded border border-red-200 bg-white px-4 py-2 text-xs font-semibold text-red-600 transition-colors hover:bg-red-50 disabled:cursor-not-allowed disabled:opacity-60 dark:border-red-800 dark:bg-red-950/10 dark:text-red-400 dark:hover:bg-red-950/20"
                                        >
                                            Tolak
                                        </button>
                                    </div>
                                @else
                                    <span class="text-xs italic text-gray-400 dark:text-slate-500">{{ $this->getBookingActionMessage($item) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-14 text-center text-sm text-gray-500 dark:text-slate-400">
                                Belum ada pesanan yang sesuai dengan pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col gap-4 border-t border-gray-100 p-4 dark:border-slate-800 dark:bg-slate-900 sm:flex-row sm:items-center sm:justify-between">
            <span class="text-xs text-gray-500 dark:text-slate-400">
                Menampilkan {{ $pesanan->firstItem() ?? 0 }} sampai {{ $pesanan->lastItem() ?? 0 }} dari {{ $pesanan->total() }} pesanan
            </span>
            {{ $pesanan->links() }}
        </div>
    </section>
</div>
