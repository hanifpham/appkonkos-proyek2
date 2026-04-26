<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Kontrakan;
use App\Models\Kosan;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PropertiesExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    private const PENDING_STATUSES = ['pending', 'menunggu'];

    public function __construct(
        protected string $search = '',
        protected string $filterStatus = '',
        protected string $filterTipe = '',
    ) {}

    public function collection(): Collection
    {
        return $this->propertyCollection()
            ->when($this->filterTipe !== '', function (Collection $properties): Collection {
                return $properties->where('tipe', $this->filterTipe)->values();
            })
            ->when($this->filterStatus !== '', function (Collection $properties): Collection {
                return $properties->filter(function (object $properti): bool {
                    if ($this->filterStatus === 'pending') {
                        return in_array($properti->status, self::PENDING_STATUSES, true);
                    }

                    return $properti->status === $this->filterStatus;
                })->values();
            })
            ->when($this->search !== '', function (Collection $properties): Collection {
                $keyword = Str::lower(trim($this->search));

                return $properties->filter(function (object $properti) use ($keyword): bool {
                    $haystack = Str::lower(implode(' ', [
                        (string) $properti->id,
                        (string) ($properti->nama ?? ''),
                        (string) ($properti->alamat ?? ''),
                        (string) ($properti->nama_pemilik ?? ''),
                        (string) ($properti->tipe ?? ''),
                    ]));

                    return Str::contains($haystack, $keyword);
                })->values();
            })
            ->sort(function (object $first, object $second): int {
                $createdComparison = ($second->created_at?->timestamp ?? 0) <=> ($first->created_at?->timestamp ?? 0);

                return $createdComparison !== 0
                    ? $createdComparison
                    : ((int) $second->id <=> (int) $first->id);
            })
            ->values();
    }

    /**
     * @return list<string>
     */
    public function headings(): array
    {
        return [
            'ID Properti',
            'Tipe Properti',
            'Nama Properti',
            'Pemilik',
            'Kategori',
            'Alamat',
            'Harga',
            'Status',
            'Tanggal Dibuat',
        ];
    }

    /**
     * @return list<string>
     */
    public function map($properti): array
    {
        return [
            'PROP-'.str_pad((string) $properti->id, 4, '0', STR_PAD_LEFT),
            $properti->tipe === 'kontrakan' ? 'Kontrakan' : 'Kosan',
            (string) $properti->nama,
            (string) ($properti->nama_pemilik ?? '-'),
            $this->kategoriLabel($properti),
            (string) ($properti->alamat ?? '-'),
            'Rp '.number_format((int) ($properti->harga ?? 0), 0, ',', '.'),
            $this->statusLabel($properti->status),
            $properti->created_at?->format('Y-m-d H:i:s') ?? '-',
        ];
    }

    protected function propertyCollection(): Collection
    {
        $kosan = Kosan::query()
            ->with(['pemilikProperti.user'])
            ->withMin('tipeKamar as harga', 'harga_per_bulan')
            ->get()
            ->map(function (Kosan $properti): Kosan {
                $properti->setAttribute('nama', $properti->nama_properti);
                $properti->setAttribute('alamat', $properti->alamat_lengkap);
                $properti->setAttribute('tipe', 'kosan');
                $properti->setAttribute('harga', $properti->harga ?? 0);
                $properti->setAttribute('nama_pemilik', $properti->pemilikProperti?->user?->name);

                return $properti;
            });

        $kontrakan = Kontrakan::query()
            ->with(['pemilikProperti.user'])
            ->get()
            ->map(function (Kontrakan $properti): Kontrakan {
                $properti->setAttribute('nama', $properti->nama_properti);
                $properti->setAttribute('alamat', $properti->alamat_lengkap);
                $properti->setAttribute('tipe', 'kontrakan');
                $properti->setAttribute('harga', $properti->harga_sewa_tahun);
                $properti->setAttribute('jenis_kos', null);
                $properti->setAttribute('nama_pemilik', $properti->pemilikProperti?->user?->name);

                return $properti;
            });

        return $kosan->concat($kontrakan);
    }

    protected function kategoriLabel(object $properti): string
    {
        if ($properti->tipe === 'kontrakan') {
            return 'Kontrakan';
        }

        return match ($properti->jenis_kos) {
            'putra' => 'Kos Putra',
            'putri' => 'Kos Putri',
            'campur' => 'Kos Campur',
            default => 'Kos',
        };
    }

    protected function statusLabel(?string $status): string
    {
        return match ($status) {
            'pending', 'menunggu' => 'Menunggu',
            'aktif' => 'Aktif',
            'ditolak' => 'Ditolak',
            default => Str::headline((string) $status),
        };
    }
}
