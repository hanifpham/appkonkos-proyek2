<?php

declare(strict_types=1);

namespace App\Livewire\SuperAdmin;

use App\Models\Booking;
use App\Models\Pembayaran;
use App\Models\PencairanDana;
use App\Models\PemilikProperti;
use App\Models\Setting;
use App\Services\MidtransService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Midtrans\Config as MidtransConfig;
use Midtrans\Transaction;
use Throwable;

class DashboardUtama extends Component
{
    private const PENDING_WITHDRAWAL_STATUSES = ['pending', 'menunggu'];

    private const PENDING_SYNC_LIMIT = 50;

    #[Layout('layouts.mitra.utama')]
    public function render(): View
    {
        Setting::ensureDefaults();

        $komisiPlatformPersen = Setting::getNumber(Setting::KEY_PLATFORM_COMMISSION, 5);

        $totalNominalSettlement = (int) $this->settledPaymentsQuery()->sum('nominal_bayar');

        $totalPendapatan = (int) round($totalNominalSettlement * ($komisiPlatformPersen / 100));

        $totalTransaksiSukses = (int) Booking::query()
            ->whereHas(
                'pembayaran',
                static fn (Builder $query): Builder => $query->whereIn('status_bayar', Pembayaran::successStatuses())
            )
            ->count();

        $mitraAktif = (int) PemilikProperti::query()
            ->where('status_verifikasi', 'terverifikasi')
            ->count();

        $pencairanTertunda = (int) PencairanDana::query()
            ->whereIn('status', self::PENDING_WITHDRAWAL_STATUSES)
            ->count();

        /** @var Collection<int, PencairanDana> $listPencairan */
        $listPencairan = PencairanDana::query()
            ->with('pemilikProperti.user')
            ->whereIn('status', self::PENDING_WITHDRAWAL_STATUSES)
            ->latest()
            ->limit(5)
            ->get();

        /** @var Collection<int, Booking> $listTransaksi */
        $listTransaksi = Booking::query()
            ->with([
                'pembayaran',
                'pencariKos.user',
                'kamar.tipeKamar.kosan.pemilikProperti.user',
                'kontrakan.pemilikProperti.user',
            ])
            ->whereHas('pembayaran')
            ->latest()
            ->limit(5)
            ->get();

        return view('livewire.superadmin.dashboard-utama', compact(
            'totalPendapatan',
            'totalNominalSettlement',
            'komisiPlatformPersen',
            'totalTransaksiSukses',
            'mitraAktif',
            'pencairanTertunda',
            'listPencairan',
            'listTransaksi',
        ));
    }

    public function syncMassalPending(MidtransService $midtransService): void
    {
        $this->configureMidtrans();

        /** @var Collection<int, Pembayaran> $pendingPayments */
        $pendingPayments = Pembayaran::query()
            ->whereIn('status_bayar', Pembayaran::pendingStatuses())
            ->whereNotNull('midtrans_order_id')
            ->where('midtrans_order_id', '!=', '')
            ->latest()
            ->limit(self::PENDING_SYNC_LIMIT)
            ->get();

        if ($pendingPayments->isEmpty()) {
            $this->dispatch(
                'appkonkos-toast',
                icon: 'info',
                title: 'Tidak ada antrean sinkronisasi',
                text: 'Tidak ditemukan transaksi pending yang perlu disinkronkan ke Midtrans.'
            );

            return;
        }

        $updated = 0;
        $unchanged = 0;
        $failed = 0;

        foreach ($pendingPayments as $payment) {
            try {
                $statusSebelum = $payment->status_bayar;
                $status = Transaction::status((string) $payment->midtrans_order_id);

                $midtransService->syncPaymentStatus(
                    $payment,
                    json_decode(json_encode($status), true, 512, JSON_THROW_ON_ERROR)
                );

                $freshPayment = $payment->fresh();

                if (($freshPayment?->status_bayar ?? $statusSebelum) !== $statusSebelum) {
                    $updated++;
                } else {
                    $unchanged++;
                }
            } catch (Throwable $exception) {
                report($exception);
                $failed++;
            }
        }

        $icon = $failed > 0 ? 'warning' : 'success';
        $title = $failed > 0 ? 'Sinkronisasi selesai dengan catatan' : 'Sinkronisasi selesai';
        $text = sprintf(
            '%d transaksi diperbarui, %d tetap pending, %d gagal diproses.',
            $updated,
            $unchanged,
            $failed
        );

        $this->dispatch(
            'appkonkos-toast',
            icon: $icon,
            title: $title,
            text: $text
        );
    }

    public function prosesCairkan(int $id): void
    {
        $pencairan = PencairanDana::query()->findOrFail($id);

        if (! in_array($pencairan->status, self::PENDING_WITHDRAWAL_STATUSES, true)) {
            return;
        }

        $pencairan->update([
            'status' => 'disetujui',
        ]);

        $this->dispatch(
            'appkonkos-toast',
            icon: 'success',
            title: 'Pencairan diproses',
            text: 'Permintaan pencairan dana berhasil diteruskan ke proses Midtrans.'
        );
    }

