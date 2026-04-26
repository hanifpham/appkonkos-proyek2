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
        Schema::table('booking', function (Blueprint $table): void {
            $table->foreignId('kamar_id')
                ->nullable()
                ->constrained('kamar')
                ->nullOnDelete();
        });

        $this->backfillLegacyKosanData();
        $this->migrateLegacyBookingsToRooms();

        Schema::table('booking', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('kosan_id');
        });

        Schema::table('kosan', function (Blueprint $table): void {
            $table->dropColumn([
                'harga_sewa_bulan',
                'fasilitas',
                'sisa_kamar',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kosan', function (Blueprint $table): void {
            $table->unsignedInteger('harga_sewa_bulan')->default(0);
            $table->text('fasilitas')->nullable();
            $table->unsignedInteger('sisa_kamar')->default(0);
        });

        $this->restoreLegacyKosanColumns();

        Schema::table('booking', function (Blueprint $table): void {
            $table->foreignId('kosan_id')
                ->nullable()
                ->constrained('kosan')
                ->nullOnDelete();
        });

        $this->restoreLegacyBookingKosanRelation();

        Schema::table('booking', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('kamar_id');
        });
    }

    private function backfillLegacyKosanData(): void
    {
        $legacyKosan = DB::table('kosan')
            ->select([
                'id',
                'harga_sewa_bulan',
                'fasilitas',
                'sisa_kamar',
            ])
            ->get();

        foreach ($legacyKosan as $kosan) {
            $existingLegacyTypeId = DB::table('tipe_kamar')
                ->where('kosan_id', $kosan->id)
                ->where('nama_tipe', 'Tipe Legacy')
                ->value('id');

            $legacyTypeId = $existingLegacyTypeId ?? DB::table('tipe_kamar')->insertGetId([
                'kosan_id' => $kosan->id,
                'nama_tipe' => 'Tipe Legacy',
                'harga_per_bulan' => (int) $kosan->harga_sewa_bulan,
                'fasilitas_tipe' => (string) $kosan->fasilitas,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $legacyBookings = DB::table('booking')
                ->where('kosan_id', $kosan->id)
                ->orderBy('created_at')
                ->orderBy('id')
                ->get();

            $targetRoomCount = max(
                1,
                (int) $kosan->sisa_kamar,
                $legacyBookings->count(),
            );

            $existingRoomsCount = DB::table('kamar')
                ->where('tipe_kamar_id', $legacyTypeId)
                ->count();

            for ($index = $existingRoomsCount + 1; $index <= $targetRoomCount; $index++) {
                DB::table('kamar')->insert([
                    'tipe_kamar_id' => $legacyTypeId,
                    'nomor_kamar' => sprintf('LEGACY-%02d', $index),
                    'status_kamar' => 'tersedia',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function migrateLegacyBookingsToRooms(): void
    {
        $legacyTypeIds = DB::table('tipe_kamar')
            ->where('nama_tipe', 'Tipe Legacy')
            ->pluck('id', 'kosan_id');

        foreach ($legacyTypeIds as $kosanId => $tipeKamarId) {
            $roomIds = DB::table('kamar')
                ->where('tipe_kamar_id', $tipeKamarId)
                ->orderBy('id')
                ->pluck('id')
                ->values();

            $legacyBookings = DB::table('booking')
                ->where('kosan_id', (int) $kosanId)
                ->whereNull('kamar_id')
                ->orderBy('created_at')
                ->orderBy('id')
                ->get();

            foreach ($legacyBookings as $index => $booking) {
                $roomId = $roomIds->get($index);

                if ($roomId === null) {
                    continue;
                }

                DB::table('booking')
                    ->where('id', $booking->id)
                    ->update(['kamar_id' => $roomId]);

                DB::table('kamar')
                    ->where('id', $roomId)
                    ->update(['status_kamar' => 'dihuni']);
            }
        }
    }

    private function restoreLegacyKosanColumns(): void
    {
        $kosanRows = DB::table('kosan')->select('id')->get();

        foreach ($kosanRows as $kosan) {
            $tipeKamar = DB::table('tipe_kamar')
                ->where('kosan_id', $kosan->id)
                ->orderByRaw("CASE WHEN nama_tipe = 'Tipe Legacy' THEN 0 ELSE 1 END")
                ->orderBy('id')
                ->first();

            $jumlahKamar = 0;

            if ($tipeKamar !== null) {
                $jumlahKamar = DB::table('kamar')
                    ->where('tipe_kamar_id', $tipeKamar->id)
                    ->count();
            }

            DB::table('kosan')
                ->where('id', $kosan->id)
                ->update([
                    'harga_sewa_bulan' => (int) ($tipeKamar->harga_per_bulan ?? 0),
                    'fasilitas' => (string) ($tipeKamar->fasilitas_tipe ?? ''),
                    'sisa_kamar' => $jumlahKamar,
                ]);
        }
    }

    private function restoreLegacyBookingKosanRelation(): void
    {
        $bookings = DB::table('booking')
            ->whereNotNull('kamar_id')
            ->get();

        foreach ($bookings as $booking) {
            $kosanId = DB::table('kamar')
                ->join('tipe_kamar', 'tipe_kamar.id', '=', 'kamar.tipe_kamar_id')
                ->where('kamar.id', $booking->kamar_id)
                ->value('tipe_kamar.kosan_id');

            DB::table('booking')
                ->where('id', $booking->id)
                ->update(['kosan_id' => $kosanId]);
        }
    }
};
