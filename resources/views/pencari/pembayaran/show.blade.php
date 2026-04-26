<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-600">Midtrans Sandbox</p>
            <h2 class="text-2xl font-bold text-slate-900">Pembayaran Booking</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-[minmax(0,1.1fr)_360px]">
                <section class="overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-6 py-5">
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-400">Detail Booking</p>
                        <h3 class="mt-2 text-2xl font-bold text-slate-900">{{ $propertyName }}</h3>
                        <p class="mt-2 text-sm text-slate-500">Pemilik: {{ $ownerName }}</p>
                    </div>

                    <div class="grid gap-4 px-6 py-6 sm:grid-cols-2">
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">ID Booking</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">#{{ strtoupper(substr($booking->id, 0, 8)) }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Periode Sewa</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">{{ $periodLabel }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Status Booking</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">{{ strtoupper((string) $booking->status_booking) }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Order Midtrans</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">{{ $booking->pembayaran?->midtrans_order_id ?? 'Belum dibuat' }}</p>
                        </div>
                    </div>
                </section>

                <aside class="overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-6 py-5">
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-400">Ringkasan Pembayaran</p>
                        <h3 class="mt-2 text-3xl font-bold text-[#0F4C81]">Rp {{ number_format((int) $booking->total_biaya, 0, ',', '.') }}</h3>
                    </div>

                    <div class="space-y-5 px-6 py-6">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Status Saat Ini</p>
                            <div class="mt-3">
                                @php($payment = $booking->pembayaran)
                                <span @class([
                                    'inline-flex rounded-full px-3 py-1 text-xs font-semibold',
                                    'bg-emerald-50 text-emerald-700 border border-emerald-200' => $payment?->isSuccessful(),
                                    'bg-rose-50 text-rose-700 border border-rose-200' => $payment?->isFailed(),
                                    'bg-amber-50 text-amber-700 border border-amber-200' => ! ($payment?->isSuccessful() || $payment?->isFailed()),
                                ])>
                                    {{ $payment?->isSuccessful() ? 'Lunas' : ($payment?->isFailed() ? 'Gagal' : 'Menunggu Pembayaran') }}
                                </span>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm leading-6 text-slate-600">
                            Pembayaran menggunakan Midtrans Sandbox. Status akhir tetap disinkronkan dari webhook Midtrans, jadi bila baru saja membayar dan status belum berubah, cukup refresh halaman ini beberapa detik kemudian.
                        </div>

                        @if ($payment?->isSuccessful())
                            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-4 text-sm font-medium text-emerald-700">
                                Pembayaran sudah diterima. Silakan tunggu konfirmasi pemilik properti.
                            </div>
                        @else
                            <button
                                type="button"
                                id="pay-button"
                                data-token-url="{{ route('pencari.pembayaran.snap-token', $booking) }}"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-[#0F4C81] px-5 py-4 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0c3d67] disabled:cursor-not-allowed disabled:opacity-70"
                            >
                                <span class="material-symbols-outlined text-lg">payments</span>
                                {{ $payment?->isFailed() ? 'Coba Bayar Lagi' : 'Bayar Sekarang' }}
                            </button>
                        @endif

                        <a href="{{ route('dashboard') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 py-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            <span class="material-symbols-outlined text-lg">arrow_back</span>
                            Kembali ke Dashboard
                        </a>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    @unless ($booking->pembayaran?->isSuccessful())
        @push('scripts')
            <script src="{{ $snapScriptUrl }}" data-client-key="{{ $midtransClientKey }}"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const payButton = document.getElementById('pay-button');

                    if (!payButton) {
                        return;
                    }

                    payButton.addEventListener('click', async function () {
                        const originalText = payButton.innerHTML;
                        payButton.disabled = true;
                        payButton.innerHTML = '<span class="material-symbols-outlined text-lg">progress_activity</span>Menyiapkan pembayaran...';

                        try {
                            const response = await fetch(payButton.dataset.tokenUrl, {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                },
                                body: JSON.stringify({}),
                            });

                            const payload = await response.json();

                            if (!response.ok || !payload.snap_token) {
                                throw new Error(payload.message || 'Gagal membuat transaksi Midtrans.');
                            }

                            window.snap.pay(payload.snap_token, {
                                onSuccess: function () {
                                    window.location.reload();
                                },
                                onPending: function () {
                                    window.location.reload();
                                },
                                onError: function () {
                                    window.location.reload();
                                },
                                onClose: function () {
                                    payButton.disabled = false;
                                    payButton.innerHTML = originalText;
                                },
                            });
                        } catch (error) {
                            alert(error.message || 'Terjadi masalah saat menyiapkan pembayaran.');
                            payButton.disabled = false;
                            payButton.innerHTML = originalText;
                        }
                    });
                });
            </script>
        @endpush
    @endunless
</x-app-layout>
