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
        Schema::table('pembayaran', function (Blueprint $table): void {
            $table->string('midtrans_order_id')->nullable()->unique()->after('url_struk_pdf');
            $table->string('midtrans_transaction_id')->nullable()->after('midtrans_order_id');
            $table->string('status_midtrans')->nullable()->after('midtrans_transaction_id');
            $table->string('snap_token')->nullable()->after('status_midtrans');
            $table->string('snap_redirect_url')->nullable()->after('snap_token');
            $table->string('fraud_status')->nullable()->after('snap_redirect_url');
            $table->json('payload_midtrans')->nullable()->after('fraud_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table): void {
            $table->dropColumn([
                'midtrans_order_id',
                'midtrans_transaction_id',
                'status_midtrans',
                'snap_token',
                'snap_redirect_url',
                'fraud_status',
                'payload_midtrans',
            ]);
        });
    }
};
