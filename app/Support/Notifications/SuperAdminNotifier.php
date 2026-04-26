<?php

declare(strict_types=1);

namespace App\Support\Notifications;

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Notification as BaseNotification;

class SuperAdminNotifier
{
    public static function send(BaseNotification $notification): void
    {
        $superAdmins = User::query()
            ->where('role', 'superadmin')
            ->get();

        if ($superAdmins->isEmpty()) {
            return;
        }

        Notification::send($superAdmins, $notification);
    }
}
