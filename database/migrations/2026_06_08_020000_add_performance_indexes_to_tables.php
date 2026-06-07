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
        // kosan table indexes
        if (Schema::hasTable('kosan')) {
            Schema::table('kosan', function (Blueprint $table) {
                if (!Schema::hasIndex('kosan', 'kosan_status_index')) {
                    $table->index('status', 'kosan_status_index');
                }
                if (!Schema::hasIndex('kosan', 'kosan_jenis_kos_index')) {
                    $table->index('jenis_kos', 'kosan_jenis_kos_index');
                }
                if (!Schema::hasIndex('kosan', 'kosan_pemilik_properti_id_index')) {
                    $table->index('pemilik_properti_id', 'kosan_pemilik_properti_id_index');
                }
                if (!Schema::hasIndex('kosan', 'kosan_status_created_at_index')) {
                    $table->index(['status', 'created_at'], 'kosan_status_created_at_index');
                }
            });
        }

        // kontrakan table indexes
        if (Schema::hasTable('kontrakan')) {
            Schema::table('kontrakan', function (Blueprint $table) {
                if (!Schema::hasIndex('kontrakan', 'kontrakan_status_index')) {
                    $table->index('status', 'kontrakan_status_index');
                }
                if (!Schema::hasIndex('kontrakan', 'kontrakan_pemilik_properti_id_index')) {
                    $table->index('pemilik_properti_id', 'kontrakan_pemilik_properti_id_index');
                }
            });
        }

        // booking table indexes
        if (Schema::hasTable('booking')) {
            Schema::table('booking', function (Blueprint $table) {
                if (!Schema::hasIndex('booking', 'booking_pencari_kos_id_index')) {
                    $table->index('pencari_kos_id', 'booking_pencari_kos_id_index');
                }
                if (!Schema::hasIndex('booking', 'booking_status_booking_index')) {
                    $table->index('status_booking', 'booking_status_booking_index');
                }
                if (!Schema::hasIndex('booking', 'booking_created_at_index')) {
                    $table->index('created_at', 'booking_created_at_index');
                }
            });
        }

        // media table indexes
        if (Schema::hasTable('media')) {
            Schema::table('media', function (Blueprint $table) {
                // morphs('model') already creates model_type + model_id index,
                // so we only add collection_name index
                if (!Schema::hasIndex('media', 'media_collection_name_index')) {
                    $table->index('collection_name', 'media_collection_name_index');
                }
            });
        }

        // users table indexes
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasIndex('users', 'users_role_index')) {
                    $table->index('role', 'users_role_index');
                }
                if (!Schema::hasIndex('users', 'users_status_akun_index')) {
                    $table->index('status_akun', 'users_status_akun_index');
                }
            });
        }

        // notifications table indexes
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                // morphs('notifiable') already indexes notifiable_type + notifiable_id,
                // but a composite with read_at helps filter unread notifications efficiently
                if (!Schema::hasIndex('notifications', 'notifications_notifiable_read_at_index')) {
                    $table->index(
                        ['notifiable_type', 'notifiable_id', 'read_at'],
                        'notifications_notifiable_read_at_index'
                    );
                }
            });
        }

        // jobs table — queue column already indexed by default migration,
        // no additional indexes needed
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('kosan')) {
            Schema::table('kosan', function (Blueprint $table) {
                $table->dropIndex('kosan_status_index');
                $table->dropIndex('kosan_jenis_kos_index');
                $table->dropIndex('kosan_pemilik_properti_id_index');
                $table->dropIndex('kosan_status_created_at_index');
            });
        }

        if (Schema::hasTable('kontrakan')) {
            Schema::table('kontrakan', function (Blueprint $table) {
                $table->dropIndex('kontrakan_status_index');
                $table->dropIndex('kontrakan_pemilik_properti_id_index');
            });
        }

        if (Schema::hasTable('booking')) {
            Schema::table('booking', function (Blueprint $table) {
                $table->dropIndex('booking_pencari_kos_id_index');
                $table->dropIndex('booking_status_booking_index');
                $table->dropIndex('booking_created_at_index');
            });
        }

        if (Schema::hasTable('media')) {
            Schema::table('media', function (Blueprint $table) {
                $table->dropIndex('media_collection_name_index');
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex('users_role_index');
                $table->dropIndex('users_status_akun_index');
            });
        }

        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->dropIndex('notifications_notifiable_read_at_index');
            });
        }
    }
};
