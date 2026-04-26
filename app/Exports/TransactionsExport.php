<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Pembayaran;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionsExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    public function __construct(
        protected string $filterStatus = '',
        protected string $filterMetode = '',
    ) {}

    public function collection()
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
            })
            ->latest()
            ->get();
    }

    /**
     * @return list<string>
     */
    public function headings(): array
    {
        return [
            'ID',
            'Order ID',
            'Penyewa',
            'Properti',
            'Pemilik',
            'Nominal',
            'Metode Bayar',
            'Status',
            'Waktu Bayar',
            'Tanggal Dibuat',
        ];
    }

    /**
     * @return list<string>
     */
    public function map($item): array
    {
        return [
            (string) $item->id,
            $this->orderId($item),
            $this->penyewaName($item),
            $this->namaProperti($item),
            $this->namaPemilik($item),
            'Rp '.number_format((int) $item->nominal_bayar, 0, ',', '.'),
            $this->metodeLabel($item),
            $this->statusLabel($item),
            $item->waktu_bayar?->format('Y-m-d H:i:s') ?? '-',
            $item->created_at?->format('Y-m-d H:i:s') ?? '-',
        ];
    }

    protected function orderId(Pembayaran $item): string
    {
        if (filled($item->midtrans_order_id)) {
            return (string) $item->midtrans_order_id;
        }

        $identifier = Str::upper(Str::substr(str_replace('-', '', (string) $item->booking_id), 0, 10));

        return '#BK-'.$identifier;
    }

    protected function penyewaName(Pembayaran $item): string
    {
        return $item->booking?->pencariKos?->user?->name ?? '-';
    }

    protected function namaProperti(Pembayaran $item): string
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

    protected function namaPemilik(Pembayaran $item): string
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

    protected function metodeLabel(Pembayaran $item): string
    {
        $metode = trim((string) $item->metode_bayar);

        if ($metode === '') {
            return '-';
        }

        return Str::upper(str_replace('_', ' ', $metode));
    }

    protected function statusLabel(Pembayaran $item): string
    {
        $status = Str::lower((string) $item->status_bayar);

        return match (true) {
            $item->isSuccessful() => 'Lunas',
            $item->isFailed() => 'Gagal',
            $item->normalizedStatus() === 'refund' => 'Refund',
            default => Str::headline($status !== '' ? $status : 'unknown'),
        };
    }
}
