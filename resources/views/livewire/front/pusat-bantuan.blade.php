<div class="bg-white pb-12">
    {{-- 1. Hero Section --}}
    <section class="relative bg-blue-50 px-4 pt-20 pb-28 sm:px-6 lg:px-8">
        <div class="absolute inset-0 bg-gradient-to-b from-[#1967d2]/5 to-transparent"></div>
        <div class="relative mx-auto max-w-4xl text-center">
            <h1 class="text-3xl font-extrabold tracking-tight text-[#1967d2] sm:text-5xl">
                Halo, ada yang bisa kami bantu?
            </h1>
            
            <div class="mx-auto mt-10 max-w-2xl relative z-20">
                <div class="flex items-center overflow-hidden rounded-full bg-white shadow-[0_8px_30px_rgba(0,0,0,0.08)] transition-shadow focus-within:ring-2 focus-within:ring-[#1967d2] focus-within:ring-offset-2 focus-within:shadow-[0_8px_30px_rgba(25,103,210,0.2)]">
                    <div class="pl-6 pr-3 text-[#1967d2]">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text" class="w-full border-none bg-transparent py-4 pl-2 pr-6 text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-0 sm:text-lg font-medium" placeholder="Cari topik bantuan (misal: cara refund, pembayaran)...">
                </div>
            </div>
        </div>
    </section>

    {{-- 2. Kategori Topik Cepat --}}
    <section class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 -mt-16 relative z-10">
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 md:gap-6">
            {{-- Card 1 --}}
            <a href="#faq" class="group flex flex-col items-center rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_10px_20px_rgba(25,103,210,0.1)] hover:ring-[#1967d2]/30">
                <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-[#1967d2] transition-colors duration-300 group-hover:bg-[#1967d2] group-hover:text-white">
                    <svg class="h-8 w-8 transition-transform duration-300 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                </div>
                <h3 class="text-center text-sm font-bold text-slate-800 transition-colors group-hover:text-[#1967d2]">Seputar Akun</h3>
            </a>

            {{-- Card 2 --}}
            <a href="#faq" class="group flex flex-col items-center rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_10px_20px_rgba(25,103,210,0.1)] hover:ring-[#1967d2]/30">
                <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-[#1967d2] transition-colors duration-300 group-hover:bg-[#1967d2] group-hover:text-white">
                    <svg class="h-8 w-8 transition-transform duration-300 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" /></svg>
                </div>
                <h3 class="text-center text-sm font-bold text-slate-800 transition-colors group-hover:text-[#1967d2]">Pembayaran & Tagihan</h3>
            </a>

            {{-- Card 3 --}}
            <a href="#faq" class="group flex flex-col items-center rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_10px_20px_rgba(25,103,210,0.1)] hover:ring-[#1967d2]/30">
                <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-[#1967d2] transition-colors duration-300 group-hover:bg-[#1967d2] group-hover:text-white">
                    <svg class="h-8 w-8 transition-transform duration-300 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185zM9.75 9h.008v.008H9.75V9zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm4.125 4.5h.008v.008h-.008V13.5zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                </div>
                <h3 class="text-center text-sm font-bold text-slate-800 transition-colors group-hover:text-[#1967d2]">Pengajuan Refund</h3>
            </a>

            {{-- Card 4 --}}
            <a href="#faq" class="group flex flex-col items-center rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_10px_20px_rgba(25,103,210,0.1)] hover:ring-[#1967d2]/30">
                <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-[#1967d2] transition-colors duration-300 group-hover:bg-[#1967d2] group-hover:text-white">
                    <svg class="h-8 w-8 transition-transform duration-300 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                </div>
                <h3 class="text-center text-sm font-bold text-slate-800 transition-colors group-hover:text-[#1967d2]">Aturan Sewa Kos</h3>
            </a>
        </div>
    </section>

    {{-- 3. FAQ Section --}}
    <section id="faq" class="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8 mt-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-slate-900">Pertanyaan yang Sering Diajukan</h2>
            <p class="mt-3 text-base text-slate-500">Temukan jawaban cepat untuk pertanyaan umum seputar layanan kami.</p>
        </div>

        <div x-data="{ tab: 'pencari' }">
            {{-- Tabs --}}
            <div class="flex justify-center border-b border-slate-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button @click="tab = 'pencari'" 
                            :class="tab === 'pencari' ? 'border-[#1967d2] text-[#1967d2]' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700'"
                            class="whitespace-nowrap border-b-2 py-4 px-2 text-base font-bold transition-colors">
                        Untuk Pencari Kos
                    </button>
                    <button @click="tab = 'mitra'" 
                            :class="tab === 'mitra' ? 'border-[#1967d2] text-[#1967d2]' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700'"
                            class="whitespace-nowrap border-b-2 py-4 px-2 text-base font-bold transition-colors">
                        Untuk Mitra/Pemilik
                    </button>
                </nav>
            </div>

            <div class="mt-8 space-y-4">
                {{-- Konten Pencari --}}
                <div x-show="tab === 'pencari'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4">
                    
                    {{-- FAQ Item 1 --}}
                    <div x-data="{ expanded: false }" class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden transition-all duration-300" :class="expanded ? 'ring-1 ring-[#1967d2]/30 shadow-md' : ''">
                        <button @click="expanded = !expanded" class="flex w-full items-center justify-between px-6 py-5 text-left focus:outline-none">
                            <span class="text-base font-bold text-slate-900 transition-colors" :class="expanded ? 'text-[#1967d2]' : ''">Bagaimana cara membayar sewa kos?</span>
                            <span class="ml-6 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-50 text-slate-400 transition-transform duration-300" :class="expanded ? 'rotate-180 bg-blue-50 text-[#1967d2]' : ''">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                            </span>
                        </button>
                        <div x-show="expanded" x-cloak>
                            <div class="px-6 pb-6 pt-0 text-[15px] leading-relaxed text-slate-600">
                                Kami menggunakan sistem pembayaran aman. Anda bisa membayar melalui transfer bank (Virtual Account), e-Wallet (GoPay, dll), atau QRIS yang terintegrasi di sistem setelah Anda memilih nomor kamar dan klik "Ajukan Sewa".
                            </div>
                        </div>
                    </div>

                    {{-- FAQ Item 2 --}}
                    <div x-data="{ expanded: false }" class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden transition-all duration-300" :class="expanded ? 'ring-1 ring-[#1967d2]/30 shadow-md' : ''">
                        <button @click="expanded = !expanded" class="flex w-full items-center justify-between px-6 py-5 text-left focus:outline-none">
                            <span class="text-base font-bold text-slate-900 transition-colors" :class="expanded ? 'text-[#1967d2]' : ''">Apakah saya bisa memilih kamar yang saya inginkan?</span>
                            <span class="ml-6 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-50 text-slate-400 transition-transform duration-300" :class="expanded ? 'rotate-180 bg-blue-50 text-[#1967d2]' : ''">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                            </span>
                        </button>
                        <div x-show="expanded" x-cloak>
                            <div class="px-6 pb-6 pt-0 text-[15px] leading-relaxed text-slate-600">
                                Ya! APPKONKOS memiliki fitur pemilihan kamar secara langsung. Anda bisa melihat denah nomor kamar yang tersedia (kotak putih) dan kamar yang sudah terisi (kotak abu-abu berikon gembok).
                            </div>
                        </div>
                    </div>

                    {{-- FAQ Item 3 --}}
                    <div x-data="{ expanded: false }" class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden transition-all duration-300" :class="expanded ? 'ring-1 ring-[#1967d2]/30 shadow-md' : ''">
                        <button @click="expanded = !expanded" class="flex w-full items-center justify-between px-6 py-5 text-left focus:outline-none">
                            <span class="text-base font-bold text-slate-900 transition-colors" :class="expanded ? 'text-[#1967d2]' : ''">Bagaimana kebijakan pembatalan dan Refund?</span>
                            <span class="ml-6 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-50 text-slate-400 transition-transform duration-300" :class="expanded ? 'rotate-180 bg-blue-50 text-[#1967d2]' : ''">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                            </span>
                        </button>
                        <div x-show="expanded" x-cloak>
                            <div class="px-6 pb-6 pt-0 text-[15px] leading-relaxed text-slate-600">
                                Jika Anda membatalkan pesanan yang sudah dibayar SEBELUM tanggal check-in, Anda berhak mendapatkan pengembalian dana (Refund) sebesar <strong class="font-bold text-slate-900">75%</strong> dari total transaksi. Pemotongan 25% digunakan untuk biaya administrasi dan kompensasi pemilik kos.
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Konten Mitra --}}
                <div x-show="tab === 'mitra'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4">
                    
                    {{-- FAQ Item 1 --}}
                    <div x-data="{ expanded: false }" class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden transition-all duration-300" :class="expanded ? 'ring-1 ring-[#1967d2]/30 shadow-md' : ''">
                        <button @click="expanded = !expanded" class="flex w-full items-center justify-between px-6 py-5 text-left focus:outline-none">
                            <span class="text-base font-bold text-slate-900 transition-colors" :class="expanded ? 'text-[#1967d2]' : ''">Berapa potongan komisi untuk aplikasi?</span>
                            <span class="ml-6 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-50 text-slate-400 transition-transform duration-300" :class="expanded ? 'rotate-180 bg-blue-50 text-[#1967d2]' : ''">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                            </span>
                        </button>
                        <div x-show="expanded" x-cloak>
                            <div class="px-6 pb-6 pt-0 text-[15px] leading-relaxed text-slate-600">
                                Sistem akan memotong persentase komisi (sesuai kebijakan saat ini) secara otomatis dari setiap transaksi sewa yang berhasil. Pendapatan yang Anda lihat di dashboard adalah Pendapatan Bersih.
                            </div>
                        </div>
                    </div>

                    {{-- FAQ Item 2 --}}
                    <div x-data="{ expanded: false }" class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden transition-all duration-300" :class="expanded ? 'ring-1 ring-[#1967d2]/30 shadow-md' : ''">
                        <button @click="expanded = !expanded" class="flex w-full items-center justify-between px-6 py-5 text-left focus:outline-none">
                            <span class="text-base font-bold text-slate-900 transition-colors" :class="expanded ? 'text-[#1967d2]' : ''">Kapan saya bisa mencairkan dana (Withdraw)?</span>
                            <span class="ml-6 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-50 text-slate-400 transition-transform duration-300" :class="expanded ? 'rotate-180 bg-blue-50 text-[#1967d2]' : ''">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                            </span>
                        </button>
                        <div x-show="expanded" x-cloak>
                            <div class="px-6 pb-6 pt-0 text-[15px] leading-relaxed text-slate-600">
                                Pendapatan dari penyewa akan masuk ke "Saldo Tersedia" Anda setelah transaksi berhasil. Anda bisa mengajukan pencairan dana ke rekening bank Anda kapan saja melalui menu Keuangan di Dashboard Mitra.
                            </div>
                        </div>
                    </div>

                    {{-- FAQ Item 3 --}}
                    <div x-data="{ expanded: false }" class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden transition-all duration-300" :class="expanded ? 'ring-1 ring-[#1967d2]/30 shadow-md' : ''">
                        <button @click="expanded = !expanded" class="flex w-full items-center justify-between px-6 py-5 text-left focus:outline-none">
                            <span class="text-base font-bold text-slate-900 transition-colors" :class="expanded ? 'text-[#1967d2]' : ''">Mengapa properti saya ditolak oleh Superadmin?</span>
                            <span class="ml-6 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-50 text-slate-400 transition-transform duration-300" :class="expanded ? 'rotate-180 bg-blue-50 text-[#1967d2]' : ''">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                            </span>
                        </button>
                        <div x-show="expanded" x-cloak>
                            <div class="px-6 pb-6 pt-0 text-[15px] leading-relaxed text-slate-600">
                                Setiap properti yang didaftarkan akan melalui tahap moderasi. Jika ditolak, Anda dapat melihat "Alasan Penolakan" (misal: foto buram atau informasi tidak lengkap) di halaman Detail Properti Anda, lalu Anda dapat mengedit dan mengajukannya kembali.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    {{-- 4. Contact CS CTA --}}
    <section class="mx-auto max-w-5xl px-4 py-8 pb-16 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-[#1967d2] to-[#0f4fb5] px-6 py-14 shadow-2xl shadow-blue-900/20 sm:px-12 sm:py-20 text-center">
            {{-- Decorative bg --}}
            <div class="absolute -right-20 -top-20 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
            
            <div class="relative z-10">
                <h2 class="text-3xl font-black tracking-tight text-white sm:text-4xl">Masih Butuh Bantuan?</h2>
                <p class="mx-auto mt-4 max-w-2xl text-lg text-blue-100">
                    Tidak menemukan jawaban yang Anda cari? Tim Customer Service kami siap membantu 24/7.
                </p>
                <div class="mt-10 flex justify-center">
                    <a href="https://wa.me/6281234567890" target="_blank" class="group inline-flex items-center gap-2 rounded-full bg-white px-8 py-4 text-base font-extrabold text-[#1967d2] shadow-lg transition-all duration-300 hover:-translate-y-1 hover:bg-slate-50 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-white/30 focus:ring-offset-0">
                        <svg class="h-6 w-6 text-[#25D366] transition-transform duration-300 group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        Hubungi via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
