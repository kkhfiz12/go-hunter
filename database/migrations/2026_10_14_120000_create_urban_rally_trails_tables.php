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
        Schema::create('trails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained('cities')->cascadeOnDelete();
            $table->foreignId('destination_id')->nullable()->constrained('destinations')->nullOnDelete();
            $table->string('title', 150);
            $table->string('slug', 160)->unique();
            $table->json('transport_modes');
            $table->boolean('is_recommended')->default(false);
            $table->string('recommendation_reason', 255)->nullable();
            $table->string('estimated_time', 50);
            $table->string('total_distance', 50);
            $table->string('estimated_cost', 50)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('trail_checkpoints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trail_id')->constrained('trails')->cascadeOnDelete();
            $table->unsignedInteger('step_order');
            $table->enum('mode', ['transjakarta', 'mrt', 'krl', 'lrt', 'bus', 'walking']);
            $table->string('stop_name', 150);
            $table->text('instruction');
            $table->unsignedInteger('duration_minutes')->default(5);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trail_checkpoints');
        Schema::dropIfExists('trails');
    }
};
