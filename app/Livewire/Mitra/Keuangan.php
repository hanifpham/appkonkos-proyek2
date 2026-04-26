<?php

declare(strict_types=1);

namespace App\Livewire\Mitra;

use App\Models\Pembayaran;
use App\Models\PemilikProperti;
use App\Models\PencairanDana;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Keuangan extends Component
{
    public int|string|null $jumlah_penarikan = null;

    public string $rekening_tujuan = '';

    public ?PencairanDana $penarikanAktif = null;

    public string $searchRiwayat = '';

    public string $filterRiwayatStatus = 'all';

    public function mount(): void
    {
        $this->refreshState();
    }

    #[Layout('layouts.mitra.utama')]
    public function render(): View
    {
        $pemilikProperti = $this->pemilikProperti()->loadMissing('user');

        $this->refreshState();

        return view('livewire.mitra.keuangan', [
            'saldoTersedia' => $this->saldoTersedia($pemilikProperti->id),
            'dalamProses' => $this->dalamProses($pemilikProperti->id),
            'totalBerhasil' => $this->totalBerhasil($pemilikProperti->id),
            'riwayatPenarikan' => $this->riwayatPenarikan($pemilikProperti),
            'rekeningOptions' => $this->rekeningOptions($pemilikProperti),
            'riwayatStatusOptions' => $this->riwayatStatusOptions(),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function ajukanPenarikan(): void
    {
        $this->refreshState();

        if ($this->penarikanAktif !== null) {
            $this->dispatch(
                'appkonkos-toast',
                icon: 'warning',
                title: 'Pengajuan masih aktif',
                text: 'Selesaikan pengajuan penarikan sebelumnya sebelum membuat yang baru.'
            );

            return;
        }

        $pemilikProperti = $this->pemilikProperti()->loadMissing('user');
        $saldoTersedia = $this->saldoTersedia($pemilikProperti->id);

        try {
            $this->validate([
                'jumlah_penarikan' => ['required', 'integer', 'min:50000', 'max:'.$saldoTersedia],
                'rekening_tujuan' => ['required', 'string'],
            ], $this->messages(), $this->validationAttributes());
        } catch (ValidationException $exception) {
            $this->dispatchValidationErrorToast($exception);

            throw $exception;
        }

        $validOptions = array_keys($this->rekeningOptions($pemilikProperti));

        if (! in_array($this->rekening_tujuan, $validOptions, true)) {
            $exception = ValidationException::withMessages([
                'rekening_tujuan' => 'Rekening tujuan tidak valid atau belum tersedia.',
            ]);

            $this->dispatchValidationErrorToast($exception);

            throw $exception;
        }

        PencairanDana::query()->create([
            'pemilik_properti_id' => $pemilikProperti->id,
            'nominal' => (int) $this->jumlah_penarikan,
            'status' => 'pending',
            'catatan_admin' => null,
        ]);

        $this->resetForm();
        $this->refreshState();

        $this->dispatch(
            'appkonkos-toast',
            icon: 'success',
            title: 'Pengajuan terkirim',
            text: 'Permintaan penarikan dana berhasil diajukan dan sedang menunggu verifikasi.'
        );
    }

    public function resetForm(): void
    {
        $this->jumlah_penarikan = null;
        $this->rekening_tujuan = '';
    }

    public function saldoTersedia(?int $pemilikId = null): int
    {
        $pemilikId ??= $this->pemilikProperti()->id;

        $pendapatanLunas = Pembayaran::query()
            ->where('status_bayar', 'lunas')
            ->whereHas('booking', fn (Builder $query): Builder => $this->applyOwnedBookingConstraint($query, $pemilikId))
            ->sum('nominal_bayar');

        $totalTertahanAtauTercairkan = PencairanDana::query()
            ->where('pemilik_properti_id', $pemilikId)
            ->whereIn('status', ['pending', 'disetujui', 'sukses'])
            ->sum('nominal');

        return max(0, (int) $pendapatanLunas - (int) $totalTertahanAtauTercairkan);
    }

    public function dalamProses(?int $pemilikId = null): int
    {
        $pemilikId ??= $this->pemilikProperti()->id;

        return (int) PencairanDana::query()
            ->where('pemilik_properti_id', $pemilikId)
            ->where('status', 'pending')
            ->sum('nominal');
    }

    public function totalBerhasil(?int $pemilikId = null): int
    {
        $pemilikId ??= $this->pemilikProperti()->id;

        return (int) PencairanDana::query()
            ->where('pemilik_properti_id', $pemilikId)
            ->where('status', 'sukses')
            ->sum('nominal');
    }

    public function getProgressWidth(): int
    {
        return match ($this->penarikanAktif?->status) {
            'pending' => 33,
            'disetujui' => 66,
            default => 0,
        };
    }

    public function getStepCircleClass(string $step): string
    {
        return match ($this->getStepState($step)) {
            'active' => 'step-active',
            'completed' => 'step-completed',
            default => 'step-inactive',
        };
    }

    public function getStepTitleClass(string $step): string
    {
        return match ($this->getStepState($step)) {
            'active' => 'text-[#0F4C81] dark:text-blue-400',
            'completed' => 'text-emerald-600 dark:text-emerald-300',
            default => 'text-gray-400 dark:text-gray-600',
        };
    }

    public function getStepSubtitleClass(string $step): string
    {
        return match ($this->getStepState($step)) {
            'active', 'completed' => 'text-gray-500 dark:text-gray-400',
            default => 'text-gray-400/50 dark:text-gray-600/50',
        };
    }

    public function getStatusBadgeClasses(string $status): string
    {
        return match ($status) {
            'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200 dark:border-amber-800',
            'disetujui' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800',
            'sukses' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800',
            'ditolak' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800',
            default => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700',
        };
    }

    public function getStatusLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'Menunggu Persetujuan',
            'disetujui' => 'Diproses Midtrans',
            'sukses' => 'Selesai',
            'ditolak' => 'Ditolak',
            default => ucfirst($status),
        };
    }

    public function getStatusProgressCount(string $status): int
    {
        return match ($status) {
            'pending' => 2,
            'disetujui' => 3,
            'sukses' => 4,
            'ditolak' => 1,
            default => 0,
        };
    }

    public function getStatusProgressDotClass(string $status, int $dotNumber): string
    {
        if ($dotNumber > $this->getStatusProgressCount($status)) {
            return 'bg-gray-300 dark:bg-gray-600';
        }

        return match ($status) {
            'ditolak' => 'bg-red-500',
            'sukses' => 'bg-emerald-500',
            default => 'bg-[#0F4C81] dark:bg-blue-400',
        };
    }

    public function getWithdrawalDisplayId(PencairanDana $riwayat): string
    {
        return '#WD-'.str_pad((string) $riwayat->id, 5, '0', STR_PAD_LEFT);
    }

    public function getWithdrawalBankName(PencairanDana $riwayat): string
    {
        return $riwayat->pemilikProperti?->nama_bank ?? 'Bank';
    }

    public function getWithdrawalBankMeta(PencairanDana $riwayat): string
    {
        $rekening = $riwayat->pemilikProperti?->nomor_rekening;
        $ownerName = $riwayat->pemilikProperti?->nama_pemilik_rekening
            ?? $riwayat->pemilikProperti?->user?->name;

        if ($rekening === null || $rekening === '') {
            return '-';
        }

        $masked = $this->maskAccountNumber($rekening);

        return $ownerName !== null && $ownerName !== ''
            ? $masked.' a.n '.$ownerName
            : $masked;
    }

    public function getRiwayatFilterLabel(): string
    {
        return $this->riwayatStatusOptions()[$this->filterRiwayatStatus] ?? 'Semua';
    }

    protected function refreshState(): void
    {
        $this->penarikanAktif = $this->pemilikProperti()
            ->pencairanDana()
            ->whereIn('status', ['pending', 'disetujui'])
            ->latest()
            ->first();
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

    /**
     * @return EloquentCollection<int, PencairanDana>
     */
    protected function riwayatPenarikan(PemilikProperti $pemilikProperti): EloquentCollection
    {
        $query = $pemilikProperti->pencairanDana()
            ->with('pemilikProperti.user')
            ->latest();

        $search = trim($this->searchRiwayat);

        if ($search !== '') {
            $query->where('id', 'like', '%'.$search.'%');
        }

        if ($this->filterRiwayatStatus !== 'all') {
            $query->where('status', $this->filterRiwayatStatus);
        }

        return $query->get();
    }

    /**
     * @return array<string, string>
     */
    protected function rekeningOptions(PemilikProperti $pemilikProperti): array
    {
        $pemilikProperti->loadMissing('user');

        $namaBank = trim((string) $pemilikProperti->nama_bank);
        $nomorRekening = trim((string) $pemilikProperti->nomor_rekening);
        $namaPemilikRekening = trim((string) ($pemilikProperti->nama_pemilik_rekening ?: $pemilikProperti->user?->name));

        if ($namaBank === '' || $nomorRekening === '') {
            return [];
        }

        $label = $namaBank.' - '.$nomorRekening.' ('.$namaPemilikRekening.')';

        return [$label => $label];
    }

    /**
     * @return array<string, string>
     */
    protected function riwayatStatusOptions(): array
    {
        return [
            'all' => 'Semua',
            'pending' => 'Pending',
            'disetujui' => 'Disetujui',
            'sukses' => 'Sukses',
            'ditolak' => 'Ditolak',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        return [
            'jumlah_penarikan' => 'jumlah penarikan',
            'rekening_tujuan' => 'rekening tujuan',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'jumlah_penarikan.required' => 'Jumlah penarikan wajib diisi.',
            'jumlah_penarikan.integer' => 'Jumlah penarikan harus berupa angka bulat.',
            'jumlah_penarikan.min' => 'Jumlah penarikan minimal Rp 50.000.',
            'jumlah_penarikan.max' => 'Jumlah penarikan tidak boleh melebihi saldo tersedia.',
            'rekening_tujuan.required' => 'Rekening tujuan wajib dipilih.',
        ];
    }

    protected function getStepState(string $step): string
    {
        $currentStep = match ($this->penarikanAktif?->status) {
            'pending' => 2,
            'disetujui' => 3,
            default => 1,
        };

        $stepOrder = [
            'input' => 1,
            'approval' => 2,
            'midtrans' => 3,
            'done' => 4,
        ];

        $position = $stepOrder[$step] ?? 1;

        if ($position < $currentStep) {
            return 'completed';
        }

        if ($position === $currentStep) {
            return 'active';
        }

        return 'inactive';
    }

    protected function applyOwnedBookingConstraint(Builder $query, int $pemilikId): Builder
    {
        return $query->where(function (Builder $nestedQuery) use ($pemilikId): void {
            $nestedQuery
                ->whereHas('kontrakan', function (Builder $kontrakanQuery) use ($pemilikId): void {
                    $kontrakanQuery->where('pemilik_properti_id', $pemilikId);
                })
                ->orWhereHas('kamar.tipeKamar.kosan', function (Builder $kosanQuery) use ($pemilikId): void {
                    $kosanQuery->where('pemilik_properti_id', $pemilikId);
                });
        });
    }

    protected function maskAccountNumber(string $accountNumber): string
    {
        $length = strlen($accountNumber);

        if ($length <= 6) {
            return $accountNumber;
        }

        return substr($accountNumber, 0, 4).'***'.substr($accountNumber, -3);
    }

    protected function dispatchValidationErrorToast(ValidationException $exception): void
    {
        $message = collect($exception->errors())
            ->flatten()
            ->first();

        $this->dispatch(
            'appkonkos-validasi-error',
            title: 'Validasi gagal',
            text: is_string($message) && $message !== ''
                ? $message
                : 'Ada form yang belum diisi dengan benar. Silakan periksa kembali.'
        );
    }
}
