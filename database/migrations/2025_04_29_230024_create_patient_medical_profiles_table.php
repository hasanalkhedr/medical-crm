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
        Schema::create('patient_medical_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->unique();
            $table->enum('blood_type', ['A+','A-','B+','B-','AB+','AB-','O+','O-'])->nullable();
            $table->decimal('height', 5, 2)->nullable()->comment('in cm');
            $table->decimal('weight', 5, 2)->nullable()->comment('in kg');
            $table->text('known_allergies')->nullable();
            $table->text('chronic_conditions')->nullable();
            $table->text('current_medications')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_medical_profiles');
    }
};
