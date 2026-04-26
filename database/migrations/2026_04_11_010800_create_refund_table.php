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
        Schema::create('refund', function (Blueprint $table): void {
            $table->id();
            $table->foreignUuid('booking_id')->constrained('booking')->cascadeOnDelete();
            $table->foreignId('pembayaran_id')->constrained('pembayaran')->cascadeOnDelete();
            $table->unsignedInteger('nominal_refund');
            $table->text('alasan_refund');
            $table->enum('status_refund', ['pending', 'diproses', 'selesai', 'ditolak'])->default('pending');
            $table->string('bukti_transfer_refund')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refund');
    }
};
