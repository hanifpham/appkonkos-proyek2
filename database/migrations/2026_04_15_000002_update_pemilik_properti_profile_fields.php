<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('pemilik_properti', 'no_rekening') && ! Schema::hasColumn('pemilik_properti', 'nomor_rekening')) {
            Schema::table('pemilik_properti', function (Blueprint $table): void {
                $table->renameColumn('no_rekening', 'nomor_rekening');
            });
        }

        $hasAlamatDomisili = Schema::hasColumn('pemilik_properti', 'alamat_domisili');
        $hasNikKtp = Schema::hasColumn('pemilik_properti', 'nik_ktp');
        $hasNamaPemilikRekening = Schema::hasColumn('pemilik_properti', 'nama_pemilik_rekening');
        $hasStatusVerifikasi = Schema::hasColumn('pemilik_properti', 'status_verifikasi');

        Schema::table('pemilik_properti', function (Blueprint $table) use ($hasAlamatDomisili, $hasNikKtp, $hasNamaPemilikRekening, $hasStatusVerifikasi): void {
            if (! $hasAlamatDomisili) {
                $table->text('alamat_domisili')->nullable()->after('user_id');
            }

            if (! $hasNikKtp) {
                $table->string('nik_ktp')->nullable()->after('alamat_domisili');
            }

            if (! $hasNamaPemilikRekening) {
                $table->string('nama_pemilik_rekening')->nullable()->after('nomor_rekening');
            }

            if (! $hasStatusVerifikasi) {
                $table->enum('status_verifikasi', ['belum', 'pending', 'terverifikasi', 'ditolak'])->default('belum')->after('nama_pemilik_rekening');
            }
        });

        Schema::table('pemilik_properti', function (Blueprint $table): void {
            $table->string('nama_bank')->nullable()->change();
            $table->string('nomor_rekening')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('pemilik_properti')
            ->whereNull('nama_bank')
            ->update(['nama_bank' => '']);

        DB::table('pemilik_properti')
            ->whereNull('nomor_rekening')
            ->update(['nomor_rekening' => '']);

        Schema::table('pemilik_properti', function (Blueprint $table): void {
            $table->string('nama_bank')->nullable(false)->change();
            $table->string('nomor_rekening')->nullable(false)->change();

            $table->dropColumn([
                'alamat_domisili',
                'nik_ktp',
                'nama_pemilik_rekening',
                'status_verifikasi',
            ]);
        });

        if (Schema::hasColumn('pemilik_properti', 'nomor_rekening') && ! Schema::hasColumn('pemilik_properti', 'no_rekening')) {
            Schema::table('pemilik_properti', function (Blueprint $table): void {
                $table->renameColumn('nomor_rekening', 'no_rekening');
            });
        }
    }
};
