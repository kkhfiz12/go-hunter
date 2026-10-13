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
        Schema::create('buddy_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('destination_id')->constrained('destinations')->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->date('departure_date');
            $table->date('return_date')->nullable();
            $table->integer('slots_needed');
            $table->integer('slots_filled')->default(1);
            $table->decimal('estimated_budget_per_person', 12, 2);
            $table->enum('status', ['open', 'full', 'departed', 'cancelled'])->default('open');
            $table->string('contact_link');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buddy_requests');
    }
};
