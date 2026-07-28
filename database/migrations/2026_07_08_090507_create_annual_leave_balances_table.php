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
        Schema::create('annual_leave_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('leave_request_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('year');
            $table->string('leave_type');
            $table->integer('days')->nullable();
            $table->enum('type', ['credit', 'debit', 'balance'])->nullable();  
            $table->integer('balanced')->nullable();
            $table->enum('source', ['annual', 'time-bank'])->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annual_leave_balances');
    }
    
};
