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
        Schema::create('ulasan', function (Blueprint $table): void {
            $table->id();
            $table->foreignUuid('booking_id')->constrained('booking')->cascadeOnDelete();
            $table->foreignId('pencari_kos_id')->constrained('pencari_kos')->cascadeOnDelete();
            $table->foreignId('kosan_id')->nullable()->constrained('kosan')->nullOnDelete();
            $table->foreignId('kontrakan_id')->nullable()->constrained('kontrakan')->nullOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('komentar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};
