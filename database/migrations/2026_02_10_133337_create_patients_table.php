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
    Schema::create('patients', function (Blueprint $table) {
        // Replace $table->id() with this:
        $table->bigIncrements('patient_id'); 
        
        $table->string('first_name');
        $table->string('middle_name')->nullable();
        $table->string('last_name');
        $table->date('birthday');
        $table->string('gender');
        $table->string('service');
        $table->text('address');
        $table->timestamps();
    });
}
};
