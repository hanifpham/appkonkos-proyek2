<x-guest-layout>
    <x-autentikasi.kartu-autentikasi>
        <x-slot name="logo">
            <x-autentikasi.logo-kartu />
        </x-slot>

        <div class="text-center lg:text-left">
            <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-[#1967d2]/10 text-[#1967d2] ring-1 ring-[#1967d2]/15 lg:mx-0">
                <span class="material-symbols-outlined text-5xl" aria-hidden="true">mark_email_unread</span>
            </div>

            <p class="mb-2 text-sm font-semibold uppercase tracking-[0.18em] text-[#1967d2]">
                Verifikasi Email
            </p>

            <h1 class="text-2xl font-bold leading-tight text-gray-950 dark:text-white">
                Aktifkan Akun APPKONKOS Anda!
            </h1>

            <p class="mt-4 text-sm leading-6 text-gray-600 dark:text-gray-300">
                Kami telah mengirimkan link verifikasi ke email Anda. Buka email tersebut, lalu klik tombol verifikasi agar akun APPKONKOS dapat digunakan sepenuhnya.
            </p>

            @if (session('status') === 'verification-link-sent')
                <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-left text-sm font-medium text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-200">
                    Email verifikasi baru berhasil dikirim. Silakan cek inbox atau folder spam email Anda.
                </div>
            @endif

            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
                <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
                    @csrf

                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-[#1967d2] px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-900/15 transition hover:bg-[#0f4fb5] focus:outline-none focus:ring-2 focus:ring-[#1967d2] focus:ring-offset-2 dark:focus:ring-offset-slate-950 sm:w-auto">
                        <span class="material-symbols-outlined text-xl" aria-hidden="true">send</span>
                        Kirim Ulang Email Verifikasi
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                    @csrf

                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-700 transition hover:border-[#1967d2]/40 hover:text-[#1967d2] focus:outline-none focus:ring-2 focus:ring-[#1967d2] focus:ring-offset-2 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-[#1967d2] dark:hover:text-white dark:focus:ring-offset-slate-950 sm:w-auto">
                        <span class="material-symbols-outlined text-xl" aria-hidden="true">logout</span>
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </x-autentikasi.kartu-autentikasi>
</x-guest-layout>
