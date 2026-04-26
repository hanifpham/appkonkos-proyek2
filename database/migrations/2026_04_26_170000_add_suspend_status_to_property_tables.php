<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE kosan MODIFY status ENUM('pending', 'menunggu', 'aktif', 'ditolak', 'suspend') NOT NULL DEFAULT 'pending'");
        DB::statement("ALTER TABLE kontrakan MODIFY status ENUM('pending', 'menunggu', 'aktif', 'ditolak', 'suspend') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE kosan MODIFY status ENUM('pending', 'menunggu', 'aktif', 'ditolak') NOT NULL DEFAULT 'pending'");
        DB::statement("ALTER TABLE kontrakan MODIFY status ENUM('pending', 'menunggu', 'aktif', 'ditolak') NOT NULL DEFAULT 'pending'");
    }
};
