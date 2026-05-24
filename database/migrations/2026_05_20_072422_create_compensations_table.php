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
        Schema::create('compensations', function (Blueprint $table) {
            $table->id();
            $table->integer('basic_salary');
            $table->integer('position_allowance');
            $table->integer('transport_allowance');
            $table->integer('meal_allowance');
            $table->integer('communication_allowance');
            $table->integer('health_benefit');
            $table->integer('insurance_benefit');
            $table->integer('retirement_benefit');
            $table->dateTime('effective_date');
            $table->dateTime('end_date');
            $table->boolean('is_active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compensations');
    }
};
