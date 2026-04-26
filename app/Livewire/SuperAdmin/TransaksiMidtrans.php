<?php

declare(strict_types=1);

namespace App\Livewire\SuperAdmin;

use App\Exports\TransactionsExport;
use App\Models\Pembayaran;
use App\Models\PencairanDana;
use App\Models\PemilikProperti;
use App\Services\MidtransService;
use Throwable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Midtrans\Config as MidtransConfig;
use Midtrans\Transaction;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TransaksiMidtrans extends Component
{
    use WithPagination;

    private const PENDING_WITHDRAWAL_STATUSES = ['pending', 'menunggu'];

    protected string $paginationTheme = 'tailwind';

    public string $filterStatus = '';

    public string $filterMetode = '';

    public function mount(): void
    {
        $this->configureMidtrans();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatingFilterMetode(): void
    {
        $this->resetPage();
    }

    #[Layout('layouts.mitra.utama')]
    public function render(): View
    {
        $transaksi = $this->transaksiQuery()
            ->latest()
            ->paginate(10);

        $totalPendapatan = (int) Pembayaran::query()
            ->whereIn('status_bayar', Pembayaran::successStatuses())
            ->sum('nominal_bayar');

        $totalSukses = (int) Pembayaran::query()
            ->whereIn('status_bayar', Pembayaran::successStatuses())
            ->count();

        $mitraAktif = (int) PemilikProperti::query()
            ->where('status_verifikasi', 'terverifikasi')
            ->count();

        $pencairanTertunda = (int) PencairanDana::query()
            ->whereIn('status', self::PENDING_WITHDRAWAL_STATUSES)
            ->count();

        return view('livewire.superadmin.transaksi-midtrans', compact(
            'transaksi',
            'totalPendapatan',
            'totalSukses',
            'mitraAktif',
            'pencairanTertunda',
        ));
    }

    public function exportData(): BinaryFileResponse
    {
        return Excel::download(
            new TransactionsExport($this->filterStatus, $this->filterMetode),
            'Laporan-Transaksi-Midtrans-'.now()->format('Ymd-His').'.xlsx',
        );
    }

    public function exportReport(): BinaryFileResponse
    {
        return Excel::download(
            new TransactionsExport($this->filterStatus, $this->filterMetode),
            'transaksi-midtrans-'.now()->format('Ymd-His').'.csv',
        );
    }

    public function syncMidtrans(string $orderId, MidtransService $midtransService): void
    {
        $this->configureMidtrans();

        $payment = Pembayaran::query()
            ->where('midtrans_order_id', $orderId)
            ->first();

        if ($payment === null) {
            $this->dispatch(
                'appkonkos-toast',
                icon: 'error',
                title: 'Error',
                text: 'Transaksi Midtrans tidak ditemukan di database lokal.'
            );

            return;
        }

        try {
            $status = Transaction::status($orderId);

            $midtransService->syncPaymentStatus($payment, json_decode(json_encode($status), true, 512, JSON_THROW_ON_ERROR));

            $this->dispatch(
                'appkonkos-toast',
                icon: 'success',
                title: 'Berhasil',
                text: 'Status tersinkronisasi dari server Midtrans.'
            );
        } catch (Throwable $exception) {
            report($exception);

            $this->dispatch(
                'appkonkos-toast',
                icon: 'error',
                title: 'Error',
                text: 'Gagal sinkronisasi status: '.$exception->getMessage()
            );
        }
    }

    public function getOrderId(Pembayaran $item): string
    {
        if (filled($item->midtrans_order_id)) {
            return (string) $item->midtrans_order_id;
        }

        $identifier = Str::upper(Str::substr(str_replace('-', '', (string) $item->booking_id), 0, 10));

        return '#BK-'.$identifier;
    }

    public function getPenyewaName(Pembayaran $item): string
    {
        return $item->booking?->pencariKos?->user?->name ?? '-';
    }

    public function getNamaProperti(Pembayaran $item): string
    {
        $booking = $item->booking;

        if ($booking?->kamar_id !== null) {
            return $booking->kamar?->tipeKamar?->kosan?->nama_properti ?? '-';
        }

        if ($booking?->kontrakan_id !== null) {
            return $booking->kontrakan?->nama_properti ?? '-';
        }

        return '-';
    }

    public function getNamaPemilik(Pembayaran $item): string
    {
        $booking = $item->booking;

        if ($booking?->kamar_id !== null) {
            return $booking->kamar?->tipeKamar?->kosan?->pemilikProperti?->user?->name ?? '-';
        }

        if ($booking?->kontrakan_id !== null) {
            return $booking->kontrakan?->pemilikProperti?->user?->name ?? '-';
        }

        return '-';
    }

    public function getMetodeLabel(Pembayaran $item): string
    {
        $metode = trim((string) $item->metode_bayar);

        if ($metode === '') {
            return '-';
        }

        return Str::upper(str_replace('_', ' ', $metode));
    }

    /**
     * @return array{icon: string, wrapper: string, iconClass: string}
     */
    public function getMetodeIcon(Pembayaran $item): array
    {
        $metode = Str::lower((string) $item->metode_bayar);

        if (Str::contains($metode, 'gopay')) {
            return [
                'icon' => 'wallet',
                'wrapper' => 'bg-blue-500/10',
                'iconClass' => 'text-blue-600 dark:text-blue-400',
            ];
        }

        if (Str::contains($metode, 'qris')) {
            return [
                'icon' => 'qr_code_2',
                'wrapper' => 'bg-purple-500/10',
                'iconClass' => 'text-purple-600 dark:text-purple-400',
            ];
        }

        if (Str::contains($metode, ['va', 'bank', 'transfer'])) {
            return [
                'icon' => 'account_balance',
                'wrapper' => 'bg-blue-800/10',
                'iconClass' => 'text-blue-800 dark:text-blue-500',
            ];
        }

        return [
            'icon' => 'payments',
            'wrapper' => 'bg-slate-500/10',
            'iconClass' => 'text-slate-600 dark:text-slate-300',
        ];
    }

    public function getStatusLabel(Pembayaran $item): string
    {
        $status = Str::lower((string) $item->status_bayar);

        return match (true) {
            $item->isSuccessful() => 'Lunas',
            $item->isFailed() => 'Gagal',
            $item->normalizedStatus() === 'refund' => 'Refund',
            default => Str::headline($status !== '' ? $status : 'unknown'),
        };
    }

    public function getStatusBadgeClasses(Pembayaran $item): string
    {
        if ($item->isSuccessful()) {
            return 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300 border border-green-200 dark:border-green-800';
        }

        if ($item->isFailed()) {
            return 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300 border border-red-200 dark:border-red-800';
        }

        return match (Str::lower((string) $item->status_bayar)) {
            'pending', 'challenge' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300 border border-amber-200 dark:border-amber-800',
            default => 'bg-gray-100 text-gray-700 dark:bg-slate-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700',
        };
    }

    public function canSync(Pembayaran $item): bool
    {
        return $item->isPending() && filled($item->midtrans_order_id);
    }

    protected function transaksiQuery(): Builder
    {
        return Pembayaran::query()
            ->with([
                'booking.pencariKos.user',
                'booking.kamar.tipeKamar.kosan.pemilikProperti.user',
                'booking.kontrakan.pemilikProperti.user',
            ])
            ->when($this->filterStatus !== '', function (Builder $query): void {
                if ($this->filterStatus === 'settlement') {
                    $query->whereIn('status_bayar', Pembayaran::successStatuses());

                    return;
                }

                if ($this->filterStatus === 'expire') {
                    $query->whereIn('status_bayar', Pembayaran::failedStatuses());

                    return;
                }

                $query->where('status_bayar', $this->filterStatus);
            })
            ->when($this->filterMetode !== '', function (Builder $query): void {
                if ($this->filterMetode === 'bank_transfer') {
                    $query->where(function (Builder $methodQuery): void {
                        $methodQuery
                            ->where('metode_bayar', 'like', '%bank_transfer%')
                            ->orWhere('metode_bayar', 'like', '%bank%')
                            ->orWhere('metode_bayar', 'like', '%va%')
                            ->orWhere('metode_bayar', 'like', '%transfer%');
                    });

                    return;
                }

                $query->where('metode_bayar', 'like', '%'.$this->filterMetode.'%');
            });
    }

    protected function configureMidtrans(): void
    {
        MidtransConfig::$serverKey = (string) config('midtrans.server_key');
        MidtransConfig::$isProduction = (bool) config('midtrans.is_production', false);
        MidtransConfig::$isSanitized = (bool) config('midtrans.is_sanitized', true);
        MidtransConfig::$is3ds = (bool) config('midtrans.is_3ds', true);
    }
}
