<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'status')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->string('status')->default('aktif')->after('status_akun');
            });
        }

        DB::table('users')
            ->whereNull('status')
            ->update([
                'status' => DB::raw("CASE WHEN status_akun = 1 THEN 'aktif' ELSE 'diblokir' END"),
            ]);
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'status')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->dropColumn('status');
            });
        }
    }
};
