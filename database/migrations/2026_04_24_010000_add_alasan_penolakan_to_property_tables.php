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
            if (! Schema::hasColumn('kosan', 'alasan_penolakan')) {
                $table->text('alasan_penolakan')->nullable()->after('status');
            }
        });

        Schema::table('kontrakan', function (Blueprint $table): void {
            if (! Schema::hasColumn('kontrakan', 'alasan_penolakan')) {
                $table->text('alasan_penolakan')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kosan', function (Blueprint $table): void {
            if (Schema::hasColumn('kosan', 'alasan_penolakan')) {
                $table->dropColumn('alasan_penolakan');
            }
        });

        Schema::table('kontrakan', function (Blueprint $table): void {
            if (Schema::hasColumn('kontrakan', 'alasan_penolakan')) {
                $table->dropColumn('alasan_penolakan');
            }
        });
    }
};
