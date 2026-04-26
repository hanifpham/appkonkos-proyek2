@section('mitra-title', 'Transaksi Midtrans')
@section('mitra-subtitle', 'Pantau transaksi pembayaran penyewa, metode bayar, dan sinkron status Midtrans.')

<div class="flex-1 space-y-8 overflow-y-auto p-8">
    <section class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Pendapatan Platform</p>
                <span class="material-symbols-outlined text-2xl text-emerald-600 dark:text-emerald-400">trending_up</span>
            </div>
            <h3 class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
            <p class="mt-2 text-[10px] font-medium uppercase tracking-tighter text-gray-400">Komisi Aplikasi Terkumpul</p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Transaksi Sukses</p>
                <span class="material-symbols-outlined text-2xl text-[#0F4C81] dark:text-blue-400">check_circle</span>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($totalSukses, 0, ',', '.') }}</h3>
            <p class="mt-2 text-[10px] font-medium uppercase tracking-tighter text-gray-400">Pembayaran Berhasil</p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Mitra Pemilik Aktif</p>
                <span class="material-symbols-outlined text-2xl text-[#0F4C81] dark:text-blue-400">person</span>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($mitraAktif, 0, ',', '.') }}</h3>
            <p class="mt-2 text-[10px] font-medium uppercase tracking-tighter text-gray-400">Mitra Terverifikasi</p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-slate-900">
            <div class="mb-4 flex items-start justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Pencairan Tertunda</p>
                <span class="material-symbols-outlined text-2xl text-orange-600 dark:text-orange-400">hourglass_top</span>
            </div>
            <h3 class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ number_format($pencairanTertunda, 0, ',', '.') }} Pengajuan</h3>
            <p class="mt-2 text-[10px] font-medium uppercase tracking-tighter text-gray-400">Perlu Persetujuan Segera</p>
        </div>
    </section>

    <section class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-md dark:border-gray-700 dark:bg-slate-900">
        <div class="flex items-center justify-between gap-4 border-b border-gray-100 bg-slate-50/50 p-6 dark:border-gray-700 dark:bg-slate-800/20">
            <div class="flex items-center gap-4">
                <h3 class="flex items-center gap-2 text-lg font-bold text-gray-800 dark:text-white">
                    <span class="material-symbols-outlined text-[#0F4C81] dark:text-blue-400">receipt_long</span>
                    Live Transaksi Midtrans
                </h3>
                <div class="flex items-center gap-2 rounded-full border border-green-100 bg-green-50 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-green-600 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
                    <span class="h-2 w-2 animate-pulse rounded-full bg-green-500"></span>
                    Live Updates
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-end gap-2">
                <select wire:model.live="filterMetode" class="w-40 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-600 focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-800 dark:text-gray-300">
                    <option value="">Semua Metode</option>
                    <option value="gopay">GoPay</option>
                    <option value="qris">QRIS</option>
                    <option value="bank_transfer">Virtual Account</option>
                </select>

                <select wire:model.live="filterStatus" class="w-40 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-600 focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-800 dark:text-gray-300">
                    <option value="">Semua Status</option>
                    <option value="settlement">Lunas</option>
                    <option value="pending">Pending</option>
                    <option value="expire">Gagal</option>
                </select>

                <button
                    type="button"
                    wire:click="exportData"
                    class="flex items-center gap-2 rounded-lg bg-[#113C7A] px-4 py-2 text-xs font-bold text-white shadow-md hover:bg-[#0d2f60]"
                >
                    <span class="material-symbols-outlined text-sm">download</span>
                    Ekspor Excel
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead class="bg-gray-50 text-[11px] font-bold uppercase tracking-widest text-gray-600 dark:bg-slate-800 dark:text-gray-300">
                    <tr>
                        <th class="border-b border-gray-100 px-6 py-5 dark:border-gray-700">Order ID</th>
                        <th class="border-b border-gray-100 px-6 py-5 dark:border-gray-700">Penyewa</th>
                        <th class="border-b border-gray-100 px-6 py-5 dark:border-gray-700">Properti</th>
                        <th class="border-b border-gray-100 px-6 py-5 dark:border-gray-700">Pemilik Kos</th>
                        <th class="border-b border-gray-100 px-6 py-5 dark:border-gray-700">Nominal</th>
                        <th class="border-b border-gray-100 px-6 py-5 dark:border-gray-700">Metode Bayar</th>
                        <th class="border-b border-gray-100 px-6 py-5 dark:border-gray-700">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($transaksi as $item)
                        @php
                            $metodeIcon = $this->getMetodeIcon($item);
                        @endphp

                        <tr wire:key="midtrans-row-{{ $item->id }}" class="transition hover:bg-blue-50/30 dark:hover:bg-slate-800/50">
                            <td class="whitespace-nowrap px-6 py-4 font-bold text-gray-800 dark:text-white">
                                {{ $this->getOrderId($item) }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $this->getPenyewaName($item) }}</span>
                            </td>
                            <td class="px-6 py-4 italic text-gray-500 dark:text-gray-400">{{ $this->getNamaProperti($item) }}</td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $this->getNamaPemilik($item) }}</td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="text-lg font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format((int) $item->nominal_bayar, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="{{ $metodeIcon['wrapper'] }} flex h-7 w-7 items-center justify-center rounded-md">
                                        <span class="material-symbols-outlined {{ $metodeIcon['iconClass'] }} text-[16px]">{{ $metodeIcon['icon'] }}</span>
                                    </div>
                                    <span class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $this->getMetodeLabel($item) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="{{ $this->getStatusBadgeClasses($item) }} rounded-full px-3 py-1 text-[10px] font-bold uppercase">
                                        {{ $this->getStatusLabel($item) }}
                                    </span>

                                    @if ($this->canSync($item))
                                        <button
                                            type="button"
                                            wire:click="syncMidtrans('{{ $item->midtrans_order_id }}')"
                                            wire:loading.attr="disabled"
                                            wire:target="syncMidtrans"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-amber-200 bg-amber-50 text-amber-700 transition hover:bg-amber-100 disabled:cursor-not-allowed disabled:opacity-60 dark:border-amber-800 dark:bg-amber-900/30 dark:text-amber-300"
                                            title="Sinkronisasi status dari Midtrans"
                                        >
                                            <span
                                                class="material-symbols-outlined text-[18px]"
                                                wire:loading.class="animate-spin"
                                                wire:target="syncMidtrans"
                                            >
                                                sync
                                            </span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                Belum ada transaksi Midtrans yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 bg-slate-50 p-6 dark:border-gray-700 dark:bg-slate-800/40">
            {{ $transaksi->links() }}
        </div>
    </section>
</div>
