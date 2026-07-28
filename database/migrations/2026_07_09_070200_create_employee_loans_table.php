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
        Schema::create('employee_loans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->decimal('total_amount', 15, 2);      // total utang
        $table->unsignedInteger('installment_count'); // jumlah cicilan (mis. 6x)
        $table->decimal('installment_amount', 15, 2); // per cicilan
        $table->decimal('remaining_balance', 15, 2);   // sisa utang
        $table->date('start_date');                    // mulai dipotong periode kapan
        $table->enum('status', ['active', 'paid_off', 'cancelled'])->default('active');
        $table->text('description')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_loans');
    }
};
