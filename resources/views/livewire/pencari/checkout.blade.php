<div>
    {{-- Bagian Atas: Breadcrumb & Stepper --}}
    <div class="bg-white border-b border-[#e5e7eb] py-4 sticky top-16 z-10 shadow-sm mb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center">
            {{-- Tombol Kembali --}}
            <a href="javascript:history.back()" class="w-10 h-10 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-100 hover:text-[#1967d2] transition-colors border border-transparent hover:border-slate-200 mr-4 shrink-0">
                <span class="material-symbols-outlined text-[24px]">arrow_back</span>
            </a>
            
            {{-- Stepper / Progress Bar Horizontal --}}
            <div class="flex-1 max-w-2xl mx-auto items-center justify-between relative hidden sm:flex">
                {{-- Line connector --}}
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-[#e5e7eb] -z-10 rounded-full"></div>
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1/2 h-1 bg-[#1967d2] -z-10 rounded-full"></div>
                
                {{-- Step 1 --}}
                <div class="flex flex-col items-center gap-2 bg-white px-2">
                    <div class="w-8 h-8 rounded-full bg-[#1967d2] text-white flex items-center justify-center border-4 border-white shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">check</span>
                    </div>
                    <span class="text-xs font-semibold text-slate-900">Pilih Kamar</span>
                </div>
                
                {{-- Step 2 --}}
                <div class="flex flex-col items-center gap-2 bg-white px-2">
                    <div class="w-8 h-8 rounded-full bg-[#1967d2] text-white flex items-center justify-center border-4 border-white shadow-sm ring-4 ring-blue-50">
                        <span class="text-sm font-bold">2</span>
                    </div>
                    <span class="text-xs font-bold text-[#1967d2]">Isi Data Sewa</span>
                </div>
                
                {{-- Step 3 --}}
                <div class="flex flex-col items-center gap-2 bg-white px-2">
                    <div class="w-8 h-8 rounded-full bg-[#f1f5f9] text-slate-400 flex items-center justify-center border-4 border-white">
                        <span class="text-sm font-bold">3</span>
                    </div>
                    <span class="text-xs font-semibold text-slate-400">Pembayaran</span>
                </div>
            </div>
            
            <div class="sm:hidden flex-1 text-center font-bold text-slate-800">
                Isi Data Sewa
            </div>
        </div>
    </div>

    {{-- Grid Layout Utama --}}
    <div class="bg-[#f8fafc] min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Flash Error Message --}}
            @if(session('error'))
            <div class="lg:col-span-3">
                <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl flex items-center gap-3">
                    <span class="material-symbols-outlined text-[20px]">error</span>
                    <span class="font-medium text-sm">{{ session('error') }}</span>
                </div>
            </div>
            @endif

            {{-- KOLOM KIRI: Form Data --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Card 1: Informasi Penyewa --}}
                <div class="bg-white rounded-2xl border border-[#e5e7eb] p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 mb-2">Informasi Penyewa</h2>
                    <div class="bg-blue-50 border border-blue-100 text-blue-700 text-sm px-4 py-2.5 rounded-lg mb-5 flex items-start gap-2">
                        <span class="material-symbols-outlined text-[18px] shrink-0 mt-0.5">info</span>
                        <p>Data ini diambil dari profil Anda dan tidak dapat diubah di sini.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap</label>
                            <input type="text" value="{{ auth()->user()->name }}" readonly class="w-full border border-slate-200 rounded-xl bg-gray-50 text-gray-500 p-3 text-sm focus:ring-0 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
                            <input type="email" value="{{ auth()->user()->email }}" readonly class="w-full border border-slate-200 rounded-xl bg-gray-50 text-gray-500 p-3 text-sm focus:ring-0 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor HP</label>
                            <input type="text" value="{{ auth()->user()->no_telepon ?? '-' }}" readonly class="w-full border border-slate-200 rounded-xl bg-gray-50 text-gray-500 p-3 text-sm focus:ring-0 cursor-not-allowed">
                        </div>
                    </div>
                </div>

                {{-- Card 2: Detail Waktu Sewa --}}
                <div class="bg-white rounded-2xl border border-[#e5e7eb] p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 mb-4">Detail Waktu Sewa</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Mulai Sewa (Check-in)</label>
                            <div class="relative">
                                <input type="date" wire:model.live="tanggal_masuk" class="w-full border border-slate-200 rounded-xl bg-white text-slate-900 p-3 text-sm focus:border-[#1967d2] focus:ring focus:ring-[#1967d2]/20 transition-all">
                            </div>
                            @error('tanggal_masuk') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Durasi Sewa</label>
                            <select wire:model.live="durasi_sewa" class="w-full border border-slate-200 rounded-xl bg-white text-slate-900 p-3 text-sm focus:border-[#1967d2] focus:ring focus:ring-[#1967d2]/20 transition-all cursor-pointer">
                                @if($tipeProperti === 'kosan')
                                    <option value="1">1 Bulan</option>
                                    <option value="3">3 Bulan</option>
                                    <option value="6">6 Bulan</option>
                                    <option value="12">1 Tahun</option>
                                @else
                                    <option value="12">1 Tahun</option>
                                    <option value="24">2 Tahun</option>
                                    <option value="36">3 Tahun</option>
                                @endif
                            </select>
                            @error('durasi_sewa') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    @if($this->tanggalKeluar)
                    <div class="mt-5 p-4 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                                <span class="material-symbols-outlined text-[20px]">calendar_today</span>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Perkiraan Check-out</p>
                                <p class="text-[15px] font-bold text-slate-900">{{ $this->tanggalKeluar }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Card 3: Catatan untuk Pemilik --}}
                <div class="bg-white rounded-2xl border border-[#e5e7eb] p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 mb-2">Catatan untuk Pemilik (Opsional)</h2>
                    <p class="text-sm text-slate-500 mb-4">Tambahkan pesan khusus untuk pemilik kos/kontrakan jika ada.</p>
                    
                    <textarea wire:model="catatan" rows="3" placeholder="Misal: Saya akan datang sekitar jam 3 sore untuk survey lokasi dulu." class="w-full border border-slate-200 rounded-xl bg-white text-slate-900 p-3 text-sm focus:border-[#1967d2] focus:ring focus:ring-[#1967d2]/20 transition-all resize-none"></textarea>
                </div>

                {{-- Card 4: Persetujuan Aturan Kos --}}
                <div class="bg-white rounded-2xl border border-[#e5e7eb] p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-rose-500 text-[22px]">gavel</span> 
                        Aturan {{ $tipeProperti === 'kosan' ? 'Kosan' : 'Kontrakan' }}
                    </h2>
                    
                    <div class="bg-slate-50 rounded-xl p-4 mb-5 border border-slate-100">
                        <ul class="space-y-2.5">
                            @foreach($aturanProperti as $aturan)
                                <li class="flex items-start gap-2.5 text-sm text-slate-700">
                                    <span class="material-symbols-outlined text-[18px] text-[#1967d2] shrink-0 mt-0.5">check_circle</span>
                                    <span>{{ $aturan }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <label class="flex items-start gap-3 cursor-pointer group">
                        <div class="relative flex items-center mt-0.5">
                            <input type="checkbox" wire:model.live="setuju_aturan" class="w-5 h-5 border-2 border-slate-300 rounded text-[#1967d2] focus:ring-[#1967d2] transition-all cursor-pointer peer">
                        </div>
                        <span class="text-sm text-slate-700 font-medium leading-relaxed group-hover:text-slate-900 transition-colors">
                            Saya telah membaca dan menyetujui seluruh aturan kos ini. Saya bersedia menerima sanksi dari pemilik kos jika melanggar.
                        </span>
                    </label>
                    @error('setuju_aturan') <span class="text-rose-500 text-xs mt-2 block font-semibold">{{ $message }}</span> @enderror
                </div>

            </div>

            {{-- KOLOM KANAN: Ringkasan Pesanan --}}
            <div class="lg:col-span-1">
                <div class="sticky top-28 bg-white rounded-2xl border border-[#e5e7eb] shadow-md p-6">
                    
                    {{-- Timer Hitung Mundur --}}
                    <div x-data="{ timer: 900 }" x-init="setInterval(() => { if(timer > 0) timer-- }, 1000)" class="bg-amber-50 border border-amber-200 text-amber-700 p-3 rounded-xl mb-5 text-center font-bold text-sm flex flex-col items-center justify-center gap-1 shadow-sm">
                        <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[18px]">timer</span>Selesaikan pengisian data dalam</span>
                        <span class="text-lg" x-text="Math.floor(timer / 60).toString().padStart(2, '0') + ':' + (timer % 60).toString().padStart(2, '0')">15:00</span>
                    </div>

                    <h2 class="font-bold text-lg text-slate-900 mb-5">Ringkasan Pesanan</h2>
                    
                    {{-- Informasi Properti --}}
                    <div class="flex gap-4 mb-5 pb-5 border-b border-[#e5e7eb]">
                        <div class="w-24 h-24 shrink-0 rounded-xl overflow-hidden bg-slate-100">
                            @if($fotoUrl)
                                <img src="{{ $fotoUrl }}" alt="{{ $namaProperti }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                    <span class="material-symbols-outlined text-[32px]">apartment</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col justify-center">
                            <span class="px-2 py-0.5 inline-block bg-blue-50 text-[#1967d2] text-[10px] font-bold rounded border border-blue-100 mb-1.5 uppercase tracking-wider self-start">
                                {{ $tipeProperti === 'kosan' ? 'Kosan' : 'Kontrakan' }}
                            </span>
                            <h3 class="font-bold text-base text-slate-900 leading-tight mb-1 truncate">{{ $namaProperti }}</h3>
                            @if($tipeProperti === 'kosan')
                                <p class="text-sm text-[#6b7280] truncate">Tipe {{ $namaTipe }} — Kamar {{ $nomorKamar }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Rincian Pembayaran --}}
                    <div class="space-y-3 mb-4 text-sm">
                        <div class="flex justify-between text-slate-600">
                            <span>Harga Sewa ({{ $durasi_sewa }} {{ $tipeProperti === 'kosan' ? 'Bulan' : 'Bulan' }})</span>
                            <span class="font-medium text-slate-900">Rp {{ number_format($this->hargaSewa, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Biaya Layanan</span>
                            <span class="font-medium text-slate-900">Rp {{ number_format($biayaLayanan, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-[#e5e7eb] my-4 pt-4">
                        <div class="flex justify-between items-end">
                            <span class="font-bold text-slate-900 text-sm">Total Tagihan</span>
                            <span class="text-2xl font-bold text-[#1967d2]">Rp {{ number_format($this->totalPembayaran, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Tombol Bayar --}}
                    <button 
                        wire:click="prosesPembayaran" 
                        wire:loading.attr="disabled"
                        @disabled(!$setuju_aturan)
                        class="w-full bg-[#1967d2] hover:bg-[#0f4fb5] text-white font-bold py-3.5 rounded-xl mt-4 shadow-md shadow-blue-500/20 transition-all flex justify-center items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed"
                    >
                        <span wire:loading.remove wire:target="prosesPembayaran">Bayar Sekarang</span>
                        <span wire:loading wire:target="prosesPembayaran" class="flex items-center gap-2">
                            <span class="material-symbols-outlined animate-spin text-[18px]">refresh</span> Memproses...
                        </span>
                    </button>
                    
                    {{-- Informasi Instant Booking --}}
                    <div class="text-xs text-slate-600 font-medium text-center mt-3 flex items-start gap-1.5 bg-slate-50 border border-slate-100 p-2.5 rounded-lg">
                        <span class="material-symbols-outlined text-[16px] text-amber-500 shrink-0 mt-0.5" style="font-variation-settings: 'FILL' 1;">bolt</span>
                        <span class="text-left leading-relaxed"><strong>Instant Booking.</strong> Kamar akan langsung menjadi milik Anda setelah pembayaran berhasil dikonfirmasi.</span>
                    </div>

                    <div class="mt-4 flex items-center justify-center gap-1.5 text-xs text-slate-500">
                        <span class="material-symbols-outlined text-[14px]">verified_user</span>
                        <span>Pembayaran aman & terenkripsi oleh Midtrans</span>
                    </div>
                </div>
            </div>
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
                    window.location.href = "{{ route('pencari.riwayat-pesanan') }}";
                },
                onPending: function(result){
                    window.location.href = "{{ route('pencari.riwayat-pesanan') }}";
                },
                onError: function(result){
                    alert('Pembayaran gagal! Silakan periksa riwayat pesanan Anda.');
                    window.location.href = "{{ route('pencari.riwayat-pesanan') }}";
                },
                onClose: function(){
                    console.log('Pop-up ditutup');
                    window.location.href = "{{ route('pencari.riwayat-pesanan') }}";
                }
            });
        });
    });
</script>
@endscript
