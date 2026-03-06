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
    Schema::create('services', function (Blueprint $table) {
        $table->id();
        $table->string('name');         // e.g., 'Immunization'
        $table->string('description');  // Short text for the card
        $table->string('icon');         // FontAwesome class like 'fa-syringe'
        $table->string('color');        // Tailwind class like 'bg-blue-500'
        $table->string('schedule');     // e.g., 'Monday, 8:00 AM - 5:00 PM'
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
