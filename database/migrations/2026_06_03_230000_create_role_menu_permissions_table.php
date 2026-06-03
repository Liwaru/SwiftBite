<?php

use App\Support\AccessControl;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_menu_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('level');
            $table->string('feature_key', 80);
            $table->boolean('is_enabled')->default(false);
            $table->timestamps();
            $table->unique(['level', 'feature_key']);
        });

        $now = now();

        foreach (AccessControl::defaults() as $level => $features) {
            foreach ($features as $key => $enabled) {
                DB::table('role_menu_permissions')->insert([
                    'level' => $level,
                    'feature_key' => $key,
                    'is_enabled' => $enabled,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('role_menu_permissions');
    }
};
