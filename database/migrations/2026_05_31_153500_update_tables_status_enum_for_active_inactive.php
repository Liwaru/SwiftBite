<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE `tables` MODIFY `status` ENUM('aktif', 'nonaktif', 'kosong', 'terisi') NOT NULL DEFAULT 'aktif'");
        DB::table('tables')->where('status', 'kosong')->update(['status' => 'aktif']);
        DB::table('tables')->where('status', 'terisi')->update(['status' => 'aktif']);
    }

    public function down(): void
    {
        DB::table('tables')->where('status', 'aktif')->update(['status' => 'kosong']);
        DB::table('tables')->where('status', 'nonaktif')->update(['status' => 'kosong']);
        DB::statement("ALTER TABLE `tables` MODIFY `status` ENUM('kosong', 'terisi') NOT NULL DEFAULT 'kosong'");
    }
};
