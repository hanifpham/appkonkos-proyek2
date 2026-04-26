<?php

declare(strict_types=1);

namespace App\Livewire\Mitra;

use App\Models\Pembayaran;
use App\Models\PemilikProperti;
use App\Models\PencairanDana;
use App\Models\Setting;
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
    public int|string|null $nominalPencairan = null;

    public string $namaBank = '';

    public string $nomorRekening = '';

    public string $atasNama = '';

    public string $searchRiwayat = '';

    public string $filterRiwayatStatus = 'all';

    public ?PencairanDana $pencairanAktif = null;

    public float $komisiPlatformPersen = 0.0;

    public int $totalPendapatanBersih = 0;

    public int $totalDanaDitarik = 0;

    public int $saldoTersedia = 0;

    public int $dalamProses = 0;

    public function mount(): void
    {
        $this->hydrateFormFromOwner();
        $this->refreshState();
    }

    #[Layout('layouts.mitra.utama')]
    public function render(): View
    {
        $this->refreshState();

        return view('livewire.mitra.keuangan', [
            'riwayatPenarikan' => $this->riwayatPenarikan(),
            'riwayatStatusOptions' => $this->riwayatStatusOptions(),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function konfirmasiPencairan(): void
    {
        $this->refreshState();

        if ($this->pencairanAktif !== null) {
            $this->dispatch(
                'appkonkos-toast',
                icon: 'warning',
                title: 'Pengajuan masih aktif',
                text: 'Selesaikan pengajuan pencairan sebelumnya sebelum membuat pengajuan baru.'
            );

            return;
        }

        $this->validatePencairanRequest();

        $this->dispatch(
            'swal:confirm-approve',
            title: 'Konfirmasi Pengajuan Pencairan',
            text: 'Apakah data rekening sudah benar? Proses ini akan ditinjau oleh Super Admin.',
            confirm_button_text: 'Ya, Ajukan Sekarang',
            confirm_button_color: '#0F4C81',
            method_name: 'mintaPencairan',
            method_args: [],
        );
    }

    /**
     * @throws ValidationException
     */
    public function mintaPencairan(): void
    {
        $this->refreshState();

        if ($this->pencairanAktif !== null) {
            $this->dispatch(
                'appkonkos-toast',
                icon: 'warning',
                title: 'Pengajuan masih aktif',
                text: 'Selesaikan pengajuan pencairan sebelumnya sebelum membuat pengajuan baru.'
            );

            return;
        }

        $this->validatePencairanRequest();

        $pemilikProperti = $this->pemilikProperti()->loadMissing('user');

        $pemilikProperti->update([
            'nama_bank' => $this->namaBank,
            'nomor_rekening' => $this->nomorRekening,
            'nama_pemilik_rekening' => $this->atasNama,
        ]);

        PencairanDana::query()->create([
            'pemilik_properti_id' => $pemilikProperti->id,
            'nominal' => (int) $this->nominalPencairan,
            'nama_bank_tujuan' => $this->namaBank,
            'nomor_rekening_tujuan' => $this->nomorRekening,
            'atas_nama_tujuan' => $this->atasNama,
            'status' => 'pending',
            'catatan_admin' => null,
        ]);

        $this->nominalPencairan = null;
        $this->refreshState();

        $this->dispatch(
            'appkonkos-toast',
            icon: 'success',
            title: 'Pengajuan terkirim',
            text: 'Permintaan pencairan dana berhasil diajukan dan sedang menunggu peninjauan Super Admin.'
        );
    }

    public function resetForm(): void
    {
        $this->nominalPencairan = null;
        $this->hydrateFormFromOwner();
    }

    public function getStatusBadgeClasses(string $status): string
    {
        return match ($status) {
            'pending', 'menunggu' => 'bg-amber-100 text-amber-700 border border-amber-200 dark:bg-amber-950/30 dark:text-amber-300 dark:border-amber-900/40',
            'disetujui' => 'bg-blue-100 text-blue-700 border border-blue-200 dark:bg-blue-950/30 dark:text-blue-300 dark:border-blue-900/40',
            'sukses' => 'bg-emerald-100 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/30 dark:text-emerald-300 dark:border-emerald-900/40',
            'ditolak' => 'bg-rose-100 text-rose-700 border border-rose-200 dark:bg-rose-950/30 dark:text-rose-300 dark:border-rose-900/40',
            default => 'bg-slate-100 text-slate-700 border border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
        };
    }

    public function getStatusLabel(string $status): string
    {
        return match ($status) {
            'pending', 'menunggu' => 'Menunggu',
            'disetujui' => 'Disetujui',
            'sukses' => 'Sukses',
            'ditolak' => 'Ditolak',
            default => ucfirst($status),
        };
    }

    public function getWithdrawalDisplayId(PencairanDana $riwayat): string
    {
        return '#WD-'.str_pad((string) $riwayat->id, 5, '0', STR_PAD_LEFT);
    }

    public function getWithdrawalBankName(PencairanDana $riwayat): string
    {
        return $riwayat->nama_bank_tujuan
            ?: ($riwayat->pemilikProperti?->nama_bank ?? 'Bank');
    }

    public function getWithdrawalBankMeta(PencairanDana $riwayat): string
    {
        $rekening = $riwayat->nomor_rekening_tujuan
            ?: ($riwayat->pemilikProperti?->nomor_rekening ?? '');
        $atasNama = $riwayat->atas_nama_tujuan
            ?: ($riwayat->pemilikProperti?->nama_pemilik_rekening ?: $riwayat->pemilikProperti?->user?->name);

        if ($rekening === '') {
            return '-';
        }

        $masked = $this->maskAccountNumber($rekening);

        return $atasNama !== null && $atasNama !== ''
            ? $masked.' a.n '.$atasNama
            : $masked;
    }

    public function getRiwayatFilterLabel(): string
    {
        return $this->riwayatStatusOptions()[$this->filterRiwayatStatus] ?? 'Semua Status';
    }

    public function formatRupiah(int $amount): string
    {
        return 'Rp '.number_format($amount, 0, ',', '.');
    }

    protected function refreshState(): void
    {
        $pemilikId = $this->pemilikProperti()->id;

        $this->komisiPlatformPersen = $this->resolveKomisiPlatformPersen();
        $this->totalPendapatanBersih = $this->hitungPendapatanBersih($pemilikId);
        $this->totalDanaDitarik = $this->hitungTotalDanaDitarik();
        $this->saldoTersedia = max(0, $this->totalPendapatanBersih - $this->totalDanaDitarik);
        $this->dalamProses = $this->hitungDalamProses();
        $this->pencairanAktif = $this->ownedWithdrawalsQuery()
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

    protected function ownedWithdrawalsQuery(): Builder
    {
        return PencairanDana::query()
            ->whereHas('pemilikProperti', function (Builder $query): void {
                $query->where('user_id', auth()->id());
            });
    }

    protected function hitungPendapatanBersih(int $pemilikId): int
    {
        $totalSettlement = (int) Pembayaran::query()
            ->whereIn('status_bayar', Pembayaran::successStatuses())
            ->whereHas('booking', fn (Builder $query): Builder => $this->applyOwnedBookingConstraint($query, $pemilikId))
            ->sum('nominal_bayar');

        $nilaiKomisi = (int) round($totalSettlement * ($this->komisiPlatformPersen / 100));

        return max(0, $totalSettlement - $nilaiKomisi);
    }

    protected function hitungTotalDanaDitarik(): int
    {
        return (int) $this->ownedWithdrawalsQuery()
            ->where('status', 'sukses')
            ->sum('nominal');
    }

    protected function hitungDalamProses(): int
    {
        return (int) $this->ownedWithdrawalsQuery()
            ->whereIn('status', ['pending', 'disetujui'])
            ->sum('nominal');
    }

    protected function resolveKomisiPlatformPersen(): float
    {
        $value = Setting::query()
            ->where('key', Setting::KEY_PLATFORM_COMMISSION)
            ->value('value');

        if ($value === null || trim((string) $value) === '' || ! is_numeric($value)) {
            return 0.0;
        }

        return (float) $value;
    }

    /**
     * @return EloquentCollection<int, PencairanDana>
     */
    protected function riwayatPenarikan(): EloquentCollection
    {
        $query = $this->ownedWithdrawalsQuery()
            ->with('pemilikProperti.user')
            ->latest();

        $search = trim($this->searchRiwayat);

        if ($search !== '') {
            $query->where(function (Builder $builder) use ($search): void {
                $builder
                    ->where('id', 'like', '%'.$search.'%')
                    ->orWhere('nama_bank_tujuan', 'like', '%'.$search.'%')
                    ->orWhere('nomor_rekening_tujuan', 'like', '%'.$search.'%')
                    ->orWhere('atas_nama_tujuan', 'like', '%'.$search.'%');
            });
        }

        if ($this->filterRiwayatStatus !== 'all') {
            $query->where('status', $this->filterRiwayatStatus);
        }

        return $query->get();
    }

    /**
     * @return array<string, string>
     */
    protected function riwayatStatusOptions(): array
    {
        return [
            'all' => 'Semua Status',
            'pending' => 'Pending',
            'disetujui' => 'Disetujui',
            'sukses' => 'Sukses',
            'ditolak' => 'Ditolak',
        ];
    }

    /**
     * @throws ValidationException
     */
    protected function validatePencairanRequest(): void
    {
        if ($this->saldoTersedia <= 0) {
            $exception = ValidationException::withMessages([
                'nominalPencairan' => 'Saldo tersedia belum mencukupi untuk pencairan dana.',
            ]);

            $this->dispatchValidationErrorToast($exception);

            throw $exception;
        }

        try {
            $this->validate(
                $this->rules(),
                $this->messages(),
                $this->validationAttributes()
            );
        } catch (ValidationException $exception) {
            $this->dispatchValidationErrorToast($exception);

            throw $exception;
        }
    }

    /**
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        return [
            'namaBank' => ['required', 'string', 'max:100'],
            'nomorRekening' => ['required', 'string', 'max:50'],
            'atasNama' => ['required', 'string', 'max:100'],
            'nominalPencairan' => ['required', 'integer', 'min:50000', 'max:'.$this->saldoTersedia],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        return [
            'namaBank' => 'nama bank',
            'nomorRekening' => 'nomor rekening',
            'atasNama' => 'atas nama',
            'nominalPencairan' => 'nominal pencairan',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'namaBank.required' => 'Nama bank wajib diisi.',
            'nomorRekening.required' => 'Nomor rekening wajib diisi.',
            'atasNama.required' => 'Atas nama rekening wajib diisi.',
            'nominalPencairan.required' => 'Nominal pencairan wajib diisi.',
            'nominalPencairan.integer' => 'Nominal pencairan harus berupa angka bulat.',
            'nominalPencairan.min' => 'Nominal pencairan minimal Rp 50.000.',
            'nominalPencairan.max' => 'Nominal pencairan tidak boleh melebihi saldo tersedia.',
        ];
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

    protected function hydrateFormFromOwner(): void
    {
        $pemilik = $this->pemilikProperti()->loadMissing('user');

        $this->namaBank = (string) ($pemilik->nama_bank ?? '');
        $this->nomorRekening = (string) ($pemilik->nomor_rekening ?? '');
        $this->atasNama = (string) ($pemilik->nama_pemilik_rekening ?: $pemilik->user?->name ?? '');
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
