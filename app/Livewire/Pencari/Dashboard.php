<?php

declare(strict_types=1);

namespace App\Livewire\Pencari;

use App\Models\Booking;
use App\Models\PencariKos;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboard extends Component
{
    /**
     * @var Collection<int, Booking>
     */
    public Collection $bookings;

    public function mount(): void
    {
        $this->bookings = $this->pencariKos()
            ->bookings()
            ->with([
                'pembayaran',
                'kamar.tipeKamar.kosan.pemilikProperti.user',
                'kontrakan.pemilikProperti.user',
            ])
            ->latest()
            ->get();
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.pencari.dashboard');
    }

    public function getPropertyName(Booking $booking): string
    {
        if ($booking->kamar !== null) {
            $kosan = $booking->kamar->tipeKamar?->kosan?->nama_properti ?? 'Kosan';
            $nomorKamar = $booking->kamar->nomor_kamar ?? '-';

            return sprintf('%s - Kamar %s', $kosan, $nomorKamar);
        }

        return $booking->kontrakan?->nama_properti ?? 'Kontrakan';
    }

    public function getOwnerName(Booking $booking): string
    {
        if ($booking->kamar !== null) {
            return $booking->kamar->tipeKamar?->kosan?->pemilikProperti?->user?->name ?? 'Pemilik properti';
        }

        return $booking->kontrakan?->pemilikProperti?->user?->name ?? 'Pemilik properti';
    }

    public function getPaymentStatusLabel(Booking $booking): string
    {
        $payment = $booking->pembayaran;

        if ($payment === null) {
            return 'Belum Dibuat';
        }

        return match (true) {
            $payment->isSuccessful() => 'Lunas',
            $payment->isFailed() => 'Gagal',
            $payment->normalizedStatus() === 'refund' => 'Refund',
            default => 'Menunggu',
        };
    }

    public function getPaymentStatusClasses(Booking $booking): string
    {
        $payment = $booking->pembayaran;

        if ($payment?->isSuccessful()) {
            return 'bg-emerald-50 text-emerald-700 border border-emerald-200';
        }

        if ($payment?->isFailed()) {
            return 'bg-rose-50 text-rose-700 border border-rose-200';
        }

        if ($payment?->normalizedStatus() === 'refund') {
            return 'bg-slate-100 text-slate-700 border border-slate-200';
        }

        return 'bg-amber-50 text-amber-700 border border-amber-200';
    }

    public function getBookingStatusLabel(Booking $booking): string
    {
        if ($booking->status_booking === 'pending' && ($booking->pembayaran?->isSuccessful() ?? false)) {
            return 'Menunggu Konfirmasi Pemilik';
        }

        return match ($booking->status_booking) {
            'lunas' => 'Dikonfirmasi Pemilik',
            'selesai' => 'Selesai',
            'batal' => 'Dibatalkan',
            default => 'Menunggu Pembayaran',
        };
    }

    public function canPay(Booking $booking): bool
    {
        return $booking->status_booking === 'pending'
            && ! ($booking->pembayaran?->isSuccessful() ?? false);
    }

    protected function pencariKos(): PencariKos
    {
        /** @var User|null $user */
        $user = Auth::user();
        $pencariKos = $user?->pencariKos;

        if ($pencariKos === null) {
            abort(403, 'Unauthorized action.');
        }

        return $pencariKos;
    }
}
