<?php

declare(strict_types=1);

namespace Tests\Feature\Database;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class KosanRoomRefactorMigrationTest extends TestCase
{
    public function test_refactor_migration_backfills_legacy_kosan_and_booking_data(): void
    {
        $databasePath = storage_path('framework/testing/backfill-'.Str::uuid().'.sqlite');

        if (! is_dir(dirname($databasePath))) {
            mkdir(dirname($databasePath), 0777, true);
        }

        touch($databasePath);

        config()->set('database.connections.migration_backfill', [
            'driver' => 'sqlite',
            'database' => $databasePath,
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]);

        DB::purge('migration_backfill');

        try {
            foreach ($this->legacyMigrations() as $migrationPath) {
                Artisan::call('migrate', [
                    '--database' => 'migration_backfill',
                    '--path' => $migrationPath,
                    '--realpath' => true,
                    '--force' => true,
                ]);
            }

            $connection = DB::connection('migration_backfill');

            $pemilikUserId = $connection->table('users')->insertGetId([
                'name' => 'Pemilik Legacy',
                'email' => 'pemilik-legacy@example.com',
                'no_telepon' => '08123456789',
                'role' => 'pemilik',
                'status_akun' => 1,
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $pencariUserId = $connection->table('users')->insertGetId([
                'name' => 'Pencari Legacy',
                'email' => 'pencari-legacy@example.com',
                'no_telepon' => '08987654321',
                'role' => 'pencari',
                'status_akun' => 1,
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $pemilikId = $connection->table('pemilik_properti')->insertGetId([
                'user_id' => $pemilikUserId,
                'nama_bank' => 'Bank Test',
                'no_rekening' => '1234567890',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $pencariKosId = $connection->table('pencari_kos')->insertGetId([
                'user_id' => $pencariUserId,
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2000-01-01',
                'pekerjaan' => 'Mahasiswa',
                'nama_instansi' => 'Polindra',
                'kota_asal' => 'Indramayu',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $kosanId = $connection->table('kosan')->insertGetId([
                'pemilik_properti_id' => $pemilikId,
                'nama_properti' => 'Kos Legacy',
                'alamat_lengkap' => 'Jl. Legacy No. 1',
                'latitude' => -6.40690782,
                'longitude' => 108.28776285,
                'harga_sewa_bulan' => 750000,
                'fasilitas' => 'Wifi, kasur',
                'peraturan_kos' => 'Tidak boleh berisik',
                'sisa_kamar' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $bookingId = Str::uuid()->toString();

            $connection->table('booking')->insert([
                'id' => $bookingId,
                'pencari_kos_id' => $pencariKosId,
                'kosan_id' => $kosanId,
                'kontrakan_id' => null,
                'tgl_mulai_sewa' => '2026-04-01',
                'tgl_selesai_sewa' => '2026-05-01',
                'total_biaya' => 750000,
                'status_booking' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($this->refactorMigrations() as $migrationPath) {
                Artisan::call('migrate', [
                    '--database' => 'migration_backfill',
                    '--path' => $migrationPath,
                    '--realpath' => true,
                    '--force' => true,
                ]);
            }

            $schema = $connection->getSchemaBuilder();

            $this->assertTrue($schema->hasTable('tipe_kamar'));
            $this->assertTrue($schema->hasTable('kamar'));
            $this->assertFalse($schema->hasColumn('kosan', 'harga_sewa_bulan'));
            $this->assertFalse($schema->hasColumn('kosan', 'fasilitas'));
            $this->assertFalse($schema->hasColumn('kosan', 'sisa_kamar'));
            $this->assertFalse($schema->hasColumn('booking', 'kosan_id'));
            $this->assertTrue($schema->hasColumn('booking', 'kamar_id'));

            $legacyType = $connection->table('tipe_kamar')
                ->where('kosan_id', $kosanId)
                ->first();

            $this->assertNotNull($legacyType);
            $this->assertSame('Tipe Legacy', $legacyType->nama_tipe);
            $this->assertSame(750000, (int) $legacyType->harga_per_bulan);
            $this->assertSame('Wifi, kasur', $legacyType->fasilitas_tipe);

            $rooms = $connection->table('kamar')
                ->where('tipe_kamar_id', $legacyType->id)
                ->orderBy('nomor_kamar')
                ->get();

            $this->assertCount(3, $rooms);
            $this->assertSame('LEGACY-01', $rooms[0]->nomor_kamar);

            $migratedBooking = $connection->table('booking')->where('id', $bookingId)->first();

            $this->assertNotNull($migratedBooking->kamar_id);

            $bookedRoom = $connection->table('kamar')->where('id', $migratedBooking->kamar_id)->first();

            $this->assertNotNull($bookedRoom);
            $this->assertSame('dihuni', $bookedRoom->status_kamar);
        } finally {
            Artisan::call('migrate:reset', [
                '--database' => 'migration_backfill',
                '--force' => true,
            ]);

            DB::disconnect('migration_backfill');
            DB::purge('migration_backfill');

            if (file_exists($databasePath)) {
                unlink($databasePath);
            }
        }
    }

    /**
     * @return list<string>
     */
    private function legacyMigrations(): array
    {
        return [
            database_path('migrations/0001_01_01_000000_create_users_table.php'),
            database_path('migrations/2026_04_11_010000_create_pencari_kos_table.php'),
            database_path('migrations/2026_04_11_010100_create_pemilik_properti_table.php'),
            database_path('migrations/2026_04_11_010200_create_kosan_table.php'),
            database_path('migrations/2026_04_11_010300_create_kontrakan_table.php'),
            database_path('migrations/2026_04_11_010400_create_booking_table.php'),
        ];
    }

    /**
     * @return list<string>
     */
    private function refactorMigrations(): array
    {
        return [
            database_path('migrations/2026_04_12_000100_create_tipe_kamar_table.php'),
            database_path('migrations/2026_04_12_000200_create_kamar_table.php'),
            database_path('migrations/2026_04_12_000300_refactor_kosan_and_booking_for_room_selection.php'),
        ];
    }
}
