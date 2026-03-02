<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{ // Added this opening brace
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('patients', function (Blueprint $table) {
        // 1. Only drop old columns if they still exist
        if (Schema::hasColumn('patients', 'name')) {
            $table->dropColumn(['name', 'age']);
        }

        // 2. Only add new columns if they DON'T exist yet
        if (!Schema::hasColumn('patients', 'first_name')) {
            $table->string('first_name')->after('patient_id')->nullable();
        }
        if (!Schema::hasColumn('patients', 'middle_name')) {
            $table->string('middle_name')->nullable()->after('first_name');
        }
        if (!Schema::hasColumn('patients', 'last_name')) {
            $table->string('last_name')->after('middle_name')->nullable();
        }
        if (!Schema::hasColumn('patients', 'suffix')) {
            $table->string('suffix', 10)->nullable()->after('last_name');
        }
        if (!Schema::hasColumn('patients', 'birthday')) {
            $table->date('birthday')->nullable()->after('suffix');
        }
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'middle_name', 'last_name', 'suffix', 'birthday']);
            $table->string('name')->after('patient_id');
            $table->integer('age')->after('name');
        });
    }
}; // Added this closing brace