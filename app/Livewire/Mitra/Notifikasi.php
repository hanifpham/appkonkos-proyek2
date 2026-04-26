<?php

declare(strict_types=1);

namespace App\Livewire\Mitra;

use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Notifikasi extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public string $tab = 'all';

    public function updatingTab(): void
    {
        $this->resetPage();
    }

    public function markAsRead(string $id): void
    {
        $notification = auth()->user()?->unreadNotifications()->find($id);

        if ($notification !== null) {
            $notification->markAsRead();
        }
    }

    public function markAllAsRead(): void
    {
        auth()->user()?->unreadNotifications()->update([
            'read_at' => now(),
        ]);
    }

    #[Layout('layouts.mitra.utama')]
    public function render(): View
    {
        $user = auth()->user();
        $notificationsQuery = $user?->notifications()?->latest();

        if ($notificationsQuery === null) {
            abort(403, 'Unauthorized action.');
        }

        if ($this->tab === 'unread') {
            $notificationsQuery->whereNull('read_at');
        }

        return view('livewire.mitra.notifikasi', [
            'notifications' => $notificationsQuery->paginate(10),
            'unreadCount' => $user->unreadNotifications()->count(),
            'totalCount' => $user->notifications()->count(),
        ]);
    }

    public function getNotificationTitle(DatabaseNotification $notification): string
    {
        return (string) data_get($notification->data, 'title', 'Notifikasi Baru');
    }

    public function getNotificationMessage(DatabaseNotification $notification): string
    {
        return (string) data_get($notification->data, 'message', 'Ada pembaruan baru pada akun Anda.');
    }

    public function getNotificationReason(DatabaseNotification $notification): ?string
    {
        $reason = data_get($notification->data, 'reason');

        return is_string($reason) && $reason !== '' ? $reason : null;
    }

    public function getNotificationActionUrl(DatabaseNotification $notification): ?string
    {
        $actionUrl = data_get($notification->data, 'action_url');

        return is_string($actionUrl) && $actionUrl !== '' ? $actionUrl : null;
    }

    public function getNotificationActionLabel(DatabaseNotification $notification): string
    {
        return (string) data_get($notification->data, 'action_label', 'Buka');
    }

    public function getNotificationIcon(DatabaseNotification $notification): string
    {
        return (string) data_get($notification->data, 'icon', 'notifications');
    }

    /**
     * @return array{wrapper: string, icon: string}
     */
    public function getNotificationPalette(DatabaseNotification $notification): array
    {
        return match (data_get($notification->data, 'color', 'blue')) {
            'green' => [
                'wrapper' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
                'icon' => 'text-emerald-700 dark:text-emerald-300',
            ],
            'amber' => [
                'wrapper' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
                'icon' => 'text-amber-700 dark:text-amber-300',
            ],
            'red' => [
                'wrapper' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                'icon' => 'text-red-700 dark:text-red-300',
            ],
            default => [
                'wrapper' => 'bg-blue-100 text-[#0F4C81] dark:bg-blue-900/40 dark:text-blue-300',
                'icon' => 'text-[#0F4C81] dark:text-blue-300',
            ],
        };
    }

    public function getNotificationTime(DatabaseNotification $notification): string
    {
        return $notification->created_at?->locale('id')->diffForHumans() ?? '-';
    }
}
