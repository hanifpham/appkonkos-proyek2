<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailMobile extends VerifyEmail
{
    // Biarkan verificationUrl() default (pakai https://)
    // Hanya override tampilan emailnya saja

    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifikasi Email - AppKonkos')
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Aktifkan akun APPKONKOS Anda dengan menekan tombol verifikasi di bawah ini.')
            ->action('Verifikasi Email Sekarang', $verificationUrl)
            ->line('Link verifikasi ini berlaku selama 60 menit.')
            ->line('Jika Anda tidak merasa membuat akun APPKONKOS, abaikan email ini.')
            ->salutation('Salam, Tim APPKONKOS');
    }
}