<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Ticket | {{ config('app.name', 'APPKONKOS') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        @page {
            margin: 1cm;
        }

        @media print {
            body {
                background-color: white !important;
                margin: 0;
                padding: 0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .no-print {
                display: none !important;
            }

            .print-exact {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .ticket-container {
                box-shadow: none !important;
                border: 1px solid #cbd5e1 !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                margin: 0 auto !important;
                width: 100% !important;
            }
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen py-10 antialiased text-slate-800 flex justify-center items-start">

    {{-- Floating Print Button --}}
    <button onclick="window.print()" class="no-print fixed bottom-8 right-8 bg-[#1967d2] hover:bg-blue-800 text-white font-bold py-3 px-6 rounded-full shadow-xl shadow-blue-500/30 transition-all flex items-center gap-2 z-50">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
        </svg>
        Cetak / Simpan PDF
    </button>

    <div class="w-full max-w-4xl px-4 print:px-0">

        {{-- Brand / Header outside ticket --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-6 px-2 no-print gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">E-Ticket Anda</h1>
                <p class="text-slate-500 text-sm mt-1">Tunjukkan tiket ini kepada pengelola saat kedatangan.</p>
            </div>
            <div class="flex items-center gap-2 text-sm font-semibold text-[#1967d2] bg-blue-50 px-4 py-2 rounded-lg border border-blue-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Pembayaran Berhasil
            </div>
        </div>

        {{-- Ticket Wrapper --}}
        <div class="ticket-container flex flex-col md:flex-row print:flex-row bg-white rounded-2xl shadow-xl shadow-slate-200/50 print-exact overflow-hidden border border-slate-200">

            {{-- Left Section: Details --}}
            <div class="w-full md:w-[70%] print:w-[70%] p-6 sm:p-8 print:p-6 relative">

                {{-- Decorative Header --}}
                <div class="flex justify-between items-start mb-8 pb-6 border-b border-slate-100">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 items-center justify-center overflow-hidden rounded-full bg-white shadow-md shadow-blue-500/15 ring-2 ring-[#1967d2]/20 transition-all duration-300 group-hover:scale-105 group-hover:shadow-blue-500/30 group-hover:ring-[#1967d2]/40">
                            <img src="{{ asset('images/appkonkos.png') }}" alt="{{ config('app.name', 'APPKONKOS') }}" class="h-9 w-9 object-contain">
                        </div>
                        <div>
                            <div class="text-x2 font-black text-slate-900  leading-none">APPKONKOS</div>
                            <div class="text-[10px] font-bold text-[#1967d2]  tracking-widest mb-0.5">Pemesanan Kos & Kontrakan</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="inline-block border-2 border-emerald-500 text-emerald-600 bg-emerald-50 text-sm font-black px-4 py-1.5 rounded-lg uppercase tracking-widest print-exact shadow-sm">
                            Lunas
                        </div>
                    </div>
                </div>

                {{-- Property Title --}}
                <div class="mb-8">
                    <h2 class="text-2xl font-black text-slate-900 mb-2 leading-tight">{{ $propertyName }}</h2>
                    @php
                    $alamat = $isKosan ? ($booking->kamar?->tipeKamar?->kosan?->alamat_lengkap ?? '-') : ($booking->kontrakan?->alamat_lengkap ?? '-');
                    $tipe = $isKosan ? ($booking->kamar?->tipeKamar?->nama_tipe ?? '-') : '-';
                    $nomorKamar = $isKosan ? ($booking->kamar?->nomor_kamar ?? '-') : '-';
                    $durasiSewa = 0;
                    if ($booking->tgl_mulai_sewa && $booking->tgl_selesai_sewa) {
                    $durasiSewa = $booking->tgl_mulai_sewa->diffInMonths($booking->tgl_selesai_sewa);
                    if ($durasiSewa == 0) $durasiSewa = $booking->tgl_mulai_sewa->diffInYears($booking->tgl_selesai_sewa) * 12;
                    if ($durasiSewa == 0) $durasiSewa = 1;
                    }
                    @endphp
                    <p class="text-slate-500 text-sm flex items-start gap-1.5 max-w-2xl">
                        <svg class="w-4 h-4 text-rose-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="leading-relaxed">{{ $alamat }}</span>
                    </p>
                </div>

                {{-- Key Details Grid --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 print:grid-cols-4 gap-6 print:gap-4 mb-8 bg-slate-50 p-5 rounded-xl border border-slate-100 print-exact">
                    <div class="col-span-2 sm:col-span-1 print:col-span-1">
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Check-in</div>
                        <div class="font-black text-slate-800 text-sm">
                            {{ $booking->tgl_mulai_sewa ? $booking->tgl_mulai_sewa->translatedFormat('d M Y') : '-' }}
                        </div>
                        <div class="text-[11px] font-medium text-slate-500 mt-0.5">14:00 WIB</div>
                    </div>
                    <div class="col-span-2 sm:col-span-1 print:col-span-1">
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Check-out</div>
                        <div class="font-black text-slate-800 text-sm">
                            {{ $booking->tgl_selesai_sewa ? $booking->tgl_selesai_sewa->translatedFormat('d M Y') : '-' }}
                        </div>
                        <div class="text-[11px] font-medium text-slate-500 mt-0.5">12:00 WIB</div>
                    </div>
                    <div class="col-span-2 sm:col-span-1 print:col-span-1">
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Durasi</div>
                        <div class="font-black text-[#1967d2] text-sm bg-blue-100/50 px-2 py-0.5 rounded border border-blue-200 inline-block print-exact">
                            {{ $durasiSewa }} Bulan
                        </div>
                    </div>
                    <div class="col-span-2 sm:col-span-1 print:col-span-1">
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Tipe / No</div>
                        <div class="font-black text-slate-800 text-sm">
                            {{ $isKosan ? "$tipe / $nomorKamar" : 'Kontrakan' }}
                        </div>
                    </div>
                </div>

                {{-- Additional Info --}}
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Nama Penyewa</div>
                        <div class="font-bold text-slate-800 text-sm flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-full bg-[#1967d2]/10 text-[#1967d2] flex items-center justify-center text-xs font-black print-exact border border-[#1967d2]/20">{{ substr($booking->pencariKos->user->name, 0, 1) }}</div>
                            {{ $booking->pencariKos->user->name }}
                        </div>
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Pengelola / Pemilik</div>
                        <div class="font-bold text-slate-800 text-sm flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center text-xs font-black print-exact border border-slate-200">{{ substr($ownerName, 0, 1) }}</div>
                            {{ $ownerName }}
                        </div>
                    </div>
                </div>

                {{-- Important Notice --}}
                <div class="mt-8 pt-6 border-t border-dashed border-slate-200">
                    <p class="text-xs text-slate-500 flex gap-2 items-start">
                        <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>E-Ticket ini bersifat rahasia. Jangan berikan kode pesanan atau QR Code kepada pihak yang tidak berkepentingan demi keamanan pesanan Anda.</span>
                    </p>
                </div>
            </div>

            {{-- Divider (Perforated line) --}}
            <div class="relative w-full md:w-0 print:w-0 border-t md:border-t-0 print:border-t-0 md:border-l-2 print:border-l-2 border-dashed border-slate-300 flex justify-center items-center print:border-slate-400">
                {{-- Semicircles for the cut effect --}}
                <div class="hidden md:block absolute -top-1 -left-3 w-6 h-6 bg-slate-50 rounded-full border-b border-slate-200 print:hidden"></div>
                <div class="hidden md:block absolute -bottom-1 -left-3 w-6 h-6 bg-slate-50 rounded-full border-t border-slate-200 print:hidden"></div>

                {{-- Mobile semicircles --}}
                <div class="block md:hidden absolute -left-1 -top-3 w-6 h-6 bg-slate-50 rounded-full border-r border-slate-200 print:hidden"></div>
                <div class="block md:hidden absolute -right-1 -top-3 w-6 h-6 bg-slate-50 rounded-full border-l border-slate-200 print:hidden"></div>
            </div>

            {{-- Right Section: Stub/Summary --}}
            <div class="w-full md:w-[30%] print:w-[30%] bg-gradient-to-b from-slate-50 to-white p-6 sm:p-8 flex flex-col items-center justify-center text-center relative print:bg-transparent">

                <div class="w-full mb-6 max-w-full">
                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Nomor Pesanan</div>
                    <div class="font-mono text-[11px] sm:text-xs font-black text-[#1967d2] bg-blue-50/50 p-2 rounded-lg border border-blue-100 print-exact break-all">{{ $orderId }}</div>
                </div>

                {{-- QR Code --}}
                @php
                    try {
                        $qrResult = (new \Endroid\QrCode\Builder\Builder(
                            data: route('pencari.e-ticket', ['booking' => $booking->id]),
                            encoding: new \Endroid\QrCode\Encoding\Encoding('UTF-8'),
                            errorCorrectionLevel: \Endroid\QrCode\ErrorCorrectionLevel::High,
                            size: 250,
                            margin: 10,
                            roundBlockSizeMode: \Endroid\QrCode\RoundBlockSizeMode::Margin,
                            writer: new \Endroid\QrCode\Writer\PngWriter()
                        ))->build();
                        $qrDataUri = $qrResult->getDataUri();
                    } catch (\Exception $e) {
                        $qrDataUri = '';
                    }
                @endphp
                <div class="bg-white p-3 rounded-xl shadow-sm border border-slate-200 mb-6 print-exact inline-block group hover:shadow-md transition-shadow">
                    @if($qrDataUri)
                    <img src="{{ $qrDataUri }}" alt="QR Code" class="w-28 h-28 object-contain group-hover:scale-105 transition-transform duration-300">
                    @else
                    <div class="w-28 h-28 flex items-center justify-center bg-slate-50 text-slate-400 text-[10px] text-center border border-dashed border-slate-300">QR Error</div>
                    @endif
                </div>

                <div class="w-full mb-6">
                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Metode Pembayaran</div>
                    <div class="font-bold text-slate-800 text-sm capitalize">
                        {{ str_replace('_', ' ', $booking->pembayaran->metode_bayar ?? 'Midtrans') }}
                    </div>
                    <div class="text-[10px] text-slate-500 mt-0.5">
                        {{ $booking->pembayaran->waktu_bayar ? $booking->pembayaran->waktu_bayar->translatedFormat('d M Y, H:i') : $booking->pembayaran->updated_at->translatedFormat('d M Y, H:i') }}
                    </div>
                </div>

                <div class="w-full pt-6 border-t border-dashed border-slate-300">
                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Total Pembayaran</div>
                    <div class="font-black text-xl text-slate-900">
                        Rp {{ number_format($booking->pembayaran->nominal_bayar ?? $booking->total_biaya, 0, ',', '.') }}
                    </div>
                </div>

            </div>

        </div>
    </div>
</body>

</html>