<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id('id_bahan');
            $table->string('nama_bahan', 100)->unique();
            $table->decimal('stok', 10, 2)->default(0);
            $table->string('satuan', 20)->default('kg');
            $table->decimal('stok_minimum', 10, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('ingredient_usages', function (Blueprint $table) {
            $table->id('id_penggunaan_bahan');
            $table->foreignId('id_bahan')->constrained('ingredients', 'id_bahan')->cascadeOnDelete();
            $table->decimal('qty', 10, 2);
            $table->string('note', 120)->nullable();
            $table->string('actor_name', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredient_usages');
        Schema::dropIfExists('ingredients');
    }
};
