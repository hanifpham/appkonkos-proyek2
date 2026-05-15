@section('mitra-title', 'Notifikasi')
@section('mitra-subtitle', 'Lihat pembaruan booking, status pencairan dana, dan moderasi properti Anda.')

<div class="flex-1 space-y-8 p-6 md:p-8">
    <section class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Belum Dibaca</p>
            <div class="mt-3 flex items-center justify-between">
                <h3 class="text-3xl font-bold text-slate-900 dark:text-white">{{ $unreadCount }}</h3>
                <span class="material-symbols-outlined rounded-xl bg-red-50 p-2 text-red-500 dark:bg-red-950/30 dark:text-red-300">notifications_active</span>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Notifikasi</p>
            <div class="mt-3 flex items-center justify-between">
                <h3 class="text-3xl font-bold text-slate-900 dark:text-white">{{ $totalCount }}</h3>
                <span class="material-symbols-outlined rounded-xl bg-blue-50 p-2 text-[#0F4C81] dark:bg-blue-950/30 dark:text-blue-300">notifications</span>
            </div>
        </div>
    </section>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex flex-col gap-4 border-b border-slate-200 p-6 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-wrap items-center gap-2">
                @foreach (['all' => 'Semua', 'unread' => 'Belum Dibaca'] as $value => $label)
                    <button
                        type="button"
                        wire:click="$set('tab', '{{ $value }}')"
                        @class([
                            'rounded-full border px-4 py-2 text-sm font-semibold transition',
                            'border-[#0F4C81] bg-[#0F4C81] text-white shadow-md' => $tab === $value,
                            'border-slate-200 bg-white text-slate-600 hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:border-blue-400 dark:hover:text-blue-300' => $tab !== $value,
                        ])
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            @if ($unreadCount > 0)
                <button
                    type="button"
                    wire:click="markAllAsRead"
                    wire:loading.attr="disabled"
                    wire:target="markAllAsRead"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-[#0F4C81] hover:text-[#0F4C81] disabled:opacity-50 disabled:cursor-not-allowed dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-blue-400 dark:hover:text-blue-300"
                >
                    <span wire:loading.remove wire:target="markAllAsRead" class="material-symbols-outlined text-[18px]">done_all</span>
                    <span wire:loading wire:target="markAllAsRead" class="material-symbols-outlined text-[18px] animate-spin">refresh</span>
                    <span wire:loading.remove wire:target="markAllAsRead">Tandai Semua Dibaca</span>
                    <span wire:loading wire:target="markAllAsRead">Memproses...</span>
                </button>
            @endif
        </div>

        <div wire:loading.remove wire:target="tab, gotoPage, previousPage, nextPage" class="divide-y divide-slate-100 dark:divide-slate-800">
            @forelse ($notifications as $notification)
                @php
                    $palette = $this->getNotificationPalette($notification);
                    $reason = $this->getNotificationReason($notification);
                    $actionUrl = $this->getNotificationActionUrl($notification);
                @endphp
                <article wire:key="mitra-notification-{{ $notification->id }}" class="px-6 py-5">
                    <div class="flex items-start gap-4">
                        <div class="{{ $palette['wrapper'] }} flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-full">
                            <span class="material-symbols-outlined text-[20px] {{ $palette['icon'] }}">{{ $this->getNotificationIcon($notification) }}</span>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ $this->getNotificationTitle($notification) }}</h3>
                                        @if ($notification->read_at === null)
                                            <span class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-red-600 dark:bg-red-950/30 dark:text-red-300">
                                                Baru
                                            </span>
                                        @endif
                                    </div>
                                    <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $this->getNotificationMessage($notification) }}</p>
                                </div>

                                @if ($notification->read_at === null)
                                    <button
                                        type="button"
                                        wire:click="markAsRead('{{ $notification->id }}')"
                                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:border-blue-400 dark:hover:text-blue-300"
                                    >
                                        <span class="material-symbols-outlined text-[16px]">done</span>
                                        Tandai Dibaca
                                    </button>
                                @endif
                            </div>

                            @if ($reason !== null)
                                <div class="mt-3 rounded-2xl border border-red-100 bg-red-50 px-4 py-3 text-sm font-medium text-red-600 dark:border-red-900/40 dark:bg-red-950/20 dark:text-red-300">
                                    Alasan: {{ $reason }}
                                </div>
                            @endif

                            <div class="mt-3 flex flex-col gap-3 text-xs text-slate-400 dark:text-slate-500 sm:flex-row sm:items-center sm:justify-between">
                                <span>{{ $this->getNotificationTime($notification) }}</span>

                                @if ($actionUrl !== null)
                                    <a
                                        href="{{ $actionUrl }}"
                                        class="inline-flex items-center gap-2 font-semibold text-[#0F4C81] transition hover:underline dark:text-blue-400"
                                    >
                                        {{ $this->getNotificationActionLabel($notification) }}
                                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="px-6 py-16 text-center text-sm text-slate-500 dark:text-slate-400">
                    Tidak ada notifikasi pada tab ini.
                </div>
            @endforelse
        </div>

        {{-- Skeleton Loading --}}
        <div wire:loading.flex wire:target="tab, gotoPage, previousPage, nextPage" class="flex-col divide-y divide-slate-100 dark:divide-slate-800 w-full">
            @for ($i = 0; $i < 3; $i++)
            <article class="px-6 py-5 animate-pulse">
                <div class="flex items-start gap-4">
                    <div class="bg-slate-200 dark:bg-slate-700 flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-full"></div>
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div class="w-full">
                                <div class="h-5 w-1/3 bg-slate-200 dark:bg-slate-700 rounded mb-2"></div>
                                <div class="h-4 w-2/3 bg-slate-200 dark:bg-slate-700 rounded mb-1"></div>
                                <div class="h-4 w-1/2 bg-slate-200 dark:bg-slate-700 rounded"></div>
                            </div>
                        </div>
                        <div class="mt-3 h-3 w-1/4 bg-slate-200 dark:bg-slate-700 rounded"></div>
                    </div>
                </div>
            </article>
            @endfor
        </div>

        <div class="border-t border-slate-200 px-6 py-4 dark:border-slate-800">
            {{ $notifications->links() }}
        </div>
    </section>
</div>