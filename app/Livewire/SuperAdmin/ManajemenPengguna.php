<?php

declare(strict_types=1);

namespace App\Livewire\SuperAdmin;

use App\Exports\UsersExport;
use App\Models\Kontrakan;
use App\Models\Kosan;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ManajemenPengguna extends Component
{
    use WithPagination;

    public string $search = '';

    public string $filterPeran = '';

    public string $filterStatus = '';

    public bool $showModalDetail = false;

    public $detailUser = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterPeran(): void
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
        /** @var LengthAwarePaginator<int, User> $users */
        $users = $this->baseUsersQuery()
            ->latest()
            ->paginate(10);

        $totalPengguna = (int) User::query()
            ->where('role', '!=', 'superadmin')
            ->count();

        $totalAktif = (int) User::query()
            ->where('role', '!=', 'superadmin')
            ->where('status', 'aktif')
            ->where(function (Builder $query): void {
                $query->where('role', 'pencari')
                    ->orWhere(function (Builder $pemilikQuery): void {
                        $pemilikQuery->where('role', 'pemilik')
                            ->whereHas('pemilikProperti', function (Builder $ownerQuery): void {
                                $ownerQuery->where('status_verifikasi', 'terverifikasi');
                            });
                    });
            })
            ->count();

        $totalPending = (int) User::query()
            ->where('role', 'pemilik')
            ->where('status', 'aktif')
            ->whereHas('pemilikProperti', function (Builder $query): void {
                $query->where('status_verifikasi', 'pending');
            })
            ->count();

        $totalTerblokir = (int) User::query()
            ->where('role', '!=', 'superadmin')
            ->where('status', 'diblokir')
            ->count();

        return view('livewire.superadmin.manajemen-pengguna', compact(
            'users',
            'totalPengguna',
            'totalAktif',
            'totalPending',
            'totalTerblokir',
        ));
    }

    public function validasiAkun(int $userId): void
    {
        $user = User::query()
            ->with('pemilikProperti')
            ->where('role', '!=', 'superadmin')
            ->findOrFail($userId);

        if ($user->role === 'pemilik' && $user->pemilikProperti !== null) {
            $user->pemilikProperti->update([
                'status_verifikasi' => 'terverifikasi',
            ]);
        }

        $user->update([
            'status' => 'aktif',
        ]);

        $this->dispatch(
            'appkonkos-toast',
            icon: 'success',
            title: 'Akun tervalidasi',
            text: 'Akun pengguna berhasil divalidasi.'
        );

        if ($this->detailUser?->id === $user->id) {
            $this->detailUser = $user->fresh(['pemilikProperti.media', 'pencariKos']);
        }
    }

    public function blokirAkun(int $userId): void
    {
        $user = User::query()
            ->with('pemilikProperti')
            ->where('role', '!=', 'superadmin')
            ->findOrFail($userId);

        DB::transaction(function () use ($user): void {
            $user->update([
                'status' => 'diblokir',
            ]);

            if ($user->role !== 'pemilik' || $user->pemilikProperti === null) {
                return;
            }

            $pemilikPropertiId = $user->pemilikProperti->id;
            $alasanSuspend = 'Properti ditangguhkan karena akun pemilik diblokir oleh super admin.';
            $payload = [
                'status' => 'suspend',
                'alasan_penolakan' => $alasanSuspend,
            ];

            try {
                Kosan::query()
                    ->where('pemilik_properti_id', $pemilikPropertiId)
                    ->update($payload);

                Kontrakan::query()
                    ->where('pemilik_properti_id', $pemilikPropertiId)
                    ->update($payload);
            } catch (QueryException) {
                $fallbackPayload = [
                    'status' => 'ditolak',
                    'alasan_penolakan' => $alasanSuspend,
                ];

                Kosan::query()
                    ->where('pemilik_properti_id', $pemilikPropertiId)
                    ->update($fallbackPayload);

                Kontrakan::query()
                    ->where('pemilik_properti_id', $pemilikPropertiId)
                    ->update($fallbackPayload);
            }
        });

        $this->dispatch(
            'appkonkos-toast',
            icon: 'success',
            title: 'Akun diblokir',
            text: 'Akun diblokir dan semua properti miliknya telah diturunkan dari publik.'
        );

        if ($this->detailUser?->id === $user->id) {
            $this->detailUser = $user->fresh(['pemilikProperti.media', 'pencariKos']);
        }
    }

    public function bukaBlokir(int $userId): void
    {
        $user = User::query()
            ->where('role', '!=', 'superadmin')
            ->findOrFail($userId);

        $user->update([
            'status' => 'aktif',
        ]);

        $this->dispatch(
            'appkonkos-toast',
            icon: 'success',
            title: 'Blokir dibuka',
            text: 'Akun pengguna berhasil diaktifkan kembali.'
        );
    }

    public function bukaDetail(int $userId): void
    {
        $this->detailUser = User::query()
            ->with(['pemilikProperti.media', 'pencariKos'])
            ->where('role', '!=', 'superadmin')
            ->findOrFail($userId);

        $this->showModalDetail = true;
    }

    public function tutupDetail(): void
    {
        $this->showModalDetail = false;
        $this->detailUser = null;
    }

    public function eksporData(): BinaryFileResponse
    {
        return Excel::download(
            new UsersExport($this->search, $this->filterStatus, $this->filterPeran),
            'Laporan-Pengguna-'.now()->format('Ymd-His').'.xlsx',
        );
    }

    public function getRoleBadgeClasses(User $user): string
    {
        return $user->role === 'pemilik'
            ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300'
            : 'bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-slate-200';
    }

    public function getRoleLabel(User $user): string
    {
        return $user->role === 'pemilik' ? 'Pemilik Kos' : 'Pencari Kos';
    }

    public function getStatusLabel(User $user): string
    {
        if ($user->status === 'diblokir') {
            return 'Diblokir';
        }

        if ($user->role === 'pemilik' && $user->pemilikProperti?->status_verifikasi === 'pending') {
            return 'Perlu Validasi';
        }

        return 'Aktif';
    }

    public function getStatusBadgeClasses(User $user): string
    {
        if ($user->status === 'diblokir') {
            return 'bg-red-50/80 text-red-700 border border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800';
        }

        if ($user->role === 'pemilik' && $user->pemilikProperti?->status_verifikasi === 'pending') {
            return 'bg-amber-50/80 text-amber-700 border border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-800';
        }

        return 'bg-green-50/80 text-green-700 border border-green-200 dark:bg-green-900/30 dark:text-green-300 dark:border-green-800';
    }

    public function getAvatarClasses(User $user): string
    {
        $palette = [
            'bg-amber-100 text-amber-700 border border-amber-200',
            'bg-green-100 text-green-700 border border-green-200',
            'bg-red-100 text-red-700 border border-red-200',
            'bg-blue-100 text-blue-700 border border-blue-200',
            'bg-purple-100 text-purple-700 border border-purple-200',
        ];

        return $palette[$user->id % count($palette)];
    }

    private function baseUsersQuery(): Builder
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
            });
    }
}
