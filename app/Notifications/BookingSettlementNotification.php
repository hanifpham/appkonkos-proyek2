<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingSettlementNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Booking $booking,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $propertyName = $this->resolvePropertyName();
        $tenantName = $this->booking->pencariKos?->user?->name ?? 'Penyewa';
        $amount = (int) ($this->booking->pembayaran?->nominal_bayar ?? $this->booking->total_biaya ?? 0);

        return [
            'title' => 'Booking Settlement Baru',
            'message' => sprintf(
                '%s telah membayar booking untuk %s sebesar Rp %s.',
                $tenantName,
                $propertyName,
                number_format($amount, 0, ',', '.')
            ),
            'reason' => null,
            'icon' => 'payments',
            'color' => 'green',
            'action_url' => route('mitra.pesanan'),
            'action_label' => 'Lihat Booking',
            'booking_id' => $this->booking->id,
            'status' => 'settlement',
        ];
    }

    protected function resolvePropertyName(): string
    {
        if ($this->booking->kamar !== null) {
            $propertyName = $this->booking->kamar->tipeKamar?->kosan?->nama_properti ?? 'Kosan';
            $roomNumber = $this->booking->kamar->nomor_kamar ?? '-';

            return sprintf('%s (Kamar %s)', $propertyName, $roomNumber);
        }

        return $this->booking->kontrakan?->nama_properti ?? 'Kontrakan';
    }
}
