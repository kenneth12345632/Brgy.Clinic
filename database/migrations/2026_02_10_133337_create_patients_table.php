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
        $table->id();
        $table->string('patient_id')->unique(); // e.g., P-001
        $table->string('name');
        $table->integer('age');
        $table->string('gender');
        $table->string('address');
        $table->date('last_visit');
        $table->string('service');
        $table->string('status')->default('Active');
        $table->timestamps();
    });
}
};
