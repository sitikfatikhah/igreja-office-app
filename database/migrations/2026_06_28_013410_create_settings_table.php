<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            $table->string('key', 100);

            $table->json('value')->nullable();

            $table->string('type', 50)->default('string');
            $table->string('group', 100)->nullable();
            $table->string('scope', 50)->default('global');
            $table->string('role', 100)->nullable();
            $table->string('permission', 100)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(
                ['key', 'group', 'scope', 'role', 'permission'],
                'scope_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};