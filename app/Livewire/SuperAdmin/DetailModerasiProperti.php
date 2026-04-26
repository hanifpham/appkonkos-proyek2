<?php

declare(strict_types=1);

namespace App\Livewire\SuperAdmin;

use App\Models\Kontrakan;
use App\Models\Kosan;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DetailModerasiProperti extends Component
{
    public Kosan|Kontrakan $properti;

    public string $tipe = '';

    public function mount(string $tipe, string $id): void
    {
        $this->tipe = $tipe;
        $this->properti = match ($tipe) {
            'kosan' => Kosan::query()
                ->with(['media', 'pemilikProperti.user', 'tipeKamar.media', 'tipeKamar.kamar'])
                ->findOrFail((int) $id),
            'kontrakan' => Kontrakan::query()
                ->with(['media', 'pemilikProperti.user'])
                ->findOrFail((int) $id),
            default => throw new NotFoundHttpException(),
        };
    }

    #[Layout('layouts.mitra.utama')]
    public function render(): View
    {
        return view('livewire.superadmin.detail-moderasi-properti');
    }

    public function getPropertyDisplayId(): string
    {
        return 'PROP-'.str_pad((string) $this->properti->id, 4, '0', STR_PAD_LEFT);
    }

    public function getKategoriLabel(): string
    {
        if ($this->tipe === 'kontrakan') {
            return 'Kontrakan';
        }

        return match ($this->properti->jenis_kos) {
            'putra' => 'Kos Putra',
            'putri' => 'Kos Putri',
            'campur' => 'Kos Campur',
            default => 'Kos',
        };
    }

    public function getHargaLabel(): string
    {
        if ($this->properti instanceof Kontrakan) {
            return 'Rp '.number_format($this->properti->harga_sewa_tahun, 0, ',', '.').' / tahun';
        }

        $hargaTermurah = $this->properti->tipeKamar->min('harga_per_bulan');

        if ($hargaTermurah === null) {
            return 'Harga belum diatur';
        }

        return 'Rp '.number_format((int) $hargaTermurah, 0, ',', '.').' / bulan';
    }

    public function getStatusLabel(): string
    {
        return Str::upper((string) ($this->properti->status ?? '-'));
    }

    public function getPropertyPhotoUrl(): string
    {
        return $this->properti->getMediaDisplayUrl('foto_properti');
    }

    public function getInteriorPhotoUrl(object $tipeKamar): string
    {
        if (! method_exists($tipeKamar, 'getMediaDisplayUrl')) {
            return '';
        }

        return $tipeKamar->getMediaDisplayUrl('foto_interior');
    }

    public function getCoordinateLabel(): string
    {
        return trim((string) $this->properti->latitude) !== '' && trim((string) $this->properti->longitude) !== ''
            ? $this->properti->latitude.', '.$this->properti->longitude
            : '-';
    }

    public function getKamarSummary(): string
    {
        if (! $this->properti instanceof Kosan) {
            return '-';
        }

        $totalKamar = $this->properti->tipeKamar->flatMap->kamar->count();
        $kamarTersedia = $this->properti->tipeKamar
            ->flatMap->kamar
            ->where('status_kamar', 'tersedia')
            ->count();

        return $kamarTersedia.' tersedia dari '.$totalKamar.' kamar';
    }

    public function getPemilikPhotoUrl(): string
    {
        $user = $this->properti->pemilikProperti?->user;

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
}
