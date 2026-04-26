<?php

declare(strict_types=1);

namespace App\Livewire\Mitra;

use App\Models\Booking;
use App\Models\Kontrakan;
use App\Models\Kamar;
use App\Models\Kosan;
use App\Models\Pembayaran;
use App\Models\PemilikProperti;
use App\Models\TipeKamar;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboard extends Component
{
    public int $saldo = 0;

    public int $totalProperti = 0;

    public int $totalKamar = 0;

    public int $sisaKamar = 0;

    public int $pesananMenunggu = 0;

    /**
     * @var Collection<int, Booking>
     */
    public Collection $pesananTerbaru;

    /**
     * @var list<array<string, mixed>>
     */
    public array $propertiSaya = [];

    public string $filterProperti = 'semua';

    public function mount(): void
    {
        $this->pesananTerbaru = collect();

        $this->refreshDashboard();
    }

    public function setFilterProperti(string $filter): void
    {
        if (! in_array($filter, ['semua', 'kosan', 'kontrakan'], true)) {
            return;
        }

        $this->filterProperti = $filter;

        $this->refreshDashboard();
    }

    public function konfirmasiBooking(string $bookingId): void
    {
        $booking = $this->ownedBookingsQuery()
            ->with('pembayaran')
            ->findOrFail($bookingId);

        if (! ($booking->pembayaran?->isSuccessful() ?? false)) {
            session()->flash('dashboard_error', 'Booking belum bisa dikonfirmasi karena pembayaran belum lunas.');

            return;
        }

        $booking->update(['status_booking' => 'lunas']);

        if ($booking->kamar !== null) {
            $booking->kamar->update(['status_kamar' => 'dihuni']);
        }

        session()->flash('dashboard_success', 'Booking berhasil dikonfirmasi.');

        $this->refreshDashboard();
    }

    public function tolakBooking(string $bookingId): void
    {
        $booking = $this->ownedBookingsQuery()->findOrFail($bookingId);

        $booking->update(['status_booking' => 'batal']);

        if ($booking->kamar !== null) {
            $booking->kamar->update(['status_kamar' => 'tersedia']);
        }

        session()->flash('dashboard_success', 'Booking berhasil ditolak.');

        $this->refreshDashboard();
    }

    #[Layout('layouts.mitra.utama')]
    public function render(): View
    {
        return view('livewire.mitra.dashboard-utama');
    }

    public function getPenyewaInitials(Booking $booking): string
    {
        $name = $booking->pencariKos?->user?->name ?? 'U';

        return collect(explode(' ', trim($name)))
            ->filter()
            ->take(2)
            ->map(static fn (string $part): string => strtoupper(substr($part, 0, 1)))
            ->implode('');
    }

    public function getPenyewaInitialsClasses(Booking $booking): string
    {
        $palette = [
            'bg-purple-100 text-purple-600 dark:bg-purple-900/50 dark:text-purple-300',
            'bg-blue-100 text-blue-600 dark:bg-blue-900/50 dark:text-blue-300',
            'bg-pink-100 text-pink-600 dark:bg-pink-900/50 dark:text-pink-300',
            'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/50 dark:text-emerald-300',
        ];

        $index = crc32((string) $booking->id) % count($palette);

        return $palette[$index];
    }

    public function getStatusBayarLabel(Booking $booking): string
    {
        return match ($booking->pembayaran?->status_bayar) {
            'settlement', 'capture', 'lunas' => 'Lunas',
            'deny', 'cancel', 'expire', 'failure', 'gagal' => 'Gagal',
            default => 'Menunggu',
        };
    }

    public function getStatusBayarClasses(Booking $booking): string
    {
        return match ($booking->pembayaran?->status_bayar) {
            'settlement', 'capture', 'lunas' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800',
            'deny', 'cancel', 'expire', 'failure', 'gagal' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800',
            default => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800',
        };
    }

    public function canProcessBooking(Booking $booking): bool
    {
        return $booking->status_booking === 'pending'
            && ($booking->pembayaran?->isSuccessful() ?? false);
    }

    public function getBookingPropertyLabel(Booking $booking): string
    {
        if ($booking->kamar !== null) {
            $namaKosan = $booking->kamar->tipeKamar?->kosan?->nama_properti ?? 'Kosan';

            return $namaKosan.' (Kamar '.$booking->kamar->nomor_kamar.')';
        }

        return $booking->kontrakan?->nama_properti ?? 'Kontrakan';
    }

    public function getPropertyBadgeLabel(Kosan|Kontrakan $properti): string
    {
        if ($properti instanceof Kontrakan) {
            return 'Rumah';
        }

        return 'Kosan';
    }

    public function getPropertyBadgeClasses(Kosan|Kontrakan $properti): string
    {
        if ($properti instanceof Kontrakan) {
            return 'bg-purple-50 text-purple-600 dark:bg-purple-900/30 dark:text-purple-300 border border-purple-100 dark:border-purple-800';
        }

        return 'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-100 dark:border-blue-800';
    }

    public function getPropertyAvailabilityLabel(Kosan|Kontrakan $properti): string
    {
        if ($properti instanceof Kontrakan) {
            return $properti->sisa_kamar > 0 ? 'Tersedia' : 'Penuh';
        }

        $tersedia = $properti->tipeKamar
            ->flatMap(static fn (TipeKamar $tipeKamar): Collection => $tipeKamar->kamar)
            ->where('status_kamar', 'tersedia')
            ->count();

        return $tersedia > 0 ? 'Tersedia '.$tersedia.' Kamar' : 'Penuh';
    }

    public function getPropertyAvailabilityClasses(Kosan|Kontrakan $properti): string
    {
        $isAvailable = $properti instanceof Kontrakan
            ? $properti->sisa_kamar > 0
            : $properti->tipeKamar
                ->flatMap(static fn (TipeKamar $tipeKamar): Collection => $tipeKamar->kamar)
                ->where('status_kamar', 'tersedia')
                ->isNotEmpty();

        return $isAvailable
            ? 'bg-green-50 text-green-600 dark:bg-green-900/30 dark:text-green-300 border border-green-100 dark:border-green-800'
            : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600';
    }

    public function getPropertyPriceLabel(Kosan|Kontrakan $properti): string
    {
        if ($properti instanceof Kontrakan) {
            return 'Rp '.number_format($properti->harga_sewa_tahun, 0, ',', '.');
        }

        return str_replace(' / bulan', '', $properti->harga_range ?? 'Harga belum diatur');
    }

    public function getPropertyPriceSuffix(Kosan|Kontrakan $properti): string
    {
        if ($properti instanceof Kontrakan) {
            return '/ tahun';
        }

        return $properti->harga_range !== null ? '/ bulan' : '';
    }

    public function getPropertyImageUrl(Kosan|Kontrakan $properti): string
    {
        return $properti->getMediaDisplayUrl('foto_properti');
    }

    public function getPropertyEditUrl(Kosan|Kontrakan $properti): string
    {
        if ($properti instanceof Kontrakan) {
            return route('mitra.properti.tambah-kontrakan', ['edit' => $properti->id]);
        }

        return route('mitra.properti.tambah-kosan', ['edit' => $properti->id]);
    }

    public function getPropertyDetailUrl(Kosan|Kontrakan $properti): string
    {
        if ($properti instanceof Kontrakan) {
            return route('mitra.properti.tambah-kontrakan', ['edit' => $properti->id]);
        }

        return route('mitra.properti.kelola-kamar', ['kosan_id' => $properti->id]);
    }

    public function getPropertyDisplayId(Kosan|Kontrakan $properti): string
    {
        $prefix = $properti instanceof Kontrakan ? 'KTR' : 'KOS';

        return $prefix.'-'.str_pad((string) $properti->id, 4, '0', STR_PAD_LEFT);
    }

    public function getPropertyLocationLabel(Kosan|Kontrakan $properti): string
    {
        $segments = collect(explode(',', (string) $properti->alamat_lengkap))
            ->map(static fn (string $segment): string => trim($segment))
            ->filter()
            ->take(2)
            ->values();

        if ($segments->isEmpty()) {
            return 'Lokasi belum diatur';
        }

        return $segments->implode(', ');
    }

    protected function refreshDashboard(): void
    {
        $pemilikId = $this->pemilikProperti()->id;

        $this->saldo = (int) Pembayaran::query()
            ->where('status_bayar', 'lunas')
            ->whereHas('booking', fn (Builder $query): Builder => $this->applyOwnedBookingConstraint($query, $pemilikId))
            ->sum('nominal_bayar');

        $jumlahKosan = Kosan::query()
            ->where('pemilik_properti_id', $pemilikId)
            ->count();

        $jumlahKontrakan = Kontrakan::query()
            ->where('pemilik_properti_id', $pemilikId)
            ->count();

        $this->totalProperti = $jumlahKosan + $jumlahKontrakan;

        $this->totalKamar = Kamar::query()
            ->whereHas('tipeKamar.kosan', function (Builder $query) use ($pemilikId): void {
                $query->where('pemilik_properti_id', $pemilikId);
            })
            ->count();

        $this->sisaKamar = Kamar::query()
            ->where('status_kamar', 'tersedia')
            ->whereHas('tipeKamar.kosan', function (Builder $query) use ($pemilikId): void {
                $query->where('pemilik_properti_id', $pemilikId);
            })
            ->count();

        $this->pesananMenunggu = $this->ownedBookingsQuery()
            ->where('status_booking', 'pending')
            ->count();

        $this->pesananTerbaru = $this->ownedBookingsQuery()
            ->with([
                'pembayaran',
                'pencariKos.user',
                'kontrakan',
                'kamar.tipeKamar.kosan',
            ])
            ->latest()
            ->limit(5)
            ->get();

        $kosan = Kosan::query()
            ->where('pemilik_properti_id', $pemilikId)
            ->with(['media', 'tipeKamar.kamar'])
            ->latest()
            ->get();

        $kontrakan = Kontrakan::query()
            ->where('pemilik_properti_id', $pemilikId)
            ->with('media')
            ->latest()
            ->get();

        $kosanCards = $kosan->map(function (Kosan $properti): array {
            return [
                'id' => $properti->id,
                'type' => 'kosan',
                'display_id' => $this->getPropertyDisplayId($properti),
                'nama_properti' => $properti->nama_properti,
                'alamat_lengkap' => $properti->alamat_lengkap,
                'lokasi_label' => $this->getPropertyLocationLabel($properti),
                'image_url' => $this->getPropertyImageUrl($properti),
                'badge_label' => $this->getPropertyBadgeLabel($properti),
                'badge_classes' => $this->getPropertyBadgeClasses($properti),
                'availability_label' => $this->getPropertyAvailabilityLabel($properti),
                'availability_classes' => $this->getPropertyAvailabilityClasses($properti),
                'price_label' => $this->getPropertyPriceLabel($properti),
                'price_suffix' => $this->getPropertyPriceSuffix($properti),
                'edit_url' => $this->getPropertyEditUrl($properti),
                'detail_url' => $this->getPropertyDetailUrl($properti),
                'created_at_ts' => $properti->created_at?->timestamp ?? 0,
                'harga_range' => $properti->harga_range,
            ];
        });

        $kontrakanCards = $kontrakan->map(function (Kontrakan $properti): array {
            return [
                'id' => $properti->id,
                'type' => 'kontrakan',
                'display_id' => $this->getPropertyDisplayId($properti),
                'nama_properti' => $properti->nama_properti,
                'alamat_lengkap' => $properti->alamat_lengkap,
                'lokasi_label' => $this->getPropertyLocationLabel($properti),
                'image_url' => $this->getPropertyImageUrl($properti),
                'badge_label' => $this->getPropertyBadgeLabel($properti),
                'badge_classes' => $this->getPropertyBadgeClasses($properti),
                'availability_label' => $this->getPropertyAvailabilityLabel($properti),
                'availability_classes' => $this->getPropertyAvailabilityClasses($properti),
                'price_label' => $this->getPropertyPriceLabel($properti),
                'price_suffix' => $this->getPropertyPriceSuffix($properti),
                'edit_url' => $this->getPropertyEditUrl($properti),
                'detail_url' => $this->getPropertyDetailUrl($properti),
                'created_at_ts' => $properti->created_at?->timestamp ?? 0,
                'harga_range' => null,
            ];
        });

        $this->propertiSaya = (match ($this->filterProperti) {
            'kosan' => $kosanCards->sortByDesc('created_at_ts'),
            'kontrakan' => $kontrakanCards->sortByDesc('created_at_ts'),
            default => $kosanCards->concat($kontrakanCards)->sortByDesc('created_at_ts'),
        })
            ->take(4)
            ->values()
            ->all();
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

    protected function ownedBookingsQuery(): Builder
    {
        return Booking::query()
            ->where(fn (Builder $query): Builder => $this->applyOwnedBookingConstraint($query, $this->pemilikProperti()->id));
    }

    protected function applyOwnedBookingConstraint(Builder $query, int $pemilikId): Builder
    {
        return $query->where(function (Builder $nestedQuery) use ($pemilikId): void {
            $nestedQuery
                ->whereHas('kontrakan', function (Builder $kontrakanQuery) use ($pemilikId): void {
                    $kontrakanQuery->where('pemilik_properti_id', $pemilikId);
                })
                ->orWhereHas('kamar.tipeKamar.kosan', function (Builder $kosanQuery) use ($pemilikId): void {
                    $kosanQuery->where('pemilik_properti_id', $pemilikId);
                });
        });
    }
}
