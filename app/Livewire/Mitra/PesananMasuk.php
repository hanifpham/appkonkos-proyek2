<?php

declare(strict_types=1);

namespace App\Livewire\Mitra;

use App\Models\Booking;
use App\Models\Kontrakan;
use App\Models\PemilikProperti;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class PesananMasuk extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public string $search = '';

    public string $statusFilter = 'all';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    #[Layout('layouts.mitra.utama')]
    public function render(): View
    {
        $ownedBookingsQuery = $this->ownedBookingsQuery();

        $statMenunggu = (clone $ownedBookingsQuery)
            ->where('status_booking', 'pending')
            ->count();

        $statDikonfirmasi = (clone $ownedBookingsQuery)
            ->whereIn('status_booking', ['lunas', 'selesai'])
            ->count();

        $statTotal = (clone $ownedBookingsQuery)
            ->whereYear('created_at', (int) now()->format('Y'))
            ->whereMonth('created_at', (int) now()->format('m'))
            ->count();

        $pesananQuery = (clone $ownedBookingsQuery)
            ->with([
                'pencariKos.user',
                'kamar.tipeKamar.kosan.media',
                'kontrakan.media',
                'pembayaran',
            ]);

        $search = trim($this->search);

        if ($search !== '') {
            $pesananQuery->where(function (Builder $query) use ($search): void {
                $query
                    ->where('id', 'like', '%'.$search.'%')
                    ->orWhereHas('pencariKos.user', function (Builder $userQuery) use ($search): void {
                        $userQuery
                            ->where('name', 'like', '%'.$search.'%')
                            ->orWhere('email', 'like', '%'.$search.'%');
                    });
            });
        }

        if ($this->statusFilter !== 'all') {
            match ($this->statusFilter) {
                'pending' => $pesananQuery->where('status_booking', 'pending'),
                'confirmed' => $pesananQuery->whereIn('status_booking', ['lunas', 'selesai']),
                'cancelled' => $pesananQuery->where('status_booking', 'batal'),
                default => null,
            };
        }

        $pesanan = $pesananQuery
            ->latest()
            ->paginate(10);

        return view('livewire.mitra.pesanan-masuk', [
            'pesanan' => $pesanan,
            'statMenunggu' => $statMenunggu,
            'statDikonfirmasi' => $statDikonfirmasi,
            'statTotal' => $statTotal,
            'filterOptions' => $this->filterOptions(),
        ]);
    }

    public function konfirmasiPesanan(string $id): void
    {
        $booking = $this->ownedBooking($id);

        if ($booking->status_booking !== 'pending') {
            $this->dispatch(
                'appkonkos-toast',
                icon: 'info',
                title: 'Tidak ada aksi',
                text: 'Pesanan ini sudah diproses sebelumnya.'
            );

            return;
        }

        if (! ($booking->pembayaran?->isSuccessful() ?? false)) {
            $this->dispatch(
                'appkonkos-toast',
                icon: 'warning',
                title: 'Belum dapat dikonfirmasi',
                text: 'Pembayaran penyewa belum lunas.'
            );

            return;
        }

        $booking->update(['status_booking' => 'lunas']);

        if ($booking->kamar !== null) {
            $booking->kamar->update(['status_kamar' => 'dihuni']);
        }

        $this->dispatch(
            'appkonkos-toast',
            icon: 'success',
            title: 'Pesanan dikonfirmasi',
            text: 'Status booking berhasil diperbarui menjadi lunas.'
        );
    }

    public function tolakPesanan(string $id): void
    {
        $booking = $this->ownedBooking($id);

        if ($booking->status_booking !== 'pending') {
            $this->dispatch(
                'appkonkos-toast',
                icon: 'info',
                title: 'Tidak ada aksi',
                text: 'Pesanan ini sudah diproses sebelumnya.'
            );

            return;
        }

        $booking->update(['status_booking' => 'batal']);

        if ($booking->kamar !== null) {
            $booking->kamar->update(['status_kamar' => 'tersedia']);
        }

        $this->dispatch(
            'appkonkos-toast',
            icon: 'warning',
            title: 'Pesanan ditolak',
            text: 'Booking telah dibatalkan.'
        );
    }

    public function getBookingDisplayId(Booking $booking): string
    {
        return '#'.strtoupper(substr($booking->id, 0, 8));
    }

    public function getPropertyImageUrl(Booking $booking): string
    {
        if ($booking->kamar !== null) {
            return $booking->kamar->tipeKamar?->kosan?->getMediaDisplayUrl('foto_properti') ?? '';
        }

        return $booking->kontrakan?->getMediaDisplayUrl('foto_properti') ?? '';
    }

    public function getPropertyName(Booking $booking): string
    {
        if ($booking->kamar !== null) {
            return $booking->kamar->tipeKamar?->kosan?->nama_properti ?? 'Kosan';
        }

        return $booking->kontrakan?->nama_properti ?? 'Kontrakan';
    }

    public function getPropertyUnitLabel(Booking $booking): string
    {
        if ($booking->kamar !== null) {
            return 'Kamar '.($booking->kamar->nomor_kamar ?? '-');
        }

        if ($booking->kontrakan instanceof Kontrakan) {
            return 'Unit Utama';
        }

        return '-';
    }

    public function getStatusBayarLabel(Booking $booking): string
    {
        return match ($booking->pembayaran?->status_bayar) {
            'settlement', 'capture', 'lunas' => 'LUNAS',
            'deny', 'cancel', 'expire', 'failure', 'gagal' => 'GAGAL',
            default => 'MENUNGGU',
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

    public function getBookingActionMessage(Booking $booking): string
    {
        if ($booking->status_booking === 'batal') {
            return 'Pesanan telah ditolak';
        }

        if (in_array($booking->status_booking, ['lunas', 'selesai'], true)) {
            return 'Pesanan telah dikonfirmasi';
        }

        return 'Menunggu pembayaran';
    }

    public function getActiveFilterLabel(): string
    {
        return $this->filterOptions()[$this->statusFilter] ?? 'Semua';
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
            ->where(function (Builder $query): void {
                $pemilikId = $this->pemilikProperti()->id;

                $query
                    ->whereHas('kontrakan', function (Builder $kontrakanQuery) use ($pemilikId): void {
                        $kontrakanQuery->where('pemilik_properti_id', $pemilikId);
                    })
                    ->orWhereHas('kamar.tipeKamar.kosan', function (Builder $kosanQuery) use ($pemilikId): void {
                        $kosanQuery->where('pemilik_properti_id', $pemilikId);
                    });
            });
    }

    protected function ownedBooking(string $id): Booking
    {
        return $this->ownedBookingsQuery()
            ->with(['pembayaran', 'kamar', 'kontrakan'])
            ->findOrFail($id);
    }

    /**
     * @return array<string, string>
     */
    protected function filterOptions(): array
    {
        return [
            'all' => 'Semua',
            'pending' => 'Menunggu',
            'confirmed' => 'Dikonfirmasi',
            'cancelled' => 'Ditolak',
        ];
    }
}
