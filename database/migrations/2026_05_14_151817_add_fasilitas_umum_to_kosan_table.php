<?php

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
        Schema::table('kosan', function (Blueprint $table) {
            $table->text('fasilitas_umum')->nullable()->after('peraturan_kos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kosan', function (Blueprint $table) {
            $table->dropColumn('fasilitas_umum');
        });
    }
};
