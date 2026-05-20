<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Booking;
use App\Models\Pembayaran;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StrukPembayaranMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Booking $booking,
        public Pembayaran $payment,
    ) {}

    public function envelope(): Envelope
    {
        $orderId = $this->payment->midtrans_order_id ?: '#TRX-'.strtoupper(substr((string) $this->booking->id, 0, 8));

        return new Envelope(
            subject: 'Struk Pembayaran APPKONKOS - '.$orderId,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.struk-pembayaran',
            with: [
                'booking' => $this->booking,
                'payment' => $this->payment,
                'pembayaran' => $this->payment,
            ],
        );
    }
}
