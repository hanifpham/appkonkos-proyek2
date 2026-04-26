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
        Schema::create('booking', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignId('pencari_kos_id')->constrained('pencari_kos')->cascadeOnDelete();
            $table->foreignId('kosan_id')->nullable()->constrained('kosan')->nullOnDelete();
            $table->foreignId('kontrakan_id')->nullable()->constrained('kontrakan')->nullOnDelete();
            $table->date('tgl_mulai_sewa');
            $table->date('tgl_selesai_sewa');
            $table->unsignedInteger('total_biaya');
            $table->enum('status_booking', ['pending', 'lunas', 'batal'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
