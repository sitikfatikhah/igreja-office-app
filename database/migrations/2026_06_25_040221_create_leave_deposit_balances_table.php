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
        Schema::create('leave_deposit_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('time_bank_request_id');
            $table->integer('days');
            $table->string('type');
            $table->integer('balanced');
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_deposit_balances');
    }
};
