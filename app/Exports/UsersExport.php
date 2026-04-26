<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    public function __construct(
        protected string $search = '',
        protected string $filterStatus = '',
        protected string $filterPeran = '',
    ) {}

    public function collection()
    {
        return User::query()
            ->with(['pemilikProperti', 'pencariKos'])
            ->where('role', '!=', 'superadmin')
            ->when($this->search !== '', function (Builder $query): void {
                $keyword = '%'.trim($this->search).'%';

                $query->where(function (Builder $searchQuery) use ($keyword): void {
                    $searchQuery->where('name', 'like', $keyword)
                        ->orWhere('email', 'like', $keyword)
                        ->orWhere('no_telepon', 'like', $keyword);
                });
            })
            ->when($this->filterPeran !== '', function (Builder $query): void {
                $query->where('role', $this->filterPeran);
            })
            ->when($this->filterStatus !== '', function (Builder $query): void {
                match ($this->filterStatus) {
                    'diblokir' => $query->where('status', 'diblokir'),
                    'aktif' => $query
                        ->where('status', 'aktif')
                        ->where(function (Builder $statusQuery): void {
                            $statusQuery->where('role', 'pencari')
                                ->orWhere(function (Builder $pemilikQuery): void {
                                    $pemilikQuery->where('role', 'pemilik')
                                        ->whereHas('pemilikProperti', function (Builder $ownerQuery): void {
                                            $ownerQuery->where('status_verifikasi', 'terverifikasi');
                                        });
                                });
                        }),
                    'pending' => $query
                        ->where('role', 'pemilik')
                        ->where('status', 'aktif')
                        ->whereHas('pemilikProperti', function (Builder $ownerQuery): void {
                            $ownerQuery->where('status_verifikasi', 'pending');
                        }),
                    default => null,
                };
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
            'Nama',
            'Email',
            'No Telepon',
            'Peran',
            'Status',
            'Status Verifikasi',
            'Nama Bank',
            'Nomor Rekening',
            'Tanggal Daftar',
        ];
    }

    /**
     * @return list<string>
     */
    public function map($user): array
    {
        return [
            (string) $user->id,
            (string) $user->name,
            (string) $user->email,
            (string) ($user->no_telepon ?? '-'),
            $user->role === 'pemilik' ? 'Pemilik Kos' : 'Pencari Kos',
            $this->statusLabel($user),
            (string) ($user->pemilikProperti?->status_verifikasi ?? '-'),
            (string) ($user->pemilikProperti?->nama_bank ?? '-'),
            (string) ($user->pemilikProperti?->nomor_rekening ?? '-'),
            $user->created_at?->format('Y-m-d H:i:s') ?? '-',
        ];
    }

    protected function statusLabel(User $user): string
    {
        if ($user->status === 'diblokir') {
            return 'Diblokir';
        }

        if ($user->role === 'pemilik' && $user->pemilikProperti?->status_verifikasi === 'pending') {
            return 'Perlu Validasi';
        }

        return 'Aktif';
    }
}
