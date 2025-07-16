<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // User who commented
            $table->foreignId('post_id')->constrained()->cascadeOnDelete(); // Post the comment belongs to
            $table->text('content'); // Comment content
            $table->timestamps(); // Created at and updated at
            $table->softDeletes(); // Soft delete support
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};