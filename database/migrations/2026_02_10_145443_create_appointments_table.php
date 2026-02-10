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
    Schema::create('appointments', function (Blueprint $table) {
        $table->id();
        // Link to your patients table
        $table->foreignId('patient_id')->constrained()->onDelete('cascade');
        $table->date('appointment_date');
        $table->time('appointment_time');
        $table->string('service_type'); // e.g., Pre-natal, Immunization
        $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
        $table->timestamps();
    });
}
};
