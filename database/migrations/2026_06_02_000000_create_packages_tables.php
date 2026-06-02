<?php

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
        Schema::create('packages', function (Blueprint $table) {
            $table->id('id_paket');
            $table->string('nama_paket', 150);
            $table->string('foto')->nullable();
            $table->decimal('harga', 10, 2);
            $table->enum('status', ['tersedia', 'habis'])->default('tersedia');
            $table->timestamps();
        });

        Schema::create('package_items', function (Blueprint $table) {
            $table->id('id_paket_item');
            $table->foreignId('id_paket')->constrained('packages', 'id_paket')->cascadeOnDelete();
            $table->foreignId('id_menu')->constrained('menus', 'id_menu')->cascadeOnDelete();
            $table->unsignedInteger('qty');
            $table->timestamps();

            $table->unique(['id_paket', 'id_menu']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_items');
        Schema::dropIfExists('packages');
    }
};
