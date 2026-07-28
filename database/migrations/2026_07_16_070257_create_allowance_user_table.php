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
        Schema::create('allowance_user', function (Blueprint $table) {
             $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('allowance_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->primary(['user_id', 'allowance_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allowance_user');
    }
};
