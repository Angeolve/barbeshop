<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            // Relación con el Cliente (tabla users)
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            // Relación con el Barbero/Staff (tabla users)
            $table->foreignId('staff_id')->constrained('users')->onDelete('cascade');
            // Relación con el Servicio
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            
            $table->dateTime('appointment_time'); // Fecha y hora reservada
            // Estados del servicio
            $table->enum('status', ['scheduled', 'completed', 'canceled'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Requisito de la rúbrica (Soft Deletes)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};