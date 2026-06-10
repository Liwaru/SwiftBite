<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE `orders` MODIFY `status` ENUM('menunggu','diproses','siap_diantar','menunggu_pembayaran','selesai','dibatalkan') NOT NULL DEFAULT 'menunggu'");
        }
    }

    public function down(): void
    {
        DB::table('orders')
            ->whereIn('status', ['siap_diantar', 'menunggu_pembayaran'])
            ->update(['status' => 'diproses']);

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE `orders` MODIFY `status` ENUM('menunggu','diproses','selesai','dibatalkan') NOT NULL DEFAULT 'menunggu'");
        }
    }
};
