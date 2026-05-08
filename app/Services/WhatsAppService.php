<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Mengirim pesan WhatsApp menggunakan API Fonnte (atau provider lain).
     *
     * @param string $nomorTujuan Nomor WhatsApp tujuan (format: 08xxx atau 628xxx)
     * @param string $pesan Isi pesan teks
     * @return bool
     */
    public static function send(string $nomorTujuan, string $pesan): bool
    {
        // Bersihkan nomor agar sesuai format
        $nomorTujuan = self::formatNomor($nomorTujuan);

        if (empty($nomorTujuan)) {
            Log::warning('WhatsAppService: Gagal mengirim pesan. Nomor tujuan kosong.');
            return false;
        }

        try {
            $token = env('FONNTE_TOKEN', 'TOKEN_SEMENTARA');
            
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $nomorTujuan,
                'message' => $pesan,
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                Log::info('WhatsAppService: Pesan berhasil dikirim ke ' . $nomorTujuan);
                return true;
            }

            Log::error('WhatsAppService: Fonnte API Error.', ['response' => $response->body()]);
            return false;
            
        } catch (\Exception $e) {
            Log::error('WhatsAppService: Terjadi kesalahan sistem. ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Format nomor HP agar standar
     */
    private static function formatNomor(string $nomor): string
    {
        $nomor = preg_replace('/[^0-9]/', '', $nomor);
        
        // Ubah awalan 0 menjadi 62 jika diperlukan oleh Fonnte
        // Walau Fonnte punya parameter countryCode, lebih baik menstandarisasi
        if (substr($nomor, 0, 1) === '0') {
            $nomor = '62' . substr($nomor, 1);
        }

        return $nomor;
    }
}
