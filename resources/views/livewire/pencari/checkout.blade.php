@php
    $pencari = auth()->user()->pencariKos;
    $noTelp = auth()->user()->no_telepon;
    $jKelamin = $pencari?->jenis_kelamin;
    $pekerjaan = $pencari?->pekerjaan;
    
    $isDataLengkap = filled($noTelp) && filled($jKelamin) && filled($pekerjaan);
@endphp
<div class="min-h-screen bg-white font-[Inter] selection:bg-blue-200 selection:text-blue-900 pb-24 text-slate-800">
    {{-- Simple Header --}}
    <header class="border-b border-slate-200 bg-white sticky top-16 z-20 h-20 flex items-center">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 w-full flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="javascript:history.back()" class="flex h-10 w-10 items-center justify-center rounded-full hover:bg-slate-100 transition-colors">
                    <span class="material-symbols-outlined text-[20px] text-slate-900">arrow_back_ios_new</span>
                </a>
                <h1 class="text-xl sm:text-2xl font-semibold text-slate-900">Konfirmasi dan Bayar</h1>
            </div>
            
            {{-- Stepper --}}
            <div class="hidden md:flex items-center gap-2 text-sm font-medium">
                <span class="text-slate-900">Pilih Kamar</span>
                <span class="material-symbols-outlined text-[16px] text-slate-400">chevron_right</span>
                <span class="text-[#1967d2] font-semibold">Isi Data Sewa</span>
                <span class="material-symbols-outlined text-[16px] text-slate-400">chevron_right</span>
                <span class="text-slate-400">Selesai</span>
            </div>
        </div>
    </header>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 pt-10 grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20">
        
        @if(session('error'))
        <div class="lg:col-span-12 animate-fade-in-up">
            <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 flex items-start gap-3 text-rose-700">
                <span class="material-symbols-outlined mt-0.5 text-[20px]">error</span>
                <span class="text-sm">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        {{-- KOLOM KIRI --}}
        <div class="lg:col-span-7 space-y-10 animate-fade-in-up">
            
            {{-- Data Penyewa --}}
            <section>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-semibold text-slate-900">Data Penyewa</h2>
                    <a href="{{ route('pencari.profil') }}" class="text-sm font-semibold text-[#1967d2] hover:text-[#1556b0] hover:underline flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">edit</span> Edit Profil
                    </a>
                </div>
                
                <div class="rounded-xl border {{ $isDataLengkap ? 'border-slate-200 shadow-sm' : 'border-rose-300 ring-1 ring-rose-300 shadow-md shadow-rose-100' }} bg-white overflow-hidden p-6 space-y-5">
                    <div class="flex items-start gap-3 rounded-lg bg-blue-50/50 p-4 border border-blue-100 mb-2">
                        <span class="material-symbols-outlined text-[#1967d2] shrink-0">info</span>
                        <p class="text-sm text-slate-600 leading-relaxed">Data ini diambil dari profil Anda dan digunakan oleh pemilik properti untuk verifikasi sebelum persetujuan sewa.</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Nama Lengkap</span>
                            <span class="block text-base font-medium text-slate-900">{{ auth()->user()->name }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Email</span>
                            <span class="block text-base font-medium text-slate-900">{{ auth()->user()->email }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Nomor Telepon</span>
                            <span class="block text-base font-medium {{ $noTelp ? 'text-slate-900' : 'text-rose-500 italic' }}">{{ $noTelp ?? 'Belum diisi' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Jenis Kelamin</span>
                            <span class="block text-base font-medium {{ $jKelamin ? 'text-slate-900 capitalize' : 'text-rose-500 italic' }}">
                                {{ $jKelamin === 'L' ? 'Laki-laki' : ($jKelamin === 'P' ? 'Perempuan' : 'Belum diisi') }}
                            </span>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Pekerjaan</span>
                            <span class="block text-base font-medium {{ $pekerjaan ? 'text-slate-900 capitalize' : 'text-rose-500 italic' }}">{{ $pekerjaan ?? 'Belum diisi' }}</span>
                        </div>
                    </div>

                    @if(!$isDataLengkap)
                    <div class="mt-6 rounded-lg border border-rose-200 bg-rose-50 p-4">
                        <div class="flex items-start gap-3 text-rose-700">
                            <span class="material-symbols-outlined text-[24px]">warning</span>
                            <div class="flex-1">
                                <p class="text-sm font-bold mb-1">Profil Belum Lengkap</p>
                                <p class="text-sm mb-4">Mohon lengkapi seluruh data diri Anda untuk dapat melanjutkan ke proses pembayaran.</p>
                                <a href="{{ route('pencari.profil') }}" class="inline-flex w-full justify-center sm:w-auto items-center gap-2 rounded-lg bg-rose-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-rose-700 transition-colors">
                                    Lengkapi Profil Sekarang
                                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </section>

            <hr class="border-slate-200">

            {{-- Detail Sewa --}}
            <section>
                <h2 class="text-2xl font-semibold text-slate-900 mb-6">Detail Waktu Sewa</h2>
                
                <div class="grid sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Tanggal Check-in</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-500">
                                <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                            </div>
                            <input type="date" wire:model.live="tanggal_masuk" class="w-full rounded-lg border border-slate-300 bg-white py-3 pl-10 pr-3 text-sm text-slate-900 outline-none focus:border-[#1967d2] focus:ring-1 focus:ring-[#1967d2] transition-shadow">
                        </div>
                        @error('tanggal_masuk') <span class="text-rose-600 text-xs mt-1.5 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Durasi Sewa</label>
                        <select wire:model.live="durasi_sewa" class="w-full rounded-lg border border-slate-300 bg-white py-3 px-3 text-sm text-slate-900 outline-none focus:border-[#1967d2] focus:ring-1 focus:ring-[#1967d2] transition-shadow">
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
                        @error('durasi_sewa') <span class="text-rose-600 text-xs mt-1.5 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                @if($this->tanggalKeluar)
                <div class="mt-4 flex items-center justify-between text-sm text-slate-600">
                    <span>Perkiraan Check-out</span>
                    <span class="font-semibold text-slate-900">{{ $this->tanggalKeluar }}</span>
                </div>
                @endif
            </section>

            <hr class="border-slate-200">

            {{-- Catatan Opsional --}}
            <section>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-semibold text-slate-900">Catatan untuk Pemilik</h2>
                    <span class="text-sm text-slate-500">Opsional</span>
                </div>
                <textarea wire:model="catatan" rows="3" placeholder="Sampaikan pesan tambahan atau pertanyaan ke pemilik..." class="w-full rounded-lg border border-slate-300 bg-white p-4 text-sm text-slate-900 outline-none focus:border-[#1967d2] focus:ring-1 focus:ring-[#1967d2] transition-shadow resize-none"></textarea>
            </section>

            <hr class="border-slate-200">

            {{-- Kebijakan & Aturan --}}
            <section>
                <h2 class="text-2xl font-semibold text-slate-900 mb-4">Kebijakan Properti</h2>
                <p class="text-sm text-slate-600 mb-6 leading-relaxed">
                    Dengan melanjutkan pembayaran, Anda menyetujui aturan yang telah ditetapkan oleh pemilik. Pelanggaran terhadap aturan dapat dikenakan sanksi sesuai ketentuan pemilik properti.
                </p>
                
                <ul class="space-y-3 mb-8">
                    @foreach($aturanProperti as $aturan)
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-slate-400 mt-0.5 text-[18px]">rule</span>
                            <span class="text-sm text-slate-700">{{ $aturan }}</span>
                        </li>
                    @endforeach
                </ul>

                <label class="flex items-start gap-3 cursor-pointer p-4 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">
                    <div class="relative flex items-center mt-0.5">
                        <input type="checkbox" wire:model.live="setuju_aturan" class="w-5 h-5 rounded border-slate-300 text-[#1967d2] focus:ring-[#1967d2] cursor-pointer">
                    </div>
                    <span class="text-sm text-slate-900 font-medium">
                        Saya menyetujui Aturan Properti dan Ketentuan Layanan APPKONKOS.
                    </span>
                </label>
                @error('setuju_aturan') <span class="text-rose-600 text-xs mt-2 block">{{ $message }}</span> @enderror
            </section>

        </div>

        {{-- KOLOM KANAN (Order Summary) --}}
        <div class="lg:col-span-5 relative">
            <div class="sticky top-28 w-full max-w-[420px] mx-auto lg:ml-auto">
                <div class="rounded-2xl border border-slate-200 bg-white shadow-xl shadow-slate-200/40 p-6 sm:p-7">
                    
                    {{-- Timer Alert --}}
                    <div x-data="{ timer: 900 }" x-init="setInterval(() => { if(timer > 0) timer-- }, 1000)" class="mb-6 flex items-center gap-3 rounded-lg bg-amber-50 p-3 text-amber-800 border border-amber-200">
                        <span class="material-symbols-outlined text-[20px]">timer</span>
                        <div class="flex-1 text-sm">
                            Selesaikan pembayaran dalam <span class="font-bold" x-text="Math.floor(timer / 60).toString().padStart(2, '0') + ':' + (timer % 60).toString().padStart(2, '0')"></span>
                        </div>
                    </div>

                    {{-- Property Card Preview --}}
                    <div class="flex gap-4 mb-6">
                        <div class="h-28 w-28 shrink-0 overflow-hidden rounded-lg bg-slate-100">
                            @if($fotoUrl)
                                <img src="{{ $fotoUrl }}" alt="{{ $namaProperti }}" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full w-full items-center justify-center text-slate-400">
                                    <span class="material-symbols-outlined text-[32px]">apartment</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-1 flex-col py-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                {{ $tipeProperti === 'kosan' ? 'Kosan' : 'Kontrakan' }}
                            </span>
                            <h3 class="text-base font-semibold text-slate-900 leading-tight mb-2 line-clamp-2">{{ $namaProperti }}</h3>
                            @if($tipeProperti === 'kosan')
                                <p class="text-sm text-slate-600">Tipe {{ $namaTipe }} • Kamar {{ $nomorKamar }}</p>
                            @endif
                        </div>
                    </div>

                    <hr class="border-slate-200 mb-6">

                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Rincian Harga</h3>
                    
                    <div class="space-y-4 mb-6 text-sm text-slate-600">
                        <div class="flex justify-between items-center">
                            <span>Harga Sewa ({{ $durasi_sewa }} {{ $tipeProperti === 'kosan' ? 'Bulan' : 'Tahun' }})</span>
                            <span class="text-slate-900">Rp {{ number_format($this->hargaSewa, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="underline decoration-dotted underline-offset-4 cursor-help" title="Biaya pemeliharaan platform APPKONKOS">Biaya Layanan</span>
                            <span class="text-slate-900">Rp {{ number_format($biayaLayanan, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <hr class="border-slate-200 mb-6">

                    <div class="flex justify-between items-center mb-8">
                        <span class="font-bold text-slate-900">Total Pembayaran</span>
                        <span class="text-2xl font-bold text-[#1967d2]">Rp {{ number_format($this->totalPembayaran, 0, ',', '.') }}</span>
                    </div>

                    <button 
                        wire:click="prosesPembayaran" 
                        wire:loading.attr="disabled"
                        @disabled(!$setuju_aturan || !$isDataLengkap)
                        class="w-full rounded-lg bg-[#1967d2] hover:bg-[#1556b0] py-4 text-base font-semibold text-white shadow-md transition-colors disabled:cursor-not-allowed disabled:bg-slate-300 disabled:text-slate-500 disabled:shadow-none flex items-center justify-center gap-2"
                    >
                        <span wire:loading.remove wire:target="prosesPembayaran">Konfirmasi & Bayar</span>
                        <span wire:loading wire:target="prosesPembayaran" class="flex items-center gap-2">
                            <span class="material-symbols-outlined animate-spin text-[20px]">refresh</span>
                            Memproses...
                        </span>
                    </button>
                    
                    @if(!$isDataLengkap)
                    <p class="mt-3 text-center text-xs font-semibold text-rose-500">Silakan lengkapi profil Anda terlebih dahulu.</p>
                    @endif
                    
                    <div class="mt-4 flex items-center justify-center gap-2 text-xs font-medium text-slate-500">
                        <span class="material-symbols-outlined text-[16px]">lock</span>
                        Pembayaran diproses dengan aman oleh Midtrans
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }
    </style>
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
