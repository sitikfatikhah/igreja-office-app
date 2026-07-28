<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_details', function (Blueprint $table) {

            $table->id();

            $table->foreignId('payroll_id')->constrained('payrolls');

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('type', [
                'earning',
                'deduction',
            ]);

            $table->string('category');

            $table->string('a')->nullable();

            $table->unsignedBigInteger('reference_id')->nullable();

            $table->string('description');

            $table->decimal('qty',8,2)->default(1);

            $table->decimal('rate',15,2)->default(0);

            $table->decimal('amount',15,2);

            $table->timestamps();

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_details');
    }
};