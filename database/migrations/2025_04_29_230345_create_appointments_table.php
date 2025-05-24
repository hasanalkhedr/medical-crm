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
            $table->foreignId('lead_id')->constrained();
            $table->foreignId('provider_id')->constrained('users');
            $table->foreignId('appointment_type_id')->constrained();
            $table->dateTime('scheduled_at');
            $table->integer('duration')->comment('in minutes');
            $table->enum('status', ['scheduled','completed','canceled','no-show'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'scheduled_at']);
            $table->index(['lead_id', 'scheduled_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
