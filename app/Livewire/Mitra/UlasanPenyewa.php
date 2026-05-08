<?php

declare(strict_types=1);

namespace App\Livewire\Mitra;

use App\Models\PemilikProperti;
use App\Models\Ulasan;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class UlasanPenyewa extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public string $search = '';

    public string $filter = 'Terbaru';

    public ?int $replyingTo = null;

    public string $balasanText = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilter(): void
    {
        $this->resetPage();
    }

    #[Layout('layouts.mitra.utama')]
    public function render(): View
    {
        $ownedUlasanQuery = $this->ownedUlasanQuery();

        $rataRataRating = round((float) ((clone $ownedUlasanQuery)->avg('rating') ?? 0), 1);
        $totalUlasan = (clone $ownedUlasanQuery)->count();
        $belumDibalas = (clone $ownedUlasanQuery)
            ->whereNull('balasan_pemilik')
            ->count();
        $ulasanPuas = (clone $ownedUlasanQuery)
            ->where('rating', '>=', 4)
            ->count();
        $kepuasanPenyewa = $totalUlasan > 0
            ? (int) round(($ulasanPuas / $totalUlasan) * 100)
            : 0;

        $ulasanQuery = (clone $ownedUlasanQuery)
            ->with([
                'booking.pencariKos.user',
                'booking.kamar.tipeKamar.kosan',
                'booking.kontrakan',
            ]);

        $search = trim($this->search);

        if ($search !== '') {
            $ulasanQuery->where(function (Builder $query) use ($search): void {
                $query
                    ->whereHas('booking.pencariKos.user', function (Builder $userQuery) use ($search): void {
                        $userQuery->where('name', 'like', '%'.$search.'%');
                    })
                    ->orWhereHas('booking.kamar.tipeKamar.kosan', function (Builder $kosanQuery) use ($search): void {
                        $kosanQuery->where('nama_properti', 'like', '%'.$search.'%');
                    })
                    ->orWhereHas('booking.kontrakan', function (Builder $kontrakanQuery) use ($search): void {
                        $kontrakanQuery->where('nama_properti', 'like', '%'.$search.'%');
                    });
            });
        }

        match ($this->filter) {
            'Rating Tertinggi' => $ulasanQuery
                ->orderByDesc('rating')
                ->latest(),
            'Rating Terendah' => $ulasanQuery
                ->orderBy('rating')
                ->latest(),
            'Belum Dibalas' => $ulasanQuery
                ->whereNull('balasan_pemilik')
                ->latest(),
            default => $ulasanQuery->latest(),
        };

        $ulasanList = $ulasanQuery->paginate(10);

        return view('livewire.mitra.ulasan-penyewa', [
            'ulasanList' => $ulasanList,
            'rataRataRating' => $rataRataRating,
            'totalUlasan' => $totalUlasan,
            'belumDibalas' => $belumDibalas,
            'kepuasanPenyewa' => $kepuasanPenyewa,
            'filterOptions' => $this->filterOptions(),
        ]);
    }

    public function bukaFormBalas(int $id): void
    {
        $ulasan = $this->ownedUlasan($id);

        $this->replyingTo = $ulasan->id;
        $this->balasanText = '';
        $this->resetValidation();
    }

    /**
     * @throws ValidationException
     */
    public function simpanBalasan(): void
    {
        if ($this->replyingTo === null) {
            return;
        }

        $this->balasanText = trim($this->balasanText);

        try {
            $validated = $this->validate([
                'balasanText' => ['required', 'string'],
            ], [
                'balasanText.required' => 'Balasan wajib diisi.',
            ], [
                'balasanText' => 'balasan',
            ]);
        } catch (ValidationException $exception) {
            $this->dispatch(
                'appkonkos-validasi-error',
                title: 'Balasan belum lengkap',
                text: 'Isi balasan ulasan terlebih dahulu sebelum mengirim.'
            );

            throw $exception;
        }

        $ulasan = $this->ownedUlasan($this->replyingTo);

        $ulasan->update([
            'balasan_pemilik' => $validated['balasanText'],
        ]);

        $this->replyingTo = null;
        $this->balasanText = '';
        $this->resetValidation();

        $this->dispatch(
            'appkonkos-toast',
            icon: 'success',
            title: 'Balasan tersimpan',
            text: 'Balasan untuk ulasan penyewa berhasil dikirim.'
        );
    }

    public function getPenyewaInitials(Ulasan $ulasan): string
    {
        if ($ulasan->is_anonymous) {
            return 'A'; // Inisial untuk Anonim
        }
        
        $name = $ulasan->booking?->pencariKos?->user?->name ?? 'Penyewa';
        $compactName = preg_replace('/\s+/', '', $name) ?? $name;

        return Str::upper(Str::substr($compactName, 0, 2));
    }

    public function getAvatarClasses(Ulasan $ulasan): string
    {
        $palettes = [
            'bg-blue-100 text-blue-600 border-blue-200/50 dark:bg-blue-900/40 dark:text-blue-300',
            'bg-purple-100 text-purple-600 border-purple-200/50 dark:bg-purple-900/40 dark:text-purple-300',
            'bg-emerald-100 text-emerald-600 border-emerald-200/50 dark:bg-emerald-900/40 dark:text-emerald-300',
            'bg-orange-100 text-orange-600 border-orange-200/50 dark:bg-orange-900/40 dark:text-orange-300',
            'bg-pink-100 text-pink-600 border-pink-200/50 dark:bg-pink-900/40 dark:text-pink-300',
            'bg-yellow-100 text-yellow-600 border-yellow-200/50 dark:bg-yellow-900/40 dark:text-yellow-300',
        ];

        return $palettes[($ulasan->id - 1) % count($palettes)];
    }

    public function getNamaProperti(Ulasan $ulasan): string
    {
        $booking = $ulasan->booking;

        if ($booking?->kamar !== null) {
            $namaKosan = $booking->kamar->tipeKamar?->kosan?->nama_properti ?? 'Kosan';
            $nomorKamar = $booking->kamar->nomor_kamar ?? '-';

            return $namaKosan.' - Kamar '.$nomorKamar;
        }

        return $booking?->kontrakan?->nama_properti ?? 'Kontrakan';
    }

    public function getIkonProperti(Ulasan $ulasan): string
    {
        return $ulasan->booking?->kamar !== null ? 'apartment' : 'home';
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

    protected function ownedUlasanQuery(): Builder
    {
        $pemilikId = $this->pemilikProperti()->id;

        return Ulasan::query()
            ->whereHas('booking', function (Builder $bookingQuery) use ($pemilikId): void {
                $bookingQuery->where(function (Builder $propertyQuery) use ($pemilikId): void {
                    $propertyQuery
                        ->whereHas('kontrakan', function (Builder $kontrakanQuery) use ($pemilikId): void {
                            $kontrakanQuery->where('pemilik_properti_id', $pemilikId);
                        })
                        ->orWhereHas('kamar.tipeKamar.kosan', function (Builder $kosanQuery) use ($pemilikId): void {
                            $kosanQuery->where('pemilik_properti_id', $pemilikId);
                        });
                });
            });
    }

    protected function ownedUlasan(int $id): Ulasan
    {
        return $this->ownedUlasanQuery()->findOrFail($id);
    }

    /**
     * @return list<string>
     */
    protected function filterOptions(): array
    {
        return [
            'Terbaru',
            'Rating Tertinggi',
            'Rating Terendah',
            'Belum Dibalas',
        ];
    }
}
