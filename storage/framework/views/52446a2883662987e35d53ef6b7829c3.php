<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Appkonkos')); ?> - Pilih Role</title>

        <link rel="icon" type="image/png" href="<?php echo e(asset('images/appkonkos.png')); ?>">
        <link rel="apple-touch-icon" href="<?php echo e(asset('images/appkonkos.png')); ?>">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Material+Symbols+Outlined:wght@100..700" rel="stylesheet">
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }

            .material-symbols-outlined {
                font-variation-settings: 'FILL' 1, 'wght' 500, 'GRAD' 0, 'opsz' 24;
            }
        </style>
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
        <div class="relative overflow-hidden">
            <div class="absolute inset-x-0 top-0 -z-10 h-[420px] bg-[radial-gradient(circle_at_top_left,_rgba(14,116,144,0.22),_transparent_38%),radial-gradient(circle_at_top_right,_rgba(249,115,22,0.18),_transparent_30%),linear-gradient(180deg,_#f8fafc_0%,_#f8fafc_100%)]"></div>

            <header class="mx-auto flex max-w-7xl items-center justify-between px-4 py-5 sm:px-6 lg:px-8">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-cyan-700">APPKONKOS</p>
                    <p class="mt-1 text-sm text-slate-600">Platform pencari kos dan kontrakan</p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="<?php echo e(route('auth.pilih-role')); ?>" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-100">
                        Login
                    </a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('register')): ?>
                        <a href="<?php echo e(route('register')); ?>" class="rounded-full bg-cyan-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-800">
                            Daftar
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </header>

            <main class="mx-auto max-w-7xl px-4 pb-16 pt-8 sm:px-6 lg:px-8 lg:pb-24 lg:pt-12">
                <section class="grid gap-10 lg:grid-cols-[1.15fr_0.85fr] lg:items-center">
                    <div>
                        <p class="inline-flex rounded-full border border-cyan-100 bg-cyan-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-cyan-700">
                            Jelajah tanpa login
                        </p>
                        <h1 class="mt-5 max-w-3xl text-4xl font-extrabold tracking-tight text-slate-950 sm:text-5xl lg:text-6xl">
                            Cari kos atau kontrakan dulu, login hanya saat mau pesan.
                        </h1>
                        <p class="mt-5 max-w-2xl text-base leading-7 text-slate-600 sm:text-lg">
                            Pengunjung bisa langsung melihat-lihat properti, area, dan kisaran harga tanpa harus masuk akun. Login baru diperlukan saat ingin booking, mengelola riwayat, atau memakai fitur akun lainnya.
                        </p>

                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            <a href="#properti-publik" class="rounded-full bg-slate-950 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                                Mulai Lihat Properti
                            </a>
                            <a href="<?php echo e(route('auth.pilih-role')); ?>" class="rounded-full border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-100">
                                Masuk Saat Dibutuhkan
                            </a>
                        </div>

                        <div class="mt-10 grid gap-4 sm:grid-cols-3">
                            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Akses Awal</p>
                                <p class="mt-2 text-lg font-bold text-slate-900">Publik</p>
                                <p class="mt-2 text-sm text-slate-600">Homepage dan eksplor properti bisa dibuka tanpa akun.</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Butuh Login</p>
                                <p class="mt-2 text-lg font-bold text-slate-900">Booking</p>
                                <p class="mt-2 text-sm text-slate-600">Pesan unit, cek riwayat, dan aktivitas akun tetap aman di balik login.</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Fokus Pengguna</p>
                                <p class="mt-2 text-lg font-bold text-slate-900">Pencari</p>
                                <p class="mt-2 text-sm text-slate-600">Saat buka situs pertama kali, pengalaman yang muncul adalah untuk pencari properti.</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-[0_24px_80px_rgba(15,23,42,0.08)]">
                        <div class="rounded-[24px] bg-slate-950 p-6 text-white">
                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-cyan-300">Preview Pencari</p>
                            <h2 class="mt-3 text-2xl font-bold">Mulai dari kebutuhan, bukan form login.</h2>
                            <div class="mt-6 space-y-4">
                                <div class="rounded-2xl bg-white/10 p-4">
                                    <p class="text-sm font-semibold">Cari berdasarkan area</p>
                                    <p class="mt-1 text-sm text-slate-200">Indramayu, Cirebon, Bandung, Jakarta, dan area lain.</p>
                                </div>
                                <div class="rounded-2xl bg-white/10 p-4">
                                    <p class="text-sm font-semibold">Lihat tipe properti</p>
                                    <p class="mt-1 text-sm text-slate-200">Kos putra, kos putri, kos campur, sampai kontrakan keluarga.</p>
                                </div>
                                <div class="rounded-2xl bg-white/10 p-4">
                                    <p class="text-sm font-semibold">Filter harga dan ketersediaan</p>
                                    <p class="mt-1 text-sm text-slate-200">Bandingkan opsi sebelum memutuskan untuk login dan booking.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="properti-publik" class="mt-16">
                    <div class="flex items-end justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-cyan-700">Tampilan Awal Pencari</p>
                            <h2 class="mt-2 text-2xl font-bold text-slate-950 sm:text-3xl">Yang dilihat pengunjung saat pertama kali membuka situs</h2>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-5 lg:grid-cols-3">
                        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Kos Putri</p>
                            <h3 class="mt-3 text-xl font-bold text-slate-900">Kos An-Nur</h3>
                            <p class="mt-2 text-sm text-slate-600">Bangkir, Indramayu</p>
                            <p class="mt-4 text-2xl font-extrabold text-slate-950">Rp 850.000<span class="text-sm font-medium text-slate-500"> / bulan</span></p>
                            <p class="mt-3 text-sm text-slate-600">Bisa dilihat tanpa login. Saat klik pesan, pengguna baru diminta masuk akun.</p>
                        </article>

                        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-orange-600">Kontrakan</p>
                            <h3 class="mt-3 text-xl font-bold text-slate-900">Kontrakan Lily</h3>
                            <p class="mt-2 text-sm text-slate-600">Jatibarang, Indramayu</p>
                            <p class="mt-4 text-2xl font-extrabold text-slate-950">Rp 12.000.000<span class="text-sm font-medium text-slate-500"> / tahun</span></p>
                            <p class="mt-3 text-sm text-slate-600">Cocok untuk keluarga kecil, bisa ditinjau dulu tanpa perlu registrasi.</p>
                        </article>

                        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-violet-600">Aksi Pengguna</p>
                            <h3 class="mt-3 text-xl font-bold text-slate-900">Booking Saat Siap</h3>
                            <p class="mt-2 text-sm text-slate-600">Alur dibuat ringan untuk eksplorasi, aman untuk transaksi.</p>
                            <div class="mt-5 rounded-2xl bg-slate-100 p-4 text-sm text-slate-600">
                                Lihat detail dulu
                                <span class="mx-2 text-slate-300">→</span>
                                bandingkan
                                <span class="mx-2 text-slate-300">→</span>
                                login
                                <span class="mx-2 text-slate-300">→</span>
                                booking
                            </div>
                        </article>
                    </div>
                </section>
            </main>

            <div class="fixed inset-0 z-40 bg-slate-950/55 backdrop-blur-[2px]"></div>

            <div class="fixed inset-0 z-50 flex items-start justify-center px-4 py-12 sm:items-center sm:px-6">
                <section class="w-full max-w-[580px] rounded-[34px] bg-white px-7 py-8 shadow-[0_34px_80px_rgba(15,23,42,0.3)] sm:px-10 sm:py-10">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-extrabold uppercase tracking-[0.24em] text-[#2169d7]">Masuk ke Appkonkos</p>
                            <h2 class="mt-7 text-[28px] font-extrabold leading-tight text-slate-900 sm:text-[34px]">
                                Pilih jenis akun untuk melanjutkan
                            </h2>
                        </div>

                        <a
                            href="<?php echo e(url('/')); ?>"
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-900 transition hover:bg-slate-200"
                            aria-label="Tutup"
                        >
                            <span class="material-symbols-outlined text-[20px]">close</span>
                        </a>
                    </div>

                    <div class="mt-9 space-y-5">
                        <a
                            href="<?php echo e(route('auth.portal-login', 'pencari')); ?>"
                            class="group flex items-center gap-5 rounded-[26px] border border-slate-200 bg-white px-6 py-7 transition hover:border-[#2169d7]/35 hover:bg-slate-50"
                        >
                            <div class="flex h-[66px] w-[66px] shrink-0 items-center justify-center rounded-[20px] bg-[#e7f0ff] text-[#2169d7]">
                                <span class="material-symbols-outlined text-[34px]">person_search</span>
                            </div>

                            <div class="min-w-0 flex-1">
                                <h3 class="text-[20px] font-extrabold leading-tight text-slate-900 sm:text-[21px]">Pencari Kos &amp; Kontrakan</h3>
                                <p class="mt-3 text-[15px] leading-7 text-slate-600 sm:text-[16px]">
                                    Masuk untuk menemukan kos &amp; kontrakan
                                </p>
                            </div>
                        </a>

                        <a
                            href="<?php echo e(route('auth.portal-login', 'pemilik')); ?>"
                            class="group flex items-center gap-5 rounded-[26px] border border-slate-200 bg-white px-6 py-7 transition hover:border-[#f59e0b]/35 hover:bg-slate-50"
                        >
                            <div class="flex h-[66px] w-[66px] shrink-0 items-center justify-center rounded-[20px] bg-[#fff3df] text-[#ff8a00]">
                                <span class="material-symbols-outlined text-[34px]">apartment</span>
                            </div>

                            <div class="min-w-0 flex-1">
                                <h3 class="text-[20px] font-extrabold leading-tight text-slate-900 sm:text-[21px]">Admin Kos &amp; Kontrakan</h3>
                                <p class="mt-3 text-[15px] leading-7 text-slate-600 sm:text-[16px]">
                                    Kelola listing kosan &amp; kontrakan
                                </p>
                            </div>
                        </a>
                    </div>

                    <div class="mt-7 flex items-center justify-between gap-4">
                        <p class="text-xs leading-6 text-slate-400">
                            Role superadmin tetap disembunyikan dari tampilan umum.
                        </p>

                        <a
                            href="<?php echo e(route('auth.portal-login', 'superadmin')); ?>"
                            class="rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-300 opacity-15 transition hover:opacity-100 focus:opacity-100"
                        >
                            Superadmin
                        </a>
                    </div>
                </section>
            </div>
        </div>
    </body>
</html>
<?php /**PATH C:\xampp\htdocs\appkonkos_2\resources\views/auth/pilih-role.blade.php ENDPATH**/ ?>