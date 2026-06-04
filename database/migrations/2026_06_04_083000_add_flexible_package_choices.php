<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_choices', function (Blueprint $table) {
            $table->id('id_paket_choice');
            $table->foreignId('id_paket')->constrained('packages', 'id_paket')->cascadeOnDelete();
            $table->string('category', 50);
            $table->unsignedInteger('qty');
            $table->timestamps();

            $table->unique(['id_paket', 'category']);
        });

        if (Schema::hasTable('order_details')) {
            Schema::table('order_details', function (Blueprint $table) {
                if (! Schema::hasColumn('order_details', 'id_paket')) {
                    $table->foreignId('id_paket')->nullable()->after('id_menu')->constrained('packages', 'id_paket')->nullOnDelete();
                }

                if (! Schema::hasColumn('order_details', 'package_name')) {
                    $table->string('package_name')->nullable()->after('id_paket');
                }

                if (! Schema::hasColumn('order_details', 'package_component_type')) {
                    $table->string('package_component_type', 20)->nullable()->after('package_name');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('order_details')) {
            Schema::table('order_details', function (Blueprint $table) {
                if (Schema::hasColumn('order_details', 'id_paket')) {
                    $table->dropConstrainedForeignId('id_paket');
                }

                if (Schema::hasColumn('order_details', 'package_name')) {
                    $table->dropColumn('package_name');
                }

                if (Schema::hasColumn('order_details', 'package_component_type')) {
                    $table->dropColumn('package_component_type');
                }
            });
        }

        Schema::dropIfExists('package_choices');
    }
};
