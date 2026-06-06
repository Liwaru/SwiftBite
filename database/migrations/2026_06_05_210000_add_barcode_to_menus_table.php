<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('menus', 'barcode')) {
            return;
        }

        Schema::table('menus', function (Blueprint $table) {
            $table->string('barcode', 80)->nullable()->unique()->after('nama_menu');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('menus', 'barcode')) {
            return;
        }

        Schema::table('menus', function (Blueprint $table) {
            $table->dropUnique('menus_barcode_unique');
            $table->dropColumn('barcode');
        });
    }
};
