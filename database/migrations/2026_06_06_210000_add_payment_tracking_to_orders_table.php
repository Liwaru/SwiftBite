<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status', 30)->default('belum_dibayar')->after('metode_pembayaran');
            }

            if (! Schema::hasColumn('orders', 'midtrans_order_id')) {
                $table->string('midtrans_order_id', 100)->nullable()->after('payment_status');
            }

            if (! Schema::hasColumn('orders', 'qris_url')) {
                $table->text('qris_url')->nullable()->after('midtrans_order_id');
            }

            if (! Schema::hasColumn('orders', 'payment_expires_at')) {
                $table->timestamp('payment_expires_at')->nullable()->after('qris_url');
            }

            if (! Schema::hasColumn('orders', 'payment_payload')) {
                $table->json('payment_payload')->nullable()->after('payment_expires_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            foreach (['payment_payload', 'payment_expires_at', 'qris_url', 'midtrans_order_id', 'payment_status'] as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
