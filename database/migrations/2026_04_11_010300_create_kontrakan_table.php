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
        Schema::create('kontrakan', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('pemilik_properti_id')->constrained('pemilik_properti')->cascadeOnDelete();
            $table->string('nama_properti');
            $table->text('alamat_lengkap');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->unsignedInteger('harga_sewa_tahun');
            $table->text('fasilitas');
            $table->text('peraturan_kontrakan');
            $table->unsignedInteger('sisa_kamar')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontrakan');
    }
};
