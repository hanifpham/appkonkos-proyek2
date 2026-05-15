<div class="relative" x-data="{ open: false }">
    <button
        type="button"
        @click="open = !open"
        class="relative flex h-10 w-10 items-center justify-center rounded-full bg-slate-50 text-slate-500 transition-all hover:bg-slate-100 hover:text-[#113C7A] dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-blue-400"
    >
        <span class="material-symbols-outlined text-[24px]">notifications</span>
        @if ($unreadCount > 0)
            <span class="absolute -right-0.5 -top-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[9px] font-black text-white ring-2 ring-white dark:ring-slate-900">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <div
        x-cloak
        x-show="open"
        @click.outside="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-1 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-1 scale-95"
        class="absolute right-0 z-[100] mt-3 w-80 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-900"
    >
        <div class="flex items-center justify-between border-b border-gray-100 bg-slate-50/50 px-5 py-4 dark:border-slate-800 dark:bg-slate-800/50">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white">Notifikasi</h3>
            <div class="flex items-center gap-3">
                @if ($unreadCount > 0)
                    <button
                        type="button"
                        wire:click="markAllAsRead"
                        class="text-[10px] font-bold text-blue-600 hover:underline dark:text-blue-400"
                    >
                        Tandai semua
                    </button>
                @endif
                <button
                    type="button"
                    wire:click="deleteAllNotifications"
                    wire:confirm="Hapus semua notifikasi?"
                    class="text-[10px] font-bold text-red-600 hover:underline dark:text-red-400"
                >
                    Hapus semua
                </button>
            </div>
        </div>

        <div class="max-h-[380px] overflow-y-auto" wire:poll.10s>
            @forelse ($notifications as $notification)
                <div
                    wire:key="notif-{{ $notification->id }}"
                    @click="open = false"
                    class="group relative flex cursor-pointer gap-3 border-b border-gray-50 p-4 transition hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800/50"
                >
                    <div @click.stop="$wire.markAsRead('{{ $notification->id }}')" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-{{ $notification->data['color'] ?? 'blue' }}-50 text-{{ $notification->data['color'] ?? 'blue' }}-600 dark:bg-{{ $notification->data['color'] ?? 'blue' }}-900/30 dark:text-{{ $notification->data['color'] ?? 'blue' }}-400">
                        <span class="material-symbols-outlined text-[20px]">{{ $notification->data['icon'] ?? 'notifications' }}</span>
                    </div>

                    <div class="flex flex-col gap-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <p class="text-xs font-bold text-slate-800 dark:text-white truncate">
                                {{ $notification->data['title'] ?? 'Notifikasi Baru' }}
                            </p>
                            <button
                                type="button"
                                wire:click.stop="deleteNotification('{{ $notification->id }}')"
                                class="text-slate-400 transition hover:text-red-500 dark:text-slate-500 dark:hover:text-red-400"
                            >
                                <span class="material-symbols-outlined text-[14px]">delete</span>
                            </button>
                        </div>
                        <p class="text-[11px] leading-relaxed text-slate-500 dark:text-slate-400">
                            {{ $notification->data['message'] ?? '' }}
                        </p>
                        <span class="text-[9px] font-medium text-slate-400">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </div>

                    @if (!$notification->read_at)
                        <span class="absolute right-4 top-4 h-2 w-2 rounded-full bg-blue-600"></span>
                    @endif
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-12">
                    <span class="material-symbols-outlined mb-2 text-4xl text-slate-200 dark:text-slate-700">notifications_off</span>
                    <p class="text-xs font-medium text-slate-400">Tidak ada notifikasi baru</p>
                </div>
            @endforelse
        </div>

        <div class="border-t border-gray-100 bg-slate-50/50 p-3 text-center dark:border-slate-800 dark:bg-slate-800/50">
            <a href="#" class="text-[11px] font-bold text-slate-500 transition hover:text-[#113C7A] dark:text-slate-400 dark:hover:text-blue-400">
                Lihat Semua Aktivitas
            </a>
        </div>
    </div>
</div>