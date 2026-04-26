<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\PencairanDana;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PencairanStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected PencairanDana $pencairan,
        protected string $status,
        protected ?string $alasan = null,
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
        $title = match ($this->status) {
            'sukses' => 'Pencairan Berhasil',
            'ditolak' => 'Pencairan Ditolak',
            default => 'Status Pencairan Diperbarui',
        };

        $message = match ($this->status) {
            'sukses' => 'Permintaan pencairan dana sebesar Rp '.number_format((int) $this->pencairan->nominal, 0, ',', '.').' telah berhasil diproses.',
            'ditolak' => 'Permintaan pencairan dana sebesar Rp '.number_format((int) $this->pencairan->nominal, 0, ',', '.').' ditolak oleh Super Admin.',
            default => 'Status pencairan dana Anda telah diperbarui.',
        };

        return [
            'title' => $title,
            'message' => $message,
            'reason' => $this->status === 'ditolak'
                ? ($this->alasan ?: 'Silakan periksa kembali data pencairan Anda.')
                : null,
            'action_url' => route('mitra.keuangan'),
            'action_label' => 'Lihat Riwayat',
            'withdrawal_id' => $this->pencairan->id,
            'status' => $this->status,
            'nominal' => (int) $this->pencairan->nominal,
        ];
    }
}
