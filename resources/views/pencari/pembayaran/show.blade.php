<x-public-layout>
    {{-- Grid Layout Utama --}}
    <div class="bg-[#f8fafc] dark:bg-slate-950 min-h-screen pb-12">
        {{-- Stepper / Progress Bar Horizontal --}}
        <div class="bg-white dark:bg-slate-900 border-b border-[#e5e7eb] dark:border-slate-800 py-4 mb-8 sticky top-16 z-10 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 flex items-center">
                {{-- Tombol Kembali --}}
                <a href="{{ route('pencari.riwayat-pesanan') }}" class="w-10 h-10 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-[#1967d2] transition-colors border border-transparent hover:border-slate-200 dark:hover:border-slate-700 mr-4 shrink-0">
                    <span class="material-symbols-outlined text-[24px]">arrow_back</span>
                </a>
                
                <div class="flex-1 max-w-2xl mx-auto items-center justify-between relative hidden sm:flex">
                    {{-- Line connector --}}
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-[#1967d2] -z-10 rounded-full"></div>
                    
                    {{-- Step 1 --}}
                    <div class="flex flex-col items-center gap-2 bg-white dark:bg-slate-900 px-2">
                        <div class="w-8 h-8 rounded-full bg-[#1967d2] text-white flex items-center justify-center border-4 border-white dark:border-slate-900 shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">check</span>
                        </div>
                        <span class="text-xs font-semibold text-slate-900 dark:text-white">Pilih Kamar</span>
                    </div>
                    
                    {{-- Step 2 --}}
                    <div class="flex flex-col items-center gap-2 bg-white dark:bg-slate-900 px-2">
                        <div class="w-8 h-8 rounded-full bg-[#1967d2] text-white flex items-center justify-center border-4 border-white dark:border-slate-900 shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">check</span>
                        </div>
                        <span class="text-xs font-semibold text-slate-900 dark:text-white">Isi Data Sewa</span>
                    </div>
                    
                    {{-- Step 3 --}}
                    <div class="flex flex-col items-center gap-2 bg-white dark:bg-slate-900 px-2">
                        <div class="w-8 h-8 rounded-full bg-[#1967d2] text-white flex items-center justify-center border-4 border-white dark:border-slate-900 shadow-sm ring-4 ring-blue-50 dark:ring-blue-900/30">
                            <span class="text-sm font-bold">3</span>
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

                        <a href="{{ route('home') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 py-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            <span class="material-symbols-outlined text-lg">arrow_back</span>
                            Kembali ke Dashboard
                        </a>
                    </div>
                </div>
                
                <div class="sm:hidden flex-1 text-center font-bold text-slate-800 dark:text-white">
                    Tahap Pembayaran
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            @php
                $payment = $booking->pembayaran;
                $durasi = ($booking->tgl_mulai_sewa && $booking->tgl_selesai_sewa) ? (int) $booking->tgl_mulai_sewa->diffInMonths($booking->tgl_selesai_sewa) : 0;
                $isKosan = $booking->kamar_id !== null;
                $properti = $isKosan ? $booking->kamar?->tipeKamar?->kosan : $booking->kontrakan;
                $propertyName = $properti?->nama_properti ?? 'Properti';
                $fotoUrl = $properti ? $properti->getMediaDisplayUrl('foto_properti') : '';
                $fallbackIcon = $isKosan ? 'bed' : 'house';
            @endphp

            {{-- KOLOM KIRI: Data Sewa (Sudah Terisi) --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Alert Midtrans Sandbox --}}
                @unless ($booking->pembayaran?->isSuccessful())
                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-2xl p-5 flex gap-3 shadow-sm">
                    <span class="material-symbols-outlined text-amber-500 shrink-0 text-[24px]">info</span>
                    <div>
                        <h4 class="font-bold text-amber-800 dark:text-amber-300 text-sm mb-1">Mode Sandbox Aktif</h4>
                        <p class="text-sm text-amber-700 dark:text-amber-400">Transaksi ini menggunakan sistem testing Midtrans. Anda dapat menggunakan metode pembayaran apapun untuk simulasi tanpa memotong saldo asli.</p>
                    </div>
                </div>
                @endunless

                {{-- Status Booking Header --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-[#e5e7eb] dark:border-slate-800 p-6 shadow-sm flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">ID Booking: <span class="text-[#1967d2]">#{{ strtoupper(substr($booking->id, 0, 8)) }}</span></h2>
                        <p class="text-sm text-[#6b7280] dark:text-slate-400 mt-1">Selesaikan pembayaran agar pesanan Anda dapat segera dikonfirmasi.</p>
                    </div>
                    
                    <div class="shrink-0 hidden sm:block">
                        <span @class([
                            'inline-flex items-center gap-1.5 rounded-full px-4 py-2 text-sm font-bold',
                            'bg-emerald-50 text-emerald-700 border border-emerald-200' => $payment?->isSuccessful(),
                            'bg-rose-50 text-rose-700 border border-rose-200' => $payment?->isFailed(),
                            'bg-amber-50 text-amber-700 border border-amber-200' => ! ($payment?->isSuccessful() || $payment?->isFailed()),
                        ])>
                            @if($payment?->isSuccessful())
                                <span class="material-symbols-outlined text-[18px]">check_circle</span> Lunas
                            @elseif($payment?->isFailed())
                                <span class="material-symbols-outlined text-[18px]">cancel</span> Gagal
                            @else
                                <span class="material-symbols-outlined text-[18px]">schedule</span> Menunggu
                            @endif
                        </span>
                    </div>
                </div>

                {{-- Card 1: Informasi Penyewa --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-[#e5e7eb] dark:border-slate-800 p-6 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-10 pointer-events-none">
                        <span class="material-symbols-outlined text-[100px]">person</span>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#1967d2]">person</span> Data Penyewa
                    </h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Nama Lengkap</p>
                            <p class="font-semibold text-slate-900 dark:text-white">{{ auth()->user()->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Email</p>
                            <p class="font-semibold text-slate-900 dark:text-white">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="sm:col-span-2 border-t border-slate-100 dark:border-slate-800 mt-2 pt-4">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Nomor Handphone</p>
                            <p class="font-semibold text-slate-900 dark:text-white">{{ auth()->user()->no_telepon ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Detail Waktu Sewa --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-[#e5e7eb] dark:border-slate-800 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#1967d2]">calendar_month</span> Rincian Waktu Sewa
                    </h2>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-4 bg-slate-50 dark:bg-slate-950/50">
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">login</span> Check-in
                            </p>
                            <p class="text-base font-bold text-slate-900 dark:text-white">
                                {{ $booking->tgl_mulai_sewa ? $booking->tgl_mulai_sewa->translatedFormat('d F Y') : '-' }}
                            </p>
                        </div>
                        <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-4 bg-slate-50 dark:bg-slate-950/50">
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">logout</span> Check-out
                            </p>
                            <p class="text-base font-bold text-slate-900 dark:text-white">
                                {{ $booking->tgl_selesai_sewa ? $booking->tgl_selesai_sewa->translatedFormat('d F Y') : '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 flex justify-between items-center bg-blue-50 dark:bg-blue-900/20 px-4 py-3 rounded-xl border border-blue-100 dark:border-blue-900/50">
                        <span class="text-sm font-semibold text-[#1967d2] dark:text-blue-400">Durasi Sewa</span>
                        <span class="font-bold text-[#1967d2] dark:text-blue-400">{{ $durasi }} Bulan</span>
                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN: Ringkasan Pesanan --}}
            <div class="lg:col-span-1">
                <div class="sticky top-40 bg-white dark:bg-slate-900 rounded-2xl border border-[#e5e7eb] dark:border-slate-800 shadow-md p-6">
                    <h2 class="font-bold text-lg text-slate-900 dark:text-white mb-5">Ringkasan Pesanan</h2>
                    
                    <div class="flex gap-4 mb-5 pb-5 border-b border-[#e5e7eb] dark:border-slate-700">
                        <div class="w-20 h-20 shrink-0 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 flex items-center justify-center relative">
                            @if($fotoUrl)
                                <img src="{{ $fotoUrl }}" alt="{{ $propertyName }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.parentElement.innerHTML='<span class=\'material-symbols-outlined text-[32px] text-slate-400\'>{{ $fallbackIcon }}</span>';">
                            @else
                                <span class="material-symbols-outlined text-[32px] text-slate-400">{{ $fallbackIcon }}</span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col justify-center">
                            <span class="px-2 py-0.5 inline-block bg-blue-50 dark:bg-blue-900/30 text-[#1967d2] dark:text-blue-400 text-[10px] font-bold rounded border border-blue-100 dark:border-blue-800 mb-1.5 uppercase tracking-wider self-start">
                                {{ $isKosan ? 'Kosan' : 'Kontrakan' }}
                            </span>
                            <h3 class="font-bold text-[15px] text-slate-900 dark:text-white leading-tight mb-1 truncate">{{ $propertyName }}</h3>
                            @if($isKosan && $booking->kamar)
                                <p class="text-sm text-[#6b7280] dark:text-slate-400 truncate">Tipe {{ $booking->kamar->tipeKamar?->nama_tipe }} — Kamar {{ $booking->kamar->nomor_kamar }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Rincian Harga --}}
                    <div class="space-y-3 mb-4 text-sm">
                        <div class="flex justify-between text-slate-600 dark:text-slate-400">
                            <span>Harga Sewa ({{ $durasi }} Bulan)</span>
                            <span class="font-medium text-slate-900 dark:text-white">Rp {{ number_format((int) $booking->total_biaya, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600 dark:text-slate-400">
                            <span>Biaya Layanan</span>
                            <span class="font-medium text-emerald-600 dark:text-emerald-400">Gratis</span>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-[#e5e7eb] dark:border-slate-700 my-4 pt-4">
                        <div class="flex justify-between items-end">
                            <span class="font-bold text-slate-900 dark:text-white text-sm">Total Tagihan</span>
                            <span class="text-2xl font-bold text-[#1967d2] dark:text-blue-400">Rp {{ number_format((int) $booking->total_biaya, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Tombol Bayar --}}
                    @if ($payment?->isSuccessful())
                        <div class="w-full bg-emerald-50 text-emerald-700 font-bold py-3.5 rounded-xl mt-4 flex justify-center items-center gap-2 border border-emerald-200">
                            <span class="material-symbols-outlined text-[20px]">check_circle</span>
                            Pembayaran Berhasil
                        </div>
                    @else
                        <button 
                            type="button"
                            id="pay-button"
                            data-token-url="{{ route('pencari.pembayaran.snap-token', $booking) }}"
                            class="w-full bg-[#1967d2] hover:bg-[#0f4fb5] text-white font-bold py-3.5 rounded-xl mt-4 shadow-md shadow-blue-500/20 transition-all flex justify-center items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed"
                        >
                            <span class="material-symbols-outlined text-lg">payments</span>
                            {{ $payment?->isFailed() ? 'Coba Bayar Lagi' : 'Bayar Sekarang' }}
                        </button>
                        
                        <div class="mt-4 flex items-center justify-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                            <span class="material-symbols-outlined text-[14px]">verified_user</span>
                            <span>Pembayaran aman & terenkripsi oleh Midtrans</span>
                        </div>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    @unless ($booking->pembayaran?->isSuccessful())
        @push('scripts')
            <script src="{{ $snapScriptUrl }}" data-client-key="{{ $midtransClientKey }}"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const payButton = document.getElementById('pay-button');

                    if (!payButton) return;

                    payButton.addEventListener('click', async function () {
                        const originalText = payButton.innerHTML;
                        payButton.disabled = true;
                        payButton.innerHTML = '<span class="material-symbols-outlined text-lg animate-spin">refresh</span> Memproses...';

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
                                    alert('Pembayaran gagal! Silakan coba lagi.');
                                    window.location.reload();
                                },
                                onClose: function () {
                                    payButton.disabled = false;
                                    payButton.innerHTML = originalText;
                                }
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
</x-public-layout>
