@section('mitra-title', 'Riwayat Booking')
@section('mitra-subtitle', 'Pantau transaksi booking yang masuk ke seluruh properti Anda dengan status pembayaran terbaru.')

<div class="flex-1 space-y-8 p-6 md:p-8">
    <section class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <div class="rounded-2xl border border-emerald-100 bg-white p-6 shadow-sm dark:border-emerald-900/30 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Settlement</p>
            <div class="mt-3 flex items-center justify-between">
                <h3 class="text-3xl font-bold text-slate-900 dark:text-white">{{ $statSettlement }}</h3>
                <span class="material-symbols-outlined rounded-xl bg-emerald-50 p-2 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-300">payments</span>
            </div>
        </div>

        <div class="rounded-2xl border border-amber-100 bg-white p-6 shadow-sm dark:border-amber-900/30 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Pending</p>
            <div class="mt-3 flex items-center justify-between">
                <h3 class="text-3xl font-bold text-slate-900 dark:text-white">{{ $statPending }}</h3>
                <span class="material-symbols-outlined rounded-xl bg-amber-50 p-2 text-amber-600 dark:bg-amber-950/30 dark:text-amber-300">hourglass_top</span>
            </div>
        </div>

        <div class="rounded-2xl border border-rose-100 bg-white p-6 shadow-sm dark:border-rose-900/30 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Refunded</p>
            <div class="mt-3 flex items-center justify-between">
                <h3 class="text-3xl font-bold text-slate-900 dark:text-white">{{ $statRefunded }}</h3>
                <span class="material-symbols-outlined rounded-xl bg-rose-50 p-2 text-rose-600 dark:bg-rose-950/30 dark:text-rose-300">assignment_return</span>
            </div>
        </div>
    </section>

    <section class="overflow-visible rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="relative z-10 flex flex-col gap-4 border-b border-slate-200 p-6 dark:border-slate-800 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Daftar Riwayat Booking</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Semua data di halaman ini hanya memuat transaksi milik properti Anda.</p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center lg:justify-end">
                <div class="relative min-w-[280px]">
                    <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400 dark:text-slate-500">
                        <span class="material-symbols-outlined text-[18px]">search</span>
                    </span>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari penyewa, properti, atau ID booking"
                        class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 pl-12 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                    >
                </div>

                <div x-data="{ open: false }" class="relative">
                    <button
                        type="button"
                        @click="open = ! open"
                        @click.outside="open = false"
                        class="flex h-11 min-w-[190px] items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-blue-400 dark:hover:text-blue-300"
                    >
                        <span class="flex min-w-0 items-center gap-2">
                            <span class="material-symbols-outlined shrink-0 text-[18px]">filter_list</span>
                            <span class="truncate">{{ $this->getActiveFilterLabel() }}</span>
                        </span>
                        <span class="material-symbols-outlined shrink-0 text-[18px] text-slate-400 dark:text-slate-500">expand_more</span>
                    </button>

                    <div
                        x-cloak
                        x-show="open"
                        x-transition.origin.top.right
                        class="absolute right-0 z-20 mt-2 w-[220px] overflow-hidden rounded-2xl border border-slate-200 bg-white p-1 shadow-xl dark:border-slate-700 dark:bg-slate-800"
                    >
                        @foreach ($filterOptions as $value => $label)
                            <button
                                type="button"
                                wire:click="$set('statusFilter', '{{ $value }}')"
                                @click="open = false"
                                @class([
                                    'flex w-full items-center justify-between rounded-xl px-4 py-3 text-left text-sm transition',
                                    'bg-blue-50 font-semibold text-[#0F4C81] dark:bg-blue-500/10 dark:text-blue-300' => $statusFilter === $value,
                                    'text-slate-600 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-700/70' => $statusFilter !== $value,
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
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-[0.08em] text-slate-500 dark:bg-slate-800 dark:text-slate-300">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Penyewa</th>
                        <th class="px-6 py-4 font-semibold">Properti</th>
                        <th class="px-6 py-4 font-semibold">Check-in</th>
                        <th class="px-6 py-4 font-semibold">Total Bayar</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($bookings as $booking)
                        @php
                            $penyewa = $booking->pencariKos?->user;
                            $propertyImageUrl = $this->getPropertyImageUrl($booking);
                            $isRefunded = $booking->pembayaran?->normalizedStatus() === 'refund' || ($booking->refund?->status_refund === 'selesai');
                        @endphp
                        <tr wire:key="booking-history-{{ $booking->id }}" class="align-top transition hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-slate-100 text-sm font-bold text-slate-500 dark:bg-slate-800 dark:text-slate-200">
                                        {{ strtoupper(substr($penyewa?->name ?? 'P', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-slate-900 dark:text-white">{{ $penyewa?->name ?? 'Penyewa' }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $this->getBookingDisplayId($booking) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    @if ($propertyImageUrl !== '')
                                        <img src="{{ $propertyImageUrl }}" alt="{{ $this->getPropertyName($booking) }}" class="h-12 w-12 rounded-xl object-cover">
                                    @else
                                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-slate-100 to-slate-200 text-[10px] font-bold text-slate-500 dark:from-slate-800 dark:to-slate-700 dark:text-slate-300">
                                            APK
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="font-semibold text-slate-800 dark:text-slate-100">{{ $this->getPropertyName($booking) }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $this->getPropertyUnitLabel($booking) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-slate-600 dark:text-slate-300">
                                {{ $booking->tgl_mulai_sewa?->locale('id')->translatedFormat('d M Y') ?? '-' }}
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $this->formatRupiah($this->getTotalBayar($booking)) }}</p>
                                @if ($isRefunded)
                                    <p class="mt-1 text-xs font-semibold text-rose-600 dark:text-rose-300">
                                        Pendapatan Mitra: {{ $this->formatRupiah($this->getPendapatanMitra($booking)) }}
                                    </p>
                                @endif
                            </td>
                            <td class="px-6 py-5">
                                <span class="{{ $this->getStatusClasses($booking) }} inline-flex rounded-full px-3 py-1 text-[11px] font-bold tracking-wide">
                                    {{ $this->getStatusLabel($booking) }}
                                </span>
                                @if ($isRefunded)
                                    <p class="mt-2 max-w-[220px] text-xs leading-5 text-rose-600 dark:text-rose-300">
                                        Booking otomatis batal dan pendapatan Mitra untuk transaksi ini menjadi {{ $this->formatRupiah(0) }}.
                                    </p>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-right">
                                @if ($this->canSyncMidtrans($booking))
                                    <button
                                        type="button"
                                        wire:click="syncMidtrans('{{ $booking->id }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="syncMidtrans"
                                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 transition hover:border-[#0F4C81] hover:text-[#0F4C81] disabled:cursor-not-allowed disabled:opacity-60 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-blue-400 dark:hover:text-blue-300"
                                    >
                                        <span class="material-symbols-outlined text-[16px]">sync</span>
                                        Sync Midtrans
                                    </button>
                                @else
                                    <span class="text-xs text-slate-400 dark:text-slate-500">
                                        {{ $isRefunded ? 'Refund selesai' : 'Tidak ada aksi' }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-14 text-center text-sm text-slate-500 dark:text-slate-400">
                                Belum ada riwayat booking yang cocok dengan filter saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col gap-4 border-t border-slate-200 px-6 py-4 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-xs text-slate-500 dark:text-slate-400">
                Menampilkan {{ $bookings->firstItem() ?? 0 }} sampai {{ $bookings->lastItem() ?? 0 }} dari {{ $bookings->total() }} booking
            </p>
            {{ $bookings->links() }}
        </div>
    </section>
</div>
