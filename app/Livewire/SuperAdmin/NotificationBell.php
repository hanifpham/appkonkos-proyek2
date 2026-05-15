<?php

declare(strict_types=1);

namespace App\Livewire\SuperAdmin;

use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Collection;
use Livewire\Component;

class NotificationBell extends Component
{
    public function getListeners(): array
    {
        return [
            'echo:notifications,NotificationSent' => '$refresh',
        ];
    }

    public function render(): View
    {
        /** @var \App\Models\User|null $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        
        /** @var Collection<int, DatabaseNotification> $notifications */
        $notifications = $user?->unreadNotifications()->latest()->limit(5)->get() ?? collect();
        $unreadCount = $user?->unreadNotifications()->count() ?? 0;

        return view('livewire.superadmin.notification-bell', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    public function markAsRead(string $id): void
    {
        /** @var \App\Models\User|null $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        $notification = $user?->notifications()->findOrFail($id);
        $notification?->markAsRead();
    }

    public function markAllAsRead(): void
    {
        /** @var \App\Models\User|null $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        $user?->unreadNotifications->markAsRead();
        
        $this->dispatch(
            'appkonkos-toast',
            icon: 'success',
            title: 'Berhasil',
            text: 'Semua notifikasi telah ditandai sebagai dibaca.'
        );
    }

    public function deleteNotification(string $id): void
    {
        /** @var \App\Models\User|null $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        $notification = $user?->notifications()->find($id);

        if ($notification !== null) {
            $notification->delete();
        }
    }

    public function deleteAllNotifications(): void
    {
        /** @var \App\Models\User|null $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        $user?->notifications()->delete();

        $this->dispatch(
            'appkonkos-toast',
            icon: 'success',
            title: 'Berhasil',
            text: 'Semua notifikasi telah dihapus.'
        );
    }
}
