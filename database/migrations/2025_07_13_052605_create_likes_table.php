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
        Schema::create('likes', function (Blueprint $table) {
            $table->id(); // Add an ID column for simplicity
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // The user who liked
            $table->morphs('likeable'); // Adds likeable_id and likeable_type columns
            $table->timestamps();

            // Composite unique constraint to prevent duplicate likes
            $table->unique(['user_id', 'likeable_id', 'likeable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};