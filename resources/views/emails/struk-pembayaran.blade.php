@php
    $payment = $payment ?? $pembayaran ?? null;
    $booking = $booking ?? $payment?->booking;

    $orderId = $payment?->midtrans_order_id ?: '#TRX-'.strtoupper(substr((string) ($booking?->id ?? ''), 0, 8));
    $propertyName = $booking?->kamar?->tipeKamar?->kosan?->nama_properti
        ?? $booking?->kontrakan?->nama_properti
        ?? 'Properti APPKONKOS';
    $roomNumber = $booking?->kamar?->nomor_kamar
        ? 'Kamar '.$booking->kamar->nomor_kamar
        : ($booking?->kontrakan ? 'Unit Kontrakan' : '-');
    $checkIn = $booking?->tgl_mulai_sewa
        ? $booking->tgl_mulai_sewa->translatedFormat('d F Y')
        : '-';
    $duration = '-';

    if ($booking?->tgl_mulai_sewa && $booking?->tgl_selesai_sewa) {
        $months = (int) $booking->tgl_mulai_sewa->diffInMonths($booking->tgl_selesai_sewa);
        $duration = max(1, $months).' Bulan';
    }

    $amount = (int) ($payment?->nominal_bayar ?? $booking?->total_biaya ?? 0);
    $formattedAmount = 'Rp '.number_format($amount, 0, ',', '.');
    $paidAt = $payment?->waktu_bayar
        ? $payment->waktu_bayar->translatedFormat('d F Y, H:i')
        : now()->translatedFormat('d F Y, H:i');
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Struk Pembayaran APPKONKOS</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f6fb; font-family:Arial, Helvetica, sans-serif; color:#0f172a;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="width:100%; background-color:#f3f6fb; padding:32px 12px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width:640px; width:100%; background-color:#ffffff; border-radius:18px; overflow:hidden; border:1px solid #dbe4f0; box-shadow:0 18px 45px rgba(15, 23, 42, 0.10);">
                    <tr>
                        <td style="background-color:#1967d2; padding:28px 32px;">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td>
                                        <div style="font-size:26px; line-height:1; font-weight:800; color:#ffffff; letter-spacing:0;">APPKONKOS</div>
                                        <div style="margin-top:8px; font-size:14px; line-height:22px; color:#dbeafe;">Struk Pembayaran Resmi</div>
                                    </td>
                                    <td align="right" style="vertical-align:middle;">
                                        <span style="display:inline-block; background-color:#dcfce7; color:#047857; border:1px solid #86efac; border-radius:999px; padding:8px 14px; font-size:11px; line-height:14px; font-weight:800; letter-spacing:0.5px;">
                                            PEMBAYARAN BERHASIL
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px 32px 8px 32px;">
                            <p style="margin:0; font-size:16px; line-height:26px; color:#334155;">
                                Halo, pembayaran Anda untuk <strong style="color:#0f172a;">{{ $propertyName }}</strong> telah berhasil dikonfirmasi.
                            </p>
                            <p style="margin:10px 0 0 0; font-size:13px; line-height:22px; color:#64748b;">
                                Simpan email ini sebagai bukti pembayaran dan tunjukkan saat proses check-in bila diperlukan.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:22px 32px 26px 32px;">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:separate; border-spacing:0; border:1px solid #e2e8f0; border-radius:14px; overflow:hidden;">
                                <tr>
                                    <td style="width:42%; background-color:#f8fafc; padding:14px 18px; border-bottom:1px solid #e2e8f0; font-size:13px; color:#64748b; font-weight:700;">Nomor Invoice (Order ID)</td>
                                    <td style="padding:14px 18px; border-bottom:1px solid #e2e8f0; font-size:14px; color:#0f172a; font-weight:700;">{{ $orderId }}</td>
                                </tr>
                                <tr>
                                    <td style="background-color:#f8fafc; padding:14px 18px; border-bottom:1px solid #e2e8f0; font-size:13px; color:#64748b; font-weight:700;">Nama Kosan</td>
                                    <td style="padding:14px 18px; border-bottom:1px solid #e2e8f0; font-size:14px; color:#0f172a;">{{ $propertyName }}</td>
                                </tr>
                                <tr>
                                    <td style="background-color:#f8fafc; padding:14px 18px; border-bottom:1px solid #e2e8f0; font-size:13px; color:#64748b; font-weight:700;">Nomor Kamar</td>
                                    <td style="padding:14px 18px; border-bottom:1px solid #e2e8f0; font-size:14px; color:#0f172a;">{{ $roomNumber }}</td>
                                </tr>
                                <tr>
                                    <td style="background-color:#f8fafc; padding:14px 18px; border-bottom:1px solid #e2e8f0; font-size:13px; color:#64748b; font-weight:700;">Tanggal Check-in</td>
                                    <td style="padding:14px 18px; border-bottom:1px solid #e2e8f0; font-size:14px; color:#0f172a;">{{ $checkIn }}</td>
                                </tr>
                                <tr>
                                    <td style="background-color:#f8fafc; padding:14px 18px; border-bottom:1px solid #e2e8f0; font-size:13px; color:#64748b; font-weight:700;">Durasi Sewa</td>
                                    <td style="padding:14px 18px; border-bottom:1px solid #e2e8f0; font-size:14px; color:#0f172a;">{{ $duration }}</td>
                                </tr>
                                <tr>
                                    <td style="background-color:#f8fafc; padding:16px 18px; font-size:13px; color:#64748b; font-weight:700;">Total Nominal Pembayaran</td>
                                    <td style="padding:16px 18px; font-size:20px; color:#1967d2; font-weight:800;">{{ $formattedAmount }}</td>
                                </tr>
                            </table>

                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin-top:18px; background-color:#eff6ff; border:1px solid #bfdbfe; border-radius:14px;">
                                <tr>
                                    <td style="padding:16px 18px;">
                                        <div style="font-size:12px; line-height:18px; font-weight:800; color:#1d4ed8; text-transform:uppercase; letter-spacing:0.5px;">Waktu Konfirmasi</div>
                                        <div style="margin-top:4px; font-size:14px; line-height:22px; color:#1e3a8a;">{{ $paidAt }} WIB</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:24px 32px 30px 32px; background-color:#f8fafc; border-top:1px solid #e2e8f0;">
                            <p style="margin:0; font-size:14px; line-height:24px; color:#334155;">
                                Terima kasih telah mempercayai APPKONKOS sebagai hunian nyaman Anda.
                            </p>
                            <p style="margin:12px 0 0 0; font-size:12px; line-height:20px; color:#94a3b8;">
                                Email ini dikirim otomatis oleh sistem APPKONKOS. Mohon tidak membalas email ini.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
