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
        Schema::table('kosan', function (Blueprint $table): void {
            if (! Schema::hasColumn('kosan', 'status')) {
                $table->enum('status', ['pending', 'menunggu', 'aktif', 'ditolak'])
                    ->default('pending')
                    ->after('longitude');
            }
        });

        Schema::table('kontrakan', function (Blueprint $table): void {
            if (! Schema::hasColumn('kontrakan', 'status')) {
                $table->enum('status', ['pending', 'menunggu', 'aktif', 'ditolak'])
                    ->default('pending')
                    ->after('harga_sewa_tahun');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kosan', function (Blueprint $table): void {
            if (Schema::hasColumn('kosan', 'status')) {
                $table->dropColumn('status');
            }
        });

        Schema::table('kontrakan', function (Blueprint $table): void {
            if (Schema::hasColumn('kontrakan', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
