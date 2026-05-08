<?php

declare(strict_types=1);

namespace App\Models;

use App\Notifications\BookingSettlementNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pembayaran extends Model
{
    use HasFactory;

    public const SUCCESS_STATUSES = ['lunas', 'settlement', 'capture'];

    public const PENDING_STATUSES = ['pending', 'authorize', 'challenge'];

    public const FAILED_STATUSES = ['gagal', 'deny', 'cancel', 'expire', 'failure'];

    public const REFUND_STATUSES = ['refund', 'partial_refund', 'chargeback', 'partial_chargeback'];

    protected $table = 'pembayaran';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'booking_id',
        'metode_bayar',
        'waktu_bayar',
        'nominal_bayar',
        'status_bayar',
        'url_struk_pdf',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'status_midtrans',
        'snap_token',
        'snap_redirect_url',
        'fraud_status',
        'payload_midtrans',
    ];

    protected static function booted(): void
    {
        static::saved(function (Pembayaran $payment): void {
            if (! $payment->isSuccessful()) {
                return;
            }

            if (! $payment->wasRecentlyCreated && ! $payment->wasChanged('status_bayar')) {
                return;
            }

            $originalStatus = strtolower(trim((string) $payment->getOriginal('status_bayar')));

            if (
                ! $payment->wasRecentlyCreated
                && in_array($originalStatus, self::SUCCESS_STATUSES, true)
            ) {
                return;
            }

            $booking = $payment->loadMissing([
                'booking.pencariKos.user',
                'booking.kamar.tipeKamar.kosan.pemilikProperti.user',
                'booking.kontrakan.pemilikProperti.user',
            ])->booking;

            if ($booking === null) {
                return;
            }

            $owner = $booking->kamar?->tipeKamar?->kosan?->pemilikProperti?->user
                ?? $booking->kontrakan?->pemilikProperti?->user;
            
            $penyewa = $booking->pencariKos?->user;
            
            $namaProperti = $booking->kamar?->tipeKamar?->kosan?->nama_properti 
                ?? $booking->kontrakan?->nama_properti ?? 'Properti';
                
            $nomorKamarText = $booking->kamar ? '(Kamar: ' . $booking->kamar->nomor_kamar . ')' : '';
            $durasiSewa = \Carbon\Carbon::parse($booking->tgl_mulai_sewa)->diffInMonths($booking->tgl_selesai_sewa);
            if ($durasiSewa == 0) $durasiSewa = \Carbon\Carbon::parse($booking->tgl_mulai_sewa)->diffInYears($booking->tgl_selesai_sewa) * 12;
            if ($durasiSewa == 0) $durasiSewa = 1; // Fallback
            
            $nominalFormatted = number_format((float) $payment->nominal_bayar, 0, ',', '.');

            if ($owner !== null) {
                $owner->notify(new BookingSettlementNotification($booking->loadMissing('pembayaran')));
                
                // Notifikasi WA ke Pemilik
                if (!empty($owner->no_telepon)) {
                    $pesanPemilik = "Notifikasi APPKONKOS 🏠\n\nSelamat! Properti Anda {$namaProperti} {$nomorKamarText} baru saja disewa oleh {$penyewa->name} selama {$durasiSewa} bulan.\n\nTotal Pendapatan: Rp {$nominalFormatted}\n\nMohon bersiap untuk menyambut penyewa. Cek selengkapnya di Dashboard Mitra.";
                    \App\Services\WhatsAppService::send($owner->no_telepon, $pesanPemilik);
                }
            }
            
            if ($penyewa !== null) {
                // Notifikasi WA ke Penyewa
                if (!empty($penyewa->no_telepon)) {
                    $pesanPenyewa = "Notifikasi APPKONKOS ✅\n\nHalo {$penyewa->name}, pembayaran sebesar Rp {$nominalFormatted} telah berhasil!\n\nKamar Anda di {$namaProperti} {$nomorKamarText} sudah dikonfirmasi.\n\nSilakan tunjukkan pesan ini atau E-Ticket Anda saat Check-in kepada pemilik kos.";
                    \App\Services\WhatsAppService::send($penyewa->no_telepon, $pesanPenyewa);
                }
            }
        });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'waktu_bayar' => 'datetime',
            'nominal_bayar' => 'integer',
            'payload_midtrans' => 'array',
        ];
    }

    /**
     * @return list<string>
     */
    public static function successStatuses(): array
    {
        return self::SUCCESS_STATUSES;
    }

    /**
     * @return list<string>
     */
    public static function pendingStatuses(): array
    {
        return self::PENDING_STATUSES;
    }

    /**
     * @return list<string>
     */
    public static function failedStatuses(): array
    {
        return self::FAILED_STATUSES;
    }

    public function normalizedStatus(): string
    {
        $status = strtolower(trim((string) $this->status_bayar));

        if (in_array($status, self::SUCCESS_STATUSES, true)) {
            return 'lunas';
        }

        if (in_array($status, self::FAILED_STATUSES, true)) {
            return 'gagal';
        }

        if (in_array($status, self::REFUND_STATUSES, true)) {
            return 'refund';
        }

        if (in_array($status, self::PENDING_STATUSES, true)) {
            return 'pending';
        }

        return $status !== '' ? $status : 'pending';
    }

    public function isSuccessful(): bool
    {
        return $this->normalizedStatus() === 'lunas';
    }

    public function isPending(): bool
    {
        return $this->normalizedStatus() === 'pending';
    }

    public function isFailed(): bool
    {
        return $this->normalizedStatus() === 'gagal';
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function refund(): HasOne
    {
        return $this->hasOne(Refund::class, 'pembayaran_id');
    }
}
