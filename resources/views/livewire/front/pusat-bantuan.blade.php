<div class="bg-slate-50 dark:bg-slate-900 pb-20 transition-colors duration-300">
    {{-- 1. Premium Hero Section --}}
    <section class="relative overflow-hidden bg-gradient-to-b from-blue-900 via-[#1967d2] to-blue-600 px-4 pt-28 pb-32 sm:px-6 lg:px-8">
        {{-- Decorative Elements --}}
        <div class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiNmZmYiLz48L3N2Zz4=')] bg-[length:30px_30px]"></div>
        <div class="absolute -right-20 -top-20 h-96 w-96 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -left-20 -bottom-20 h-96 w-96 rounded-full bg-white/10 blur-3xl"></div>

        <div class="relative mx-auto max-w-4xl text-center">
            <h1 class="text-4xl font-black tracking-tight text-white sm:text-6xl drop-shadow-lg">
                Pusat Bantuan APPKONKOS
            </h1>
            <p class="mt-4 text-lg font-medium text-blue-100 sm:text-xl">
                Temukan panduan lengkap, solusi cepat, dan jawaban untuk semua pertanyaan Anda.
            </p>
        </div>
    </section>

    {{-- 2. Kategori Topik Cepat --}}
    <section class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 -mt-16 relative z-10">
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 md:gap-6">
            @php
            $categories = [
            ['icon' => 'manage_accounts', 'title' => 'Akun & Profil', 'desc' => 'Pengaturan data diri dan password'],
            ['icon' => 'account_balance_wallet', 'title' => 'Pembayaran', 'desc' => 'Metode bayar dan tagihan'],
            ['icon' => 'home_work', 'title' => 'Pemesanan', 'desc' => 'Cara sewa kos & kontrakan'],
            ['icon' => 'currency_exchange', 'title' => 'Refund', 'desc' => 'Pengajuan pengembalian dana'],
            ];
            @endphp

            @foreach($categories as $cat)
            <a href="#faq-section" class="group flex flex-col items-center rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-lg shadow-slate-200/50 dark:shadow-none ring-1 ring-slate-100 dark:ring-slate-700 transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:shadow-blue-500/10 dark:hover:shadow-blue-900/20 hover:ring-[#1967d2]/30 dark:hover:ring-blue-500/50">
                <div class="mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 dark:bg-slate-700 text-[#1967d2] dark:text-blue-400 transition-colors duration-300 group-hover:bg-[#1967d2] group-hover:text-white">
                    <span class="material-symbols-outlined text-3xl transition-transform duration-300 group-hover:scale-110">{{ $cat['icon'] }}</span>
                </div>
                <h3 class="text-center text-base font-bold text-slate-900 dark:text-slate-100 transition-colors group-hover:text-[#1967d2] dark:group-hover:text-blue-400">{{ $cat['title'] }}</h3>
                <p class="mt-1 text-center text-xs text-slate-500 dark:text-slate-400">{{ $cat['desc'] }}</p>
            </a>
            @endforeach
        </div>
    </section>

    {{-- 3. Comprehensive FAQ Section --}}
    <section id="faq-section" class="mx-auto max-w-5xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <h2 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white sm:text-4xl">Pertanyaan Paling Sering Diajukan (FAQ)</h2>
            <div class="mx-auto mt-4 h-1.5 w-20 rounded-full bg-[#1967d2]"></div>
        </div>

        <div x-data="{ tab: 'pencari' }" class="rounded-3xl bg-white dark:bg-slate-800 p-6 shadow-sm ring-1 ring-slate-100 dark:ring-slate-700 sm:p-10 transition-colors duration-300">
            {{-- Tabs --}}
            <div class="flex justify-center border-b border-slate-200 dark:border-slate-700">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button @click="tab = 'pencari'"
                        :class="tab === 'pencari' ? 'border-[#1967d2] text-[#1967d2] dark:border-blue-400 dark:text-blue-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:border-slate-300 hover:text-slate-700 dark:hover:border-slate-600 dark:hover:text-slate-200'"
                        class="whitespace-nowrap border-b-2 py-4 px-4 text-base font-bold transition-colors">
                        <span class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">person_search</span>
                            Pencari Kos & Kontrakan
                        </span>
                    </button>
                    <button @click="tab = 'mitra'"
                        :class="tab === 'mitra' ? 'border-[#1967d2] text-[#1967d2] dark:border-blue-400 dark:text-blue-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:border-slate-300 hover:text-slate-700 dark:hover:border-slate-600 dark:hover:text-slate-200'"
                        class="whitespace-nowrap border-b-2 py-4 px-4 text-base font-bold transition-colors">
                        <span class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">real_estate_agent</span>
                            Mitra Pemilik Properti
                        </span>
                    </button>
                </nav>
            </div>

            <div class="mt-10">
                {{-- KONTEN PENCARI KOS --}}
                <div x-show="tab === 'pencari'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4">

                    @php
                    $faqPencari = [
                    [
                    'q' => 'Bagaimana cara mencari dan menyewa kos atau kontrakan?',
                    'a' => 'Sangat mudah! Gunakan fitur pencarian di halaman utama, filter berdasarkan kota atau jenis properti. Setelah menemukan yang cocok, klik "Detail", pilih nomor kamar yang tersedia, tentukan durasi sewa, lalu klik "Ajukan Sewa". Anda akan diarahkan ke halaman pembayaran.'
                    ],
                    [
                    'q' => 'Metode pembayaran apa saja yang tersedia?',
                    'a' => 'Kami terintegrasi dengan Payment Gateway resmi (Midtrans). Anda dapat membayar tagihan menggunakan Transfer Bank (Virtual Account Mandiri, BCA, BNI, dll), e-Wallet (GoPay, OVO, ShopeePay), hingga scan QRIS.'
                    ],
                    [
                    'q' => 'Apakah saya bisa memilih kamar tertentu?',
                    'a' => 'Tentu. Sistem kami memiliki fitur Manajemen Kamar dimana Anda bisa melihat denah atau daftar nomor kamar. Anda bebas memilih kamar yang statusnya "Tersedia". Kamar yang sudah dihuni akan ditandai secara otomatis.'
                    ],
                    [
                    'q' => 'Bagaimana cara memberikan ulasan secara anonim?',
                    'a' => 'Setelah masa sewa selesai (status transaksi lunas), Anda bisa membuka halaman "Ulasan Saya". Saat mengisi rating dan komentar, centang kotak "Kirim sebagai Anonim". Identitas Anda akan disembunyikan sebagai "Anonim" dan foto profil Anda tidak akan terlihat oleh publik atau pemilik kos.'
                    ],
                    [
                    'q' => 'Bagaimana kebijakan pembatalan dan Refund (Pengembalian Dana)?',
                    'a' => 'Anda bisa membatalkan pesanan sebelum tanggal check-in tiba. Sistem akan memproses pengembalian dana sebesar <strong class="text-slate-900 dark:text-white">75%</strong> dari total pembayaran. Sisa 25% digunakan sebagai biaya administrasi platform dan kompensasi bagi mitra pemilik.'
                    ],
                    ];
                    @endphp

                    @foreach($faqPencari as $index => $faq)
                    <div x-data="{ expanded: false }" class="rounded-2xl border border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 overflow-hidden transition-all duration-300" :class="expanded ? 'ring-1 ring-[#1967d2]/30 dark:ring-blue-500/50 bg-white dark:bg-slate-800 shadow-md' : 'hover:bg-slate-50 dark:hover:bg-slate-700/80'">
                        <button @click="expanded = !expanded" class="flex w-full items-center justify-between px-6 py-5 text-left focus:outline-none">
                            <span class="text-base font-bold text-slate-800 dark:text-slate-200 transition-colors" :class="expanded ? 'text-[#1967d2] dark:text-blue-400' : ''">{{ $faq['q'] }}</span>
                            <span class="ml-6 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-400 transition-transform duration-300" :class="expanded ? 'rotate-180 bg-blue-100 dark:bg-[#1967d2]/30 text-[#1967d2] dark:text-blue-400' : ''">
                                <span class="material-symbols-outlined text-xl">expand_more</span>
                            </span>
                        </button>
                        <div x-show="expanded" x-collapse x-cloak>
                            <div class="px-6 pb-6 text-[15px] leading-relaxed text-slate-600 dark:text-slate-400 border-t border-slate-100 dark:border-slate-700 mt-2 pt-4">
                                {!! $faq['a'] !!}
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>

                {{-- KONTEN MITRA PEMILIK --}}
                <div x-show="tab === 'mitra'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4">

                    @php
                    $faqMitra = [
                    [
                    'q' => 'Bagaimana cara mendaftarkan properti (Kosan/Kontrakan) saya?',
                    'a' => 'Daftar sebagai Mitra terlebih dahulu. Kemudian masuk ke Dashboard Mitra, klik "Tambah Properti". Lengkapi detail seperti tipe properti (Kosan atau Kontrakan), fasilitas, harga, dan manajemen kamar. Properti Anda akan direview oleh Superadmin sebelum tayang.'
                    ],
                    [
                    'q' => 'Berapa lama proses moderasi properti?',
                    'a' => 'Tim Superadmin kami akan memverifikasi properti Anda dalam waktu maksimal 1x24 jam kerja. Pastikan foto dan informasi yang dimasukkan valid agar proses persetujuan lebih cepat.'
                    ],
                    [
                    'q' => 'Berapa komisi yang dipotong oleh sistem APPKONKOS?',
                    'a' => 'Sistem akan memotong persentase komisi (misalnya 5-10% tergantung promo dan jenis layanan) secara otomatis dari setiap transaksi sewa yang "Berhasil" dan "Lunas". Nilai yang tertera di Saldo Tersedia Anda adalah pendapatan bersih.'
                    ],
                    [
                    'q' => 'Bagaimana cara mencairkan pendapatan (Withdraw)?',
                    'a' => 'Buka menu Keuangan di Dashboard Mitra. Pastikan Anda sudah mengatur rekening bank penerima. Anda dapat menarik "Saldo Tersedia" kapan saja, dan dana akan masuk ke rekening Anda dalam waktu 1-2 hari kerja.'
                    ],
                    [
                    'q' => 'Bisakah saya membalas ulasan penyewa?',
                    'a' => 'Tentu saja! Di menu "Ulasan Penyewa" pada Dashboard Mitra, Anda dapat melihat semua ulasan yang masuk dan memberikan tanggapan/balasan resmi. Jika penyewa mengirimkan ulasan secara Anonim, Anda hanya akan melihat inisial dan nama "Anonim".'
                    ],
                    ];
                    @endphp

                    @foreach($faqMitra as $index => $faq)
                    <div x-data="{ expanded: false }" class="rounded-2xl border border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 overflow-hidden transition-all duration-300" :class="expanded ? 'ring-1 ring-[#1967d2]/30 dark:ring-blue-500/50 bg-white dark:bg-slate-800 shadow-md' : 'hover:bg-slate-50 dark:hover:bg-slate-700/80'">
                        <button @click="expanded = !expanded" class="flex w-full items-center justify-between px-6 py-5 text-left focus:outline-none">
                            <span class="text-base font-bold text-slate-800 dark:text-slate-200 transition-colors" :class="expanded ? 'text-[#1967d2] dark:text-blue-400' : ''">{{ $faq['q'] }}</span>
                            <span class="ml-6 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-400 transition-transform duration-300" :class="expanded ? 'rotate-180 bg-blue-100 dark:bg-[#1967d2]/30 text-[#1967d2] dark:text-blue-400' : ''">
                                <span class="material-symbols-outlined text-xl">expand_more</span>
                            </span>
                        </button>
                        <div x-show="expanded" x-collapse x-cloak>
                            <div class="px-6 pb-6 text-[15px] leading-relaxed text-slate-600 dark:text-slate-400 border-t border-slate-100 dark:border-slate-700 mt-2 pt-4">
                                {!! $faq['a'] !!}
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
    </section>

    {{-- 4. Multi-Channel Contact CTA --}}
    <section class="mx-auto max-w-5xl px-4 py-8 pb-16 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-[3rem] bg-gradient-to-br from-blue-600 via-[#1967d2] to-[#0f4fb5] dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 px-6 py-16 shadow-2xl sm:px-12 sm:py-20 text-center transition-colors duration-300">
            <div class="absolute -right-32 -top-32 h-96 w-96 rounded-full bg-white/10 dark:bg-blue-500/10 blur-3xl"></div>
            <div class="absolute -bottom-32 -left-32 h-96 w-96 rounded-full bg-white/10 dark:bg-indigo-500/10 blur-3xl"></div>

            <div class="relative z-10">
                <span class="mb-4 inline-block rounded-full bg-white/20 dark:bg-blue-500/20 px-4 py-1.5 text-xs font-black uppercase tracking-widest text-white dark:text-blue-300">Customer Support 24/7</span>
                <h2 class="text-3xl font-black tracking-tight text-white sm:text-4xl">Masih Butuh Bantuan Spesifik?</h2>
                <p class="mx-auto mt-5 max-w-2xl text-lg text-blue-100 dark:text-slate-300">
                    Tim Customer Service kami selalu siap membantu menyelesaikan kendala Anda terkait sistem, pembayaran, atau akun.
                </p>
                <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                    <a href="https://wa.me/628120000000" target="_blank" class="group flex w-full sm:w-auto items-center justify-center gap-3 rounded-full bg-white dark:bg-[#25D366] px-8 py-4 text-base font-bold text-[#1967d2] dark:text-white shadow-lg transition-all duration-300 hover:-translate-y-1 hover:bg-slate-50 dark:hover:bg-[#20bd5a] hover:shadow-xl dark:hover:shadow-[#25D366]/30">
                        <svg class="h-6 w-6 text-[#25D366] dark:text-white transition-transform duration-300 group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                        </svg>
                        Chat via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>