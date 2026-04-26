<?php

declare(strict_types=1);

namespace App\Livewire\SuperAdmin;

use App\Exports\PropertiesExport;
use App\Models\Kontrakan;
use App\Models\Kosan;
use App\Notifications\PropertiDisetujuiNotification;
use App\Notifications\PropertiDitolakNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ModerasiProperti extends Component
{
    use WithPagination;

    private const PENDING_STATUSES = ['pending', 'menunggu'];

    protected string $paginationTheme = 'tailwind';

    public string $search = '';

    public string $filterTipe = '';

    public string $filterStatus = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterTipe(): void
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
        $listProperti = $this->getListProperti();
        $statistik = $this->getStatistik();

        return view('livewire.superadmin.moderasi-properti', array_merge(
            $statistik,
            ['listProperti' => $listProperti],
        ));
    }

    public function exportData(): BinaryFileResponse
    {
        return Excel::download(
            new PropertiesExport($this->search, $this->filterStatus, $this->filterTipe),
            'Laporan-Properti-'.now()->format('Ymd-His').'.xlsx',
        );
    }

    public function verifikasiProperti(string $tipe, int $id): void
    {
        $properti = $this->findProperti($tipe, $id);

        $properti->update([
            'status' => 'aktif',
            'alasan_penolakan' => null,
        ]);

        $this->notifyPropertyOwner($properti, new PropertiDisetujuiNotification($properti));

        $this->dispatch(
            'appkonkos-toast',
            icon: 'success',
            title: 'Properti diverifikasi',
            text: 'Status properti berhasil diubah menjadi aktif.'
        );
    }

    public function konfirmasiTolak(string $tipe, int $id): void
    {
        $properti = $this->findProperti($tipe, $id);

        if (! $this->isPendingStatus($properti->status)) {
            $this->dispatch(
                'appkonkos-toast',
                icon: 'warning',
                title: 'Properti tidak dapat ditolak',
                text: 'Status properti ini sudah berubah dan tidak lagi menunggu moderasi.'
            );

            return;
        }

        $this->dispatch(
            'swal:confirm-reject',
            title: 'Alasan Penolakan',
            text: 'Tuliskan alasan penolakan properti ini sebelum melanjutkan.',
            input_placeholder: 'Tuliskan alasan penolakan di sini...',
            confirm_button_text: 'Tolak Sekarang',
            confirm_button_color: '#EF4444',
            method_name: 'prosesTolak',
            method_args: [$tipe, $id],
        );
    }

    public function prosesTolak(string $tipe, int $id, string $alasan): void
    {
        $alasan = trim($alasan);

        if ($alasan === '') {
            $this->dispatch(
                'appkonkos-toast',
                icon: 'warning',
                title: 'Alasan wajib diisi',
                text: 'Alasan penolakan properti tidak boleh kosong.'
            );

            return;
        }

        $properti = $this->findProperti($tipe, $id);

        if (! $this->isPendingStatus($properti->status)) {
            $this->dispatch(
                'appkonkos-toast',
                icon: 'warning',
                title: 'Properti tidak dapat ditolak',
                text: 'Status properti ini sudah berubah dan tidak lagi menunggu moderasi.'
            );

            return;
        }

        $properti->update([
            'status' => 'ditolak',
            'alasan_penolakan' => $alasan,
        ]);

        $this->notifyPropertyOwner($properti, new PropertiDitolakNotification($properti, $alasan));

        $this->dispatch(
            'appkonkos-toast',
            icon: 'success',
            title: 'Properti ditolak',
            text: 'Status properti berhasil diubah menjadi ditolak.'
        );
    }

    public function konfirmasiTakedown(string $tipe, int $id): void
    {
        $properti = $this->findProperti($tipe, $id);

        if ($properti->status !== 'aktif') {
            $this->dispatch(
                'appkonkos-toast',
                icon: 'warning',
                title: 'Properti tidak dapat ditakedown',
                text: 'Hanya properti dengan status aktif yang bisa ditakedown.'
            );

            return;
        }

        $this->dispatch(
            'swal:confirm-approve',
            title: 'Takedown Properti',
            text: 'Properti aktif akan diturunkan dari daftar publik. Lanjutkan?',
            confirm_button_text: 'Ya, Takedown',
            confirm_button_color: '#B91C1C',
            method_name: 'takedownProperti',
            method_args: [$tipe, $id],
        );
    }

    public function takedownProperti(string $tipe, int $id): void
    {
        $properti = $this->findProperti($tipe, $id);

        if ($properti->status !== 'aktif') {
            $this->dispatch(
                'appkonkos-toast',
                icon: 'warning',
                title: 'Properti tidak dapat ditakedown',
                text: 'Hanya properti dengan status aktif yang bisa ditakedown.'
            );

            return;
        }

        $properti->update([
            'status' => 'ditolak',
            'alasan_penolakan' => 'Properti ditakedown oleh super admin.',
        ]);

        $this->notifyPropertyOwner(
            $properti,
            new PropertiDitolakNotification(
                $properti,
                'Properti ditakedown oleh super admin.',
                'Properti Ditangguhkan',
                'Properti Anda diturunkan sementara dari daftar publik dan memerlukan peninjauan ulang.',
            ),
        );

        $this->dispatch(
            'appkonkos-toast',
            icon: 'success',
            title: 'Properti ditakedown',
            text: 'Properti aktif berhasil diturunkan dari daftar publik.'
        );
    }

    public function getPropertyDisplayId(object $properti): string
    {
        return 'PROP-'.str_pad((string) $properti->id, 4, '0', STR_PAD_LEFT);
    }

    public function getPemilikInitial(object $properti): string
    {
        $namaPemilik = trim((string) ($properti->nama_pemilik ?? ''));

        if ($namaPemilik === '') {
            return '--';
        }

        $potonganNama = collect(preg_split('/\s+/', $namaPemilik) ?: [])
            ->filter()
            ->take(2)
            ->map(static fn (string $bagian): string => Str::upper(Str::substr($bagian, 0, 1)))
            ->implode('');

        if (Str::length($potonganNama) >= 2) {
            return Str::substr($potonganNama, 0, 2);
        }

        return Str::upper(Str::substr($namaPemilik, 0, 2));
    }

    public function getPemilikPhotoUrl(object $properti): string
    {
        $user = $properti->pemilikProperti?->user;

        if ($user?->profile_photo_path) {
            $baseUrl = rtrim(request()->getBaseUrl(), '/');

            return ($baseUrl === '' ? '' : $baseUrl).'/storage/'.ltrim($user->profile_photo_path, '/')
                .'?v='.($user->updated_at?->timestamp ?? now()->timestamp);
        }

        if ($user !== null && method_exists($user, 'getFirstMediaUrl')) {
            $mediaUrl = $user->getFirstMediaUrl('profile_photos');

            if (is_string($mediaUrl) && $mediaUrl !== '') {
                return $mediaUrl;
            }
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($user?->name ?? 'User').'&color=113C7A&background=EBF4FF';
    }

    public function getKategoriLabel(object $properti): string
    {
        if ($properti->tipe === 'kontrakan') {
            return 'KONTRAKAN';
        }

        return match ($properti->jenis_kos) {
            'putra' => 'KOS PUTRA',
            'putri' => 'KOS PUTRI',
            'campur' => 'KOS CAMPUR',
            default => 'KOS',
        };
    }

    public function getKategoriBadgeClasses(object $properti): string
    {
        if ($properti->tipe === 'kontrakan') {
            return 'bg-teal-100 text-teal-800 dark:bg-teal-900/30 dark:text-teal-300';
        }

        return match ($properti->jenis_kos) {
            'putra' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
            'putri' => 'bg-pink-100 text-pink-800 dark:bg-pink-900/30 dark:text-pink-300',
            'campur' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300',
            default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
        };
    }

    public function getHargaSuffix(object $properti): string
    {
        return $properti->tipe === 'kontrakan' ? '/ thn' : '/ bln';
    }

    public function getStatusLabel(?string $status): string
    {
        return match ($status) {
            'pending', 'menunggu' => 'MENUNGGU',
            'aktif' => 'AKTIF',
            'ditolak' => 'DITOLAK',
            default => Str::upper((string) $status),
        };
    }

    public function getStatusBadgeClasses(?string $status): string
    {
        return match ($status) {
            'pending', 'menunggu' => 'bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-800',
            'aktif' => 'bg-green-50 text-green-700 border border-green-200 dark:bg-green-900/30 dark:text-green-300 dark:border-green-800',
            'ditolak' => 'bg-red-50 text-red-700 border border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800',
            default => 'bg-gray-100 text-gray-700 border border-gray-200 dark:bg-slate-800 dark:text-gray-300 dark:border-slate-700',
        };
    }

    public function isPendingStatus(?string $status): bool
    {
        return in_array($status, self::PENDING_STATUSES, true);
    }

    /**
     * @return array<string, int>
     */
    protected function getStatistik(): array
    {
        $today = now()->toDateString();

        return [
            'menungguVerifikasi' => (int) DB::table('kosan')->whereIn('status', self::PENDING_STATUSES)->count()
                + (int) DB::table('kontrakan')->whereIn('status', self::PENDING_STATUSES)->count(),
            'disetujuiHariIni' => (int) DB::table('kosan')
                ->where('status', 'aktif')
                ->whereDate('updated_at', $today)
                ->count()
                + (int) DB::table('kontrakan')
                    ->where('status', 'aktif')
                    ->whereDate('updated_at', $today)
                    ->count(),
            'totalAktif' => (int) DB::table('kosan')->where('status', 'aktif')->count()
                + (int) DB::table('kontrakan')->where('status', 'aktif')->count(),
            'totalDitolak' => (int) DB::table('kosan')->where('status', 'ditolak')->count()
                + (int) DB::table('kontrakan')->where('status', 'ditolak')->count(),
        ];
    }

    protected function getListProperti(): LengthAwarePaginator
    {
        $listProperti = $this->propertyCollection()
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

        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;

        return new LengthAwarePaginator(
            $listProperti->forPage($page, $perPage)->values(),
            $listProperti->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ],
        );
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

    protected function findProperti(string $tipe, int $id): Kosan|Kontrakan
    {
        return match ($tipe) {
            'kosan' => Kosan::query()->findOrFail($id),
            'kontrakan' => Kontrakan::query()->findOrFail($id),
            default => throw new NotFoundHttpException,
        };
    }

    protected function notifyPropertyOwner(
        Kosan|Kontrakan $properti,
        PropertiDisetujuiNotification|PropertiDitolakNotification $notification,
    ): void {
        $owner = $properti->loadMissing('pemilikProperti.user')->pemilikProperti?->user;

        if ($owner !== null) {
            $owner->notify($notification);
        }
    }
}
