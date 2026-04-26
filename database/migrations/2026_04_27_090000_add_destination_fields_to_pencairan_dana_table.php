<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pencairan_dana', function (Blueprint $table): void {
            $table->string('nama_bank_tujuan')->nullable()->after('nominal');
            $table->string('nomor_rekening_tujuan')->nullable()->after('nama_bank_tujuan');
            $table->string('atas_nama_tujuan')->nullable()->after('nomor_rekening_tujuan');
        });
    }

    public function down(): void
    {
        Schema::table('pencairan_dana', function (Blueprint $table): void {
            $table->dropColumn([
                'nama_bank_tujuan',
                'nomor_rekening_tujuan',
                'atas_nama_tujuan',
            ]);
        });
    }
};
