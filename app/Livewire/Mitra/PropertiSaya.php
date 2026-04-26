<?php

declare(strict_types=1);

namespace App\Livewire\Mitra;

use App\Models\Kamar;
use App\Models\Kontrakan;
use App\Models\Kosan;
use App\Models\PemilikProperti;
use App\Models\TipeKamar;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class PropertiSaya extends Component
{
    public string $filterTab = 'semua';

    public function setFilter(string $tab): void
    {
        if (! in_array($tab, ['semua', 'kosan', 'kontrakan'], true)) {
            return;
        }

        $this->filterTab = $tab;
    }

    public function hapusKosan(int $kosanId): void
    {
        $kosan = $this->pemilikProperti()
            ->kosan()
            ->with(['tipeKamar.media', 'tipeKamar.kamar'])
            ->findOrFail($kosanId);

        foreach ($kosan->tipeKamar as $tipeKamar) {
            $tipeKamar->clearMediaCollection('foto_interior');

            foreach ($tipeKamar->kamar as $kamar) {
                $kamar->delete();
            }

            $tipeKamar->delete();
        }

        $kosan->clearMediaCollection('foto_properti');
        $kosan->delete();

        session()->flash('success', 'Kosan berhasil dihapus.');
    }

    public function hapusKontrakan(int $kontrakanId): void
    {
        $kontrakan = $this->pemilikProperti()
            ->kontrakan()
            ->findOrFail($kontrakanId);

        $kontrakan->clearMediaCollection('foto_properti');
        $kontrakan->delete();

        session()->flash('success', 'Kontrakan berhasil dihapus.');
    }

    #[Layout('layouts.mitra.utama')]
    public function render(): View
    {
        $pemilikProperti = $this->pemilikProperti();

        $kosan = $pemilikProperti->kosan()
            ->with(['media', 'tipeKamar.kamar.bookings'])
            ->latest()
            ->get();

        $kontrakan = $pemilikProperti->kontrakan()
            ->with(['media', 'bookings'])
            ->latest()
            ->get();

        $totalProperti = $kosan->count() + $kontrakan->count();

        return view('livewire.mitra.properti.daftar-properti', [
            'propertyCards' => $this->buildPropertyCards($kosan, $kontrakan),
            'kosanCount' => $kosan->count(),
            'kontrakanCount' => $kontrakan->count(),
            'totalProperti' => $totalProperti,
        ]);
    }

    /**
     * @param  Collection<int, Kosan>  $kosan
     * @param  Collection<int, Kontrakan>  $kontrakan
     * @return Collection<int, array<string, mixed>>
     */
    protected function buildPropertyCards(Collection $kosan, Collection $kontrakan): Collection
    {
        $kosanCards = $kosan->map(function (Kosan $item): array {
            /** @var Collection<int, Kamar> $rooms */
            $rooms = $item->tipeKamar->flatMap(static fn (TipeKamar $tipeKamar): Collection => $tipeKamar->kamar);

            $availableRooms = $rooms->where('status_kamar', 'tersedia')->count();
            $activeBookings = $rooms
                ->flatMap(static fn (Kamar $kamar): EloquentCollection => $kamar->bookings)
                ->whereIn('status_booking', ['pending', 'lunas'])
                ->count();

            return [
                'id' => $item->id,
                'type' => 'kosan',
                'label' => 'KOSAN',
                'category_label' => $this->getCategoryLabel('kosan', $item->jenis_kos),
                'category_badge_classes' => $this->getCategoryBadgeClasses('kosan', $item->jenis_kos),
                'status_label' => $availableRooms > 0 ? 'TERSEDIA' : 'PENUH',
                'status_badge_classes' => $availableRooms > 0
                    ? 'bg-emerald-500 text-white'
                    : 'bg-rose-500 text-white',
                'moderation_status' => (string) ($item->status ?? ''),
                'show_rejection_alert' => in_array($item->status, ['ditolak', 'suspend'], true),
                'rejection_title' => $item->status === 'suspend' ? 'Ditangguhkan' : 'Ditolak',
                'rejection_reason' => $item->alasan_penolakan ?: 'Melanggar syarat dan ketentuan platform.',
                'name' => $item->nama_properti,
                'price' => $item->tipeKamar->min('harga_per_bulan'),
                'price_label' => $item->harga_range !== null
                    ? str_replace(' / bulan', '', $item->harga_range)
                    : 'Harga belum diatur',
                'price_suffix' => $item->harga_range !== null ? '/ bulan' : '',
                'address' => $item->alamat_lengkap,
                'location_label' => $this->locationLabel($item->alamat_lengkap),
                'image_url' => $item->getMediaDisplayUrl('foto_properti'),
                'edit_url' => route('mitra.properti.tambah-kosan', ['edit' => $item->id]),
                'manage_url' => route('mitra.properti.kelola-kamar', ['kosan_id' => $item->id]),
                'delete_action' => 'hapusKosan',
                'delete_confirm' => 'Hapus kosan ini?',
                'stats' => [
                    [
                        'label' => 'TOTAL KAMAR',
                        'value' => $rooms->count().' Unit',
                        'icon' => 'bed',
                        'wrapper_classes' => 'bg-gray-50 border border-gray-100 dark:bg-slate-800 dark:border-slate-700',
                        'label_classes' => 'text-gray-400 dark:text-slate-400',
                        'value_classes' => 'text-gray-800 dark:text-slate-100',
                        'icon_classes' => 'text-gray-400 dark:text-slate-400',
                    ],
                    [
                        'label' => 'KAMAR TERSEDIA',
                        'value' => $availableRooms.' Unit',
                        'icon' => $availableRooms > 0 ? 'check_circle' : 'cancel',
                        'wrapper_classes' => $availableRooms > 0
                            ? 'bg-emerald-50 border border-emerald-100 dark:bg-emerald-950/20 dark:border-emerald-900/40'
                            : 'bg-rose-50 border border-rose-100 dark:bg-rose-950/20 dark:border-rose-900/40',
                        'label_classes' => $availableRooms > 0 ? 'text-emerald-600 dark:text-emerald-300' : 'text-rose-600 dark:text-rose-300',
                        'value_classes' => $availableRooms > 0 ? 'text-emerald-700 dark:text-emerald-200' : 'text-rose-700 dark:text-rose-200',
                        'icon_classes' => $availableRooms > 0 ? 'text-emerald-500 dark:text-emerald-300' : 'text-rose-500 dark:text-rose-300',
                    ],
                    [
                        'label' => 'PESANAN AKTIF',
                        'value' => $activeBookings.' Pesanan',
                        'icon' => 'shopping_cart',
                        'wrapper_classes' => 'bg-blue-50 border border-blue-100 dark:bg-blue-950/20 dark:border-blue-900/40',
                        'label_classes' => 'text-blue-600 dark:text-blue-300',
                        'value_classes' => 'text-blue-700 dark:text-blue-200',
                        'icon_classes' => 'text-blue-500 dark:text-blue-300',
                    ],
                ],
                'created_at' => $item->created_at,
            ];
        });

        $kontrakanCards = $kontrakan->map(function (Kontrakan $item): array {
            $activeBookings = $item->bookings
                ->whereIn('status_booking', ['pending', 'lunas'])
                ->count();

            $availableUnits = max(0, (int) $item->sisa_kamar);
            $totalUnits = max(1, $activeBookings + $availableUnits);

            return [
                'id' => $item->id,
                'type' => 'kontrakan',
                'label' => 'KONTRAKAN',
                'category_label' => $this->getCategoryLabel('kontrakan'),
                'category_badge_classes' => $this->getCategoryBadgeClasses('kontrakan'),
                'status_label' => $availableUnits > 0 ? 'TERSEDIA' : 'PENUH',
                'status_badge_classes' => $availableUnits > 0
                    ? 'bg-emerald-500 text-white'
                    : 'bg-rose-500 text-white',
                'moderation_status' => (string) ($item->status ?? ''),
                'show_rejection_alert' => in_array($item->status, ['ditolak', 'suspend'], true),
                'rejection_title' => $item->status === 'suspend' ? 'Ditangguhkan' : 'Ditolak',
                'rejection_reason' => $item->alasan_penolakan ?: 'Melanggar syarat dan ketentuan platform.',
                'name' => $item->nama_properti,
                'price' => $item->harga_sewa_tahun,
                'price_label' => 'Rp '.number_format($item->harga_sewa_tahun, 0, ',', '.'),
                'price_suffix' => '/ tahun',
                'address' => $item->alamat_lengkap,
                'location_label' => $this->locationLabel($item->alamat_lengkap),
                'image_url' => $item->getMediaDisplayUrl('foto_properti'),
                'edit_url' => route('mitra.properti.tambah-kontrakan', ['edit' => $item->id]),
                'manage_url' => route('mitra.properti.tambah-kontrakan', ['edit' => $item->id]),
                'delete_action' => 'hapusKontrakan',
                'delete_confirm' => 'Hapus kontrakan ini?',
                'stats' => [
                    [
                        'label' => 'TOTAL UNIT',
                        'value' => $totalUnits.' Rumah',
                        'icon' => 'home_work',
                        'wrapper_classes' => 'bg-gray-50 border border-gray-100 dark:bg-slate-800 dark:border-slate-700',
                        'label_classes' => 'text-gray-400 dark:text-slate-400',
                        'value_classes' => 'text-gray-800 dark:text-slate-100',
                        'icon_classes' => 'text-gray-400 dark:text-slate-400',
                    ],
                    [
                        'label' => 'UNIT TERSEDIA',
                        'value' => $availableUnits.' Unit',
                        'icon' => $availableUnits > 0 ? 'check_circle' : 'cancel',
                        'wrapper_classes' => $availableUnits > 0
                            ? 'bg-emerald-50 border border-emerald-100 dark:bg-emerald-950/20 dark:border-emerald-900/40'
                            : 'bg-rose-50 border border-rose-100 dark:bg-rose-950/20 dark:border-rose-900/40',
                        'label_classes' => $availableUnits > 0 ? 'text-emerald-600 dark:text-emerald-300' : 'text-rose-600 dark:text-rose-300',
                        'value_classes' => $availableUnits > 0 ? 'text-emerald-700 dark:text-emerald-200' : 'text-rose-700 dark:text-rose-200',
                        'icon_classes' => $availableUnits > 0 ? 'text-emerald-500 dark:text-emerald-300' : 'text-rose-500 dark:text-rose-300',
                    ],
                    [
                        'label' => 'PESANAN AKTIF',
                        'value' => $activeBookings.' Pesanan',
                        'icon' => 'shopping_cart',
                        'wrapper_classes' => 'bg-blue-50 border border-blue-100 dark:bg-blue-950/20 dark:border-blue-900/40',
                        'label_classes' => 'text-blue-600 dark:text-blue-300',
                        'value_classes' => 'text-blue-700 dark:text-blue-200',
                        'icon_classes' => 'text-blue-500 dark:text-blue-300',
                    ],
                ],
                'created_at' => $item->created_at,
            ];
        });

        return match ($this->filterTab) {
            'kosan' => $kosanCards->values(),
            'kontrakan' => $kontrakanCards->values(),
            default => $kosanCards->concat($kontrakanCards)->sortByDesc('created_at')->values(),
        };
    }

    protected function pemilikProperti(): PemilikProperti
    {
        /** @var User|null $user */
        $user = Auth::user();
        $pemilikProperti = $user?->pemilikProperti()->first();

        if ($pemilikProperti === null) {
            abort(403, 'Unauthorized action.');
        }

        return $pemilikProperti;
    }

    protected function locationLabel(string $alamatLengkap): string
    {
        $segments = collect(explode(',', $alamatLengkap))
            ->map(static fn (string $segment): string => trim($segment))
            ->filter()
            ->take(2)
            ->values();

        if ($segments->isEmpty()) {
            return 'Lokasi belum diatur';
        }

        return $segments->implode(', ');
    }

    protected function getCategoryLabel(string $type, ?string $jenisKos = null): string
    {
        if ($type === 'kontrakan') {
            return 'Kontrakan';
        }

        return match ($jenisKos) {
            'putra' => 'Kos Putra',
            'putri' => 'Kos Putri',
            'campur' => 'Kos Campur',
            default => 'Kos',
        };
    }

    protected function getCategoryBadgeClasses(string $type, ?string $jenisKos = null): string
    {
        if ($type === 'kontrakan') {
            return 'bg-teal-100 text-teal-700 border border-teal-200 dark:bg-teal-950/30 dark:text-teal-300 dark:border-teal-900/40';
        }

        return match ($jenisKos) {
            'putra' => 'bg-blue-100 text-blue-700 border border-blue-200 dark:bg-blue-950/30 dark:text-blue-300 dark:border-blue-900/40',
            'putri' => 'bg-pink-100 text-pink-700 border border-pink-200 dark:bg-pink-950/30 dark:text-pink-300 dark:border-pink-900/40',
            'campur' => 'bg-purple-100 text-purple-700 border border-purple-200 dark:bg-purple-950/30 dark:text-purple-300 dark:border-purple-900/40',
            default => 'bg-slate-100 text-slate-700 border border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
        };
    }
}
