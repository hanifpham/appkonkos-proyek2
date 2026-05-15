@section('mitra-title', 'Ulasan Penyewa')
@section('mitra-subtitle', 'Pantau masukan penyewa dan kirim tanggapan langsung dari dashboard mitra.')

@push('styles')
<style>
    .material-symbols-outlined.fill-1 {
        font-variation-settings: 'FILL' 1, 'wght' 300, 'GRAD' 0, 'opsz' 24;
    }
</style>
@endpush

@php
$fullAverageStars = (int) floor($rataRataRating);
$hasHalfAverageStar = ($rataRataRating - $fullAverageStars) >= 0.5;
$activeFilterLabel = in_array($filter, $filterOptions, true) ? $filter : 'Terbaru';
@endphp

<div class="flex-1 space-y-8 p-6 md:p-8">
    <section class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-700 dark:bg-slate-900">
        <div class="flex flex-col items-center gap-10 md:flex-row">
            <div class="flex min-w-[240px] flex-col items-center justify-center rounded-2xl border border-blue-100 bg-blue-50 p-6 dark:border-blue-800/30 dark:bg-blue-900/20">
                <span class="mb-1 text-xs font-bold uppercase tracking-widest text-[#0F4C81] dark:text-blue-400">Rata-rata Rating</span>
                <div class="flex items-baseline gap-1">
                    <span class="text-5xl font-bold text-slate-800 dark:text-white">{{ number_format($rataRataRating, 1) }}</span>
                    <span class="text-xl font-medium text-gray-400">/ 5.0</span>
                </div>
                <div class="my-3 flex text-yellow-400">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <=$fullAverageStars)
                        <span class="material-symbols-outlined fill-1 text-2xl">star</span>
                        @elseif ($i === $fullAverageStars + 1 && $hasHalfAverageStar)
                        <span class="material-symbols-outlined fill-1 text-2xl">star_half</span>
                        @else
                        <span class="material-symbols-outlined text-2xl">star</span>
                        @endif
                        @endfor
                </div>
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Berdasarkan {{ number_format($totalUlasan, 0, ',', '.') }} ulasan
                </span>
            </div>

            <div class="grid h-full w-full flex-1 grid-cols-1 gap-8 sm:grid-cols-3">
                <div class="flex flex-col justify-center rounded-xl border border-gray-100 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-slate-800/40">
                    <span class="mb-1 text-sm text-gray-500 dark:text-gray-400">Total Ulasan</span>
                    <span class="text-3xl font-bold text-slate-800 dark:text-white">{{ number_format($totalUlasan, 0, ',', '.') }}</span>
                    <div class="mt-2 flex items-center gap-1 text-xs font-medium text-green-600 dark:text-green-400">
                        <span class="material-symbols-outlined text-[14px]">rate_review</span>
                        <span>Semua ulasan properti Anda</span>
                    </div>
                </div>

                <div class="flex flex-col justify-center rounded-xl border border-gray-100 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-slate-800/40">
                    <span class="mb-1 text-sm text-gray-500 dark:text-gray-400">Belum Dibalas</span>
                    <span class="text-3xl font-bold text-red-600 dark:text-red-400">{{ number_format($belumDibalas, 0, ',', '.') }}</span>
                    <div class="mt-2 flex items-center gap-1 text-xs font-medium text-red-600 dark:text-red-400">
                        <span class="material-symbols-outlined text-[14px]">priority_high</span>
                        <span>Butuh respon segera</span>
                    </div>
                </div>

                <div class="flex flex-col justify-center rounded-xl border border-gray-100 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-slate-800/40">
                    <span class="mb-1 text-sm text-gray-500 dark:text-gray-400">Kepuasan Penyewa</span>
                    <span class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $kepuasanPenyewa }}%</span>
                    <div class="mt-3 h-2 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                        <div class="h-full bg-green-600 dark:bg-green-400" @style(['width' => $kepuasanPenyewa . '%'])></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
        <h3 class="text-xl font-bold text-slate-800 dark:text-white">Semua Ulasan</h3>
        <div class="flex w-full gap-3 sm:w-auto">
            <div class="relative flex-1 sm:w-64">
                <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400 dark:text-slate-500">
                    <span class="material-symbols-outlined text-[18px]">search</span>
                </span>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 pl-12 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                    placeholder="Cari ulasan atau properti...">
            </div>

            <div x-data="{ open: false }" class="relative sm:w-auto">
                <button
                    type="button"
                    @click="open = ! open"
                    @click.outside="open = false"
                    class="flex h-11 min-w-[190px] items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-[#0F4C81] hover:text-[#0F4C81] dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-blue-400 dark:hover:text-blue-300">
                    <span class="flex min-w-0 items-center gap-2">
                        <span class="material-symbols-outlined shrink-0 text-[18px]">filter_list</span>
                        <span class="truncate">{{ $activeFilterLabel }}</span>
                    </span>
                    <span class="material-symbols-outlined shrink-0 text-[18px] text-slate-400 dark:text-slate-500">expand_more</span>
                </button>

                <div
                    x-cloak
                    x-show="open"
                    x-transition.origin.top.right
                    class="absolute right-0 z-20 mt-2 w-[220px] overflow-hidden rounded-2xl border border-slate-200 bg-white p-1 shadow-xl dark:border-slate-700 dark:bg-slate-800">
                    @foreach ($filterOptions as $option)
                    <button
                        type="button"
                        wire:click="$set('filter', '{{ $option }}')"
                        @click="open = false"
                        @class([ 'flex w-full items-center justify-between rounded-xl px-4 py-3 text-left text-sm transition' , 'bg-blue-50 font-semibold text-[#0F4C81] dark:bg-blue-500/10 dark:text-blue-300'=> $filter === $option,
                        'text-slate-600 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-700/70' => $filter !== $option,
                        ])
                        >
                        <span>{{ $option }}</span>
                        @if ($filter === $option)
                        <span class="material-symbols-outlined text-[18px]">check</span>
                        @endif
                    </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div wire:loading.remove wire:target="search, filter, gotoPage, nextPage, previousPage" class="flex flex-col gap-6">
        @forelse ($ulasanList as $ulasan)
        @php
        $penyewa = $ulasan->booking?->pencariKos?->user;
        @endphp

        <div
            wire:key="ulasan-{{ $ulasan->id }}"
            class="group flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition-all hover:border-[#0F4C81]/30 hover:shadow-md dark:border-gray-700 dark:bg-slate-900">
            <div class="flex-1 p-6">
                <div class="mb-4 flex items-start justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full border text-lg font-bold {{ $this->getAvatarClasses($ulasan) }}">
                            {{ $this->getPenyewaInitials($ulasan) }}
                        </div>
                        <div class="flex flex-col">
                            <h4 class="text-base font-bold leading-tight text-slate-800 dark:text-white">
                                {{ $ulasan->is_anonymous ? 'Anonim' : ($penyewa?->name ?? 'Penyewa') }}
                            </h4>
                            <div class="mt-0.5 flex origin-left scale-75 text-yellow-400">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <=$ulasan->rating)
                                    <span class="material-symbols-outlined fill-1">star</span>
                                    @else
                                    <span class="material-symbols-outlined">star</span>
                                    @endif
                                    @endfor
                            </div>
                        </div>
                    </div>

                    <span class="rounded bg-gray-100 px-2 py-1 text-[11px] font-medium text-gray-400 dark:bg-gray-800">
                        {{ $ulasan->created_at?->locale('id')->translatedFormat('d M Y') ?? '-' }}
                    </span>
                </div>

                <div class="mb-3">
                    <div class="flex items-center gap-1.5 text-xs font-semibold text-[#0F4C81] dark:text-blue-300">
                        <span class="material-symbols-outlined text-[16px]">{{ $this->getIkonProperti($ulasan) }}</span>
                        {{ $this->getNamaProperti($ulasan) }}
                    </div>
                </div>

                <p class="mb-4 text-sm leading-relaxed text-gray-600 italic dark:text-gray-400">
                    "{{ $ulasan->komentar }}"
                </p>

                @if ($ulasan->balasan_pemilik !== null)
                <div class="mt-2 rounded-xl border-l-4 border-[#0F4C81] bg-blue-50 p-4 dark:bg-blue-900/20">
                    <div class="mb-2 flex items-center justify-between">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#0F4C81] dark:text-blue-400">
                            Tanggapan Anda:
                        </span>
                    </div>
                    <p class="text-sm text-gray-700 dark:text-gray-300">"{{ $ulasan->balasan_pemilik }}"</p>
                </div>
                @elseif ($replyingTo === $ulasan->id)
                <div class="mt-4 rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-slate-800/60">
                    <label for="balasan-{{ $ulasan->id }}" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        Tulis Balasan
                    </label>
                    <textarea
                        id="balasan-{{ $ulasan->id }}"
                        wire:model="balasanText"
                        rows="4"
                        class="w-full rounded-xl border border-gray-200 bg-white text-sm text-slate-700 focus:border-[#0F4C81] focus:ring-[#0F4C81] dark:border-gray-700 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-400"
                        placeholder="Tulis tanggapan Anda untuk ulasan ini..."></textarea>
                    @error('balasanText')
                    <p class="mt-2 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                @endif
            </div>

            <div class="flex justify-end border-t border-gray-100 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-slate-800/50">
                @if ($ulasan->balasan_pemilik !== null)
                <span class="inline-flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 px-4 py-2 text-sm font-semibold text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-300">
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    Sudah Dibalas
                </span>
                @elseif ($replyingTo === $ulasan->id)
                <button
                    type="button"
                    wire:click="simpanBalasan"
                    wire:loading.attr="disabled"
                    wire:target="simpanBalasan"
                    class="flex items-center gap-2 rounded-lg bg-[#0F4C81] px-5 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-[#0c3c66] active:scale-95 disabled:cursor-not-allowed disabled:opacity-60">
                    <span class="material-symbols-outlined text-[18px]">send</span>
                    Kirim Balasan
                </button>
                @else
                <button
                    type="button"
                    wire:click="bukaFormBalas({{ $ulasan->id }})"
                    wire:loading.attr="disabled"
                    wire:target="bukaFormBalas"
                    class="flex items-center gap-2 rounded-lg bg-[#0F4C81] px-5 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-[#0c3c66] active:scale-95 disabled:cursor-not-allowed disabled:opacity-60">
                    <span class="material-symbols-outlined text-[18px]">reply</span>
                    Balas Ulasan
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="rounded-2xl border border-dashed border-gray-300 bg-white px-6 py-14 text-center text-sm text-gray-500 dark:border-gray-700 dark:bg-slate-900 dark:text-slate-400">
            Belum ada ulasan yang sesuai dengan pencarian atau filter saat ini.
        </div>
        @endforelse
    </div>

    {{-- Skeleton Ulasan --}}
    <div wire:loading.flex wire:target="search, filter, gotoPage, nextPage, previousPage" class="flex-col gap-6 w-full">
        @for ($i = 0; $i < 3; $i++)
        <div class="group flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-slate-900 animate-pulse">
            <div class="flex-1 p-6">
                <div class="mb-4 flex items-start justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-full bg-slate-200 dark:bg-slate-700"></div>
                        <div class="flex flex-col gap-2">
                            <div class="h-4 w-32 rounded bg-slate-200 dark:bg-slate-700"></div>
                            <div class="h-3 w-24 rounded bg-slate-200 dark:bg-slate-700"></div>
                        </div>
                    </div>
                    <div class="h-6 w-20 rounded bg-slate-200 dark:bg-slate-700"></div>
                </div>
                <div class="mb-3 h-4 w-40 rounded bg-slate-200 dark:bg-slate-700"></div>
                <div class="mb-4 space-y-2">
                    <div class="h-3 w-full rounded bg-slate-200 dark:bg-slate-700"></div>
                    <div class="h-3 w-5/6 rounded bg-slate-200 dark:bg-slate-700"></div>
                </div>
            </div>
            <div class="flex justify-end border-t border-gray-100 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-slate-800/50">
                <div class="h-10 w-32 rounded-lg bg-slate-200 dark:bg-slate-700"></div>
            </div>
        </div>
        @endfor
    </div>

    <div class="flex justify-center pb-12 pt-4">
        {{ $ulasanList->links() }}
    </div>
</div>