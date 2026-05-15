<div class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row gap-8">

        {{-- SIDEBAR KIRI --}}
        <x-pencari.sidebar active="ulasan" />

        {{-- KONTEN UTAMA KANAN --}}
        <div class="flex-1 min-w-0 space-y-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Ulasan Saya</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Daftar ulasan yang telah Anda berikan untuk properti yang pernah Anda sewa.</p>
            </div>

            @if (session()->has('success'))
                <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 font-medium flex items-center gap-3">
                    <span class="material-symbols-outlined">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif

            {{-- DAFTAR PESANAN YANG BELUM DIULAS --}}
            <div class="bg-white dark:bg-slate-900 rounded-[32px] shadow-sm border border-slate-200 dark:border-slate-800 p-8 transition-colors">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#1967d2]">rate_review</span>
                    Pesanan Menunggu Ulasan
                </h2>
                
                @if(isset($pesananBelumDiulas) && $pesananBelumDiulas->isEmpty() && !$selectedBookingId)
                    <div class="text-center py-8 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-dashed border-slate-300 dark:border-slate-700">
                        <span class="material-symbols-outlined text-4xl text-slate-400 dark:text-slate-500 mb-3 block">inventory_2</span>
                        <p class="text-slate-500 dark:text-slate-400 font-medium">Hore! Belum ada pesanan yang perlu diulas saat ini.</p>
                    </div>
                @elseif(isset($pesananBelumDiulas))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($pesananBelumDiulas as $pesanan)
                            <div class="border border-slate-200 dark:border-slate-700 rounded-2xl p-5 flex flex-col justify-between bg-slate-50 dark:bg-slate-800/50 hover:border-[#1967d2]/30 transition-all">
                                <div>
                                    <h3 class="font-bold text-slate-900 dark:text-white text-lg">
                                        {{ $pesanan->kamar ? $pesanan->kamar->tipeKamar->kosan->nama_properti : $pesanan->kontrakan->nama_properti }}
                                    </h3>
                                    <div class="flex items-center gap-2 mt-2 text-sm text-slate-500 dark:text-slate-400">
                                        <span class="material-symbols-outlined text-[16px]">receipt_long</span>
                                        ID Booking: {{ $pesanan->id }}
                                    </div>
                                    <div class="flex items-center gap-2 mt-1 text-sm text-slate-500 dark:text-slate-400">
                                        <span class="material-symbols-outlined text-[16px]">event_available</span>
                                        Selesai pada: {{ $pesanan->tgl_selesai_sewa->format('d M Y') }}
                                    </div>
                                </div>
                                <button 
                                    wire:click="pilihPesanan('{{ $pesanan->id }}', '{{ $pesanan->pencari_kos_id }}', '{{ $pesanan->kamar ? $pesanan->kamar->tipeKamar->kosan->id : '' }}', '{{ $pesanan->kontrakan ? $pesanan->kontrakan->id : '' }}')" 
                                    class="mt-5 w-full bg-[#1967d2] hover:bg-[#0f4fb5] text-white font-bold py-2.5 px-4 rounded-xl transition-all shadow-md shadow-blue-500/10 flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-[20px]">edit_square</span>
                                    Tulis Ulasan
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- FORM ULASAN (Muncul jika ada pesanan dipilih) --}}
            @if($selectedBookingId)
            <div class="bg-white dark:bg-slate-900 rounded-[32px] shadow-sm border border-[#1967d2]/30 p-8 ring-4 ring-[#1967d2]/5 animate-fade-in-up">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#1967d2]">stars</span>
                        Beri Penilaian
                    </h2>
                    <button wire:click="$set('selectedBookingId', null)" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                
                <form wire:submit.prevent="simpanUlasan" class="space-y-6">
                    <!-- Bintang Interaktif -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-3">Seberapa puas Anda?</label>
                        <div class="flex items-center gap-2">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" wire:click="$set('rating', {{ $i }})" class="focus:outline-none transition-transform hover:scale-110">
                                    <svg class="w-10 h-10 {{ $rating >= $i ? 'text-amber-400' : 'text-slate-200 dark:text-slate-700' }} fill-current drop-shadow-sm transition-colors" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </button>
                            @endfor
                            <span class="ml-3 text-lg font-bold text-amber-500">{{ $rating }}/5</span>
                        </div>
                        @error('rating') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Komentar -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Tulis Pengalaman Anda</label>
                        <textarea wire:model="komentar" rows="4" 
                                class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl shadow-sm focus:border-[#1967d2] focus:ring-[#1967d2] transition-all placeholder:text-slate-400" 
                                placeholder="Bagaimana kondisi kamar, fasilitas, dan pelayanannya?"></textarea>
                        @error('komentar') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Checkbox Anonim -->
                    <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <div class="flex items-center h-5 mt-0.5">
                                <input wire:model="is_anonymous" type="checkbox" class="w-5 h-5 text-[#1967d2] bg-white border-slate-300 rounded focus:ring-[#1967d2] focus:ring-offset-0 dark:bg-slate-800 dark:border-slate-600 transition-colors cursor-pointer">
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-800 dark:text-white group-hover:text-[#1967d2] transition-colors">Sembunyikan nama saya (Anonim)</span>
                                <span class="text-xs text-slate-500 dark:text-slate-400 mt-1">Nama profil Anda tidak akan ditampilkan kepada publik maupun pemilik properti.</span>
                            </div>
                        </label>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <button type="submit" wire:loading.attr="disabled" wire:target="simpanUlasan" class="flex-1 bg-[#1967d2] hover:bg-[#0f4fb5] text-white font-bold py-3 px-6 rounded-xl transition-all shadow-lg shadow-blue-500/20 flex items-center justify-center gap-2 disabled:opacity-50">
                            <span wire:loading.remove wire:target="simpanUlasan" class="flex items-center gap-2"><span class="material-symbols-outlined text-[20px]">send</span> Kirim Ulasan Sekarang</span>
                            <span wire:loading wire:target="simpanUlasan" class="flex items-center gap-2"><span class="material-symbols-outlined animate-spin text-[20px]">refresh</span> Mengirim...</span>
                        </button>
                        <button type="button" wire:click="$set('selectedBookingId', null)" class="sm:w-auto w-full bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold py-3 px-6 rounded-xl transition-all">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
            @endif

            {{-- RIWAYAT ULASAN --}}
            <div class="bg-white dark:bg-slate-900 rounded-[32px] shadow-sm border border-slate-200 dark:border-slate-800 p-8 transition-colors">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#1967d2]">history</span>
                    Riwayat Ulasan Anda
                </h2>
                
                @if(isset($riwayatUlasan) && $riwayatUlasan->isEmpty())
                    <div class="text-center py-10">
                        <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100 dark:border-slate-700">
                            <span class="material-symbols-outlined text-3xl text-slate-300 dark:text-slate-600">chat_bubble_outline</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Belum ada riwayat</h3>
                        <p class="text-slate-500 dark:text-slate-400 mt-2 max-w-xs mx-auto text-sm">Anda belum pernah memberikan ulasan. Ulasan Anda sangat berharga bagi komunitas kami!</p>
                    </div>
                @elseif(isset($riwayatUlasan))
                    <div class="space-y-4">
                        @foreach($riwayatUlasan as $ulasan)
                            <div class="p-5 border border-slate-200 dark:border-slate-700 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <div class="flex flex-col sm:flex-row justify-between sm:items-start gap-4">
                                    <div class="flex-1">
                                        <h4 class="font-bold text-slate-900 dark:text-white text-lg">
                                            {{ $ulasan->kosan ? $ulasan->kosan->nama_properti : ($ulasan->kontrakan ? $ulasan->kontrakan->nama_properti : 'Properti tidak ditemukan') }}
                                        </h4>
                                        <div class="flex items-center gap-2 mt-1 text-xs text-slate-500 dark:text-slate-400 font-medium">
                                            <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                                            {{ $ulasan->created_at->format('d M Y, H:i') }}
                                        </div>
                                    </div>
                                    <!-- Render Bintang -->
                                    <div class="flex items-center gap-1 bg-amber-50 dark:bg-amber-500/10 px-3 py-1.5 rounded-lg border border-amber-100 dark:border-amber-500/20">
                                        <span class="text-amber-500 font-bold text-sm mr-1">{{ $ulasan->rating }}.0</span>
                                        <div class="flex text-amber-400">
                                            @for($i = 0; $i < $ulasan->rating; $i++)
                                                <svg class="w-4 h-4 fill-current drop-shadow-sm" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 relative">
                                    <span class="material-symbols-outlined absolute text-4xl text-slate-100 dark:text-slate-800 -top-2 -left-2 -z-10 rotate-180">format_quote</span>
                                    <p class="text-slate-700 dark:text-slate-300 text-sm leading-relaxed pl-2 z-10 relative">"{{ $ulasan->komentar }}"</p>
                                </div>
                                
                                <div class="flex items-center gap-3 mt-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                                    @if($ulasan->is_anonymous)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-md">
                                            <span class="material-symbols-outlined text-[14px]">visibility_off</span>
                                            Dikirim sebagai Anonim
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 text-xs font-bold rounded-md">
                                            <span class="material-symbols-outlined text-[14px]">public</span>
                                            Terlihat oleh publik
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
