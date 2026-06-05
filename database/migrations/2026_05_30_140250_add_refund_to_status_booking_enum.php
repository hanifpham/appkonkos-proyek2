<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE booking
            MODIFY COLUMN status_booking
            ENUM('pending', 'lunas', 'batal', 'refund')
            NOT NULL DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE booking
            MODIFY COLUMN status_booking
            ENUM('pending', 'lunas', 'batal')
            NOT NULL DEFAULT 'pending'
        ");
    }
};