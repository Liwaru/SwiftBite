<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('role', 30);
            $table->string('user_name', 100)->nullable();
            $table->string('activity');
            $table->timestamps();
        });

        Schema::create('data_changes', function (Blueprint $table) {
            $table->id();
            $table->string('action', 30);
            $table->string('data_type', 50);
            $table->string('data_name', 150);
            $table->string('actor_role', 30);
            $table->string('actor_name', 100)->nullable();
            $table->string('target_table', 80)->nullable();
            $table->unsignedBigInteger('target_id')->nullable();
            $table->json('before_data')->nullable();
            $table->json('after_data')->nullable();
            $table->timestamp('restored_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_changes');
        Schema::dropIfExists('activity_logs');
    }
};
