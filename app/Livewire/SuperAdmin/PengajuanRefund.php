<?php

declare(strict_types=1);

namespace App\Livewire\SuperAdmin;

use App\Models\Refund;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Midtrans\Config as MidtransConfig;
use Midtrans\Transaction;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class PengajuanRefund extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public string $search = '';

    public string $filterStatus = '';

    public ?int $selectedRefundId = null;

    public bool $showModalDetail = false;

    public ?Refund $detailRefund = null;

    public bool $showModalRefund = false;

    #[Locked]
    public ?Refund $selectedRefund = null;

    public int $totalPotongan = 0;

    public int $totalKembali = 0;

    public float $potonganRefundPersen = 25.0;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    #[Layout('layouts.mitra.utama')]
    public function render(): View
    {
        $listRefund = $this->refundQuery()
            ->latest()
            ->paginate(10);

        $totalPengajuanBulanIni = (int) Refund::query()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $perluDitinjau = (int) Refund::query()
            ->where('status_refund', 'pending')
            ->count();

        $disetujui = (int) Refund::query()
            ->whereIn('status_refund', $this->statusValues('approved'))
            ->count();

        $ditolak = (int) Refund::query()
            ->whereIn('status_refund', $this->statusValues('rejected'))
            ->count();

        return view('livewire.superadmin.pengajuan-refund', compact(
            'listRefund',
            'totalPengajuanBulanIni',
            'perluDitinjau',
            'disetujui',
            'ditolak',
        ));
    }

    public function exportData(): StreamedResponse
    {
        $rows = $this->refundQuery()
            ->latest()
            ->get();

        $filename = 'pengajuan-refund-'.Carbon::now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($rows): void {
            $handle = fopen('php://output', 'wb');

            if ($handle === false) {
                return;
            }

            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'ID Refund',
                'ID Booking',
                'ID Transaksi',
                'Pengguna',
                'Email',
                'Nominal Refund',
                'Status',
                'Alasan Refund',
                'Tanggal Pengajuan',
            ]);

            /** @var \App\Models\Refund $refund */
            foreach ($rows as $refund) {
                fputcsv($handle, [
                    $this->getRefundDisplayId($refund),
                    $refund->booking_id,
                    $this->getTransactionDisplayId($refund),
                    $this->getUserName($refund),
                    $refund->booking?->pencariKos?->user?->email ?? '-',
                    (int) $refund->nominal_refund,
                    $this->getStatusLabel((string) $refund->status_refund),
                    $refund->alasan_refund,
                    $refund->created_at?->format('Y-m-d H:i:s') ?? '-',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function tinjauPengajuan(int $id): void
    {
        $this->selectedRefundId = $id;
        $this->detailRefund = Refund::query()
            ->with([
                'booking.pencariKos.user',
                'pembayaran',
            ])
            ->findOrFail($id);
        $this->showModalDetail = true;
    }

    public function tutupDetail(): void
    {
        $this->showModalDetail = false;
        $this->selectedRefundId = null;
        $this->detailRefund = null;
    }

    public function tinjauRefund(int $id): void
    {
        $refund = Refund::query()
            ->with([
                'booking.pencariKos.user',
                'booking.kamar.tipeKamar.kosan',
                'booking.kontrakan',
                'pembayaran',
            ])
            ->findOrFail($id);

        if ($refund->status_refund !== 'pending') {
            $this->dispatch(
                'appkonkos-toast',
                icon: 'warning',
                title: 'Refund tidak dapat ditinjau',
                text: 'Pengajuan refund ini sudah diproses sebelumnya.'
            );

            return;
        }

        $nominalTransaksi = (int) ($refund->pembayaran?->nominal_bayar ?? $refund->booking?->total_biaya ?? 0);
        $this->potonganRefundPersen = Setting::getNumber(Setting::KEY_REFUND_DEDUCTION, 25);
        $persenPotongan = $this->potonganRefundPersen / 100;

        $this->showModalDetail = false;
        $this->selectedRefund = $refund;
        $this->totalPotongan = (int) round($nominalTransaksi * $persenPotongan);
        $this->totalKembali = max(0, $nominalTransaksi - $this->totalPotongan);
        $this->showModalRefund = true;
    }

    public function tutupModalRefund(): void
    {
        $this->resetRefundReviewState();
    }

    public function setujuiRefund(int $id): void
    {
        $this->tinjauRefund($id);
    }

    public function tolakRefund(int $id): void
    {
        $refund = Refund::query()
            ->with(['booking.kamar', 'booking.kontrakan'])
            ->findOrFail($id);

        if ($refund->status_refund !== 'pending') {
            $this->dispatch(
                'appkonkos-toast',
                icon: 'warning',
                title: 'Refund tidak dapat ditolak',
                text: 'Status pengajuan refund ini sudah berubah.'
            );

            return;
        }

        DB::transaction(function () use ($refund) {
            $refund->update([
                'status_refund' => 'ditolak',
            ]);

            if ($refund->booking) {
                $this->bebaskanProperti($refund->booking);
            }
        });

        $this->refreshDetailRefund($refund->id);

        $this->dispatch(
            'appkonkos-toast',
            icon: 'info',
            title: 'Refund ditolak',
            text: 'Pengajuan refund berhasil ditolak dan properti telah tersedia kembali.'
        );
    }

    public function tandaiSudahDitransfer(int $id): void
    {
        $this->prosesRefund($id);
    }

    public function prosesRefund(?int $refundId = null): void
    {
        $id = $refundId ?? $this->selectedRefund?->id;

        if (! $id) {
            session()->flash('error', 'Tidak ada pengajuan refund yang sedang diproses.');
            return;
        }

        try {
            $refund = Refund::query()
                ->with([
                    'booking.kamar.tipeKamar.kosan',
                    'booking.kontrakan',
                    'pembayaran',
                ])
                ->findOrFail($id);

            $payment = $refund->pembayaran;
            $booking = $refund->booking;

            if ($payment === null || $booking === null) {
                session()->flash('error', 'Data transaksi tidak valid.');
                return;
            }

            // 1. AMBIL PENGATURAN POTONGAN DARI SUPERADMIN
            $nominalTransaksi = (int) ($payment->nominal_bayar ?? $booking->total_biaya ?? 0);
            $potonganRefundPersen = Setting::getNumber(Setting::KEY_REFUND_DEDUCTION, 25);
            $persenPotongan = $potonganRefundPersen / 100;

            $totalPotongan = (int) round($nominalTransaksi * $persenPotongan);
            $nominalRefund = (int) max(0, $nominalTransaksi - $totalPotongan);

            // 2. CEK JALUR REFUND (MANUAL VS OTOMATIS)
            $metode = strtolower($payment->metode_bayar ?? '');
            $metodeManual = ['bank_transfer', 'echannel', 'bca_va', 'bni_va', 'bri_va', 'cstore'];

            if (in_array($metode, $metodeManual)) {
                // JALUR MANUAL
                $this->updateStatusBerhasil($refund, $payment, $booking, $nominalRefund);
                $this->refreshDetailRefund($refund->id);
                if ($refundId === null) $this->resetRefundReviewState();
                
                session()->flash('success', "Status berhasil diupdate! Jangan lupa segera transfer manual sebesar Rp " . number_format($nominalRefund, 0, ',', '.') . " ke rekening penyewa.");
                return;
            }

            if (empty($payment->midtrans_order_id)) {
                session()->flash('error', 'Data transaksi tidak valid atau order_id kosong.');
                return;
            }

            // JALUR OTOMATIS
            $serverKey = config('midtrans.server_key');
            $isProduction = config('midtrans.is_production', false);

            // KONFIGURASI SDK MIDTRANS
            \Midtrans\Config::$serverKey = (string) $serverKey;
            \Midtrans\Config::$isProduction = (bool) $isProduction;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            // Gunakan transaction_id jika ada, jika tidak gunakan order_id
            $idTransaksiMidtrans = $payment->midtrans_transaction_id ?: $payment->midtrans_order_id;

            $params = [
                'refund_key' => 'ref-' . $payment->midtrans_order_id . '-' . time(),
                'amount'     => (int) $nominalRefund,
                'reason'     => 'Refund disetujui Super Admin',
            ];

            try {
                $response = \Midtrans\Transaction::refund($idTransaksiMidtrans, $params);
                
                // Cek apakah Midtrans menerima request refund (status_code 200, 201)
                $statusCode = (string) ($response->status_code ?? '');
                
                if ($statusCode === '200' || $statusCode === '201') {
                    $this->updateStatusBerhasil($refund, $payment, $booking, (int) $nominalRefund);
                    $this->refreshDetailRefund($refund->id);
                    if ($refundId === null) $this->resetRefundReviewState();
                    
                    session()->flash('success', "Refund Otomatis senilai Rp " . number_format($nominalRefund, 0, ',', '.') . " berhasil diproses!");
                } else {
                    $pesanError = $response->status_message ?? 'Gagal memproses refund di Midtrans.';
                    \Illuminate\Support\Facades\Log::error('Midtrans Refund Error: ' . json_encode($response));
                    session()->flash('error', 'Midtrans menolak: ' . $pesanError . ' (Status: ' . $statusCode . ')');
                }
            } catch (\Exception $e) {
                // Jika terjadi error dari SDK (misal 404 atau 412)
                \Illuminate\Support\Facades\Log::error('Midtrans SDK Error: ' . $e->getMessage());
                session()->flash('error', 'Error Midtrans: ' . $e->getMessage());
            }

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Refund System Error: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan sistem saat menghubungi Midtrans.');
        }
    }

    private function updateStatusBerhasil(Refund $refund, \App\Models\Pembayaran $payment, \App\Models\Booking $booking, int $nominalRefund): void
    {
        DB::transaction(function () use ($refund, $payment, $booking, $nominalRefund) {
            $refund->update([
                'status_refund' => 'selesai',
                'nominal_refund' => $nominalRefund,
            ]); 
            
            $payment->update([
                'status_bayar' => 'refund',
                'status_midtrans' => 'refund',
            ]);

            $booking->update([
                'status_booking' => 'batal',
            ]);

            $this->bebaskanProperti($booking);
        });
    }

    /**
     * Helper untuk mengembalikan status kamar menjadi tersedia atau menambah stok kontrakan.
     */
    private function bebaskanProperti(\App\Models\Booking $booking): void
    {
        // 1. Jika itu Kamar Kosan
        if ($booking->kamar !== null) {
            $booking->kamar->update([
                'status_kamar' => 'tersedia',
            ]);
        }

        // 2. Jika itu Kontrakan
        if ($booking->kontrakan !== null) {
            $booking->kontrakan->increment('sisa_kamar');
            
            // Jika sebelumnya nonaktif karena stok habis, aktifkan kembali
            if ($booking->kontrakan->status === 'nonaktif') {
                $booking->kontrakan->update(['status' => 'aktif']);
            }
        }
    }

    public function getRefundDisplayId(Refund $refund): string
    {
        return 'REF-'.str_pad((string) $refund->id, 5, '0', STR_PAD_LEFT);
    }

    public function getTransactionDisplayId(Refund $refund): string
    {
        if ($refund->pembayaran_id !== null) {
            return 'TX-'.str_pad((string) $refund->pembayaran_id, 5, '0', STR_PAD_LEFT);
        }

        $bookingId = (string) $refund->booking_id;

        return 'BK-'.Str::upper(Str::substr(str_replace('-', '', $bookingId), 0, 8));
    }

    public function getUserName(Refund $refund): string
    {
        return $refund->booking?->pencariKos?->user?->name ?? '-';
    }

    public function getStatusLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'Perlu Ditinjau',
            'approved', 'selesai' => 'Disetujui',
            'rejected', 'ditolak' => 'Ditolak',
            'diproses' => 'Diproses',
            default => Str::headline($status !== '' ? $status : 'unknown'),
        };
    }

    public function getStatusBadgeClasses(string $status): string
    {
        return match ($status) {
            'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300 border border-amber-200 dark:border-amber-800/50',
            'approved', 'selesai' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300 border border-green-200 dark:border-green-800/50',
            'rejected', 'ditolak' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300 border border-red-200 dark:border-red-800/50',
            default => 'bg-gray-100 text-gray-700 dark:bg-slate-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700',
        };
    }

    public function getStatusDotClasses(string $status): string
    {
        return match ($status) {
            'pending' => 'bg-amber-500',
            'approved', 'selesai' => 'bg-green-500',
            'rejected', 'ditolak' => 'bg-red-500',
            default => 'bg-gray-500',
        };
    }

    /**
     * @return list<string>
     */
    protected function statusValues(string $status): array
    {
        return match ($status) {
            'approved' => ['approved', 'selesai'],
            'rejected' => ['rejected', 'ditolak'],
            default => [$status],
        };
    }

    protected function refundQuery(): Builder
    {
        return Refund::query()
            ->with([
                'booking.pencariKos.user',
                'pembayaran',
            ])
            ->when(trim($this->search) !== '', function (Builder $query): void {
                $search = trim($this->search);
                $numericSearch = preg_replace('/\D+/', '', $search);

                $query->where(function (Builder $searchQuery) use ($search, $numericSearch): void {
                    $searchQuery
                        ->where('id', 'like', '%'.$search.'%')
                        ->orWhere('booking_id', 'like', '%'.$search.'%')
                        ->orWhere('pembayaran_id', 'like', '%'.$search.'%')
                        ->orWhereHas('booking.pencariKos.user', function (Builder $userQuery) use ($search): void {
                            $userQuery
                                ->where('name', 'like', '%'.$search.'%')
                                ->orWhere('email', 'like', '%'.$search.'%');
                        });

                    if (is_string($numericSearch) && $numericSearch !== '') {
                        $searchQuery
                            ->orWhere('id', 'like', '%'.$numericSearch.'%')
                            ->orWhere('pembayaran_id', 'like', '%'.$numericSearch.'%');
                    }
                });
            })
            ->when($this->filterStatus !== '', function (Builder $query): void {
                $query->whereIn('status_refund', $this->statusValues($this->filterStatus));
            });
    }

    protected function refreshDetailRefund(int $id): void
    {
        if ($this->selectedRefundId !== $id) {
            return;
        }

        $this->detailRefund = Refund::query()
            ->with([
                'booking.pencariKos.user',
                'pembayaran',
            ])
            ->find($id);
    }

    protected function configureMidtrans(): void
    {
        MidtransConfig::$serverKey = (string) config('midtrans.server_key');
        MidtransConfig::$isProduction = (bool) config('midtrans.is_production', false);
        MidtransConfig::$isSanitized = (bool) config('midtrans.is_sanitized', true);
        MidtransConfig::$is3ds = (bool) config('midtrans.is_3ds', true);
    }

    protected function resetRefundReviewState(): void
    {
        $this->showModalRefund = false;
        $this->selectedRefund = null;
        $this->totalPotongan = 0;
        $this->totalKembali = 0;
        $this->potonganRefundPersen = Setting::getNumber(Setting::KEY_REFUND_DEDUCTION, 25);
    }

    protected function getRefundPersen(): string
    {
        return $this->formatPercentage(max(0, 100 - $this->potonganRefundPersen));
    }

    protected function formatPercentage(float $value): string
    {
        return rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.');
    }
}
