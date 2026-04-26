<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Kontrakan;
use App\Models\Kosan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PropertiDisetujuiNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Kosan|Kontrakan $properti,
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
        return [
            'title' => 'Properti Disetujui',
            'message' => 'Properti '.$this->properti->nama_properti.' telah disetujui dan sekarang aktif di platform.',
            'reason' => null,
            'action_url' => route('mitra.properti'),
            'action_label' => 'Lihat Properti',
            'property_id' => $this->properti->id,
            'property_type' => $this->properti instanceof Kosan ? 'kosan' : 'kontrakan',
            'property_name' => $this->properti->nama_properti,
            'status' => 'aktif',
        ];
    }
}
