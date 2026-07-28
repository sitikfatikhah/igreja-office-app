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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('position')->nullable();
            $table->string('nip')->nullable();
            $table->date('date');
            $table->timestamp('check_in')->nullable();
            $table->timestamp('check_out')->nullable();
            $table->decimal('check_in_latitude', 10, 7)->nullable();
            $table->decimal('check_in_longitude', 10, 7)->nullable();
            $table->decimal('check_out_latitude', 10, 7)->nullable();
            $table->decimal('check_out_longitude', 10, 7)->nullable();
            $table->string('check_in_location_name')->nullable();
            $table->string('check_out_location_name')->nullable();
            $table->decimal('verification_score', 5, 4)->nullable();
            $table->string('verification_method')->default('face_recognition');
            $table->boolean('face_verified')->default(false);
            $table->enum('status', ['present', 'absent', 'leave', 'time_off'])->default('present');
            $table->foreignId('leave_request_id')->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('face_verified');
            $table->dropColumn('status');
            $table->dropColumn('leave_request_id');
        });
    }
};
