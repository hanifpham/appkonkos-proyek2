<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pemilik_properti', function (Blueprint $table): void {
            $table->boolean('notif_whatsapp_pesanan_baru')->default(true)->after('status_verifikasi');
            $table->boolean('notif_whatsapp_pembayaran_sukses')->default(true)->after('notif_whatsapp_pesanan_baru');
            $table->boolean('notif_whatsapp_ulasan_baru')->default(true)->after('notif_whatsapp_pembayaran_sukses');
            $table->boolean('notif_email_pesanan_baru')->default(true)->after('notif_whatsapp_ulasan_baru');
            $table->boolean('notif_email_pembayaran_sukses')->default(true)->after('notif_email_pesanan_baru');
            $table->boolean('notif_email_ulasan_baru')->default(true)->after('notif_email_pembayaran_sukses');
            $table->boolean('notif_aplikasi_pesanan_baru')->default(true)->after('notif_email_ulasan_baru');
            $table->boolean('notif_aplikasi_pembayaran_sukses')->default(true)->after('notif_aplikasi_pesanan_baru');
            $table->boolean('notif_aplikasi_ulasan_baru')->default(true)->after('notif_aplikasi_pembayaran_sukses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemilik_properti', function (Blueprint $table): void {
            $table->dropColumn([
                'notif_whatsapp_pesanan_baru',
                'notif_whatsapp_pembayaran_sukses',
                'notif_whatsapp_ulasan_baru',
                'notif_email_pesanan_baru',
                'notif_email_pembayaran_sukses',
                'notif_email_ulasan_baru',
                'notif_aplikasi_pesanan_baru',
                'notif_aplikasi_pembayaran_sukses',
                'notif_aplikasi_ulasan_baru',
            ]);
        });
    }
};
