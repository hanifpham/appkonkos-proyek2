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
        Schema::create('pembayaran', function (Blueprint $table): void {
            $table->id();
            $table->foreignUuid('booking_id')->constrained('booking')->cascadeOnDelete();
            $table->string('metode_bayar')->nullable();
            $table->dateTime('waktu_bayar')->nullable();
            $table->unsignedInteger('nominal_bayar');
            $table->string('status_bayar')->default('pending');
            $table->string('url_struk_pdf')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
