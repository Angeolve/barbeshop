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
        Schema::create('staff_schedules', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $blueprint->boolean('monday')->default(true);
            $blueprint->boolean('tuesday')->default(true);
            $blueprint->boolean('wednesday')->default(true);
            $blueprint->boolean('thursday')->default(true);
            $blueprint->boolean('friday')->default(true);
            $blueprint->boolean('saturday')->default(true);
            $blueprint->boolean('sunday')->default(false);
            $blueprint->enum('shift', ['mañana', 'noche'])->default('mañana');
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_schedules');
    }
};
