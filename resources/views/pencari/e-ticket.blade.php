<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Ticket | {{ config('app.name', 'APPKONKOS') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        @media print {
            body {
                background-color: white !important;
                margin: 0;
                padding: 0;
            }

            .no-print {
                display: none !important;
            }

            .print-container {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                max-width: 100% !important;
            }

            .print-exact {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>

<body class="bg-slate-100 py-10 antialiased text-slate-900">

    {{-- Floating Print Button --}}
    <button onclick="window.print()" class="no-print fixed bottom-8 right-8 bg-[#1967d2] hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-full shadow-lg shadow-blue-500/30 transition-all flex items-center gap-2 z-50">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
        </svg>
        Cetak / Simpan PDF
    </button>

    <div class="print-container print-exact max-w-3xl mx-auto bg-white p-8 md:p-10 rounded-2xl shadow-xl border border-slate-200 relative overflow-hidden">

        {{-- Background Pattern/Decoration --}}
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-50/50 rounded-bl-full -z-10"></div>

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b-2 border-dashed border-slate-200 pb-5 mb-6">
            <div class="flex items-center gap-3 mb-4 sm:mb-0">
                {{-- Logo --}}
                <div class="flex h-11 w-11 items-center justify-center overflow-hidden rounded-full bg-white shadow-md shadow-blue-500/15 ring-2 ring-[#1967d2]/20 transition-all duration-300 group-hover:scale-105 group-hover:shadow-blue-500/30 group-hover:ring-[#1967d2]/40">
                    <img src="{{ asset('images/appkonkos.png') }}" alt="{{ config('app.name', 'APPKONKOS') }}" class="h-9 w-9 object-contain">
                </div>
                <div>
                    <h1 class="text-xl font-black tracking-tight text-slate-900">APPKONKOS</h1>
                    <p class="text-[10px] text-[#1967d2] font-bold tracking-widest uppercase">E-Ticket & Bukti Pembayaran</p>
                </div>
            </div>
            <div class="text-left sm:text-right bg-slate-50 p-2.5 rounded-xl border border-slate-100 print-exact sm:max-w-[240px]">
                <p class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider mb-0.5">Nomor Pesanan</p>
                <p class="text-xs font-bold text-slate-800 font-mono break-all leading-tight">{{ $orderId }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 relative">

            {{-- Status Stamp --}}
            <div class="absolute -top-3 right-0 opacity-90 pointer-events-none rotate-[15deg] z-10 hidden sm:block">
                <div class="border-4 border-emerald-500 text-emerald-500 text-xl font-black px-4 py-1.5 rounded-lg inline-block uppercase tracking-widest shadow-sm bg-white/95 backdrop-blur-sm print-exact" style="font-family: monospace;">
                    LUNAS / PAID
                </div>
            </div>

            {{-- Stamp for mobile --}}
            <div class="block sm:hidden text-center mb-6">
                <div class="border-4 border-emerald-500 text-emerald-500 text-lg font-black px-4 py-1.5 rounded-lg inline-block uppercase tracking-widest bg-emerald-50 print-exact">
                    LUNAS / PAID
                </div>
            </div>

            <div class="lg:col-span-2 space-y-5 z-10">
                {{-- Detail Transaksi --}}
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Informasi Transaksi
                    </h3>
                    <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-100 print-exact">
                        <div>
                            <p class="text-[10px] text-slate-500 mb-0.5 uppercase font-semibold tracking-wider">Tanggal Bayar</p>
                            <p class="font-bold text-slate-800 text-xs">
                                {{ $booking->pembayaran->waktu_bayar ? $booking->pembayaran->waktu_bayar->translatedFormat('d M Y, H:i') : $booking->pembayaran->updated_at->translatedFormat('d M Y, H:i') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-500 mb-0.5 uppercase font-semibold tracking-wider">Metode Pembayaran</p>
                            <p class="font-bold text-slate-800 text-xs capitalize">
                                {{ str_replace('_', ' ', $booking->pembayaran->metode_bayar ?? 'Midtrans') }}
                            </p>
                        </div>
                        <div class="col-span-2 pt-2.5 border-t border-slate-200/60 mt-0.5">
                            <p class="text-[10px] text-slate-500 mb-0.5 uppercase font-semibold tracking-wider">Nama Penyewa</p>
                            <p class="font-black text-slate-900 text-sm">{{ $booking->pencariKos->user->name }}</p>
                        </div>
                    </div>
                </div>

                {{-- Detail Properti --}}
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Detail Properti & Menginap
                    </h3>
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 print-exact">
                        <div class="flex justify-between items-start mb-2">
                            <p class="font-black text-lg text-[#1967d2] pr-2">{{ $propertyName }}</p>
                            @php
                            $durasiSewa = 0;
                            if ($booking->tgl_mulai_sewa && $booking->tgl_selesai_sewa) {
                            $durasiSewa = $booking->tgl_mulai_sewa->diffInMonths($booking->tgl_selesai_sewa);
                            if ($durasiSewa == 0) $durasiSewa = $booking->tgl_mulai_sewa->diffInYears($booking->tgl_selesai_sewa) * 12;
                            if ($durasiSewa == 0) $durasiSewa = 1;
                            }
                            @endphp
                            @if($durasiSewa > 0)
                            <span class="bg-[#1967d2]/10 text-[#1967d2] text-[10px] font-bold px-2 py-0.5 rounded-full border border-[#1967d2]/20 whitespace-nowrap shrink-0 mt-0.5">{{ $durasiSewa }} Bulan</span>
                            @endif
                        </div>
                        @php
                        $alamat = $isKosan ? ($booking->kamar?->tipeKamar?->kosan?->alamat ?? '-') : ($booking->kontrakan?->alamat ?? '-');
                        $tipe = $isKosan ? ($booking->kamar?->tipeKamar?->nama_tipe ?? '-') : '-';
                        $nomorKamar = $isKosan ? ($booking->kamar?->nomor_kamar ?? '-') : '-';
                        @endphp
                        <p class="text-xs text-slate-600 mb-4 leading-relaxed bg-white p-2.5 rounded-lg border border-slate-100 shadow-sm">{{ $alamat }}</p>

                        <div class="grid grid-cols-2 gap-y-4 gap-x-3">
                            <div>
                                <p class="text-[10px] text-slate-500 mb-0.5 uppercase font-semibold tracking-wider">Tipe / Kamar</p>
                                <p class="font-bold text-slate-800 text-xs">{{ $isKosan ? "$tipe / $nomorKamar" : 'Kontrakan' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-500 mb-0.5 uppercase font-semibold tracking-wider">Pemilik</p>
                                <p class="font-bold text-slate-800 text-xs flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ $ownerName }}
                                </p>
                            </div>
                            <div class="bg-blue-50/50 p-2.5 rounded-lg border border-blue-100/50">
                                <p class="text-[10px] text-[#1967d2] mb-0.5 uppercase font-semibold tracking-wider">Check-in</p>
                                <p class="font-black text-slate-900 text-xs">{{ $booking->tgl_mulai_sewa ? $booking->tgl_mulai_sewa->translatedFormat('d M Y') : '-' }}</p>
                            </div>
                            <div class="bg-rose-50/50 p-2.5 rounded-lg border border-rose-100/50">
                                <p class="text-[10px] text-rose-600 mb-0.5 uppercase font-semibold tracking-wider">Check-out</p>
                                <p class="font-black text-slate-900 text-xs">{{ $booking->tgl_selesai_sewa ? $booking->tgl_selesai_sewa->translatedFormat('d M Y') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan (Ringkasan Harga) --}}
            <div class="z-10 flex flex-col justify-start mt-6 lg:mt-0 lg:pt-8">
                {{-- Rincian Biaya --}}
                <div class="bg-[#1967d2] rounded-xl overflow-hidden print-exact shadow-md shadow-blue-500/20 text-white">
                    <div class="px-4 py-3 border-b border-blue-400/30 border-dashed">
                        <h3 class="text-xs font-bold text-blue-100 uppercase tracking-wider text-center">Total Pembayaran</h3>
                    </div>
                    <div class="p-4 text-center">
                        <div class="flex items-end justify-center gap-1">
                            <span class="font-medium text-blue-200 text-xs mb-1">IDR</span>
                            <span class="font-black text-2xl tracking-tight">Rp {{ number_format($booking->pembayaran->nominal_bayar ?? $booking->total_biaya, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer/Instruksi --}}
        <div class="mt-8 pt-6 border-t-2 border-dashed border-slate-200">
            <div class="flex items-start gap-4 bg-gradient-to-r from-blue-50 to-transparent p-5 rounded-2xl border border-blue-100/50 print-exact">
                <div class="bg-white p-2 rounded-xl shadow-sm border border-blue-100 shrink-0">
                    <svg class="w-6 h-6 text-[#1967d2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="font-black text-[#1967d2] text-sm mb-1.5 uppercase tracking-wider">Instruksi Kedatangan</h4>
                    <p class="text-[13px] text-slate-600 leading-relaxed font-medium">Silakan tunjukkan E-Ticket ini kepada pemilik kos saat kedatangan untuk proses serah terima kunci. E-Ticket ini adalah bukti pembayaran sah yang diterbitkan oleh sistem APPKONKOS. Harap simpan dengan baik.</p>
                </div>
            </div>
        </div>

    </div>
</body>

</html>