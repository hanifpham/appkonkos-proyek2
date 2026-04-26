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
            if (! Schema::hasColumn('kosan', 'jenis_kos')) {
                $table->enum('jenis_kos', ['putra', 'putri', 'campur'])
                    ->nullable()
                    ->after('longitude');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kosan', function (Blueprint $table): void {
            if (Schema::hasColumn('kosan', 'jenis_kos')) {
                $table->dropColumn('jenis_kos');
            }
        });
    }
};
