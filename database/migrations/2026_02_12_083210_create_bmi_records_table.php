<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('bmi_records', function (Blueprint $table) {
        $table->id();
        $table->string('patient_id');
        $table->string('patient_name');
        $table->integer('age');
        $table->string('gender');
        $table->date('date');
        $table->float('weight');
        $table->float('height');
        $table->float('bmi');
        $table->string('category');
        $table->timestamps();
    });
}
};
