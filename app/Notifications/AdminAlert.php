<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminAlert extends Notification
{
    use Queueable;

    public function __construct(
        protected string $title,
        protected string $message,
        protected string $icon = 'notifications',
        protected ?string $actionUrl = null,
        protected string $actionLabel = 'Buka',
        protected ?string $reason = null,
        protected string $color = 'blue',
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, string|null>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'icon' => $this->icon,
            'action_url' => $this->actionUrl,
            'action_label' => $this->actionLabel,
            'reason' => $this->reason,
            'color' => $this->color,
        ];
    }
}
