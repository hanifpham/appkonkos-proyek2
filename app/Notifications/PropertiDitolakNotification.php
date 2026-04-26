<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Kontrakan;
use App\Models\Kosan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PropertiDitolakNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Kosan|Kontrakan $properti,
        protected ?string $alasan,
        protected string $title = 'Properti Ditolak',
        protected string $message = '',
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
        $message = $this->message !== ''
            ? $this->message
            : 'Properti '.$this->properti->nama_properti.' memerlukan perbaikan sebelum dapat diajukan ulang.';

        return [
            'title' => $this->title,
            'message' => $message,
            'reason' => $this->alasan ?: 'Melanggar syarat dan ketentuan platform.',
            'action_url' => route('mitra.properti'),
            'action_label' => 'Edit Properti',
            'property_id' => $this->properti->id,
            'property_type' => $this->properti instanceof Kosan ? 'kosan' : 'kontrakan',
            'property_name' => $this->properti->nama_properti,
            'status' => 'ditolak',
        ];
    }
}
