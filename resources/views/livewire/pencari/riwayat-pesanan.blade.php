<div class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row gap-8">

        {{-- SIDEBAR KIRI --}}
        <x-pencari.sidebar active="riwayat" />

        {{-- KONTEN UTAMA KANAN --}}
        <div class="flex-1 min-w-0">

            {{-- Flash Messages --}}
            @if(session('success'))
            <div class="mb-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-xl flex items-center gap-3">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
            @endif
            @if(session('error'))
            <div class="mb-4 bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 px-4 py-3 rounded-xl flex items-center gap-3">
                <span class="material-symbols-outlined text-[20px]">error</span>
                <span class="font-medium text-sm">{{ session('error') }}</span>
            </div>
            @endif

            {{-- Header & Tab Filter --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-5">Riwayat Pesanan Saya</h1>
                <div class="flex flex-wrap gap-2">
                    @php
                    $tabs = [
                    'semua' => 'Semua',
                    'menunggu_pembayaran' => 'Menunggu Pembayaran',
                    'aktif_berhasil' => 'Aktif / Berhasil',
                    'dibatalkan_refund' => 'Dibatalkan / Refund',
                    ];
                    @endphp
                    @foreach($tabs as $key => $label)
                    <button wire:click="setFilter('{{ $key }}')"
                        class="px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200
                            {{ $filterStatus === $key
                                ? 'bg-[#1967d2] text-white shadow-md shadow-blue-500/20'
                                : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-[#e5e7eb] dark:border-slate-700 hover:border-[#1967d2] hover:text-[#1967d2] dark:hover:text-blue-400' }}">
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Booking Cards --}}
            @forelse($bookings as $booking)
            @php
            $status = $this->getDisplayStatus($booking);
            $isKosan = $booking->kamar_id !== null;
            $properti = $isKosan ? $booking->kamar?->tipeKamar?->kosan : $booking->kontrakan;
            $namaProperti = $properti?->nama_properti ?? 'Properti';
            $fotoUrl = $properti ? $properti->getMediaDisplayUrl('foto_properti') : '';
            $orderId = $booking->pembayaran?->midtrans_order_id ?? '#TRX-' . strtoupper(substr($booking->id, 0, 8));
            $ownerPhone = $isKosan
            ? $booking->kamar?->tipeKamar?->kosan?->pemilikProperti?->user?->no_telepon
            : $booking->kontrakan?->pemilikProperti?->user?->no_telepon;
            $waPhone = $ownerPhone ? preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $ownerPhone)) : '';
            $durasi = ($booking->tgl_mulai_sewa && $booking->tgl_selesai_sewa)
            ? (int) $booking->tgl_mulai_sewa->diffInMonths($booking->tgl_selesai_sewa) : 0;
            @endphp

            <div class="bg-white dark:bg-slate-900 border border-[#e5e7eb] dark:border-slate-800 rounded-2xl shadow-sm p-5 mb-4 transition-all hover:shadow-md">
                {{-- Header Card --}}
                <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                    <div>
                        <span class="font-bold text-slate-900 dark:text-white text-sm">{{ $orderId }}</span>
                        <span class="text-sm text-[#6b7280] dark:text-slate-400 ml-2">{{ $booking->created_at->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                    @switch($status)
                    @case('menunggu_pembayaran')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>Menunggu Pembayaran
                    </span>
                    @break
                    @case('kedaluwarsa')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>Kedaluwarsa
                    </span>
                    @break
                    @case('berhasil')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aktif / Berhasil
                    </span>
                    @break
                    @case('selesai')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>Selesai
                    </span>
                    @break
                    @case('dibatalkan')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>Dibatalkan
                    </span>
                    @break
                    @case('refund')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-[#6b7280] dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Refund
                    </span>
                    @break
                    @endswitch
                </div>

                {{-- Body Card --}}
                <div class="flex gap-4">
                    <div class="shrink-0">
                        @if($fotoUrl)
                        <img src="{{ $fotoUrl }}" alt="{{ $namaProperti }}" class="w-24 h-24 rounded-lg object-cover border border-slate-100 dark:border-slate-700">
                        @else
                        <div class="w-24 h-24 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                            <span class="material-symbols-outlined text-[32px]">apartment</span>
                        </div>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="font-bold text-lg text-slate-900 dark:text-white truncate">{{ $namaProperti }}</h3>
                        @if($isKosan && $booking->kamar)
                        <p class="text-sm text-[#6b7280] dark:text-slate-400 mt-0.5">
                            Tipe {{ $booking->kamar->tipeKamar?->nama_tipe ?? '-' }} — Nomor Kamar: <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $booking->kamar->nomor_kamar }}</span>
                        </p>
                        @else
                        <p class="text-sm text-[#6b7280] dark:text-slate-400 mt-0.5">Kontrakan</p>
                        @endif
                        @if($booking->tgl_mulai_sewa)
                        <p class="text-sm text-[#6b7280] dark:text-slate-400 mt-1 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">calendar_month</span>
                            Check-in: {{ $booking->tgl_mulai_sewa->translatedFormat('d F Y') }}
                            @if($durasi > 0) <span class="font-semibold text-slate-700 dark:text-slate-300">({{ $durasi }} Bulan)</span> @endif
                        </p>
                        @endif
                    </div>
                </div>

                {{-- Footer Card --}}
                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-[#e5e7eb] dark:border-slate-800 mt-4 pt-4">
                    <div>
                        <p class="text-xs text-[#6b7280] dark:text-slate-500">Total Pembayaran</p>
                        <p class="text-xl font-bold text-[#1967d2] dark:text-blue-400">Rp {{ number_format($booking->total_biaya ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex flex-wrap gap-3 items-end">
                        @if($status === 'menunggu_pembayaran' || $status === 'kedaluwarsa')
                        <div class="flex flex-col items-end gap-2">
                            @if($this->getExpiryTime($booking))
                            <div x-data="{ 
                                expiry: new Date('{{ $this->getExpiryTime($booking) }}').getTime(),
                                timeLeft: 0,
                                formatTime(ms) {
                                    if (ms <= 0) return '00:00:00';
                                    const hours = Math.floor(ms / 3600000);
                                    const minutes = Math.floor((ms % 3600000) / 60000);
                                    const seconds = Math.floor((ms % 60000) / 1000);
                                    return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                                }
                            }" x-init="
                                timeLeft = expiry - new Date().getTime();
                                setInterval(() => {
                                    timeLeft = expiry - new Date().getTime();
                                }, 1000);
                            " class="text-[11px] font-bold px-2 py-0.5 rounded bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 border border-rose-100 dark:border-rose-800/50">
                                <span x-show="timeLeft > 0">Sisa Waktu: <span x-text="formatTime(timeLeft)"></span></span>
                                <span x-show="timeLeft <= 0">Waktu Habis</span>
                            </div>
                            @endif
                            <div class="flex gap-2">
                                <button wire:click="batalkanPesanan('{{ $booking->id }}')" wire:confirm="Apakah Anda yakin ingin membatalkan pesanan ini?" class="px-4 py-2 border border-red-500 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl text-sm font-semibold transition">
                                    Batalkan
                                </button>
                                <button wire:click="lanjutkanPembayaran('{{ $booking->id }}')" class="inline-flex items-center gap-2 px-5 py-2 bg-[#1967d2] hover:bg-[#0f4fb5] text-white text-sm font-bold rounded-xl transition-all shadow-md shadow-blue-500/20">
                                    <span class="material-symbols-outlined text-[18px]">payment</span>
                                    {{ $status === 'kedaluwarsa' ? 'Ulangi Pembayaran' : 'Lanjutkan Pembayaran' }}
                                </button>
                            </div>
                        </div>
                        @elseif($status === 'berhasil')
                        <a href="{{ route('pencari.e-ticket', $booking->id) }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition-all shadow-md shadow-emerald-500/20">
                            <span class="material-symbols-outlined text-[18px]">receipt_long</span>Lihat E-Ticket
                        </a>
                        @if($waPhone)
                        <a href="https://wa.me/{{ $waPhone }}?text={{ urlencode('Halo, saya penyewa dari APPKONKOS untuk properti ' . $namaProperti . '. Saya ingin menghubungi Anda.') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 border border-emerald-500 dark:border-emerald-600 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 text-sm font-bold rounded-xl transition-all">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                                <path d="M12 0C5.373 0 0 5.373 0 12c0 2.112.549 4.098 1.504 5.834L0 24l6.335-1.652A11.94 11.94 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.94 0-3.76-.558-5.304-1.516l-.38-.226-3.758.98.998-3.648-.25-.394A9.954 9.954 0 012 12C2 6.486 6.486 2 12 2s10 4.486 10 10-4.486 10-10 10z" />
                            </svg>
                            Hubungi Pemilik
                        </a>
                        @endif
                        <button wire:click="openRefundModal('{{ $booking->id }}')" class="inline-flex items-center gap-2 px-4 py-2.5 border border-rose-400 dark:border-rose-600 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 text-sm font-bold rounded-xl transition-all">
                            <span class="material-symbols-outlined text-[18px]">assignment_return</span>Ajukan Refund
                        </button>
                        @elseif($status === 'selesai' && !$booking->ulasan)
                        <button class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#32baff] hover:bg-[#1da8f0] text-white text-sm font-bold rounded-xl transition-all shadow-md shadow-sky-500/20">
                            <span class="material-symbols-outlined text-[18px]">rate_review</span>Beri Ulasan
                        </button>
                        @elseif($status === 'dibatalkan')
                        <button wire:click="hapusPesanan('{{ $booking->id }}')" 
                                wire:confirm="Apakah Anda yakin ingin menghapus riwayat pesanan ini secara permanen?" 
                                class="px-4 py-2 flex items-center gap-2 border border-red-500 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl text-sm font-semibold transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Hapus Riwayat
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center py-12 text-center bg-white dark:bg-slate-900 border border-[#e5e7eb] dark:border-slate-800 rounded-2xl shadow-sm">
                <svg class="text-gray-300 dark:text-slate-700 w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500 dark:text-slate-400 font-medium">Belum ada pesanan di sini.</p>
            </div>
            @endforelse

            {{-- Paginasi --}}
            <div class="mt-6">
                {{ $bookings->links() }}
            </div>

            {{-- Refund Confirmation Modal (Alpine.js) --}}
            @if($showRefundModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data="{ show: true }" x-show="show"
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeRefundModal"></div>
                <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-md p-6 z-10 border border-slate-200 dark:border-slate-800"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                    <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[28px] text-amber-500">warning</span>
                    </div>

                    <h3 class="text-lg font-bold text-slate-900 dark:text-white text-center mb-2">Konfirmasi Pengajuan Refund</h3>

                    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4 mb-4">
                        <p class="text-sm text-amber-800 dark:text-amber-300 leading-relaxed">
                            <strong>Sesuai kebijakan APPKONKOS,</strong> pembatalan pesanan hanya akan mengembalikan dana sebesar <strong>75%</strong> dari total transaksi. <strong>25%</strong> akan dipotong untuk biaya administrasi. Apakah Anda yakin ingin membatalkan?
                        </p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Alasan Refund <span class="text-rose-500">*</span></label>
                        <textarea wire:model="alasanRefund" rows="3" class="w-full border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm focus:border-[#1967d2] focus:ring focus:ring-[#1967d2]/20 text-slate-900 dark:text-white bg-slate-50/50 dark:bg-slate-950 p-3 text-sm placeholder:text-slate-400" placeholder="Jelaskan alasan Anda mengajukan refund (min. 10 karakter)..."></textarea>
                        @error('alasanRefund') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex gap-3">
                        <button wire:click="closeRefundModal" class="flex-1 px-4 py-2.5 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 font-bold text-sm rounded-xl transition-all">
                            Batal
                        </button>
                        <button wire:click="submitRefund" class="flex-1 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm rounded-xl transition-all shadow-md shadow-rose-500/20 flex items-center justify-center gap-2">
                            <span wire:loading.remove wire:target="submitRefund">Ya, Ajukan Refund</span>
                            <span wire:loading wire:target="submitRefund" class="flex items-center gap-2"><span class="material-symbols-outlined animate-spin text-[16px]">refresh</span>Memproses...</span>
                        </button>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

@script
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('pay-midtrans', (event) => {
            const token = event[0]?.token || event.token || event;
            if (!token || typeof window.snap === 'undefined') {
                alert('Midtrans Snap belum dimuat. Silakan refresh halaman.');
                return;
            }
            window.snap.pay(token, {
                onSuccess: function(result){
                    window.location.reload();
                },
                onPending: function(result){
                    window.location.reload();
                },
                onError: function(result){
                    alert('Pembayaran gagal!');
                },
                onClose: function(){
                    console.log('Pop-up ditutup');
                }
            });
        });
    });
</script>
@endscript