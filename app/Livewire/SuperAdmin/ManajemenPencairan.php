<?php

declare(strict_types=1);

namespace App\Livewire\SuperAdmin;

use App\Models\PencairanDana;
use App\Notifications\PencairanStatusNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class ManajemenPencairan extends Component
{
    use WithPagination;

    private const PENDING_WITHDRAWAL_STATUSES = ['pending', 'menunggu'];

    protected string $paginationTheme = 'tailwind';

    public string $search = '';

    public string $filterStatus = '';

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
        $listPencairan = PencairanDana::query()
            ->with('pemilikProperti.user')
            ->when(trim($this->search) !== '', function (Builder $query): void {
                $search = trim($this->search);
                $numericSearch = preg_replace('/\D+/', '', $search);

                $query->where(function (Builder $searchQuery) use ($search, $numericSearch): void {
                    $searchQuery
                        ->where('id', 'like', '%'.$search.'%')
                        ->orWhereHas('pemilikProperti.user', function (Builder $userQuery) use ($search): void {
                            $userQuery->where('name', 'like', '%'.$search.'%');
                        });

                    if (is_string($numericSearch) && $numericSearch !== '') {
                        $searchQuery->orWhere('id', 'like', '%'.$numericSearch.'%');
                    }
                });
            })
            ->when($this->filterStatus !== '', function (Builder $query): void {
                if ($this->filterStatus === 'pending') {
                    $query->whereIn('status', self::PENDING_WITHDRAWAL_STATUSES);

                    return;
                }

                $query->where('status', $this->filterStatus);
            })
            ->latest()
            ->paginate(10);

        $totalDicairkan = (int) PencairanDana::query()
            ->where('status', 'sukses')
            ->sum('nominal');

        $permintaanPending = (int) PencairanDana::query()
            ->whereIn('status', self::PENDING_WITHDRAWAL_STATUSES)
            ->count();

        $berhasilHariIni = (int) PencairanDana::query()
            ->where('status', 'sukses')
            ->whereDate('updated_at', today())
            ->sum('nominal');

        $rataRataNominal = (int) round((float) PencairanDana::query()
            ->where('status', 'sukses')
            ->avg('nominal'));

        return view('livewire.superadmin.manajemen-pencairan', compact(
            'listPencairan',
            'totalDicairkan',
            'permintaanPending',
            'berhasilHariIni',
            'rataRataNominal',
        ));
    }

    public function konfirmasiSetuju(int $id): void
    {
        $pencairan = PencairanDana::query()->findOrFail($id);

        if (! in_array($pencairan->status, self::PENDING_WITHDRAWAL_STATUSES, true)) {
            $this->dispatch(
                'appkonkos-toast',
                icon: 'warning',
                title: 'Pencairan tidak dapat diproses',
                text: 'Status pengajuan ini sudah berubah dan tidak menunggu persetujuan.'
            );

            return;
        }

        $this->dispatch(
            'swal:confirm-approve',
            title: 'Konfirmasi Eksekusi Pencairan',
            text: 'Pastikan Anda telah mentransfer dana ke rekening mitra. Lanjutkan?',
            confirm_button_text: 'Ya, Lanjutkan',
            confirm_button_color: '#0F4C81',
            method_name: 'prosesSetuju',
            method_args: [$id],
        );
    }

    public function prosesSetuju(int $id): void
    {
        $pencairan = PencairanDana::query()->findOrFail($id);

        if (! in_array($pencairan->status, self::PENDING_WITHDRAWAL_STATUSES, true)) {
            $this->dispatch(
                'appkonkos-toast',
                icon: 'warning',
                title: 'Pencairan tidak dapat diproses',
                text: 'Status pengajuan ini sudah berubah dan tidak menunggu persetujuan.'
            );

            return;
        }

        $pencairan->update([
            'status' => 'sukses',
            'catatan_admin' => null,
        ]);

        $this->notifyWithdrawalOwner($pencairan, new PencairanStatusNotification($pencairan, 'sukses'));

        $this->dispatch(
            'appkonkos-toast',
            icon: 'success',
            title: 'Pencairan disetujui',
            text: 'Permintaan pencairan dana berhasil disetujui.'
        );
    }

    public function konfirmasiTolak(int $id): void
    {
        $pencairan = PencairanDana::query()->findOrFail($id);

        if (! in_array($pencairan->status, self::PENDING_WITHDRAWAL_STATUSES, true)) {
            $this->dispatch(
                'appkonkos-toast',
                icon: 'warning',
                title: 'Pencairan tidak dapat ditolak',
                text: 'Status pengajuan ini sudah berubah dan tidak menunggu persetujuan.'
            );

            return;
        }

        $this->dispatch(
            'swal:confirm-reject',
            title: 'Alasan Penolakan',
            text: 'Tuliskan alasan penolakan pencairan dana ini sebelum melanjutkan.',
            input_placeholder: 'Tuliskan alasan penolakan di sini...',
            confirm_button_text: 'Tolak Sekarang',
            confirm_button_color: '#EF4444',
            method_name: 'prosesTolak',
            method_args: [$id],
        );
    }

    public function prosesTolak(int $id, string $alasan): void
    {
        $alasan = trim($alasan);

        if ($alasan === '') {
            $this->dispatch(
                'appkonkos-toast',
                icon: 'warning',
                title: 'Alasan wajib diisi',
                text: 'Alasan penolakan pencairan dana tidak boleh kosong.'
            );

            return;
        }

        $pencairan = PencairanDana::query()->findOrFail($id);

        if (! in_array($pencairan->status, self::PENDING_WITHDRAWAL_STATUSES, true)) {
            $this->dispatch(
                'appkonkos-toast',
                icon: 'warning',
                title: 'Pencairan tidak dapat ditolak',
                text: 'Status pengajuan ini sudah berubah dan tidak menunggu persetujuan.'
            );

            return;
        }

        $pencairan->update([
            'status' => 'ditolak',
            'catatan_admin' => $alasan,
        ]);

        $this->notifyWithdrawalOwner($pencairan, new PencairanStatusNotification($pencairan, 'ditolak', $alasan));

        $this->dispatch(
            'appkonkos-toast',
            icon: 'info',
            title: 'Pencairan ditolak',
            text: 'Permintaan pencairan dana berhasil ditolak.'
        );
    }

    public function getWithdrawalDisplayId(PencairanDana $pencairan): string
    {
        return '#WD-'.str_pad((string) $pencairan->id, 5, '0', STR_PAD_LEFT);
    }

    public function getStatusLabel(string $status): string
    {
        return match ($status) {
            'pending', 'menunggu' => 'Menunggu',
            'sukses' => 'Sukses',
            'ditolak' => 'Ditolak',
            'disetujui' => 'Diproses',
            default => Str::headline($status !== '' ? $status : 'unknown'),
        };
    }

    protected function notifyWithdrawalOwner(PencairanDana $pencairan, PencairanStatusNotification $notification): void
    {
        $owner = $pencairan->loadMissing('pemilikProperti.user')->pemilikProperti?->user;

        if ($owner !== null) {
            $owner->notify($notification);
        }
    }
}
