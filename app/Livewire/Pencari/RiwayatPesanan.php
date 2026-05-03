<?php

namespace App\Livewire\Pencari;

use App\Models\Booking;
use App\Models\Pembayaran;
use App\Models\Refund;
use App\Services\MidtransService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

use Livewire\WithPagination;

class RiwayatPesanan extends Component
{
    use WithPagination;

    public string $filterStatus = 'semua';

    public bool $showRefundModal = false;
    public ?string $refundBookingId = null;
    public string $alasanRefund = '';

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function setFilter(string $status): void
    {
        $this->filterStatus = $status;
        $this->resetPage();
    }

    public function batalkanPesanan(string $id): void
    {
        $pencariKos = Auth::user()->pencariKos;
        if (! $pencariKos) {
            return;
        }

        $booking = Booking::where('id', $id)
            ->where('pencari_kos_id', $pencariKos->id)
            ->first();

        if (! $booking) {
            session()->flash('error', 'Pesanan tidak ditemukan.');
            return;
        }

        $booking->update(['status_booking' => 'batal']);
        
        if ($booking->pembayaran) {
            $booking->pembayaran->update(['status_bayar' => 'cancel']);
        }

        session()->flash('success', 'Pesanan berhasil dibatalkan.');
    }

    public function hapusPesanan(string $id): void
    {
        $pencariKos = Auth::user()->pencariKos;
        if (! $pencariKos) {
            return;
        }

        $booking = Booking::where('id', $id)
            ->where('pencari_kos_id', $pencariKos->id)
            ->first();

        if (! $booking) {
            session()->flash('error', 'Pesanan tidak ditemukan.');
            return;
        }

        if ($booking->status_booking !== 'batal' && $this->getDisplayStatus($booking) !== 'dibatalkan') {
            session()->flash('error', 'Hanya pesanan yang telah dibatalkan yang dapat dihapus.');
            return;
        }

        $booking->delete();

        session()->flash('success', 'Riwayat pesanan berhasil dihapus.');
    }

    public function lanjutkanPembayaran(string $bookingId, MidtransService $midtrans): void
    {
        $pencariKos = Auth::user()->pencariKos;
        if (! $pencariKos) {
            return;
        }

        $booking = Booking::where('id', $bookingId)
            ->where('pencari_kos_id', $pencariKos->id)
            ->with('pembayaran')
            ->first();

        if (! $booking) {
            session()->flash('error', 'Pesanan tidak ditemukan.');
            return;
        }

        try {
            $payment = $midtrans->createOrRefreshTransaction($booking);
        } catch (RuntimeException $e) {
            session()->flash('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
            return;
        }

        if (! filled($payment->snap_token)) {
            session()->flash('error', 'Token pembayaran tidak dapat dibuat. Silakan coba lagi.');
            return;
        }

        $this->dispatch('pay-midtrans', token: $payment->snap_token);
    }

    public function openRefundModal(string $bookingId): void
    {
        $this->refundBookingId = $bookingId;
        $this->alasanRefund = '';
        $this->showRefundModal = true;
    }

    public function closeRefundModal(): void
    {
        $this->showRefundModal = false;
        $this->refundBookingId = null;
        $this->alasanRefund = '';
    }

    public function submitRefund(): void
    {
        $this->validate([
            'alasanRefund' => 'required|string|min:10|max:500',
        ]);

        $pencariKos = Auth::user()->pencariKos;
        if (! $pencariKos) {
            return;
        }

        $booking = Booking::where('id', $this->refundBookingId)
            ->where('pencari_kos_id', $pencariKos->id)
            ->with('pembayaran')
            ->first();

        if (! $booking || ! $booking->pembayaran) {
            session()->flash('error', 'Booking tidak ditemukan.');
            $this->closeRefundModal();
            return;
        }

        if ($booking->refund) {
            session()->flash('error', 'Pengajuan refund sudah pernah dilakukan untuk pesanan ini.');
            $this->closeRefundModal();
            return;
        }

        $nominalRefund = (int) round($booking->total_biaya * 0.75);

        Refund::create([
            'booking_id'     => $booking->id,
            'pembayaran_id'  => $booking->pembayaran->id,
            'nominal_refund' => $nominalRefund,
            'alasan_refund'  => $this->alasanRefund,
            'status_refund'  => 'pending',
        ]);

        $booking->update(['status_booking' => 'refund']);

        session()->flash('success', 'Pengajuan refund berhasil dikirim. Tim APPKONKOS akan meninjau permintaan Anda.');
        $this->closeRefundModal();
    }

    /**
     * Determine the display status of a booking for UI rendering.
     */
    public function getDisplayStatus(Booking $booking): string
    {
        if ($booking->status_booking === 'batal') {
            return 'dibatalkan';
        }

        if ($booking->refund) {
            return 'refund';
        }

        $pembayaran = $booking->pembayaran;

        if (! $pembayaran || $pembayaran->isPending()) {
            return 'menunggu_pembayaran';
        }

        if ($pembayaran->isFailed()) {
            return 'dibatalkan';
        }

        if ($pembayaran->normalizedStatus() === 'refund') {
            return 'refund';
        }

        if ($pembayaran->isSuccessful()) {
            if ($booking->tgl_selesai_sewa && now()->greaterThan($booking->tgl_selesai_sewa)) {
                return 'selesai';
            }
            return 'berhasil';
        }

        return 'menunggu_pembayaran';
    }

    private function buildQuery()
    {
        $pencariKos = Auth::user()->pencariKos;

        if (! $pencariKos) {
            return Booking::whereNull('id');
        }

        $query = Booking::where('pencari_kos_id', $pencariKos->id)
            ->with([
                'pembayaran',
                'kamar.tipeKamar.kosan.pemilikProperti.user',
                'kamar.tipeKamar.kosan.media',
                'kamar.tipeKamar.media',
                'kontrakan.pemilikProperti.user',
                'kontrakan.media',
                'ulasan',
                'refund',
            ])
            ->latest();

        switch ($this->filterStatus) {
            case 'menunggu_pembayaran':
                $query->where(function ($q) {
                    $q->whereDoesntHave('pembayaran')
                      ->orWhereHas('pembayaran', function ($pq) {
                          $pq->whereIn('status_bayar', Pembayaran::PENDING_STATUSES);
                      });
                })->where('status_booking', '!=', 'batal');
                break;

            case 'aktif_berhasil':
                $query->whereHas('pembayaran', function ($pq) {
                    $pq->whereIn('status_bayar', Pembayaran::SUCCESS_STATUSES);
                });
                break;

            case 'dibatalkan_refund':
                $query->where(function ($q) {
                    $q->whereHas('pembayaran', function ($pq) {
                        $pq->whereIn('status_bayar', array_merge(
                            Pembayaran::FAILED_STATUSES,
                            Pembayaran::REFUND_STATUSES
                        ));
                    })->orWhereHas('refund')->orWhere('status_booking', 'batal');
                });
                break;
        }

        return $query;
    }

    #[Layout('layouts.public')]
    public function render()
    {
        return view('livewire.pencari.riwayat-pesanan', [
            'user'     => Auth::user(),
            'bookings' => $this->buildQuery()->paginate(5),
        ]);
    }
}
