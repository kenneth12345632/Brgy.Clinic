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
        
        // Updated to link specifically to the 'patient_id' column
        $table->unsignedBigInteger('patient_id');
        $table->foreign('patient_id')
              ->references('patient_id')
              ->on('patients')
              ->onDelete('cascade');

        $table->date('appointment_date');
        $table->time('appointment_time');
        $table->string('service_type'); 
        $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
        $table->timestamps();
    });
}
};
