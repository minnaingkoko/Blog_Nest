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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            
            // Basic Site Information
            $table->string('site_name');
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->string('color_theme')->default('light');
            
            // Contact Information
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();
            $table->string('contact_address')->nullable();
            
            // Google Maps Embed
            $table->text('google_map_embed')->nullable();
            
            // About Us Content
            $table->text('about_us_content')->nullable();
            $table->string('about_us_image')->nullable();
            
            // Contact Us Content
            $table->text('contact_us_content')->nullable();
            $table->string('contact_us_image')->nullable();
            
            // Footer Content
            $table->text('footer_content')->nullable(); // For custom HTML/text in the footer
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};