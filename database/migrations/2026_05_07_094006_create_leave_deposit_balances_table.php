<?php

use App\Models\LeaveRequest;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('leave_deposit_balances', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->foreignId('time_bank_request_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();
        $table->foreignId('leave_request_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();

        $table->integer('days');
        $table->string('type');
        $table->integer('balanced')->default(0);
        $table->string('description')->nullable();

        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_deposit_balances');
    }
};