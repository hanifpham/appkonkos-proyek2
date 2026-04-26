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
        Schema::create('tipe_kamar', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('kosan_id')->constrained('kosan')->cascadeOnDelete();
            $table->string('nama_tipe');
            $table->unsignedInteger('harga_per_bulan');
            $table->text('fasilitas_tipe');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipe_kamar');
    }
};
