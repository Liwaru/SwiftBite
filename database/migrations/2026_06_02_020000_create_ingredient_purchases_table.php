<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingredient_purchases', function (Blueprint $table) {
            $table->id('id_pembelian_bahan');
            $table->foreignId('id_bahan')->constrained('ingredients', 'id_bahan')->cascadeOnDelete();
            $table->decimal('qty', 10, 2);
            $table->string('satuan', 20);
            $table->unsignedInteger('harga_total');
            $table->string('note', 140)->nullable();
            $table->timestamp('purchased_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredient_purchases');
    }
};