    public function tolakCairan(int $id): void
    {
        $pencairan = PencairanDana::query()->findOrFail($id);

        if (! in_array($pencairan->status, self::PENDING_WITHDRAWAL_STATUSES, true)) {
            return;
        }

        $pencairan->update([
            'status' => 'ditolak',
        ]);

        $this->dispatch(
            'appkonkos-toast',
            icon: 'success',
            title: 'Pencairan ditolak',
            text: 'Permintaan pencairan dana berhasil ditolak.'
        );
    }

    public function getWithdrawalDisplayId(PencairanDana $pencairan): string
    {
        return 'WD-'.str_pad((string) $pencairan->id, 5, '0', STR_PAD_LEFT);
    }

    public function getWithdrawalStatusLabel(string $status): string
    {
        return match ($status) {
            'pending', 'menunggu' => 'MENUNGGU',
            'disetujui' => 'DIPROSES',
            'sukses' => 'SELESAI',
            'ditolak' => 'DITOLAK',
            default => Str::upper($status),
        };
    }

    public function getWithdrawalStatusClasses(string $status): string
    {
        return match ($status) {
            'pending', 'menunggu' => 'bg-amber-50 text-amber-700 border border-amber-100 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-800',
            'disetujui' => 'bg-sky-50 text-sky-700 border border-sky-100 dark:bg-sky-900/30 dark:text-sky-300 dark:border-sky-800',
            'sukses' => 'bg-green-50 text-green-700 border border-green-100 dark:bg-green-900/30 dark:text-green-300 dark:border-green-800',
            'ditolak' => 'bg-red-50 text-red-700 border border-red-100 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800',
            default => 'bg-gray-100 text-gray-700 border border-gray-200 dark:bg-slate-800 dark:text-gray-300 dark:border-slate-700',
        };
    }

    public function getOrderDisplayId(Booking $booking): string
    {
        return $booking->pembayaran?->midtrans_order_id ?? '#BK-'.Str::upper(Str::substr(str_replace('-', '', $booking->id), 0, 10));
    }

    public function getNamaProperti(Booking $booking): string
    {
        if ($booking->kamar_id !== null) {
            return $booking->kamar?->tipeKamar?->kosan?->nama_properti ?? '-';
        }

        if ($booking->kontrakan_id !== null) {
            return $booking->kontrakan?->nama_properti ?? '-';
        }

        return '-';
    }

    public function getNamaPemilik(Booking $booking): string
    {
        if ($booking->kamar_id !== null) {
            return $booking->kamar?->tipeKamar?->kosan?->pemilikProperti?->user?->name ?? '-';
        }

        if ($booking->kontrakan_id !== null) {
            return $booking->kontrakan?->pemilikProperti?->user?->name ?? '-';
        }

        return '-';
    }

    public function getMetodePembayaran(Booking $booking): string
    {
        $metode = trim((string) ($booking->pembayaran?->metode_bayar ?? ''));

        return $metode !== '' ? Str::upper($metode) : '-';
    }

    public function getNominalTransaksi(Booking $booking): int
    {
        return (int) ($booking->pembayaran?->nominal_bayar ?? $booking->total_biaya);
    }

    public function getStatusTransaksiLabel(Booking $booking): string
    {
        $status = trim((string) ($booking->pembayaran?->status_bayar ?? ''));

        if ($status === '') {
            return 'BELUM ADA';
        }

        return match (true) {
            $booking->pembayaran?->isSuccessful() ?? false => 'LUNAS',
            $booking->pembayaran?->isFailed() ?? false => 'GAGAL',
            $status === '' => 'BELUM ADA',
            default => Str::upper($status),
        };
    }

    public function getStatusTransaksiClasses(Booking $booking): string
    {
        if ($booking->pembayaran?->isSuccessful() ?? false) {
            return 'bg-green-50 text-green-700 border border-green-100 dark:bg-green-900/30 dark:text-green-300 dark:border-green-800';
        }

        if ($booking->pembayaran?->isFailed() ?? false) {
            return 'bg-red-50 text-red-700 border border-red-100 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800';
        }

        return match ($booking->pembayaran?->status_bayar) {
            'challenge' => 'bg-sky-50 text-sky-700 border border-sky-100 dark:bg-sky-900/30 dark:text-sky-300 dark:border-sky-800',
            'pending' => 'bg-amber-50 text-amber-700 border border-amber-100 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-800',
            default => 'bg-gray-100 text-gray-700 border border-gray-200 dark:bg-slate-800 dark:text-gray-300 dark:border-slate-700',
        };
    }

    protected function settledPaymentsQuery(): Builder
    {
        return Pembayaran::query()
            ->where(function (Builder $query): void {
                $query
                    ->where('status_midtrans', 'settlement')
                    ->orWhere(function (Builder $legacyQuery): void {
                        $legacyQuery
                            ->whereNull('status_midtrans')
                            ->where('status_bayar', 'settlement');
                    });
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
