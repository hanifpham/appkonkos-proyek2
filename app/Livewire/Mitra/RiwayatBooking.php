<?php

declare(strict_types=1);

namespace App\Livewire\Mitra;

use App\Models\Booking;
use App\Models\Kontrakan;
use App\Models\Pembayaran;
use App\Models\PemilikProperti;
use App\Models\Refund;
use App\Models\User;
use App\Services\MidtransService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Midtrans\Config as MidtransConfig;
use Midtrans\Transaction;
use Throwable;

class RiwayatBooking extends Component
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

        $statSettlement = (clone $ownedBookingsQuery)
            ->whereHas('pembayaran', fn (Builder $query): Builder => $query->whereIn('status_bayar', Pembayaran::successStatuses()))
            ->count();

        $statPending = (clone $ownedBookingsQuery)
            ->whereHas('pembayaran', fn (Builder $query): Builder => $query->whereIn('status_bayar', Pembayaran::pendingStatuses()))
            ->count();

        $statRefunded = (clone $ownedBookingsQuery)
            ->where(function (Builder $query): void {
                $query
                    ->whereHas('pembayaran', fn (Builder $paymentQuery): Builder => $paymentQuery->whereIn('status_bayar', Pembayaran::REFUND_STATUSES))
                    ->orWhereHas('refund', fn (Builder $refundQuery): Builder => $refundQuery->where('status_refund', 'selesai'));
            })
            ->count();

        $bookingsQuery = (clone $ownedBookingsQuery)
            ->with([
                'pencariKos.user',
                'kamar.tipeKamar.kosan.media',
                'kontrakan.media',
                'pembayaran',
                'refund',
            ]);

        $search = trim($this->search);

        if ($search !== '') {
            $bookingsQuery->where(function (Builder $query) use ($search): void {
                $query
                    ->where('id', 'like', '%'.$search.'%')
                    ->orWhereHas('pencariKos.user', function (Builder $userQuery) use ($search): void {
                        $userQuery
                            ->where('name', 'like', '%'.$search.'%')
                            ->orWhere('email', 'like', '%'.$search.'%');
                    })
                    ->orWhereHas('kontrakan', function (Builder $propertyQuery) use ($search): void {
                        $propertyQuery->where('nama_properti', 'like', '%'.$search.'%');
                    })
                    ->orWhereHas('kamar.tipeKamar.kosan', function (Builder $propertyQuery) use ($search): void {
                        $propertyQuery->where('nama_properti', 'like', '%'.$search.'%');
                    });
            });
        }

        if ($this->statusFilter !== 'all') {
            match ($this->statusFilter) {
                'settlement' => $bookingsQuery->whereHas(
                    'pembayaran',
                    fn (Builder $query): Builder => $query->whereIn('status_bayar', Pembayaran::successStatuses())
                ),
                'pending' => $bookingsQuery->whereHas(
                    'pembayaran',
                    fn (Builder $query): Builder => $query->whereIn('status_bayar', Pembayaran::pendingStatuses())
                ),
                'refunded' => $bookingsQuery->where(function (Builder $query): void {
                    $query
                        ->whereHas('pembayaran', fn (Builder $paymentQuery): Builder => $paymentQuery->whereIn('status_bayar', Pembayaran::REFUND_STATUSES))
                        ->orWhereHas('refund', fn (Builder $refundQuery): Builder => $refundQuery->where('status_refund', 'selesai'));
                }),
                default => null,
            };
        }

        $bookings = $bookingsQuery
            ->latest()
            ->paginate(10);

        return view('livewire.mitra.riwayat-booking', [
            'bookings' => $bookings,
            'statSettlement' => $statSettlement,
            'statPending' => $statPending,
            'statRefunded' => $statRefunded,
            'filterOptions' => $this->filterOptions(),
        ]);
    }

    public function syncMidtrans(string $bookingId, MidtransService $midtransService): void
    {
        $this->configureMidtrans();

        $booking = $this->ownedBooking($bookingId);
        $payment = $booking->pembayaran;

        if ($payment === null || blank($payment->midtrans_order_id)) {
            $this->dispatch(
                'appkonkos-toast',
                icon: 'warning',
                title: 'Order Midtrans belum tersedia',
                text: 'Transaksi ini belum memiliki referensi order Midtrans untuk diverifikasi.'
            );

            return;
        }

        try {
            $status = Transaction::status((string) $payment->midtrans_order_id);

            $midtransService->syncPaymentStatus(
                $payment,
                json_decode(json_encode($status), true, 512, JSON_THROW_ON_ERROR)
            );

            $this->dispatch(
                'appkonkos-toast',
                icon: 'success',
                title: 'Status tersinkronisasi',
                text: 'Status pembayaran berhasil diperbarui dari Midtrans Sandbox.'
            );
        } catch (Throwable $exception) {
            report($exception);

            $this->dispatch(
                'appkonkos-toast',
                icon: 'error',
                title: 'Sinkronisasi gagal',
                text: 'Status pembayaran tidak dapat diverifikasi ke Midtrans saat ini.'
            );
        }
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

    public function getStatusLabel(Booking $booking): string
    {
        $payment = $booking->pembayaran;

        if ($payment?->normalizedStatus() === 'refund' || $this->isRefunded($booking)) {
            return 'REFUNDED';
        }

        return match ($payment?->normalizedStatus()) {
            'lunas' => 'SETTLEMENT',
            'gagal' => 'GAGAL',
            default => 'PENDING',
        };
    }

    public function getStatusClasses(Booking $booking): string
    {
        $payment = $booking->pembayaran;

        if ($payment?->normalizedStatus() === 'refund' || $this->isRefunded($booking)) {
            return 'bg-rose-100 text-rose-700 border border-rose-200 dark:bg-rose-950/30 dark:text-rose-300 dark:border-rose-900/40';
        }

        return match ($payment?->normalizedStatus()) {
            'lunas' => 'bg-emerald-100 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/30 dark:text-emerald-300 dark:border-emerald-900/40',
            'gagal' => 'bg-red-100 text-red-700 border border-red-200 dark:bg-red-950/30 dark:text-red-300 dark:border-red-900/40',
            default => 'bg-amber-100 text-amber-700 border border-amber-200 dark:bg-amber-950/30 dark:text-amber-300 dark:border-amber-900/40',
        };
    }

    public function getTotalBayar(Booking $booking): int
    {
        return (int) ($booking->pembayaran?->nominal_bayar ?? $booking->total_biaya ?? 0);
    }

    public function getPendapatanMitra(Booking $booking): int
    {
        if ($this->isRefunded($booking)) {
            return 0;
        }

        return $this->getTotalBayar($booking);
    }

    public function canSyncMidtrans(Booking $booking): bool
    {
        return $booking->pembayaran !== null
            && filled($booking->pembayaran->midtrans_order_id)
            && in_array($booking->pembayaran->normalizedStatus(), ['pending', 'gagal'], true);
    }

    public function getActiveFilterLabel(): string
    {
        return $this->filterOptions()[$this->statusFilter] ?? 'Semua';
    }

    public function formatRupiah(int $amount): string
    {
        return 'Rp '.number_format($amount, 0, ',', '.');
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
            ->with([
                'pembayaran',
                'refund',
                'kamar.tipeKamar.kosan',
                'kontrakan',
            ])
            ->findOrFail($id);
    }

    /**
     * @return array<string, string>
     */
    protected function filterOptions(): array
    {
        return [
            'all' => 'Semua Status',
            'settlement' => 'Settlement',
            'pending' => 'Pending',
            'refunded' => 'Refunded',
        ];
    }

    protected function isRefunded(Booking $booking): bool
    {
        return $booking->pembayaran?->normalizedStatus() === 'refund'
            || ($booking->refund instanceof Refund && $booking->refund->status_refund === 'selesai');
    }

    protected function configureMidtrans(): void
    {
        MidtransConfig::$serverKey = (string) config('midtrans.server_key');
        MidtransConfig::$isProduction = (bool) config('midtrans.is_production', false);
        MidtransConfig::$isSanitized = (bool) config('midtrans.is_sanitized', true);
        MidtransConfig::$is3ds = (bool) config('midtrans.is_3ds', true);
    }
}
