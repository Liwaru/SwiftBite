<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('absensis')) {
            return;
        }

        Schema::table('absensis', function (Blueprint $table) {
            if (! Schema::hasColumn('absensis', 'foto_masuk')) {
                $table->string('foto_masuk')->nullable()->after('jam_masuk');
            }

            if (! Schema::hasColumn('absensis', 'foto_pulang')) {
                $table->string('foto_pulang')->nullable()->after('jam_keluar');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('absensis')) {
            return;
        }

        Schema::table('absensis', function (Blueprint $table) {
            if (Schema::hasColumn('absensis', 'foto_masuk')) {
                $table->dropColumn('foto_masuk');
            }

            if (Schema::hasColumn('absensis', 'foto_pulang')) {
                $table->dropColumn('foto_pulang');
            }
        });
    }
};
